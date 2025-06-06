<?php
require_once __DIR__ . '/db_config.php';
require_once __DIR__ . '/db_connect.php';
require_once __DIR__ . '/sesiones.php';
require_once __DIR__ . '/funciones.php';



// Definir la URL base del sistema
define('BASE_URL', '/InventoryManagementSystem/');

// Función para incluir automáticamente el modo oscuro en todas las páginas
function incluirModoOscuro() {
    ob_start();
    include_once __DIR__ . '/../app/Views/includes/dark-mode-include.php';
    $modoOscuro = ob_get_clean();
    
    // Insertar el modo oscuro antes de </head>
    ob_start(function($buffer) use ($modoOscuro) {
        return str_replace('</head>', $modoOscuro . '</head>', $buffer);
    });
}


incluirModoOscuro();