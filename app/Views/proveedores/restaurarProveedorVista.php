<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurar Proveedor | Stock Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-content">
                <span class="card-title">
                    <i class="fas fa-trash-restore"></i> Restaurar Proveedor
                </span>
                <p>Está a punto de restaurar un proveedor previamente eliminado</p>
            </div>

            <div class="card-content">
                <?php if (isset($_SESSION["errores"])): ?>
                    <div class="card-panel red lighten-4 red-text text-darken-4">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>
                            <?php foreach ($_SESSION["errores"] as $error): ?>
                                <div><?php echo $error; ?></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php unset($_SESSION["errores"]); ?>
                <?php endif; ?>

                <div class="card-panel blue lighten-4 blue-text text-darken-4">
                    <div class="proveedor-info">
                        <h5><?php echo htmlspecialchars(
                            $proveedor["nombre"]
                        ); ?></h5>
                        <p>
                            RUC/NIT: <?php echo htmlspecialchars(
                                $proveedor["ruc"] ?? "N/A"
                            ); ?> |
                            Teléfono: <?php echo htmlspecialchars(
                                $proveedor["telefono"] ?? "N/A"
                            ); ?>
                        </p>
                        <p>
                            Email: <?php echo htmlspecialchars(
                                $proveedor["email"] ?? "N/A"
                            ); ?>
                        </p>
                    </div>
                </div>

                <p class="confirmation-text">
                    ¿Está seguro que desea <strong>restaurar</strong> este proveedor?<br>
                    El proveedor volverá a estar disponible en el sistema activo.
                </p>

                <form method="POST">
                    <input type="hidden" name="confirmarRestaurar" value="1">
                    <div class="row">
                        <div class="col s12">
                            <button type="submit" class="btn waves-effect waves-light green">
                                <i class="fas fa-trash-restore"></i> Restaurar Proveedor
                            </button>
                            <a href="../../Controller/proveedores/ListarProveedoresEliminadosController.php" class="btn waves-effect waves-light grey">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                        </div>
                    </div>
                </form>

                <div class="card-panel yellow lighten-4 yellow-text text-darken-4">
                    <i class="fas fa-info-circle"></i>
                    <div>
                        Al restaurar este proveedor, se recuperarán sus datos y volverá a estar disponible para asociar con productos y realizar compras.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>