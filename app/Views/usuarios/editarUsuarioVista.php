<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario | InvSys</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../../public/css/editarUsuario.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col s12 m4">
                <div class="card-panel blue darken-3 white-text">
                    <div class="center-align">
                        <i class="fas fa-boxes fa-3x"></i>
                        <h5>InvSys</h5>
                    </div>
                    <div class="user-profile center-align">
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
                        <a href="../../Views/usuarios/dashboard.php" class="nav-item waves-effect waves-light">
                            <i class="fas fa-home"></i>
                            <span>Dashboard</span>
                        </a>
                        <a href="#" class="nav-item active waves-effect waves-light">
                            <i class="fas fa-user-edit"></i>
                            <span>Editar Perfil</span>
                        </a>
                        <a href="#" class="nav-item waves-effect waves-light">
                            <i class="fas fa-lock"></i>
                            <span>Seguridad</span>
                        </a>
                        <a href="#" class="nav-item waves-effect waves-light">
                            <i class="fas fa-sliders-h"></i>
                            <span>Preferencias</span>
                        </a>
                        <a href="../../Controller/usuarios/cerrarSesionController.php" class="nav-item waves-effect waves-light">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Cerrar Sesión</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col s12 m8">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title">Editar Perfil</span>
                        <p>Actualiza tu información personal en el sistema</p>
                        <form action="../../Controller/usuarios/editarUsuario.php" method="POST">
                            <input type="hidden" name="id_usuario" value="<?php echo htmlspecialchars(
                                $_SESSION["id_usuario"]
                            ); ?>">

                            <div class="input-field">
                                <i class="fas fa-user prefix"></i>
                                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars(
                                    $usuario["nombre"] ?? ""
                                ); ?>" required>
                                <label for="nombre">Nombre Completo</label>
                            </div>

                            <div class="input-field">
                                <i class="fas fa-at prefix"></i>
                                <input type="text" id="nombreUsuario" name="nombreUsuario" value="<?php echo htmlspecialchars(
                                    $usuario["nombreUsuario"] ?? ""
                                ); ?>" required>
                                <label for="nombreUsuario">Nombre de Usuario</label>
                                <?php if (
                                    isset($_SESSION["error_nombreUsuario"])
                                ): ?>
                                    <span class="helper-text red-text">
                                        <i class="fas fa-exclamation-circle"></i>
                                        <?php
                                        echo $_SESSION["error_nombreUsuario"];
                                        unset($_SESSION["error_nombreUsuario"]);
                                        ?>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <div class="input-field">
                                <i class="fas fa-envelope prefix"></i>
                                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars(
                                    $usuario["email"] ?? ""
                                ); ?>" readonly>
                                <label for="email">Correo Electrónico</label>
                                <span class="helper-text grey-text">
                                    <i class="fas fa-info-circle"></i>
                                    El correo electrónico no se puede modificar.
                                </span>
                            </div>

                            <div class="right-align">
                                <button type="submit" name="actualizarUsuario" class="btn waves-effect waves-light blue">
                                    <i class="fas fa-save"></i> Guardar Cambios
                                </button>
                                <a href="../../Views/usuarios/dashboard.php" class="btn waves-effect waves-light grey">
                                    <i class="fas fa-times"></i> Cancelar
                                </a>
                            </div>
                        </form>

                        <?php if (isset($_SESSION["mensaje"])): ?>
                            <div class="card-panel green lighten-4 green-text text-darken-4">
                                <i class="fas fa-check-circle"></i>
                                <span><?php
                                echo $_SESSION["mensaje"];
                                unset($_SESSION["mensaje"]);
                                ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($_SESSION["error"])): ?>
                            <div class="card-panel red lighten-4 red-text text-darken-4">
                                <i class="fas fa-exclamation-triangle"></i>
                                <span><?php
                                echo $_SESSION["error"];
                                unset($_SESSION["error"]);
                                ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
