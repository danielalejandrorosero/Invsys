<?php

require_once __DIR__ . '/../../../config/cargarConfig.php';
require_once __DIR__ . '/../../Models/productos/productos.php';
require_once __DIR__ . '/../../Models/imagen/imagen.php';
require_once __DIR__ . '/../../Models/usuarios/Usuarios.php';


class SubirImagenController {

    private $usuarioModel;
    private $productoModel;
    private $imagenModel;

    public function __construct($conn) {
        $this->usuarioModel = new Usuario($conn);
        $this->productoModel = new Productos($conn);
        $this->imagenModel = new Imagen($conn);
    }

    public function subirImagen($tipo) {
        $error = [];
        $data = [];
        $redirectSuccess = '';
        $view = '';

        // Determinar si es para un usuario o producto
        if ($tipo == 'usuario') {
            $id = $_SESSION['id_usuario'];
            $destinoBase = __DIR__ . '/../../../public/uploads/imagenes/usuarios/';
            $redirectSuccess = '../../Controller/usuarios/listarUsuarios.php';
            $view = 'subirImagenUsuarioView.php';
            $data = $this->usuarioModel->obtenerUsuarios();
        } elseif ($tipo == 'producto') {
            $id = isset($_POST['id_producto']) ? (int)$_POST['id_producto'] : null; 
            $destinoBase = __DIR__ . '/../../../public/uploads/imagenes/productos/';
            $redirectSuccess = '../../Controller/productos/ListarProductosController.php';
            $view = 'subirImagenProductoView.php';
            $data = $this->productoModel->obtenerProductos();
        } else {
            die("Tipo de imagen no válido.");
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["subirImagen{$tipo}"])) {
            if ($tipo === 'producto') {
                if (!$id || $id <= 0) {
                    $error[] = "Producto inválido.";
                } elseif (!$this->productoModel->nombreProductoExiste($id)) {
                    $error[] = "El producto no existe.";
                }
            } elseif ($tipo === 'usuario' && !$id) {
                $error[] = "Usuario no autenticado.";
            }

            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $archivoTmp = $_FILES['imagen']['tmp_name'];
                $nombreArchivo = $_FILES['imagen']['name'];
                $archivoExtension = strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));

                $extensionesPermitidas = ['jpg', 'jpeg', 'png'];
                if (!in_array($archivoExtension, $extensionesPermitidas)) {
                    $error[] = "Solo se permiten archivos JPG, JPEG y PNG.";
                }

                // Validar tamaño máximo (5MB)
                if ($_FILES['imagen']['size'] > 5 * 1024 * 1024) {
                    $error[] = "El archivo es demasiado grande (máximo 5MB).";
                }

                // Validar MIME real
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($finfo, $archivoTmp);
                finfo_close($finfo);
                if (!in_array($mime, ['image/jpeg', 'image/png'])) {
                    $error[] = "El archivo no es una imagen válida.";
                }

                // Si no hay errores, procesar la imagen
                if (empty($error)) {
                    $nombreArchivoSeguro = hash('sha256', uniqid() . $nombreArchivo) . '.' . $archivoExtension;
                    $destino = $destinoBase . $nombreArchivoSeguro;

                    // Crear directorio si no existe
                    if (!is_dir(dirname($destino))) {
                        mkdir(dirname($destino), 0755, true);
                    }

                    // Mover archivo y guardar en la base de datos
                    if (move_uploaded_file($archivoTmp, $destino)) {
                        if ($this->imagenModel->subirImagen($tipo, $id, $nombreArchivoSeguro, $destino)) {
                            $_SESSION['mensaje'] = "Imagen subida correctamente.";
                            header("Location: $redirectSuccess");
                            exit();
                        } else {
                            $error[] = "Error al insertar la imagen en la base de datos.";
                        }
                    } else {
                        $error[] = "Error al mover la imagen.";
                    }
                }
            } else {
                $error[] = "Debe seleccionar una imagen.";
            }

            // Si hay errores, almacenar en sesión
            if (!empty($error)) {
                $_SESSION['errores'] = $error;
            }
        }

        // Cargar la vista con los datos
        $productos = $tipo === 'producto' ? $data : null;
        $usuarios = $tipo === 'usuario' ? $data : null;
        require_once __DIR__ . '/../../Views/subirImagenes/' . $view;
    }
}

    
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'producto'; 
$controller = new SubirImagenController($conn);
$controller->subirImagen($tipo);
?>