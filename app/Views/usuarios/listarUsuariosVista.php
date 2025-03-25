<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuarios</title>
    <link rel="stylesheet" href="../../../frontend/listaUsuarios.css"> <!-- Asegúrate de tener un archivo CSS para los estilos -->
</head>
<body>
    <div class="container">
        <h1>Lista de Usuarios</h1>
        <?php if (!empty($usuarios)) : ?>
            <table>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Grupo</th>
                </tr>
                <?php foreach ($usuarios as $usuario) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['grupo']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else : ?>
            <p>No hay usuarios para mostrar.</p>
        <?php endif; ?>
    </div>
</body>
</html>