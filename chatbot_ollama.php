<?php
declare(strict_types=1);
require __DIR__.'/vendor/autoload.php';

use Dotenv\Dotenv;

// Configuración inicial
$dotenv = Dotenv::createImmutable(__DIR__ .'/config');
$dotenv->load();

class InventoryChatbot {
    private $conn;
    private $systemPrompt;

    public function __construct() {
        // Configurar base de datos
        $this->conn = new mysqli(
            $_ENV['DB_SERVER'],
            $_ENV['DB_USERNAME'],
            $_ENV['DB_PASSWORD'],
            $_ENV['DB_DATABASE']
        );
        
        if ($this->conn->connect_error) {
            $this->sendError('Database connection failed: '.$this->conn->connect_error);
        }

        // Definir prompt del sistema
        $this->systemPrompt = <<<EOT
Eres un asistente especializado en gestión de inventario para el sistema IMS_invsys.

TABLAS PRINCIPALES:
- productos: id_producto, nombre, precio_compra, precio_venta, stock_minimo, stock_maximo
- stock_almacen: id_stock, id_almacen, id_producto, cantidad_disponible
- almacenes: id_almacen, nombre, ubicacion
- categorias: id_categoria, nombre
- proveedores: id_proveedor, nombre

FUNCIONES:
1. Consultar stock actual
2. Identificar productos con stock bajo
3. Calcular valor del inventario
4. Generar informes básicos

FORMATO DE RESPUESTA:
**Consulta**: [SQL]
**Explicación**: [Texto]
**Resultado**: [Datos]
EOT;
    }

    public function handleRequest(): void {
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $userMessage = $this->sanitizeInput($input['message'] ?? '');
            
            if (empty($userMessage)) {
                $this->sendError('Mensaje vacío');
            }

            $aiResponse = $this->getAIResponse($userMessage);
            $processedResponse = $this->processResponse($aiResponse);
            
            $this->logInteraction($userMessage, $processedResponse);
            $this->sendResponse($processedResponse);
            
        } catch (Exception $e) {
            $this->sendError($e->getMessage());
        }
    }

    private function getAIResponse(string $message): string {
        try {
            // Formato para el endpoint /api/generate
            $payload = [
                'model' => 'gemma:2b',
                'prompt' => $this->systemPrompt . "\n\nUsuario: " . $message,
                'temperature' => 0.5,
                'max_tokens' => 10000,
                'stream' => false
            ];

            $ch = curl_init('http://localhost:11434/api/generate');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            $response = curl_exec($ch);
            $error = curl_error($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            // Registrar información de depuración
            error_log("Ollama API Request: " . json_encode($payload));
            error_log("Ollama API Response Code: " . $http_code);
            error_log("Ollama API Response: " . $response);
            if ($error) {
                error_log("Ollama API Error: " . $error);
            }

            if ($http_code !== 200 || !$response) {
                throw new Exception('Ollama API Error: No response or invalid response. HTTP Code: ' . $http_code . '. Error: ' . $error);
            }

            $data = json_decode($response, true);
            return $data['response'] ?? 'Error procesando respuesta';
            
        } catch (Exception $e) {
            error_log("Ollama Exception: " . $e->getMessage());
            throw new Exception('Ollama API Error: '.$e->getMessage());
        }
    }

    private function processResponse(string $response): string {
        if (preg_match('/\*\*Consulta\*\*: (SELECT.*?);/is', $response, $matches)) {
            $sql = trim($matches[1]);
            $result = $this->executeSafeQuery($sql);
            return $response."\n**Resultado**: ".json_encode($result);
        }
        
        return $response;
    }

    private function executeSafeQuery(string $sql): array {
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception('Error en consulta: '.$this->conn->error);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        
        $stmt->close();
        return $data;
    }

    private function sanitizeInput(string $input): string {
        return htmlspecialchars(strip_tags($input), ENT_QUOTES, 'UTF-8');
    }

    private function logInteraction(string $input, string $output): void {
        $stmt = $this->conn->prepare(
            "INSERT INTO chat_logs (user_input, bot_response) VALUES (?, ?)"
        );
        $stmt->bind_param('ss', $input, $output);
        $stmt->execute();
        $stmt->close();
    }

    private function sendResponse(string $response): void {
        // Agregar encabezados CORS
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');
        
        header('Content-Type: application/json');
        echo json_encode(['response' => $response]);
        exit;
    }

    private function sendError(string $message): void {
        // Agregar encabezados CORS
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');
        
        http_response_code(500);
        $this->sendResponse("⚠️ Error: $message");
    }

    public function __destruct() {
        $this->conn->close();
    }
}

// Ejecutar chatbot
$chatbot = new InventoryChatbot();
$chatbot->handleRequest();
?>