<?php

class ChatbotController {
    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            return;
        }

        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if (!isset($data['message'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Mensaje no proporcionado']);
            return;
        }

        try {
            // Llamar al microservicio Flask
            $ch = curl_init('http://127.0.0.1:5005/chat');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['message' => $data['message']]));
            $result = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpcode !== 200) {
                throw new Exception('Error en el servicio del chatbot');
            }

            echo $result;
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Error al procesar la solicitud: ' . $e->getMessage()]);
        }
    }
}

$controller = new ChatbotController();
$controller->handleRequest(); 