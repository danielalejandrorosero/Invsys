<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transferir Stock | Stock Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .container {
            margin-top: 20px;
        }
        .form-title {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Título -->
        <h4 class="form-title"><i class="fas fa-exchange-alt"></i> Transferir Stock</h4>

        <!-- Mensajes de éxito o error -->
        <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="card-panel green lighten-4 green-text text-darken-4">
                <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($_SESSION['mensaje']); ?>
            </div>
            <?php unset($_SESSION['mensaje']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['errores']) && !empty($_SESSION['errores'])): ?>
            <div class="card-panel red lighten-4 red-text text-darken-4">
                <i class="fas fa-exclamation-circle"></i>
                <ul>
                    <?php foreach ($_SESSION['errores'] as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php unset($_SESSION['errores']); ?>
        <?php endif; ?>

        <!-- Formulario de transferencia -->
        <form action="" method="POST">
            <input type="hidden" name="transferirStock" value="1">

            <!-- Seleccionar Producto -->
            <div class="input-field">
                <select name="id_producto" required>
                    <option value="" disabled selected>Selecciona un producto</option>
                    <?php foreach ($productos as $producto): ?>
                        <option value="<?php echo $producto['id_producto']; ?>">
                            <?php echo htmlspecialchars($producto['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <label>Producto</label>
            </div>

            <!-- Seleccionar Almacén de Origen -->
            <div class="input-field">
                <select name="id_almacen_origen" required>
                    <option value="" disabled selected>Selecciona el almacén de origen</option>
                    <?php foreach ($almacenes as $almacen): ?>
                        <option value="<?php echo $almacen['id_almacen']; ?>">
                            <?php echo htmlspecialchars($almacen['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <label>Almacén de Origen</label>
            </div>

            <!-- Seleccionar Almacén de Destino -->
            <div class="input-field">
                <select name="id_almacen_destino" required>
                    <option value="" disabled selected>Selecciona el almacén de destino</option>
                    <?php foreach ($almacenes as $almacen): ?>
                        <option value="<?php echo $almacen['id_almacen']; ?>">
                            <?php echo htmlspecialchars($almacen['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <label>Almacén de Destino</label>
            </div>

            <!-- Cantidad a Transferir -->
            <div class="input-field">
                <input type="number" name="cantidad" id="cantidad" min="1" required>
                <label for="cantidad">Cantidad a Transferir</label>
            </div>

            <!-- Botón de Enviar -->
            <button type="submit" class="btn waves-effect waves-light green">
                <i class="fas fa-paper-plane"></i> Transferir
            </button>
        </form>
    </div>

    <!-- Materialize JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar select
            var elems = document.querySelectorAll('select');
            M.FormSelect.init(elems);
        });
    </script>
</body>
</html>