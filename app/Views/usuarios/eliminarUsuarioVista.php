<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Usuario</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        :root {
            --primary-color: #6200ee;
            --primary-light: #9c4dff;
            --primary-dark: #3700b3;
            --secondary-color: #03dac6;
            --error-color: #b00020;
            --success-color: #43a047;
            --warning-color: #ff9800;
            --background-color: #f5f5f5;
            --surface-color: #ffffff;
            --text-primary: rgba(0, 0, 0, 0.87);
            --text-secondary: rgba(0, 0, 0, 0.6);
            --text-disabled: rgba(0, 0, 0, 0.38);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: var(--background-color);
            color: var(--text-primary);
            line-height: 1.6;
        }

        .container {
            width: 90%;
            max-width: 600px;
            margin: 2rem auto;
            padding: 0;
        }

        .card {
            background-color: var(--surface-color);
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1), 0 4px 8px rgba(0, 0, 0, 0.06);
            overflow: hidden;
            position: relative;
            transition: box-shadow 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.12), 0 8px 16px rgba(0, 0, 0, 0.08);
        }

        .card-header {
            padding: 1.5rem;
            background-color: var(--surface-color);
            border-bottom: 1px solid rgba(0, 0, 0, 0.12);
            display: flex;
            align-items: center;
        }

        .card-header .material-icons {
            margin-right: 0.75rem;
            color: var(--primary-color);
            font-size: 1.75rem;
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 500;
            color: var(--text-primary);
            margin: 0;
        }

        .card-content {
            padding: 1.5rem;
        }

        .alert {
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: flex-start;
        }

        .alert-danger {
            background-color: rgba(176, 0, 32, 0.08);
            color: var(--error-color);
            border-left: 4px solid var(--error-color);
        }

        .alert-success {
            background-color: rgba(67, 160, 71, 0.08);
            color: var(--success-color);
            border-left: 4px solid var(--success-color);
        }

        .alert .material-icons {
            margin-right: 0.75rem;
            font-size: 1.25rem;
        }

        .alert p {
            margin: 0;
            flex: 1;
        }

        .warning-panel {
            background-color: rgba(255, 152, 0, 0.08);
            border-left: 4px solid var(--warning-color);
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: flex-start;
        }

        .warning-panel .material-icons {
            color: var(--warning-color);
            margin-right: 0.75rem;
            font-size: 1.25rem;
        }

        .warning-panel-content {
            flex: 1;
        }

        .warning-panel-title {
            color: var(--warning-color);
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .warning-panel p {
            color: var(--text-primary);
            margin: 0;
        }

        .form {
            display: flex;
            flex-direction: column;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-group label {
            display: block;
            color: var(--text-secondary);
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            color: var(--text-primary);
            background-color: transparent;
            border: 1px solid rgba(0, 0, 0, 0.23);
            border-radius: 4px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            appearance: none;
            -webkit-appearance: none;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(98, 0, 238, 0.2);
        }

        .form-control option {
            padding: 0.5rem;
        }

        .select-wrapper {
            position: relative;
        }

        .select-wrapper::after {
            content: "\e5cf"; /* Material Icons dropdown arrow */
            font-family: 'Material Icons';
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            color: var(--text-secondary);
        }

        .form-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 1.25rem;
            font-size: 0.875rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.0892857143em;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s, box-shadow 0.3s;
            text-decoration: none;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
            min-width: 100px;
            height: 36px;
        }

        .btn .material-icons {
            margin-right: 0.5rem;
            font-size: 1.125rem;
        }

        .btn-danger {
            background-color: var(--error-color);
            color: white;
        }

        .btn-danger:hover, .btn-danger:focus {
            background-color: #cf0025;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2), 0 1px 2px rgba(0, 0, 0, 0.3);
        }

        .btn-secondary {
            background-color: transparent;
            color: var(--primary-color);
            box-shadow: none;
            border: 1px solid rgba(0, 0, 0, 0.12);
        }

        .btn-secondary:hover, .btn-secondary:focus {
            background-color: rgba(98, 0, 238, 0.04);
        }

        .checkbox-container {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1rem;
            position: relative;
            padding-left: 30px;
            cursor: pointer;
        }

        .checkbox-container input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }

        .custom-checkbox {
            position: absolute;
            top: 2px;
            left: 0;
            height: 18px;
            width: 18px;
            background-color: #fff;
            border: 2px solid rgba(0, 0, 0, 0.54);
            border-radius: 2px;
            transition: all 0.2s ease;
        }

        .checkbox-container:hover input ~ .custom-checkbox {
            border-color: rgba(0, 0, 0, 0.87);
        }

        .checkbox-container input:checked ~ .custom-checkbox {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .custom-checkbox:after {
            content: "";
            position: absolute;
            display: none;
        }

        .checkbox-container input:checked ~ .custom-checkbox:after {
            display: block;
        }

        .checkbox-container .custom-checkbox:after {
            left: 6px;
            top: 2px;
            width: 4px;
            height: 8px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        .ripple {
            position: absolute;
            border-radius: 50%;
            transform: scale(0);
            background-color: rgba(0, 0, 0, 0.3);
            animation: ripple 0.6s linear;
            pointer-events: none;
        }

        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }

        @media screen and (max-width: 600px) {
            .container {
                width: 95%;
                margin: 1rem auto;
            }
            
            .form-buttons {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
        }
    </style>
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