<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../../public/css/recuperarCorreo.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col s12 m8 offset-m2 l6 offset-l3">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title center-align">
                            <i class="fas fa-key"></i> Recuperar Contraseña
                        </span>
                        <form action="../../Controller/usuarios/solicitarRecuperacionController.php" method="POST">
                            <div class="input-field">
                                <i class="fas fa-envelope prefix"></i>
                                <input type="email" id="correo" name="correo" required>
                                <label for="correo">Correo Electrónico</label>
                            </div>
                            <div class="center-align">
                                <button type="submit" class="btn waves-effect waves-light blue">
                                    <i class="fas fa-paper-plane"></i> Enviar Correo de Recuperación
                                </button>
                            </div>
                        </form>
                        <?php if (!empty($mensaje)): ?>
                            <div class="card-panel green lighten-4 green-text text-darken-4">
                                <i class="fas fa-check-circle"></i>
                                <span><?php echo $mensaje; ?></span>
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
