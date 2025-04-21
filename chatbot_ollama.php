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
Funciones disponibles:
1. Consultar stock: Generar consultas SELECT
2. Alertas de stock: Comparar con mínimos/máximos
3. Informes básicos: Resúmenes de inventario

Reglas:
- Usar exclusivamente las tablas `stock_almacen` y `productos`
- Generar SQL válido y seguro
- Formatear respuestas con: 
  **Consulta**: [SQL]
  **Explicación**: [Texto]
  **Resultado**: [Datos o error]
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
            $payload = [
                'model' => 'llama3.2', // Usar el modelo funcional
                'messages' => [
                    ['role' => 'system', 'content' => $this->systemPrompt],
                    ['role' => 'user', 'content' => $message]
                ],
                'temperature' => 0.5,
                'max_tokens' => 500,
                'stream' => false
            ];

            $ch = curl_init('http://localhost:11434/v1/chat/completions');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($http_code !== 200 || !$response) {
                throw new Exception('Ollama API Error: No response or invalid response');
            }

            $data = json_decode($response, true);
            return $data['choices'][0]['message']['content'] ?? 'Error procesando respuesta';
            
        } catch (Exception $e) {
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
        header('Content-Type: application/json');
        echo json_encode(['response' => $response]);
        exit;
    }

    private function sendError(string $message): void {
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