<?php
// Incluir solo la configuración de base de datos sin el modo oscuro
require_once __DIR__ . '/../../../config/db_config.php';
require_once __DIR__ . '/../../../config/db_connect.php';
require_once __DIR__ . '/../../Models/stock/stock.php';

class ExportarMovimientosController {
    private $stockModel;

    public function __construct($conn) {
        $this->stockModel = new Stock($conn);
    }
    
    public function exportarMovimientos() {
        $formato = $_GET['formato'] ?? 'csv';
        $almacen = isset($_GET['almacen']) ? trim($_GET['almacen']) : null;
        $producto = isset($_GET['producto']) ? trim($_GET['producto']) : null;
        $tipo = isset($_GET['tipo']) ? trim($_GET['tipo']) : null;
        $fecha_desde = isset($_GET['fecha_desde']) ? trim($_GET['fecha_desde']) : null;
        $fecha_hasta = isset($_GET['fecha_hasta']) ? trim($_GET['fecha_hasta']) : null;
        
        // Obtener todos los movimientos (sin paginación para exportar todo)
        $movimientos = $this->stockModel->obtenerMovimientos(
            $almacen, 
            $producto, 
            $tipo,
            $fecha_desde,
            $fecha_hasta
        );
        
        if (!$movimientos) {
            die('No hay datos para exportar');
        }
        
        switch ($formato) {
            case 'csv':
                $this->exportarCSV($movimientos);
                break;
            case 'excel':
                $this->exportarExcel($movimientos);
                break;
            case 'pdf':
                $this->exportarPDF($movimientos);
                break;
            default:
                $this->exportarCSV($movimientos);
        }
    }
    
    private function exportarCSV($movimientos) {
        $filename = 'movimientos_stock_' . date('Y-m-d_H-i-s') . '.csv';
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        // Crear el archivo CSV
        $output = fopen('php://output', 'w');
        
        // BOM para UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Encabezados
        fputcsv($output, [
            'Producto',
            'Tipo de Movimiento',
            'Cantidad',
            'Fecha',
            'Almacén Origen',
            'Almacén Destino',
            'Usuario'
        ]);
        
        // Datos
        while ($row = $movimientos->fetch_assoc()) {
            $fecha = new DateTime($row['fecha_movimiento']);
            fputcsv($output, [
                $row['producto'],
                $row['tipo_movimiento'],
                $row['cantidad'],
                $fecha->format('d/m/Y H:i:s'),
                $row['almacen_origen'] ?: '-',
                $row['almacen_destino'] ?: '-',
                $row['usuario'] ?: '-'
            ]);
        }
        
        fclose($output);
        exit;
    }
    
    private function exportarExcel($movimientos) {
        $filename = 'movimientos_stock_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        // Crear un archivo temporal
        $tempFile = tempnam(sys_get_temp_dir(), 'excel_');
        
        // Por ahora, siempre usar el fallback ya que PhpSpreadsheet no está disponible
        $this->crearExcelFallback($movimientos, $tempFile);
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($tempFile));
        header('Pragma: no-cache');
        header('Expires: 0');
        
