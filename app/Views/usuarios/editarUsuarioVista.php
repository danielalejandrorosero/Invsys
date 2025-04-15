<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario | Stock Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../../public/css/editarUsuario.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <div class="sidebar-header">
                <div class="app-logo">
                    <i class="fas fa-boxes"></i>
                    <span>StockManager</span>
                </div>
            </div>

            <div class="user-profile">
                <div class="user-avatar">
                    <?php
                    // Get first letter of name for avatar
                    $firstLetter = !empty($usuario["nombre"])
                        ? strtoupper(substr($usuario["nombre"], 0, 1))
                        : "U";
                    echo htmlspecialchars($firstLetter);
                    ?>
                    <div class="edit-avatar">
                        <i class="fas fa-camera"></i>
                    </div>
                </div>
                <div class="user-name"><?php echo htmlspecialchars(
                    $usuario["nombre"] ?? "Usuario"
                ); ?></div>
                <div class="user-role">
                    <i class="fas fa-badge-check"></i>
                    <?php echo isset($usuario["nivel_usuario"]) &&
                    $usuario["nivel_usuario"] == 1
                        ? "Administrador"
                        : "Usuario Regular"; ?>
                </div>
            </div>

            <div class="sidebar-nav">
                <a href="../../Views/usuarios/index.php" class="nav-item">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
                <a href="#" class="nav-item active">
                    <i class="fas fa-user-edit"></i>
                    <span>Editar Perfil</span>
                </a>
                <a href="#" class="nav-item">
                    <i class="fas fa-lock"></i>
                    <span>Seguridad</span>
                </a>
                <a href="#" class="nav-item">
                    <i class="fas fa-sliders-h"></i>
                    <span>Preferencias</span>
                </a>
                <a href="../../Controller/usuarios/cerrarSesionController.php" class="nav-item">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Cerrar Sesión</span>
                </a>
            </div>
        </div>

        <div class="content">
            <div class="page-header">
                <h1 class="page-title">Editar Perfil</h1>
                <p class="page-subtitle">Actualiza tu información personal en el sistema</p>
            </div>

            <form action="../../Controller/usuarios/editarUsuario.php" method="POST">
                <input type="hidden" name="id_usuario" value="<?php echo htmlspecialchars(
                    $_SESSION["id_usuario"]
                ); ?>">

                <div class="form-row">
                    <label for="nombre" class="form-label">Nombre Completo</label>
                    <div class="input-icon-group">
                        <i class="fas fa-user input-icon"></i>
                        <input type="text" id="nombre" name="nombre"
                               class="form-control has-icon"
                               value="<?php echo htmlspecialchars(
                                   $usuario["nombre"] ?? ""
                               ); ?>"
                               placeholder="Ingrese su nombre completo"
                               required>
                    </div>
                </div>

                <div class="form-row">
                    <label for="nombreUsuario" class="form-label">Nombre de Usuario</label>
                    <div class="input-icon-group">
                        <i class="fas fa-at input-icon"></i>
                        <input type="text" id="nombreUsuario" name="nombreUsuario"
                               class="form-control has-icon <?php echo isset(
                                   $_SESSION["error_nombreUsuario"]
                               )
                                   ? "error"
                                   : ""; ?>"
                               value="<?php echo htmlspecialchars(
                                   $usuario["nombreUsuario"] ?? ""
                               ); ?>"
                               placeholder="Ingrese su nombre de usuario"
                               required>
                    </div>
                    <?php if (isset($_SESSION["error_nombreUsuario"])): ?>
                        <div class="error-msg">
                            <i class="fas fa-exclamation-circle"></i>
                            <?php
                            echo $_SESSION["error_nombreUsuario"];
                            unset($_SESSION["error_nombreUsuario"]);
                            ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-row">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <div class="input-icon-group">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" id="email" name="email"
                               class="form-control has-icon"
                               value="<?php echo htmlspecialchars(
                                   $usuario["email"] ?? ""
                               ); ?>"
                               placeholder="ejemplo@correo.com"
                               readonly>
                    </div>
                    <small style="color: #6c757d; margin-top: 5px; display: block;">
                        <i class="fas fa-info-circle"></i>
                        El correo electrónico no se puede modificar.
                    </small>
                </div>

                <div class="action-buttons">
                    <button type="submit" name="actualizarUsuario" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                    <a href="index.php" class="btn btn-light">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </form>

            <?php if (isset($_SESSION["mensaje"])): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <span><?php
                    echo $_SESSION["mensaje"];
                    unset($_SESSION["mensaje"]);
                    ?></span>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION["error"])): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span><?php
                    echo $_SESSION["error"];
                    unset($_SESSION["error"]);
                    ?></span>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
