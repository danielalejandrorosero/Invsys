<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Usuario</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../../../public/css/eliminarUsuario.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <i class="material-icons">person_remove</i>
                <h1 class="card-title">Eliminar Usuario</h1>
            </div>
            
            <div class="card-content">
                <div class="warning-panel">
                    <i class="material-icons">warning</i>
                    <div class="warning-panel-content">
                        <h3 class="warning-panel-title">¡Advertencia! Acción irreversible</h3>
                        <p>Al eliminar un usuario, todos sus datos serán borrados permanentemente del sistema y no podrán ser recuperados.</p>
                    </div>
                </div>
                
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger">
                        <i class="material-icons">error_outline</i>
                        <?php foreach ($error as $err): ?>
                            <p><?= htmlspecialchars($err) ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_SESSION["mensaje"])): ?>
                    <div class="alert alert-success">
                        <i class="material-icons">check_circle</i>
                        <p><?= htmlspecialchars($_SESSION["mensaje"]) ?></p>
                        <?php unset($_SESSION["mensaje"]); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="" class="form">
                    <div class="form-group">
                        <label for="id_usuario">Seleccione usuario a eliminar:</label>
                        <div class="select-wrapper">
                            <select name="id_usuario" id="id_usuario" class="form-control" required>
                                <option value="">-- Seleccione un usuario --</option>
                                <?php foreach ($usuarios as $usuario): ?>
                                    <option value="<?= htmlspecialchars($usuario['id_usuario']) ?>">
                                        <?= htmlspecialchars($usuario['nombreUsuario']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <label class="checkbox-container">
                        <span>Confirmo que deseo eliminar permanentemente al usuario seleccionado y entiendo que <strong>esta acción no se puede deshacer</strong>.</span>
                        <input type="checkbox" id="confirmDelete">
                        <span class="custom-checkbox"></span>
                    </label>

                    <div class="form-buttons">
                        <button type="submit" name="eliminarUsuario" id="btnEliminar" class="btn btn-danger" disabled>
                            <i class="material-icons">delete</i> Eliminar Usuario
                        </button>
                        <a href="../../Controller/usuarios/listarUsuarios.php" class="btn btn-secondary">
                            <i class="material-icons">arrow_back</i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Control de habilitación del botón según checkbox
        const confirmCheckbox = document.getElementById('confirmDelete');
        const deleteButton = document.getElementById('btnEliminar');
        
        confirmCheckbox.addEventListener('change', function() {
            deleteButton.disabled = !this.checked;
        });
        
        // Confirmación adicional al eliminar
        deleteButton.addEventListener('click', function(e) {
            if (!confirm('¿Está seguro que desea eliminar este usuario? Esta acción no se puede deshacer.')) {
                e.preventDefault();
            }
        });
        
        // Efecto ripple para botones
        const buttons = document.querySelectorAll('.btn');
        buttons.forEach(button => {
            button.addEventListener('click', function(e) {
                const x = e.clientX - e.target.getBoundingClientRect().left;
                const y = e.clientY - e.target.getBoundingClientRect().top;
                
                const ripple = document.createElement('span');
                ripple.classList.add('ripple');
                ripple.style.left = `${x}px`;
                ripple.style.top = `${y}px`;
                
                this.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });
    });
    </script>
</body>
</html>