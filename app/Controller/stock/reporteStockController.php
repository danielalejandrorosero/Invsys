<?php
// Cargar configuración y dependencias
require_once __DIR__ . '/../../../config/cargarConfig.php';
require_once __DIR__ . '/../../Models/stock/stock.php';


class ReporteController {
    private $stockModel;
    private $errores = [];

    public function __construct($conn) {

        $this->stockModel = new Stock($conn);
        nivelRequerido([1,2]);
    }

    public function reporteStockController() {
        try {
            // Obtener y sanitizar parámetros de filtro
            $almacen = $this->sanitizeInput($_GET['almacen'] ?? '');
            $categoria = $this->sanitizeInput($_GET['categoria'] ?? '');
            $proveedor = $this->sanitizeInput($_GET['proveedor'] ?? '');

            // Generar reporte
            $resultado = $this->stockModel->generarReporte($almacen, $categoria, $proveedor);
            
            // Verificar si hay resultados
            if (!$resultado) {
                $_SESSION['error'] = "Error al generar el reporte de stock";
                $productos = [];
                $totalProductos = 0;
            } else {
                // Convertir resultado a array para facilitar el manejo
                $productos = [];
                $totalProductos = 0;
                
                if ($resultado instanceof mysqli_result) {
                    $totalProductos = $resultado->num_rows;
                    while ($row = $resultado->fetch_assoc()) {
                        $productos[] = $row;
                    }
                    // Resetear el puntero del resultado para la vista
                    $resultado->data_seek(0);
                }
            }

            // Cargar la vista con los datos
            require_once __DIR__ . '/../../Views/stock/reporteStockVista.php';

        } catch (Exception $e) {
            // Manejar errores
            $_SESSION['error'] = "Error al procesar el reporte: " . $e->getMessage();
            $productos = [];
            $totalProductos = 0;
            $resultado = null;
            
            // Cargar la vista con datos vacíos
            require_once __DIR__ . '/../../Views/stock/reporteStockVista.php';
        }
    }

    /**
     * Sanitizar entrada de datos
     */
    private function sanitizeInput($input) {
        if (empty($input)) {
            return null;
        }
        return trim(htmlspecialchars($input, ENT_QUOTES, 'UTF-8'));
    }

    /**
     * Obtener estadísticas del reporte
     */
    public function obtenerEstadisticas($productos) {
        $estadisticas = [
            'total_productos' => count($productos),
            'sin_stock' => 0,
            'stock_bajo' => 0,
            'stock_normal' => 0,
            'stock_exceso' => 0,
            'valor_total' => 0
        ];

        foreach ($productos as $producto) {
            $cantidad = (int)$producto['cantidad'];
            $min = (int)$producto['stock_minimo'];
            $max = (int)$producto['stock_maximo'];
            $valor = (float)($producto['valor_total'] ?? 0);

            $estadisticas['valor_total'] += $valor;

            if ($cantidad <= 0) {
                $estadisticas['sin_stock']++;
            } elseif ($cantidad < $min) {
                $estadisticas['stock_bajo']++;
            } elseif ($cantidad > $max) {
                $estadisticas['stock_exceso']++;
            } else {
                $estadisticas['stock_normal']++;
            }
        }

        return $estadisticas;
    }
}

// Inicializar y ejecutar el controlador
try {
    $reporteStock = new ReporteController($conn);
    $reporteStock->reporteStockController();
} catch (Exception $e) {
    $_SESSION['error'] = "Error crítico: " . $e->getMessage();
    header("Location: ../../Views/usuarios/dashboard.php");
    exit();
}
?>