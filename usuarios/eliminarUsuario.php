<?php

require_once '../config/cargarConfig.php';

session_start(); // Asegurar que la sesión está iniciada
$error = [];

nivelRequerido(1);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminarUsuario'])) {
    $camposRequeridos = ['id_usuario'];
    validarCampos($camposRequeridos);

    if (empty($error)) {
        $id_usuario = isset($_POST['id_usuario']) ? intval($_POST['id_usuario']) : 0;
        $id_usuario_sesion = $_SESSION['id_usuario'] ?? 0;
        $nivel_sesion = $_SESSION['nivel_usuario'] ?? 0;

        if ($nivel_sesion == 1 && $id_usuario !== $id_usuario_sesion) {
            $sql = "DELETE FROM usuarios WHERE id_usuario = ?";
            if ($stmt = $conn->prepare($sql)) 
                $stmt->bind_param("i", $id_usuario);
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    echo "Usuario eliminado exitosamente!";
                } else {
                    $error[] = "No se encontró el usuario o error al eliminar.";
                }
                $stmt->close();
            } else {
                error_log("Error en la consulta SQL: " . $conn->error);
                $error[] = "Error interno, intenta más tarde.";
            }
        } else {
            $error[] = "No tienes permisos para eliminar este usuario.";
        }
    }


// Mostrar errores si los hay
if (!empty($error)) {
    foreach ($error as $err) {
        echo "<p style='color:red;'>$err</p>";
    }
}
