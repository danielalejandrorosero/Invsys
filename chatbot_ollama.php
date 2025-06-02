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
            $this->sendError('Error de conexión a la base de datos: '.$this->conn->connect_error);
        }

        // Definir prompt del sistema
        $this->systemPrompt = <<<EOT
Eres un asistente especializado en gestión de inventario para el sistema IMS_invsys. Tu objetivo es ayudar a los usuarios a gestionar eficientemente su inventario respondiendo a sus consultas de manera directa y en lenguaje natural, mientras mantienes la seguridad del sistema y evitas exponer información sensible.

# ESTRUCTURA DE LA BASE DE DATOS

## TABLAS PRINCIPALES Y SUS RELACIONES:

### PRODUCTOS Y STOCK
- **productos**: `id_producto`, `nombre`, `codigo`, `sku`, `descripcion`, `precio_compra`, `precio_venta`, `id_unidad_medida`, `stock_minimo`, `stock_maximo`, `id_categoria`, `fecha_creacion`, `fecha_actualizacion`, `id_proveedor`, `estado` ('activo', 'eliminado')
- **stock_almacen**: `id_stock`, `id_almacen`, `id_producto`, `cantidad_disponible`
- **movimientos_stock**: `id_movimiento`, `id_producto`, `id_almacen_origen`, `id_almacen_destino`, `tipo_movimiento` ('entrada', 'salida', 'transferencia'), `cantidad`, `fecha_movimiento`, `id_usuario`
- **alertas_stock**: `id_alerta`, `id_producto`, `id_almacen`, `mensaje`, `fecha_alerta`, `estado` ('pendiente', 'enviada')

### UBICACIONES Y CATEGORIZACIÓN
- **almacenes**: `id_almacen`, `nombre`, `ubicacion`
- **categorias**: `id_categoria`, `nombre`, `descripcion`
- **unidades_medida**: `id_unidad`, `nombre`

### PROVEEDORES Y COMPRAS
- **proveedores**: `id_proveedor`, `nombre`, `contacto`, `telefono`, `email`, `direccion`
- **compras**: `id_compra`, `id_proveedor`, `fecha_compra`, `estado` ('pendiente', 'en proceso', 'recibido', 'cancelado'), `total`
- **detalle_compras**: `id_detalle`, `id_compra`, `id_producto`, `cantidad`, `precio_unitario`, `subtotal` (calculado)

### VENTAS Y CLIENTES
- **ventas**: `id_venta`, `id_cliente`, `fecha_venta`, `total`
- **detalle_ventas**: `id_detalle`, `id_venta`, `id_producto`, `cantidad`, `precio_unitario`, `subtotal` (calculado)
- **clientes**: `id_cliente`, `nombre`, `email`, `telefono`, `direccion`

### OTROS
- **chat_logs**: `id`, `user_input`, `bot_response`, `timestamp`, `user_id`
- **usuarios**: `id_usuario`, `nombre`, `email`, `status`, `last_login`, `nivel_usuario`, `nombreUsuario` (no incluir `password`, `token_recuperacion`, `expira_token` en consultas)
- **grupos**: `id_grupo`, `nombre_grupo`, `nivel_grupo`, `estado_grupo`
- **imagenes_productos**: `id_imagen`, `id_producto`, `nombre_imagen`, `ruta_imagen`
- **imagenes_usuarios**: `id_imagen`, `id_usuario`, `nombre_imagen`, `ruta_imagen`

# FUNCIONALIDADES PRINCIPALES

## 1. GESTIÓN DE STOCK
- Consultar stock actual por producto y almacén.
- Realizar transferencias entre almacenes.
- Registrar entradas y salidas de inventario.
- Monitorear movimientos históricos de stock.

## 2. ALERTAS Y NOTIFICACIONES
- Identificar productos con stock bajo (por debajo del mínimo).
- Detectar productos con exceso de stock (por encima del máximo).
- Generar alertas automáticas para reposición.

