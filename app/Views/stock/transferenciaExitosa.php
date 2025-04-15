<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transferencia Exitosa</title>
    <link rel="stylesheet" href="../../../frontend/transferenciaExitosa.css"> <!-- Asegúrate de tener un archivo CSS para los estilos -->
</head>
<body>
    <div class="container">
        <h1>Transferencia realizada con éxito</h1>
        <?php if (!empty($_SESSION["success"])) {
            echo "<p style='color:green;'>{$_SESSION["success"]}</p>";
            unset($_SESSION["success"]);
        } ?>
    </div>
</body>
</html>