        readfile($tempFile);
        unlink($tempFile);
        exit;
    }
    
    private function crearExcelFallback($movimientos, $tempFile) {
        $output = fopen($tempFile, 'w');
        
        // Encabezados
        fwrite($output, "Producto\tTipo de Movimiento\tCantidad\tFecha\tAlmacén Origen\tAlmacén Destino\tUsuario\n");
        
        // Datos
        while ($row = $movimientos->fetch_assoc()) {
            $fecha = new DateTime($row['fecha_movimiento']);
            fwrite($output, sprintf(
                "%s\t%s\t%s\t%s\t%s\t%s\t%s\n",
                $row['producto'],
                $row['tipo_movimiento'],
                $row['cantidad'],
                $fecha->format('d/m/Y H:i:s'),
                $row['almacen_origen'] ?: '-',
                $row['almacen_destino'] ?: '-',
                $row['usuario'] ?: '-'
            ));
        }
        
        fclose($output);
    }
    
    private function exportarPDF($movimientos) {
        $filename = 'movimientos_stock_' . date('Y-m-d_H-i-s') . '.pdf';
        
        // Crear HTML para el PDF
        $html = $this->generarHTMLParaPDF($movimientos);
        
        // Por ahora, siempre usar el fallback HTML para imprimir
        // ya que TCPDF no está disponible
        $this->mostrarHTMLParaImpresion($html, $filename);
    }
    
    private function generarHTMLParaPDF($movimientos) {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Reporte de Movimientos de Stock</title>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
            <style>
                body { 
                    font-family: Arial, sans-serif; 
                    font-size: 12px; 
                    line-height: 1.4;
                    color: #333;
                }
                table { 
                    width: 100%; 
                    border-collapse: collapse; 
                    margin-top: 20px; 
                    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                }
                th, td { 
                    border: 1px solid #ddd; 
                    padding: 10px 8px; 
                    text-align: left; 
                    vertical-align: middle;
                }
                th { 
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white; 
                    font-weight: bold; 
                    font-size: 11px;
                }
                tr:nth-child(even) { background-color: #f9f9f9; }
                tr:hover { background-color: #f5f5f5; }
                .header { 
                    text-align: center; 
                    margin-bottom: 30px; 
                    padding: 20px;
                    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                    border-radius: 8px;
                }
                .header h1 { 
                    color: #2c3e50; 
                    margin: 0; 
                    font-size: 24px;
                    font-weight: bold;
                }
                .header p { 
                    color: #6c757d; 
                    margin: 5px 0; 
                    font-size: 14px;
                }
                .badge { 
                    padding: 4px 8px; 
                    border-radius: 12px; 
                    font-size: 10px; 
                    font-weight: bold;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                }
                .badge-entrada { 
                    background-color: #d4edda; 
                    color: #155724; 
                }
                .badge-salida { 
                    background-color: #f8d7da; 
                    color: #721c24; 
                }
                .badge-transferencia { 
                    background-color: #d1ecf1; 
                    color: #0c5460; 
                }
                .badge-ajuste { 
                    background-color: #fff3cd; 
                    color: #856404; 
                }
                .summary {
                    margin: 20px 0;
                    padding: 15px;
                    background: #f8f9fa;
                    border-radius: 5px;
                    border-left: 4px solid #007bff;
                }
                .summary h3 {
                    margin: 0 0 10px 0;
                    color: #2c3e50;
                    font-size: 16px;
                }
                .summary p {
                    margin: 5px 0;
                    color: #6c757d;
                }
            </style>
        </head>
        <body>
            <div class="header">
                <h1><i class="fas fa-exchange-alt"></i> Reporte de Movimientos de Stock</h1>
                <p><i class="fas fa-calendar"></i> Generado el: ' . date('d/m/Y H:i:s') . '</p>
                <p><i class="fas fa-database"></i> Total de registros: ' . $movimientos->num_rows . '</p>
            </div>';
        
        // Agregar resumen si hay datos
        if ($movimientos->num_rows > 0) {
            $html .= '<div class="summary">
                <h3><i class="fas fa-info-circle"></i> Resumen del Reporte</h3>
                <p><strong>Formato:</strong> PDF</p>
                <p><strong>Filtros aplicados:</strong> ' . $this->obtenerFiltrosAplicados() . '</p>
            </div>';
        }
        
        $html .= '<table>
                <thead>
                    <tr>
                        <th><i class="fas fa-box"></i> Producto</th>
                        <th><i class="fas fa-tag"></i> Tipo</th>
                        <th><i class="fas fa-sort-numeric-up"></i> Cantidad</th>
                        <th><i class="fas fa-calendar-alt"></i> Fecha</th>
                        <th><i class="fas fa-warehouse"></i> Almacén Origen</th>
                        <th><i class="fas fa-warehouse"></i> Almacén Destino</th>
                        <th><i class="fas fa-user"></i> Usuario</th>
                    </tr>
                </thead>
                <tbody>';
        
        while ($row = $movimientos->fetch_assoc()) {
            $fecha = new DateTime($row['fecha_movimiento']);
            $tipoClass = 'badge-' . strtolower($row['tipo_movimiento']);
            
            $html .= '<tr>
                <td>' . htmlspecialchars($row['producto']) . '</td>
                <td><span class="badge ' . $tipoClass . '">' . htmlspecialchars($row['tipo_movimiento']) . '</span></td>
                <td><strong>' . $row['cantidad'] . '</strong></td>
                <td>' . $fecha->format('d/m/Y H:i:s') . '</td>
                <td>' . htmlspecialchars($row['almacen_origen'] ?: '-') . '</td>
                <td>' . htmlspecialchars($row['almacen_destino'] ?: '-') . '</td>
                <td>' . htmlspecialchars($row['usuario'] ?: '-') . '</td>
            </tr>';
        }
        
        $html .= '</tbody></table>
            <div style="margin-top: 30px; text-align: center; color: #6c757d; font-size: 11px;">
                <p><i class="fas fa-print"></i> Este reporte está optimizado para impresión</p>
                <p> INVSYS - ' . date('Y') . '</p>
            </div>
        </body></html>';
        
        return $html;
    }
    
    private function mostrarHTMLParaImpresion($html, $filename) {
        // Configurar headers para HTML
        header('Content-Type: text/html; charset=utf-8');
        
        // Agregar estilos adicionales para mejor presentación
        $html = str_replace('</head>', '
            <style>
                @media print {
                    body { margin: 0; padding: 20px; }
                    .no-print { display: none !important; }
                    table { page-break-inside: auto; }
                    tr { page-break-inside: avoid; page-break-after: auto; }
                }
                @media screen {
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    .print-button { 
                        position: fixed; 
                        top: 20px; 
                        right: 20px; 
                        background: #007bff; 
                        color: white; 
                        padding: 10px 20px; 
                        border: none; 
                        border-radius: 5px; 
                        cursor: pointer; 
                        z-index: 1000;
                    }
                    .print-button:hover { background: #0056b3; }
                }
            </style>
        </head>', $html);
        
        // Agregar botón de impresión
        $html = str_replace('<body>', '<body>
            <button class="print-button no-print" onclick="window.print()">
                <i class="fas fa-print"></i> Imprimir Reporte
            </button>', $html);
        
        echo $html;
        exit;
    }
    
    private function obtenerFiltrosAplicados() {
        $filtros = [];
        
        if (!empty($_GET['almacen'])) {
            $filtros[] = 'Almacén: ' . htmlspecialchars($_GET['almacen']);
        }
        if (!empty($_GET['producto'])) {
            $filtros[] = 'Producto: ' . htmlspecialchars($_GET['producto']);
        }
        if (!empty($_GET['tipo'])) {
            $filtros[] = 'Tipo: ' . htmlspecialchars($_GET['tipo']);
        }
        if (!empty($_GET['fecha_desde'])) {
            $filtros[] = 'Desde: ' . htmlspecialchars($_GET['fecha_desde']);
        }
        if (!empty($_GET['fecha_hasta'])) {
            $filtros[] = 'Hasta: ' . htmlspecialchars($_GET['fecha_hasta']);
        }
        
        return empty($filtros) ? 'Ninguno (todos los movimientos)' : implode(', ', $filtros);
    }
}

// Inicializar y ejecutar
$exportarController = new ExportarMovimientosController($conn);
$exportarController->exportarMovimientos();
?> 

