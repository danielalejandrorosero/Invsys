<?php
require_once __DIR__ . '/../../../config/cargarConfig.php';
require_once __DIR__ . '/../../Models/usuarios/Usuarios.php';

header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar que el usuario esté autenticado
if (!isset($_SESSION["id_usuario"]) || !isset($_SESSION["session_id"])) {
    echo json_encode(['success' => false, 'message' => 'No autenticado']);
    exit();
}

// Verificar que la sesión sea válida
$usuarioModel = new Usuario($conn);
if (!$usuarioModel->verificarSesionValida($_SESSION["id_usuario"], $_SESSION["session_id"])) {
    echo json_encode(['success' => false, 'message' => 'Sesión inválida']);
    exit();
}

// Actualizar last_login para mantener la sesión activa
try {
    $stmt = $conn->prepare("UPDATE usuarios SET last_login = NOW() WHERE id_usuario = ? AND session_id = ?");
    $stmt->bind_param("is", $_SESSION["id_usuario"], $_SESSION["session_id"]);
    $stmt->execute();
    
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Sesión actualizada']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar sesión']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error en el servidor']);
} finally {
    $stmt->close();
}
?> 