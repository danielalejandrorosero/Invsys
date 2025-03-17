<?php

require_once '../config/cargarConfig.php';


$error = [];

nivelRequerido(1);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregarUsuario'])) {
    $camposRequeridos = ['email', 'nombre', 'nombreUsuario', 'password', 'nivel_usuario']; 
    validarCampos($camposRequeridos);

    if (empty($error)) {
        // Limpiar y sanitizar los datos
        $nombre = trim($_POST['nombre']);
        $nombreUsuario = trim($_POST['nombreUsuario']);
        $email = filter_var(trim($_POST['email']),  );
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $nivel_usuario = intval($_POST['nivel_usuario']);
        $status = 1; // 1 = activo, 0 = inactivo
        $last_login = date('Y-m-d H:i:s');

        // Verificar si el nivel de usuario existe en la tabla 'grupos'
        $checkGroup = $conn->prepare("SELECT nivel_grupo FROM grupos WHERE nivel_grupo = ?");
        $checkGroup->bind_param("i", $nivel_usuario);
        $checkGroup->execute();
        $checkGroup->store_result();

        if ($checkGroup->num_rows === 0) {
            $error[] = "El nivel de usuario seleccionado no existe.";
        } else {
            // Insertar usuario en la base de datos
            $sql = "INSERT INTO usuarios (nombre, nombreUsuario, email, password, status, nivel_usuario, last_login) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssiis", $nombre, $nombreUsuario, $email, $password, $status, $nivel_usuario, $last_login);

            if ($stmt->execute()) {
                echo "Usuario agregado exitosamente";
            } else {
                $error[] = "Error al agregar usuario: " . $stmt->error;
            }
            $stmt->close();
        }
        $checkGroup->close();
    }
}

// Mostrar errores acumulados
if (!empty($error)) {
    foreach ($error as $err) {
        echo "<p style='color:red;'>$err</p>";
    }
}
?>
