<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuarios | Stock Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../../public/css/listarUsuarios.css">
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
                    <a href="../../Views/usuarios/dashboard.php" class="btn waves-effect waves-light grey">
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
