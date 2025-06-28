<?php
// Mostrar mensajes de sesión si existen
if (isset($_SESSION['mensaje'])) {
    echo "<div class='alert alert-success'>" . htmlspecialchars($_SESSION['mensaje']) . "</div>";
    unset($_SESSION['mensaje']);
}

if (isset($_SESSION['errores'])) {
    foreach ($_SESSION['errores'] as $error) {
        echo "<div class='alert alert-error'>" . htmlspecialchars($error) . "</div>";
    }
    unset($_SESSION['errores']);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Proveedores</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../public/css/listarProveedores.css">
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-truck"></i>
                Gestión de Proveedores
            </h1>
            <a href="../../Controller/proveedores/agregarProveedor.php" class="btn btn-success">
                <i class="fas fa-plus"></i>
                Nuevo Proveedor
            </a>
        </div>

        <!-- Buscador -->
        <div class="search-container">
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Buscar por nombre, email o teléfono..." onkeyup="filterTable()">
                <button type="button" class="btn btn-secondary" onclick="clearSearch()">
                    <i class="fas fa-times"></i>
                    Limpiar
                </button>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="stats">
            <p>
                <i class="fas fa-chart-bar"></i>
                Total de proveedores: <strong><?php echo $totalProveedores; ?></strong> | 
                Página <strong><?php echo $page; ?></strong> de <strong><?php echo $totalPaginas; ?></strong>
            </p>
        </div>

        <!-- Tabla de proveedores -->
        <div class="table-container">
            <?php if (empty($proveedores)): ?>
                <div class="no-data">
                    <i class="fas fa-inbox"></i>
                    <p>No hay proveedores registrados en el sistema.</p>
                    <a href="../../Controller/proveedores/agregarProveedor.php" class="btn btn-success">
                        <i class="fas fa-plus"></i>
                        Crear primer proveedor
                    </a>
                </div>
            <?php else: ?>
                <table class="table" id="proveedoresTable">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Contacto</th>
                            <th>Información de Contacto</th>
                            <th>Dirección</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($proveedores as $proveedor): ?>
                        <tr>
                            <td>
                                <div class="provider-name">
                                    <i class="fas fa-building"></i>
                                    <?php echo htmlspecialchars($proveedor['nombre']); ?>
                                </div>
                            </td>
                            <td>
                                <div class="contact-person">
                                    <i class="fas fa-user"></i>
                                    <?php echo htmlspecialchars($proveedor['contacto']); ?>
                                </div>
                            </td>
                            <td>
                                <div class="contact-info">
                                    <div><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($proveedor['email']); ?></div>
                                    <div><i class="fas fa-phone"></i> <?php echo htmlspecialchars($proveedor['telefono']); ?></div>
                                </div>
                            </td>
                            <td>
                                <div class="address-info">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <?php echo htmlspecialchars($proveedor['direccion']); ?>
                                </div>
                            </td>
                            <td class="actions">
                                <a href="../../Controller/proveedores/editarProveedorController.php?id=<?php echo $proveedor["id_proveedor"]; ?>" class="btn btn-secondary btn-sm" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="confirmarEliminacion(<?php echo $proveedor['id_proveedor']; ?>, '<?php echo htmlspecialchars($proveedor['nombre'], ENT_QUOTES); ?>')" 
                                        class="btn btn-danger btn-sm" title="Eliminar proveedor">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <!-- Paginación -->
        <?php if ($totalPaginas > 1): ?>
        <div class="pagination">
            <!-- Botón Anterior -->
            <?php if ($page > 1): ?>
                <a href="?page=1" title="Primera página"><i class="fas fa-angle-double-left"></i></a>
                <a href="?page=<?php echo $page - 1; ?>" title="Página anterior"><i class="fas fa-angle-left"></i></a>
            <?php else: ?>
                <span class="disabled"><i class="fas fa-angle-double-left"></i></span>
                <span class="disabled"><i class="fas fa-angle-left"></i></span>
            <?php endif; ?>

            <!-- Números de página -->
            <?php
            $start = max(1, $page - 2);
            $end = min($totalPaginas, $page + 2);
            
            for ($i = $start; $i <= $end; $i++):
            ?>
                <?php if ($i == $page): ?>
                    <span class="current"><?php echo $i; ?></span>
                <?php else: ?>
                    <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <!-- Botón Siguiente -->
            <?php if ($page < $totalPaginas): ?>
                <a href="?page=<?php echo $page + 1; ?>" title="Página siguiente"><i class="fas fa-angle-right"></i></a>
                <a href="?page=<?php echo $totalPaginas; ?>" title="Última página"><i class="fas fa-angle-double-right"></i></a>
            <?php else: ?>
                <span class="disabled"><i class="fas fa-angle-right"></i></span>
                <span class="disabled"><i class="fas fa-angle-double-right"></i></span>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
    <script src="../../../public/js/listarProveedores.js"></script> 
</body>
</html>