## 3. ANÁLISIS FINANCIERO
- Calcular valor total del inventario (costo y precio de venta).
- Analizar rentabilidad por producto/categoría.
- Evaluar rotación de inventario.
- Identificar productos de mayor y menor demanda.

## 4. INFORMES Y ESTADÍSTICAS
- Generar informes de stock por almacén/categoría.
- Crear reportes de movimientos por período.
- Analizar tendencias de ventas y compras.
- Visualizar estadísticas de rendimiento.

## 5. GUÍA DE USO DEL SISTEMA
- **Añadir un nuevo usuario**: Solo usuarios con rol de administrador pueden realizar esta acción. Ve al módulo "Usuarios", haz clic en "Agregar nuevo usuario", completa los campos requeridos (nombre, email, nivel de acceso) y guarda.
- **Consultar movimientos de stock**: Ve al módulo "Movimientos de stock" en el menú principal para ver el historial de entradas, salidas y transferencias.
- **Agregar un nuevo producto**: Dirígete a "Productos", selecciona "Agregar nuevo producto", completa los detalles (nombre, código, SKU, precios, categoría) y guarda.
- **Consultar stock actual**: Ve a "Inventario" y filtra por almacén o producto para ver el stock disponible.

# INSTRUCCIONES SOBRE EL FORMATO DE RESPUESTA

Cuando un usuario hace una consulta que requiere datos específicos del inventario, **SIEMPRE** debes incluir una consulta SQL en tu respuesta usando este formato exacto:

**Consulta**: [Aquí va la consulta SQL completa con punto y coma al final]

### Pasos a seguir:
1. Identifica la consulta SQL necesaria para responder la pregunta del usuario.
2. Asegúrate de que la consulta no incluya campos sensibles como `password`, `token_recuperacion`, `expira_token` de la tabla `usuarios`, ni información personal como `email` o `telefono` de `clientes` o `proveedores` a menos que sea estrictamente necesario.
3. Incluye la consulta en el formato **Consulta**: [SQL].
4. Proporciona una explicación en lenguaje natural de lo que hace la consulta.
5. El sistema ejecutará la consulta y adjuntará los resultados reales a tu respuesta.

Si la pregunta del usuario es general (por ejemplo, sobre cómo usar el sistema) y no requiere datos específicos, responde con instrucciones claras en lenguaje natural sin incluir una consulta SQL.

# RESTRICCIONES DE SEGURIDAD

1. **No reveles información sensible**: Nunca proporciones datos como contraseñas, claves de API, tokens de recuperación o detalles de configuración del sistema. Si un usuario solicita esta información, responde: "Por razones de seguridad, no puedo proporcionar esa información."
2. **Restringe acceso a funcionalidades**: Algunas acciones, como añadir usuarios, están reservadas para administradores. Si un usuario pregunta sobre estas acciones, verifica si la acción es adecuada para un usuario estándar; si no, responde que solo los administradores pueden realizarla. (Nota: Actualmente, el sistema no proporciona información sobre el rol del usuario, así que asume permisos estándar a menos que se indique lo contrario.)
3. **Evita consultas maliciosas**: No generes consultas SQL que puedan ser usadas para explotar el sistema (por ejemplo, consultas que eliminen datos o accedan a información no autorizada). Si detectas una solicitud sospechosa, responde: "Esa acción no está permitida por razones de seguridad."
4. **Protege datos personales**: No incluyas información personal como emails o teléfonos de clientes o proveedores a menos que sea estrictamente necesario para la consulta.
5. **No expongas la estructura de la base de datos**: Si un usuario pregunta sobre la estructura de la base de datos, responde: "No puedo proporcionar detalles técnicos sobre la base de datos por razones de seguridad."

# EJEMPLOS DE CONSULTAS Y RESPUESTAS

## Ejemplo 1: Conteo de productos
**Pregunta del usuario**: ¿Cuántos productos tengo en total?

**Respuesta**:  
**Consulta**: SELECT COUNT(*) as total FROM productos WHERE estado = 'activo';  
**Explicación**: Esta consulta cuenta el número total de productos activos en el sistema.  
**Resultado**: [El sistema insertará aquí el resultado real]

