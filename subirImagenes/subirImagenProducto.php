<?php

require_once '../config/cargarConfig.php';
session_start(); // Iniciar sesión

// Verificar si el usuario está logueado
if (!isset($_SESSION['id_usuario'])) {
    $_SESSION['error'] = "Debes iniciar sesión para subir imágenes.";
    header('Location: login.php'); // Redirigir a la página de inicio de sesión
    exit();
}

// Verificar si el usuario es administrador (nivel admin)
if ($_SESSION['usuario']['rol'] !== 'admin') {
    $_SESSION['error'] = "No tienes permisos para subir imágenes.";
    header('Location: lista_productos.php');
    exit();
}

$error = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Validar que se haya enviado un producto válido
    if (!isset($_POST['id_producto']) || !is_numeric($_POST['id_producto'])) {
        $error[] = "No se ha enviado un producto válido.";
    } else {
        $id_producto = (int) $_POST['id_producto'];
    }

    // Si el administrador no sube imagen, se permite continuar sin error
    if (empty($_FILES['imagen']['name'])) {
        $_SESSION['mensaje'] = "Producto actualizado sin imagen.";
        header('Location: lista_productos.php');
        exit();
    }

    // Validar la imagen
    if ($_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
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

        if (empty($error)) {
            // Generar nombre único
            $nombreArchivoEncriptado = md5($nombreArchivo . time()) . '.' . $archivoExtension;
            $destino = '../uploads/imagenes/productos/' . $nombreArchivoEncriptado;

            // Crear directorio si no existe
            if (!file_exists('../uploads/imagenes/productos/')) {
                mkdir('../uploads/imagenes/productos/', 0755, true);
            }

            // Mover archivo
            if (move_uploaded_file($archivoTmp, $destino)) {
                // Insertar en la BD
                $sql = "INSERT INTO imagenes_productos (id_producto, nombre_imagen, ruta_imagen) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iss", $id_producto, $nombreArchivoEncriptado, $destino);

                if ($stmt->execute()) {
                    $_SESSION['mensaje'] = "Imagen subida correctamente.";
                    header('Location: lista_productos.php');
                    exit();
                } else {
                    $error[] = "Error al insertar en la base de datos.";
                }
            } else {
                $error[] = "Error al mover la imagen.";
            }
        }
    } else {
        $error[] = "Error en la subida del archivo.";
    }

    // Si hay errores, guardarlos en sesión y redirigir
    if (!empty($error)) {
        $_SESSION['error'] = implode("<br>", $error);
        // si hay errores, se redirige a la página de subir imagen
        header('Location: subir_imagen.php');
        exit();
    }
}
