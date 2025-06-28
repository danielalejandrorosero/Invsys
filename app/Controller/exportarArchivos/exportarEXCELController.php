<?php
// Incluir solo la configuración de base de datos sin el modo oscuro
require_once __DIR__ . '/../../../config/db_config.php';
require_once __DIR__ . '/../../../config/db_connect.php';
require_once __DIR__ . '/../../Models/stock/stock.php';

class ExportarExcelController {
    private $stockModel;

    public function __construct($conn) {
        $this->stockModel = new Stock($conn);
    }
    
    public function exportarExcel() {
        // Obtener parámetros de filtro
        $filtros = isset($_GET['filtro']) ? json_decode($_GET['filtro'], true) : [];
        $almacen = $filtros['almacen'] ?? null;
        $categoria = $filtros['categoria'] ?? null;
        $proveedor = $filtros['proveedor'] ?? null;
        
        // Obtener datos del reporte
        $resultado = $this->stockModel->generarReporte($almacen, $categoria, $proveedor);
        
        if (!$resultado) {
            die('No hay datos para exportar');
        }
        
        $filename = 'reporte_stock_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        // Crear un archivo temporal
        $tempFile = tempnam(sys_get_temp_dir(), 'excel_');
        
        // Crear archivo Excel usando fallback
        $this->crearExcelFallback($resultado, $tempFile);
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($tempFile));
        header('Pragma: no-cache');
        header('Expires: 0');
        
        readfile($tempFile);
        unlink($tempFile);
        exit;
    }
    
    private function crearExcelFallback($resultado, $tempFile) {
        $output = fopen($tempFile, 'w');
        
        // Encabezados
        fwrite($output, "Producto\tAlmacén\tCantidad\tStock Mínimo\tStock Máximo\tCategoría\tProveedor\tEstado\n");
        
        // Datos
        while ($row = $resultado->fetch_assoc()) {
            $cantidad = (int)$row['cantidad'];
            $min = (int)$row['stock_minimo'];
            $max = (int)$row['stock_maximo'];
            
            // Determinar estado
            $estado = "Normal";
            if ($cantidad <= 0) {
                $estado = "Sin Stock";
            } elseif ($cantidad < $min) {
                $estado = "Bajo";
            } elseif ($cantidad > $max) {
                $estado = "Exceso";
            }
            
            fwrite($output, sprintf(
                "%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\n",
                $row['producto'],
                $row['almacen'],
                $cantidad,
                $min,
                $max,
                $row['categoria'],
                $row['proveedor'],
                $estado
            ));
        }
        
        fclose($output);
    }
}

// Inicializar y ejecutar el controlador
$exportarExcel = new ExportarExcelController($conn);
$exportarExcel->exportarExcel();
?> 