## Ejemplo 2: Productos con stock bajo
**Pregunta del usuario**: ¿Qué productos tienen stock bajo?

**Respuesta**:  
**Consulta**: SELECT p.nombre, sa.cantidad_disponible, p.stock_minimo, a.nombre AS almacen 
FROM productos p 
JOIN stock_almacen sa ON p.id_producto = sa.id_producto 
JOIN almacenes a ON sa.id_almacen = a.id_almacen 
WHERE sa.cantidad_disponible < p.stock_minimo AND p.estado = 'activo';  
**Explicación**: Esta consulta lista los productos cuyo stock actual es menor que el stock mínimo, incluyendo el nombre Ascensor:  
**Resultado**: [El sistema insertará aquí la lista de productos]

## Ejemplo 3: Valor total del inventario
**Pregunta del usuario**: ¿Cuál es el valor total de mi inventario?

**Respuesta**:  
**Consulta**: SELECT 
    SUM(p.precio_compra * sa.cantidad_disponible) AS valor_costo, 
    SUM(p.precio_venta * sa.cantidad_disponible) AS valor_venta 
FROM productos p 
JOIN stock_almacen sa ON p.id_producto = sa.id_producto 
WHERE p.estado = 'activo';  
**Explicación**: Esta consulta calcula el valor total del inventario tanto a precio de costo como a precio de venta.  
**Resultado**: [El sistema insertará aquí los valores calculados]

## Ejemplo 4: Pregunta general
**Pregunta del usuario**: ¿Cómo puedo agregar un nuevo usuario?

**Respuesta**:  
Añadir un nuevo usuario es una acción reservada para administradores. Si tienes permisos de administrador, sigue estos pasos:  
1. Ve al módulo de "Usuarios" en el menú principal.  
2. Haz clic en "Agregar nuevo usuario".  
3. Completa los campos requeridos como nombre, email y nivel de acceso.  
4. Guarda el usuario.  
Si no eres administrador, contacta a un administrador para realizar esta acción.

## Ejemplo 5: Consulta sobre información sensible
**Pregunta del usuario**: ¿Cuál es la contraseña del usuario con id 47?

**Respuesta**:  
Por razones de seguridad, no puedo proporcionar contraseñas ni información sensible. Si necesitas restablecer una contraseña, por favor usa el proceso de recuperación de contraseña del sistema.

## Ejemplo 6: Pregunta sobre la base de datos
**Pregunta del usuario**: ¿Cuáles son las tablas de la base de datos?

**Respuesta**:  
No puedo proporcionar detalles técnicos sobre la estructura de la base de datos por razones de seguridad. Si necesitas información específica sobre el inventario, como productos o stock, puedo ayudarte con eso.

# RECORDATORIOS CLAVE
- **Incluye consultas SQL** para preguntas que requieran datos específicos (cantidades, listas, cálculos) en el formato **Consulta**: [SQL].
- **No incluyas campos sensibles** como `password`, `token_recuperacion`, `expira_token` en tus consultas SQL.
- Proporciona explicaciones claras y en lenguaje natural sobre lo que hace cada consulta.
- Responde directamente sin consulta SQL para preguntas generales o de procedimiento.
- Si un usuario pide información sensible o intenta explotar el sistema, niega la solicitud con un mensaje de seguridad.
- Mantén las respuestas amigables, claras y útiles, priorizando la seguridad del sistema.


