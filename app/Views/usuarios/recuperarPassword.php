<?php

require_once __DIR__ . '/../../../config/cargarConfig.php';


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reiniciar Contraseña</title>
    <link rel="stylesheet" href="../../../frontend/reiniciarPassword.css"> <!-- Asegúrate de tener un archivo CSS para los estilos -->
</head>
<body>
    <div class="container">
        <h1>Reiniciar Contraseña</h1>
        <form action="../../Controller/usuarios/recuperarPasswordController.php" method="POST">
            <div class="form-group">
<input type="hidden" name="token" 
       value="<?php echo isset($_GET['token']) ? htmlspecialchars($_GET['token']) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="password">Nueva Contraseña</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Reiniciar Contraseña</button>
        </form>
        <?php
        if (isset($mensaje)) {
            echo "<p>{$mensaje}</p>";
        }
        ?>
    </div>  
</body>
</html>
