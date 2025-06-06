<?php

require_once __DIR__ . '/../../../config/cargarConfig.php';
require_once __DIR__ . '/../../Models/proveedor/proveedores.php';
nivelRequerido(1);

class HistorialProveedoresController {
    private $proveedorModel;

    public function __construct($conn) {
        $this->proveedorModel = new Proveedor($conn);
    }

    public function mostrarHistorial() {
        try {
            // Obtener el ID del proveedor de la URL
            $id_proveedor = isset($_GET['id']) ? (int)$_GET['id'] : 0;
            
            if ($id_proveedor <= 0) {
                $_SESSION['error'] = "ID de proveedor no válido";
                header("Location: ../../Controller/proveedores/listarProveedores.php");
                exit();
            }

            // Parámetros de paginación
            $pagina_actual = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $registros_por_pagina = 10;
            $offset = ($pagina_actual - 1) * $registros_por_pagina;

            // Parámetros de ordenamiento y filtrado
            $orden = isset($_GET['orden']) ? $_GET['orden'] : 'nombre';
            $filtro_estado = isset($_GET['estado']) ? $_GET['estado'] : 'activo';

            // Validar el parámetro de ordenamiento
            $ordenes_permitidos = ['nombre', 'codigo', 'sku', 'precio_venta'];
            if (!in_array($orden, $ordenes_permitidos)) {
                $orden = 'nombre';
            }

            // Validar el estado
            $estados_permitidos = ['activo', 'inactivo', 'eliminado'];
            if (!in_array($filtro_estado, $estados_permitidos)) {
                $filtro_estado = 'activo';
            }

            // Obtener el historial del proveedor
            $historial = $this->proveedorModel->obtenerHistorialProveedores(
                $id_proveedor,
                $registros_por_pagina,
                $offset,
                $orden,
                $filtro_estado
            );

            if ($historial === null) {
                $_SESSION['error'] = "No se encontró el proveedor o no tiene productos asociados";
                header("Location: ../../Controller/proveedores/listarProveedores.php");
                exit();
            }

            // Calcular el total de páginas
            $total_productos = count($historial['productos']);
            $total_paginas = ceil($total_productos / $registros_por_pagina);

            // Construir parámetros de URL para mantener los filtros
            $params_url = '';
            if (isset($_GET['orden'])) {
                $params_url .= '&orden=' . urlencode($_GET['orden']);
            }
            if (isset($_GET['estado'])) {
                $params_url .= '&estado=' . urlencode($_GET['estado']);
            }

            // Cargar la vista
            require_once __DIR__ . '/../../Views/proveedores/historialProveedoresView.php';

        } catch (Exception $e) {
            error_log("Error en HistorialProveedoresController: " . $e->getMessage());
            $_SESSION['error'] = "Ocurrió un error al mostrar el historial";
            header("Location: ../../Controller/proveedores/listarProveedores.php");
            exit();
        }
    }
}

// Inicializar el controlador y mostrar el historial
$controller = new HistorialProveedoresController($conn);
$controller->mostrarHistorial(); 