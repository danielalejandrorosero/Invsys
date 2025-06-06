<?php
/**
 * Vista de Selección de Proveedor para Historial
 * 
 * Esta vista permite seleccionar un proveedor para ver su historial
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar Proveedor - Historial</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        .collection-item {
            transition: background-color 0.3s ease;
        }
        .collection-item:hover {
            background-color: #e3f2fd !important;
        }
        .provider-info {
            margin: 0;
            padding: 10px 0;
        }
        .provider-info p {
            margin: 5px 0;
            color: #666;
        }
        .provider-info i {
            margin-right: 5px;
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-content">
                <!-- Header -->
                <div class="row">
                    <div class="col s12 m6">
                        <h4><i class="fas fa-history"></i> Seleccionar Proveedor</h4>
                        <p>Seleccione un proveedor para ver su historial</p>
                    </div>
                    <div class="col s12 m6 right-align">
                        <a href="../../Views/usuarios/dashboard.php" class="btn grey">
                            <i class="material-icons left">arrow_back</i> Volver al Dashboard
                        </a>
                    </div>
                </div>

                <!-- Lista de Proveedores -->
                <div class="row">
                    <div class="col s12">
                        <?php if (empty($proveedores)): ?>
                            <div class="card-panel red lighten-4">
                                <span class="white-text">
                                    No hay proveedores disponibles para mostrar.
                                </span>
                            </div>
                        <?php else: ?>
                            <div class="collection">
                                <?php foreach ($proveedores as $proveedor): ?>
                                    <a href="../../Controller/proveedores/historialProveedoresController.php?id=<?= $proveedor['id_proveedor'] ?>" 
                                       class="collection-item">
                                        <div class="row provider-info">
                                            <div class="col s12">
                                                <h5 class="blue-text"><?= htmlspecialchars($proveedor['nombre']) ?></h5>
                                                <div class="row" style="margin: 0;">
                                                    <div class="col s12 m6">
                                                        <p>
                                                            <i class="material-icons tiny">person</i> 
                                                            <strong>Contacto:</strong> <?= htmlspecialchars($proveedor['contacto']) ?>
                                                        </p>
                                                        <p>
                                                            <i class="material-icons tiny">location_on</i> 
                                                            <strong>Dirección:</strong> <?= htmlspecialchars($proveedor['direccion']) ?>
                                                        </p>
                                                    </div>
                                                    <div class="col s12 m6">
                                                        <p>
                                                            <i class="material-icons tiny">email</i> 
                                                            <strong>Email:</strong> <?= htmlspecialchars($proveedor['email']) ?>
                                                        </p>
                                                        <p>
                                                            <i class="material-icons tiny">phone</i> 
                                                            <strong>Teléfono:</strong> <?= htmlspecialchars($proveedor['telefono']) ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
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