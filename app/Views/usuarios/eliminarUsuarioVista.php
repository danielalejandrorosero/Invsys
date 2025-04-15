<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Usuario | Stock Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../../public/css/eliminarUsuario.css">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteForm = document.getElementById('deleteForm');
            const confirmCheck = document.getElementById('confirmDelete');
            const deleteBtn = document.getElementById('deleteBtn');
            const userSelect = document.getElementById('id_usuario');
            const modal = document.getElementById('confirmModal');
            const confirmModalBtn = document.getElementById('confirmModalBtn');
            const cancelModalBtn = document.getElementById('cancelModalBtn');
            const closeModalBtn = document.getElementById('closeModal');
            const modalUserName = document.getElementById('modalUserName');
            const modalUserInitial = document.getElementById('modalUserInitial');
            const modalUserId = document.getElementById('modalUserId');

            // Toggle delete button based on checkbox
            confirmCheck.addEventListener('change', function() {
                deleteBtn.disabled = !this.checked;
            });

            // Open modal instead of submitting form
            deleteForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Get selected user info
                const selectedOption = userSelect.options[userSelect.selectedIndex];
                const userName = selectedOption.text;
                const userId = selectedOption.value;

                // Update modal content
                modalUserName.textContent = userName;
                modalUserInitial.textContent = userName.charAt(0).toUpperCase();
                modalUserId.textContent = 'ID: ' + userId;

                // Show modal
                modal.classList.add('show');
            });

            // Handle modal confirm button
            confirmModalBtn.addEventListener('click', function() {
                // Submit the form
                deleteForm.submit();
            });

            // Handle modal cancel and close buttons
            cancelModalBtn.addEventListener('click', function() {
                modal.classList.remove('show');
            });

            closeModalBtn.addEventListener('click', function() {
                modal.classList.remove('show');
            });

            // Close modal when clicking outside
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.classList.remove('show');
                }
            });
        });
    </script>
</head>
<body>
    <div class="container">
        <div class="form-header">
            <a href="../../Controller/usuarios/listarUsuarios.php" class="back-btn">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <h1><i class="fas fa-user-minus"></i> Eliminar Usuario</h1>
            <p>Eliminar un usuario del sistema</p>
        </div>

        <div class="form-body">
            <div class="warning-box">
                <i class="fas fa-exclamation-triangle"></i>
                <div class="warning-box-content">
                    <h3>¡Advertencia! Acción irreversible</h3>
                    <p>Al eliminar un usuario, se eliminarán permanentemente todos sus datos y registros asociados. Esta acción no se puede deshacer.</p>
                </div>
            </div>

            <form id="deleteForm" action="../../Controller/usuarios/eliminarUsuarioController.php" method="POST">
                <div class="form-group">
                    <label for="id_usuario">Seleccione el usuario a eliminar:</label>
                    <div class="select-wrapper">
                        <select id="id_usuario" name="id_usuario" required>
                            <option value="" disabled selected>-- Seleccionar usuario --</option>
                            <?php foreach ($usuarios as $usuario): ?>
                                <option value="<?php echo $usuario[
                                    "id_usuario"
                                ]; ?>"><?php echo htmlspecialchars(
    $usuario["nombreUsuario"]
); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="confirmation-check">
                    <input type="checkbox" id="confirmDelete" name="confirmDelete">
                    <label for="confirmDelete">Confirmo que deseo eliminar permanentemente al usuario seleccionado y entiendo que <span>esta acción no se puede deshacer</span>.</label>
                </div>

                <button type="submit" id="deleteBtn" name="eliminarUsuario" class="delete-btn" disabled>
                    <i class="fas fa-trash-alt"></i> Eliminar Usuario
                </button>

                <a href="../../Controller/usuarios/listarUsuarios.php" class="cancel-btn">Cancelar y volver</a>
            </form>

            <?php if (isset($mensaje)): ?>
                <div class="message-box success">
                    <i class="fas fa-check-circle"></i>
                    <p><?php echo $mensaje; ?></p>
                </div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="message-box error">
                    <i class="fas fa-exclamation-circle"></i>
                    <p><?php echo is_array($error)
                        ? implode("<br>", $error)
                        : $error; ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <i class="fas fa-trash-alt"></i>
                <h2>Confirmar Eliminación</h2>
            </div>
            <div class="modal-body">
                <p>Está a punto de eliminar permanentemente el siguiente usuario. Esta acción no se puede deshacer.</p>

                <div class="modal-user-info">
                    <div class="user-avatar-small" id="modalUserInitial">U</div>
                    <div class="user-details">
                        <div class="user-name" id="modalUserName">Nombre de Usuario</div>
                        <div class="user-id" id="modalUserId">ID: 0</div>
                    </div>
                </div>

                <p>¿Está seguro de que desea continuar?</p>

                <div class="modal-actions">
                    <button id="confirmModalBtn" class="btn-confirm-delete">
                        <i class="fas fa-trash-alt"></i> Sí, Eliminar
                    </button>
                    <button id="cancelModalBtn" class="btn-cancel">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
