<?php

function validarCampos($camposRequeridos)
{
    foreach ($camposRequeridos as $campo) {
        if (!isset($_POST[$campo]) || empty(trim($_POST[$campo]))) {
            die("Error: El campo '{$campo}' es obligatorio.");
        }
    }
}

function nivelRequerido($nivel)
{
    global $sesion;

    // Verificar si el usuario está autenticado
    if (!$sesion->usuarioAutenticado()) {
        header("Location: ../public/index.php");
        exit();
    }

    // Obtener el usuario actual
    $usuarioActual = UsuarioActual();
    if (!$usuarioActual) {
        $_SESSION["error"] =
            "Error: No se pudo obtener la información del usuario.";
        header("Location: ../public/index.php");
        exit();
    }

    // Obtener el nivel del usuario desde la base de datos
    $nivel_sesion = encontrarPorGrupoNivel($usuarioActual["nivel_usuario"]);
    if (!$nivel_sesion) {
        $_SESSION["error"] =
            "Error: No se encontró el nivel del usuario en la base de datos.";
        header("Location: ../public/index.php");
        exit();
    }

    // Si el grupo de usuario está inactivo
    if ($nivel_sesion["estado_grupo"] === 0) {
        $_SESSION["error"] = "Este nivel de usuario ha sido deshabilitado.";
        header("Location: ../public/index.php");
        exit();
    }

    // Verificar si el usuario tiene el nivel requerido
    if ((int) $usuarioActual["nivel_usuario"] > (int) $nivel) {
        if (basename($_SERVER["PHP_SELF"]) !== "index.php") {
            $_SESSION["error"] =
                "Lo siento, no tienes permisos para ver esta página.";
            header("Location: ../../Views/usuarios/dashboard.php");
            exit();
        }
    }
}


function encontrarPorGrupoNivel($nivel)
{
    global $conn;

    $sql = "SELECT nivel_grupo, estado_grupo FROM grupos WHERE nivel_grupo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $nivel);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    $grupo = $resultado->num_rows > 0 ? $resultado->fetch_assoc() : null;

    $stmt->close();
    return $grupo;
}

function UsuarioActual()
{
    static $usuarioActual = null;
    global $conn;

    if ($usuarioActual === null && isset($_SESSION["id_usuario"])) {
        $id_usuario = $_SESSION["id_usuario"];

        $stmt = $conn->prepare(
            "SELECT id_usuario, nombre, email, password, status, last_login, nivel_usuario, nombreUsuario FROM usuarios WHERE id_usuario = ?"
        );
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $usuarioActual = $resultado->fetch_assoc();
        }

        $stmt->close();
    }

    if (!isset($usuarioActual["nivel_usuario"])) {
        throw new Exception(
            "Error: No se encontró el nivel de usuario en la sesión."
        );
    }

    return $usuarioActual;
}

?>
