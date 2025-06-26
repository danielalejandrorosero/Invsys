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
        #almacen-origen-info {
            margin: 20px 0;
            padding: 15px;
            border-radius: 5px;
            background-color: #e8f5e9;
            display: none;
        }
        #error-mensaje {
            color: #d32f2f;
            margin: 10px 0;
            display: none;
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
        <form action="" method="POST" id="transferencia-form">
            <input type="hidden" name="transferirStock" value="1">
            <input type="hidden" id="id_almacen_origen" name="id_almacen_origen" value="">

            <!-- Seleccionar Producto -->
            <div class="input-field">
                <select name="id_producto" id="id_producto" required>
                    <option value="" disabled selected>Selecciona un producto</option>
                    <?php foreach ($productos as $producto): ?>
                        <option value="<?php echo $producto['id_producto']; ?>">
                            <?php echo htmlspecialchars($producto['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <label>Producto</label>
            </div>

            <!-- Información del almacén de origen (se muestra después de seleccionar producto) -->
            <div id="almacen-origen-info" class="card-panel">
                <h6><i class="fas fa-warehouse"></i> Almacén de origen:</h6>
                <p><strong id="nombre-almacen-origen"></strong></p>
                <p><small class="grey-text">Stock disponible: <span id="stock-disponible">0</span> unidades</small></p>
            </div>
            
            <!-- Mensaje de error si no hay stock -->
            <div id="error-mensaje" class="card-panel red lighten-4 red-text text-darken-4">
                <i class="fas fa-exclamation-circle"></i> No hay stock disponible de este producto en ningún almacén.
            </div>

            <!-- Seleccionar Almacén de Destino -->
            <div class="input-field">
                <select name="id_almacen_destino" id="id_almacen_destino" required>
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
            
            // Manejar cambio en el select de productos
            document.getElementById('id_producto').addEventListener('change', function() {
                const productoId = this.value;
                if (productoId) {
                    obtenerAlmacenOrigen(productoId);
                }
            });
            
            // Validar que no se elija el mismo almacén de origen y destino
            document.getElementById('transferencia-form').addEventListener('submit', function(e) {
                const idAlmacenOrigen = document.getElementById('id_almacen_origen').value;
                const idAlmacenDestino = document.getElementById('id_almacen_destino').value;
                
                if (idAlmacenOrigen === idAlmacenDestino) {
                    e.preventDefault();
                    M.toast({html: 'El almacén de origen y destino no pueden ser el mismo', classes: 'red'});
                }
            });
        });
        
        // Función para obtener el almacén de origen mediante AJAX
        function obtenerAlmacenOrigen(productoId) {
            // Crear objeto FormData para enviar datos
            const formData = new FormData();
            formData.append('obtenerAlmacenOrigen', '1');
            formData.append('id_producto', productoId);
            
            // Realizar petición AJAX
            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    // Mostrar mensaje de error
                    document.getElementById('error-mensaje').style.display = 'block';
                    document.getElementById('almacen-origen-info').style.display = 'none';
                    document.getElementById('id_almacen_origen').value = '';
                } else {
                    // Mostrar información del almacén de origen
                    document.getElementById('error-mensaje').style.display = 'none';
                    document.getElementById('almacen-origen-info').style.display = 'block';
                    document.getElementById('nombre-almacen-origen').textContent = data.nombre;
                    document.getElementById('id_almacen_origen').value = data.id_almacen;
                    document.getElementById('stock-disponible').textContent = data.cantidad_disponible;
                    
                    // Actualizar el máximo del campo cantidad
                    document.getElementById('cantidad').max = data.cantidad_disponible;
                    document.getElementById('cantidad').placeholder = `Máximo: ${data.cantidad_disponible}`;
                    
                    // Actualizar opciones del almacén de destino para evitar seleccionar el mismo
                    const selectDestino = document.getElementById('id_almacen_destino');
                    for (let i = 0; i < selectDestino.options.length; i++) {
                        if (selectDestino.options[i].value == data.id_almacen) {
                            selectDestino.options[i].disabled = true;
                        } else {
                            selectDestino.options[i].disabled = false;
                        }
                    }
                    // Reinicializar el select de Materialize
                    M.FormSelect.init(selectDestino);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                M.toast({html: 'Error al obtener información del almacén', classes: 'red'});
            });
        }
    </script>
</body>
</html>