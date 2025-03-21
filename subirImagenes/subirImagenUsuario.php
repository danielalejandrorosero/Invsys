<?php

require_once '../config/cargarConfig.php';

$error = [];


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['subirImagenUsuario'])) {
    $camposRequeridos = ['id_usuario'];
    validarCampos($camposRequeridos);

    // Sanitizar y limpiar datos
    $id_usuario = isset($_POST['id_usuario']) ? (int) $_POST['id_usuario'] : 0;

    // Validaciones
    if ($id_usuario <= 0) {
        $error[] = "Usuario inválido.";
    } else {
        // Validar existencia del usuario en la base de datos
        $sql = "SELECT COUNT(*) FROM usuarios WHERE id_usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $stmt->bind_result($existe);
        $stmt->fetch();
        $stmt->close();

        if ($existe === 0) {
            $error[] = "El usuario seleccionado no existe.";
        }
    }

    // Validar imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $archivoTmp = $_FILES['imagen']['tmp_name'];
        $nombreArchivo = $_FILES['imagen']['name'];
        $archivoExtension = strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));

        // Extensiones permitidas
        $extensionesPermitidas = ['jpg', 'jpeg', 'png'];
        if (!in_array($archivoExtension, $extensionesPermitidas)) {
            $error[] = "Solo se permiten archivos JPG, JPEG y PNG.";
        }

        // Validar MIME real
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $archivoTmp);
        finfo_close($finfo);

        if (!in_array($mime, ['image/jpeg', 'image/png'])) {
            $error[] = "El archivo no es una imagen válida.";
        }

        // Validar tamaño máximo 5MB
        if ($_FILES['imagen']['size'] > 5 * 1024 * 1024) {
            $error[] = "El archivo es demasiado grande (máximo 5MB).";
        }

        // Si pasa todas las validaciones, subir imagen
        if (empty($error)) {
            $nombreArchivoSeguro = hash('sha256', uniqid() . $nombreArchivo) . '.' . $archivoExtension;
            $destino = '../uploads/imagenes/usuarios/' . $nombreArchivoSeguro;

            // Crear directorio si no existe
            if (!is_dir('../uploads/imagenes/usuarios/')) {
                mkdir('../uploads/imagenes/usuarios/', 0755, true);
            }

            // Mover archivo
            if (move_uploaded_file($archivoTmp, $destino)) {
                $sql = "INSERT INTO imagenes_usuarios (id_usuario, nombre_imagen, ruta_imagen) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iss", $id_usuario, $nombreArchivoSeguro, $destino);

                if ($stmt->execute()) {
                    $_SESSION['mensaje'] = "Imagen subida correctamente.";
                    header('Location: ../usuarios/listarUsuarios.php');
                    exit();
                } else {
                    $error[] = "Error al insertar en la base de datos.";
                }
            } else {
                $error[] = "Error al mover la imagen.";
            }
        }
    } else {
        $error[] = "Debe seleccionar una imagen.";
    }

    // Si hay errores, almacenarlos en la sesión y redirigir
    if (!empty($error)) {
        $_SESSION['error'] = implode("<br>", $error);
        header("Location: subirImagenUsuario.php");
        exit();
    }
}

// Obtener lista de usuarios
$sql = "SELECT id_usuario, nombreUsuario FROM usuarios";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$usuarios = [];
while ($row = $result->fetch_assoc()) {
    $usuarios[] = $row;
}

?>
