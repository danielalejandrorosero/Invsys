<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuarios | Stock Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f0f3fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            width: 100%;
        }

        .card {
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .card-header h1 {
            margin: 0;
            font-size: 24px;
        }

        .user-count {
            font-size: 16px;
            color: #6c757d;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .action-button {
            display: inline-flex;
            align-items: center;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            color: white;
            transition: background-color 0.3s ease;
        }

        .action-button.primary {
            background-color: #4a6cf7;
        }

        .action-button.primary:hover {
            background-color: #3151e4;
        }

        .action-button.secondary {
            background-color: #6c757d;
        }

        .action-button.secondary:hover {
            background-color: #5a6268;
        }

        .action-button i {
            margin-right: 8px;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .users-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .users-table th, .users-table td {
            padding: 12px 15px;
            text-align: left;
        }

        .users-table th {
            background-color: #f8f9fa;
            color: #343a40;
        }

        .users-table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #4a6cf7;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            margin-right: 10px;
        }

        .user-details {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: bold;
        }

        .user-username {
            font-size: 14px;
            color: #6c757d;
        }

        .badge {
            padding: 5px 10px;
            border-radius: 12px;
            color: white;
            font-size: 14px;
        }

        .badge-admin {
            background-color: #dc3545;
        }

        .badge-user {
            background-color: #28a745;
        }

        .action-cell {
            display: flex;
            align-items: center;
        }

        .row-actions {
            display: flex;
            gap: 10px;
        }

        .row-action {
            color: #4a6cf7;
            transition: color 0.3s ease;
        }

        .row-action:hover {
            color: #3151e4;
        }

        .pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .pagination-info {
            font-size: 14px;
            color: #6c757d;
        }

        .pagination-controls {
            display: flex;
            gap: 5px;
        }

        .page-item {
            padding: 8px 12px;
            border-radius: 8px;
            background-color: #f8f9fa;
            color: #343a40;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .page-item:hover {
            background-color: #e9ecef;
        }

        .page-item.active {
            background-color: #4a6cf7;
            color: white;
        }

        .page-item.disabled {
            background-color: #e9ecef;
            color: #6c757d;
            cursor: not-allowed;
        }

        .empty-state {
            text-align: center;
            padding: 50px 20px;
        }

        .empty-state i {
            font-size: 48px;
            color: #6c757d;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .empty-state p {
            font-size: 16px;
            color: #6c757d;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-content">
                <span class="card-title">
                    <i class="fas fa-users"></i> Lista de Usuarios
                </span>
                <?php if (!empty($usuarios)): ?>
                    <span class="user-count"><?php echo count(
                        $usuarios
                    ); ?> usuarios</span>
                <?php endif; ?>
                <div class="right-align">
                    <a href="../../Controller/usuarios/agregarController.php" class="btn waves-effect waves-light green">
                        <i class="fas fa-user-plus"></i> Agregar Usuario
                    </a>
                    <a href="../../Views/usuarios/index.php" class="btn waves-effect waves-light grey">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </div>
            </div>

            <?php if (!empty($usuarios)): ?>
                <div class="card-content">
                    <div class="table-responsive">
                        <table class="highlight">
                            <thead>
                                <tr>
                                    <th>Usuario</th>
                                    <th>Email</th>
                                    <th>Fecha de registro</th>
                                    <th>Grupo</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($usuarios as $usuario):

                                    // Obtener iniciales para el avatar
                                    $iniciales = substr(
                                        $usuario["nombre"],
                                        0,
                                        1
                                    );
                                    // Determinar el tipo de badge según el grupo
                                    $badgeClass =
                                        $usuario["grupo"] == "Administrador"
                                            ? "red"
                                            : "green";
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="user-info">
                                                <div class="user-avatar circle">
                                                    <?php echo htmlspecialchars(
                                                        $iniciales
                                                    ); ?>
                                                </div>
                                                <div class="user-details">
                                                    <span class="user-name"><?php echo htmlspecialchars(
                                                        $usuario["nombre"]
                                                    ); ?></span>
                                                    <span class="user-username">@<?php echo htmlspecialchars(
                                                        $usuario[
                                                            "nombre_usuario"
                                                        ] ?? "username"
                                                    ); ?></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars(
                                            $usuario["email"]
                                        ); ?></td>
                                        <td><?php echo htmlspecialchars(
                                            $usuario["fecha_registro"] ??
                                                date("d/m/Y")
                                        ); ?></td>
                                        <td><span class="new badge <?php echo $badgeClass; ?>" data-badge-caption=""><?php echo htmlspecialchars(
    $usuario["grupo"]
); ?></span></td>
                                        <td>
                                            <div class="row-actions">
                                                <a href="../../Controller/usuarios/editarUsuario.php?id=<?php echo $usuario[
                                                    "id"
                                                ] ??
                                                    ""; ?>" class="btn-floating btn-small waves-effect waves-light blue" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php
                                endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination controls -->
                    <ul class="pagination">
                        <li class="disabled">
                            <a href="#!"><i class="fas fa-chevron-left"></i></a>
                        </li>
                        <li class="active"><a href="#!">1</a></li>
                        <li class="disabled">
                            <a href="#!"><i class="fas fa-chevron-right"></i></a>
                        </li>
                    </ul>
                </div>
            <?php else: ?>
                <div class="card-content center-align">
                    <i class="fas fa-users fa-3x"></i>
                    <h5>No hay usuarios para mostrar</h5>
                    <p>No se encontraron usuarios en el sistema. Puedes agregar un nuevo usuario usando el botón de arriba.</p>
                    <a href="../../Controller/usuarios/agregarController.php" class="btn waves-effect waves-light green">
                        <i class="fas fa-user-plus"></i> Agregar Usuario
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
