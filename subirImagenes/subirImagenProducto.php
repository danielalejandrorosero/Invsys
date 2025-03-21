<?php

//session_start(); // Iniciar la sesión
require_once '../config/cargarConfig.php';


$error = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['subirImagenProducto'])) {
    $camposRequeridos = ['id_producto'];
    validarCampos($camposRequeridos);

    // Sanitizar y limpiar datos
    $id_producto = (int) $_POST['id_producto'];
    
    // Validaciones
    if (!$id_producto || $id_producto <= 0) {
        $error[] = "Producto inválido.";
    }

    // Validar existencia del producto en la base de datos
    $sql = "SELECT id_producto FROM productos WHERE id_producto = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_producto);
    $stmt->execute();
    $stmt->bind_result($existe);
    $stmt->fetch();
    $stmt->close();
    
    if (!$existe) {
        $error[] = "El producto seleccionado no existe.";
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
            $destino = '../uploads/imagenes/productos/' . $nombreArchivoSeguro;

            // Crear directorio si no existe
            if (!is_dir('../uploads/imagenes/productos/')) {
                mkdir('../uploads/imagenes/productos/', 0755, true);
            }

            // Mover archivo
            if (move_uploaded_file($archivoTmp, $destino)) {
                $sql = "INSERT INTO imagenes_productos (id_producto, nombre_imagen, ruta_imagen) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iss", $id_producto, $nombreArchivoSeguro, $destino);
                
                if ($stmt->execute()) {
                    $_SESSION['mensaje'] = "Imagen subida correctamente.";
                    header('Location: ../productos/listarProductos.php');
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
        header("Location: subirImagenProducto.php");
        exit();
    }
}

// Obtener los productos de la base de datos
$sql = "SELECT id_producto, nombre FROM productos";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$productos = [];
while ($row = $result->fetch_assoc()) {
    $productos[] = $row;
}
?>