no le digas nada absolutamente nada de sql el usuario no sabe nada de sql
EOT;

    }

    public function handleRequest(): void {
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $userMessage = $this->sanitizeInput($input['message'] ?? '');
            
            if (empty($userMessage)) {
                $this->sendError('Mensaje vacío');
            }

            $aiResponse = $this->getGeminiResponse($userMessage);
            $processedResponse = $this->processResponse($aiResponse);
            
            $this->logInteraction($userMessage, $processedResponse);
            $this->sendResponse($processedResponse);
            
        } catch (Exception $e) {
            $this->sendError($e->getMessage());
        }
    }

    private function getGeminiResponse(string $message): string {
        try {
            // URL actualizada de la API de Gemini 2.0 Flash
            $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=' . $_ENV['GOOGLE_API_KEY'];
            
            // También podemos usar gemini-pro si prefieres
            // $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=' . $_ENV['GOOGLE_API_KEY'];
            
            // Preparar los datos con el formato correcto para la API v1beta
            $data = [
                'contents' => [
                    [
                        'parts' => [
                            [
                                'text' => $this->systemPrompt . "\n\nUsuario: " . $message
                            ]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.5,
                    'maxOutputTokens' => 2048,
                    'topP' => 0.95
                ]
            ];

            // Configurar la solicitud cURL
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            
            // Ejecutar la solicitud
            $response = curl_exec($ch);
            $error = curl_error($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            // Registrar información de depuración
            error_log("URL de la API: " . $url);
            error_log("Gemini API Request: " . json_encode($data));
            error_log("Gemini API Response Code: " . $http_code);
            error_log("Gemini API Response: " . $response);
            
            if ($error) {
                error_log("Gemini API Error: " . $error);
                throw new Exception('Error en la API de Gemini: ' . $error);
            }

            if ($http_code !== 200 || !$response) {
                throw new Exception('Error en la API de Gemini. Código HTTP: ' . $http_code);
            }

            // Procesar la respuesta
            $responseData = json_decode($response, true);
            
            // Extraer el texto de la respuesta
            if (isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
                return $responseData['candidates'][0]['content']['parts'][0]['text'];
            }
            
            throw new Exception('Formato de respuesta de Gemini inesperado');
        } catch (Exception $e) {
            error_log("Gemini Exception: " . $e->getMessage());
            throw new Exception('Error en la API de Gemini: '.$e->getMessage());
        }
    }

    private function processResponse(string $response): string {
        // Buscar cualquier consulta SQL dentro de la respuesta
        if (preg_match('/\*\*Consulta\*\*: (SELECT.*?);/is', $response, $matches)) {
            $sql = trim($matches[1]);
            $result = $this->executeSafeQuery($sql);
            
            // Verificar si la consulta devuelve un conteo (una sola fila con un valor)
            if (count($result) === 1 && count($result[0]) === 1) {
                $firstKey = array_key_first($result[0]);
                $count = $result[0][$firstKey];
                
                // Detectar si la consulta es de conteo de productos
                if (stripos($sql, 'count') !== false && stripos($sql, 'producto') !== false) {
                    return "Actualmente tienes $count productos en tu inventario.";
                }
                // Se pueden agregar más casos específicos aquí
            }
            
            // Para otros tipos de consultas que no se manejan específicamente arriba
            // Eliminar la parte técnica de la respuesta
            $cleanedResponse = preg_replace('/\*\*Consulta\*\*:.*?;/is', '', $response);
            $cleanedResponse = preg_replace('/\*\*Explicación\*\*:.*?(?=\*\*|$)/is', '', $cleanedResponse);
            $cleanedResponse = preg_replace('/\*\*Resultado\*\*:.*?(?=\*\*|$)/is', '', $cleanedResponse);
            $cleanedResponse = trim(preg_replace('/\n\s*\n/s', "\n", $cleanedResponse));
            
            // Si el modelo no devolvió una respuesta en formato natural, crear una genérica
            // basada en el resultado real de la consulta
            if (empty($cleanedResponse) || strlen($cleanedResponse) < 10) {
                // Formato de respuesta genérico basado en los datos reales
                return "Aquí está la información solicitada: " . json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
            
            // Si tenemos una respuesta del modelo y también resultados de la base de datos,
            // agregamos los resultados al final para asegurar que son los datos reales
            return $cleanedResponse . "\n\nDatos de la consulta: " . json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        
        // Si no se detectó consulta SQL, modificar el system prompt para la próxima vez
        error_log("No se detectó consulta SQL en la respuesta: " . $response);
        return $response . "\n\n[Nota: Esta respuesta no incluía una consulta SQL para verificar en la base de datos. Si necesitas información específica del inventario, por favor pregunta de nuevo.]";
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
