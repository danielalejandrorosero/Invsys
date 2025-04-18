<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transferencia Exitosa</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
</head>
<body>
    <div class="container center-align" style="margin-top: 50px;">
        <h1 class="green-text">Transferencia realizada con éxito</h1>
        <?php if (!empty($_SESSION["success"])): ?>
            <p class="green-text"><?php echo $_SESSION["success"]; ?></p>
            <?php unset($_SESSION["success"]); ?>
        <?php endif; ?>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
