<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario | Stock Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f0f3fa;
            min-height: 100vh;
            padding: 50px 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.07);
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .sidebar {
            background: linear-gradient(to bottom, #4a6cf7, #2f46bd);
            padding: 40px 30px;
            color: white;
        }

        .sidebar-header {
            margin-bottom: 40px;
            text-align: center;
        }

        .app-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 50px;
        }

        .app-logo i {
            font-size: 30px;
            margin-right: 10px;
        }

        .user-profile {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px 0;
        }

        .user-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            font-weight: 600;
            margin-bottom: 20px;
            position: relative;
            border: 3px solid rgba(255, 255, 255, 0.3);
        }

        .edit-avatar {
            position: absolute;
            bottom: 0;
            right: 0;
            background: white;
            color: var(--primary);
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
        }

        .edit-avatar:hover {
            transform: scale(1.1);
        }

        .user-name {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 8px;
            text-align: center;
        }

        .user-role {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 30px;
            display: flex;
            align-items: center;
        }

        .user-role i {
            margin-right: 6px;
        }

        .sidebar-nav {
            margin-top: 40px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: rgba(255, 255, 255, 0.7);
            border-radius: 8px;
            margin-bottom: 8px;
            text-decoration: none;
            transition: var(--transition);
        }

        .nav-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .nav-item.active {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .nav-item i {
            margin-right: 12px;
            font-size: 18px;
        }

        .content {
            padding: 40px;
        }

        .page-header {
            margin-bottom: 30px;
        }

        .page-title {
            font-size: 24px;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 10px;
        }

        .page-subtitle {
            color: var(--secondary);
            font-size: 15px;
        }

        .form-row {
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark);
        }

        .form-control {
            display: block;
            width: 100%;
            padding: 12px 16px;
            font-size: 15px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            background-color: white;
            transition: var(--transition);
        }

        .form-control:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 4px rgba(74, 108, 247, 0.15);
        }

        .form-control.error {
            border-color: var(--danger);
        }

        .error-msg {
            display: flex;
            align-items: center;
            color: var(--danger);
            font-size: 13px;
            margin-top: 6px;
        }

        .error-msg i {
            margin-right: 6px;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 40px;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
        }

        .btn i {
            margin-right: 8px;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 12px rgba(74, 108, 247, 0.25);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            box-shadow: 0 6px 16px rgba(74, 108, 247, 0.35);
            transform: translateY(-2px);
        }

        .btn-light {
            background: #e9ecef;
            color: var(--secondary);
        }

        .btn-light:hover {
            background: #dee2e6;
            color: var(--dark);
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            display: flex;
            align-items: flex-start;
        }

        .alert i {
            margin-right: 12px;
            font-size: 18px;
            padding-top: 2px;
        }

        .alert-success {
            background-color: rgba(40, 167, 69, 0.1);
            color: var(--success);
        }

        .alert-danger {
            background-color: rgba(220, 53, 69, 0.1);
            color: var(--danger);
        }

        .input-icon-group {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--secondary);
        }

        .has-icon {
            padding-left: 45px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .container {
                grid-template-columns: 1fr;
            }

            .sidebar {
                padding: 30px 20px;
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            .sidebar-nav {
                display: none;
            }

            .user-avatar {
                width: 80px;
                height: 80px;
                font-size: 30px;
            }

            .content {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col s12 m4">
                <div class="card-panel blue darken-3 white-text">
                    <div class="center-align">
                        <i class="fas fa-boxes fa-3x"></i>
                        <h5>StockManager</h5>
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
                        <a href="../../Views/usuarios/index.php" class="nav-item waves-effect waves-light">
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
                                <a href="index.php" class="btn waves-effect waves-light grey">
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
