<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuarios | Stock Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../../public/css/listarUsuarios.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="card-header-left">
                    <h1>Lista de Usuarios</h1>
                    <?php if (!empty($usuarios)): ?>
                        <span class="user-count"><?php echo count(
                            $usuarios
                        ); ?> usuarios</span>
                    <?php endif; ?>
                </div>
                <div class="action-buttons">
                    <a href="../../Controller/usuarios/agregarController.php" class="action-button primary">
                        <i class="fas fa-user-plus"></i> Agregar Usuario
                    </a>
                    <a href="../../Views/usuarios/index.php" class="action-button secondary">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </div>
            </div>

            <?php if (!empty($usuarios)): ?>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="users-table">
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
                                            ? "badge-admin"
                                            : "badge-user";
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="user-info">
                                                <div class="user-avatar">
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
                                        <td><span class="badge <?php echo $badgeClass; ?>"><?php echo htmlspecialchars(
    $usuario["grupo"]
); ?></span></td>
                                        <td class="action-cell">
                                            <div class="row-actions">
                                                <a href="../../Controller/usuarios/editarUsuario.php?id=<?php echo $usuario[
                                                    "id"
                                                ] ??
                                                    ""; ?>" class="row-action edit" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="../../Controller/usuarios/eliminarUsuarioController.php?id=<?php echo $usuario[
                                                    "id"
                                                ] ?? ""; ?>"
                                                   class="row-action delete"
                                                   title="Eliminar"
                                                   onclick="return confirm('¿Está seguro de que desea eliminar este usuario?');">
                                                    <i class="fas fa-trash-alt"></i>
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
                    <div class="pagination">
                        <div class="pagination-info">
                            Mostrando 1-<?php echo count(
                                $usuarios
                            ); ?> de <?php echo count($usuarios); ?> usuarios
                        </div>
                        <div class="pagination-controls">
                            <div class="page-item disabled"><i class="fas fa-chevron-left"></i></div>
                            <div class="page-item active">1</div>
                            <div class="page-item disabled"><i class="fas fa-chevron-right"></i></div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-users"></i>
                    <h3>No hay usuarios para mostrar</h3>
                    <p>No se encontraron usuarios en el sistema. Puedes agregar un nuevo usuario usando el botón de arriba.</p>
                    <a href="../../Controller/usuarios/agregarController.php" class="action-button primary">
                        <i class="fas fa-user-plus"></i> Agregar Usuario
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
