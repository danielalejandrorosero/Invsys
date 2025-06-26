<?php

require_once __DIR__ . '/../../../config/cargarConfig.php';
require_once __DIR__ . '/../../Models/proveedor/proveedores.php';

class SeleccionarProveedorHistorialController {
    private $proveedorModel;

    public function __construct($conn) {
        $this->proveedorModel = new Proveedor($conn);
        nivelRequerido([1,2,3]);
    }

    public function mostrarSeleccion() {
        try {
            // Obtener todos los proveedores activos
            $proveedores = $this->proveedorModel->obtenerProveedoresConPaginacion(100, 0); // Mostrar hasta 100 proveedores

            // Cargar la vista de selección
            require_once __DIR__ . '/../../Views/proveedores/seleccionarProveedorHistorialView.php';
        } catch (Exception $e) {
            error_log("Error en SeleccionarProveedorHistorialController: " . $e->getMessage());
            $_SESSION['error'] = "Ocurrió un error al cargar la lista de proveedores";
            header("Location: ../../Views/usuarios/dashboard.php");
            exit();
        }
    }
}

// Inicializar el controlador y mostrar la selección
$controller = new SeleccionarProveedorHistorialController($conn);
$controller->mostrarSeleccion(); 