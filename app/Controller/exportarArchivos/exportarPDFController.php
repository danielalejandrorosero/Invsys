<?php
// Incluir solo la configuración de base de datos sin el modo oscuro
require_once __DIR__ . '/../../../config/db_config.php';
require_once __DIR__ . '/../../../config/db_connect.php';
require_once __DIR__ . '/../../Models/stock/stock.php';

class ExportarPDFController {
    private $stockModel;

    public function __construct($conn) {
        $this->stockModel = new Stock($conn);
    }
    
    public function exportarPDF() {
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
        
        $filename = 'reporte_stock_' . date('Y-m-d_H-i-s') . '.pdf';
        
        // Crear HTML para el PDF
        $html = $this->generarHTMLParaPDF($resultado, $almacen, $categoria, $proveedor);
        
        // Mostrar HTML optimizado para impresión
        $this->mostrarHTMLParaImpresion($html, $filename);
    }
    
    private function generarHTMLParaPDF($resultado, $almacen, $categoria, $proveedor) {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Reporte de Stock</title>
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
                    padding: 8px 6px; 
                    text-align: left; 
                    vertical-align: middle;
                    font-size: 10px;
                }
                th { 
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white; 
                    font-weight: bold; 
                    font-size: 9px;
                }
                tr:nth-child(even) { background-color: #f9f9f9; }
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
                    padding: 3px 6px; 
                    border-radius: 10px; 
                    font-size: 8px; 
                    font-weight: bold;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                }
                .badge-normal { 
                    background-color: #d4edda; 
                    color: #155724; 
                }
                .badge-bajo { 
                    background-color: #fff3cd; 
                    color: #856404; 
                }
                .badge-sin-stock { 
                    background-color: #f8d7da; 
                    color: #721c24; 
                }
                .badge-exceso { 
                    background-color: #d1ecf1; 
                    color: #0c5460; 
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
                @media print {
                    body { margin: 0; }
                    .header { margin-bottom: 20px; }
                    table { font-size: 9px; }
                    th, td { padding: 6px 4px; }
                }
            </style>
        </head>
        <body>
            <div class="header">
                <h1><i class="fas fa-chart-bar"></i> Reporte de Stock</h1>
                <p><i class="fas fa-calendar"></i> Generado el: ' . date('d/m/Y H:i:s') . '</p>
                <p><i class="fas fa-database"></i> Total de registros: ' . $resultado->num_rows . '</p>
            </div>';
        
        // Agregar resumen de filtros
        $html .= '<div class="summary">
            <h3><i class="fas fa-info-circle"></i> Resumen del Reporte</h3>
            <p><strong>Formato:</strong> PDF</p>
            <p><strong>Filtros aplicados:</strong> ' . $this->obtenerFiltrosAplicados($almacen, $categoria, $proveedor) . '</p>
        </div>';
        
        // Tabla de datos
        $html .= '<table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Almacén</th>
                    <th>Cantidad</th>
                    <th>Stock Mín.</th>
                    <th>Stock Máx.</th>
                    <th>Categoría</th>
                    <th>Proveedor</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>';
        
        while ($row = $resultado->fetch_assoc()) {
            $cantidad = (int)$row['cantidad'];
            $min = (int)$row['stock_minimo'];
            $max = (int)$row['stock_maximo'];
            
            // Determinar estado y clase CSS
            $estado = "Normal";
            $badgeClass = "badge-normal";
            
            if ($cantidad <= 0) {
                $estado = "Sin Stock";
                $badgeClass = "badge-sin-stock";
            } elseif ($cantidad < $min) {
                $estado = "Bajo";
                $badgeClass = "badge-bajo";
            } elseif ($cantidad > $max) {
                $estado = "Exceso";
                $badgeClass = "badge-exceso";
            }
            
            $html .= '<tr>
                <td><strong>' . htmlspecialchars($row['producto']) . '</strong></td>
                <td>' . htmlspecialchars($row['almacen']) . '</td>
                <td>' . $cantidad . '</td>
                <td>' . $min . '</td>
                <td>' . $max . '</td>
                <td>' . htmlspecialchars($row['categoria']) . '</td>
                <td>' . htmlspecialchars($row['proveedor']) . '</td>
                <td><span class="badge ' . $badgeClass . '">' . $estado . '</span></td>
            </tr>';
        }
        
        $html .= '</tbody></table>
            <div style="margin-top: 30px; text-align: center; color: #6c757d; font-size: 11px;">
                <p><i class="fas fa-print"></i> Este reporte está optimizado para impresión</p>
                <p>INVSYS - ' . date('Y') . '</p>
            </div>
        </body></html>';
        
        return $html;
    }
    
    private function mostrarHTMLParaImpresion($html, $filename) {
        // Configurar headers para descarga
        header('Content-Type: text/html; charset=utf-8');
        header('Content-Disposition: inline; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        // Agregar botón de impresión al HTML
        $html = str_replace('</head>', '
            <script>
                window.onload = function() {
                    // Agregar botón de impresión
                    var printBtn = document.createElement("button");
                    printBtn.innerHTML = "<i class=\\"fas fa-print\\"></i> Imprimir Reporte";
                    printBtn.style.cssText = "position: fixed; top: 20px; right: 20px; z-index: 1000; padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14px;";
                    printBtn.onclick = function() { window.print(); };
                    document.body.appendChild(printBtn);
                    
                    // Ocultar botón al imprimir
                    var style = document.createElement("style");
                    style.textContent = "@media print { button { display: none !important; } }";
                    document.head.appendChild(style);
                };
            </script>
        </head>', $html);
        
        echo $html;
        exit;
    }
    
    private function obtenerFiltrosAplicados($almacen, $categoria, $proveedor) {
        $filtros = [];
        
        if ($almacen) {
            $filtros[] = "Almacén: " . htmlspecialchars($almacen);
        }
        if ($categoria) {
            $filtros[] = "Categoría: " . htmlspecialchars($categoria);
        }
        if ($proveedor) {
            $filtros[] = "Proveedor: " . htmlspecialchars($proveedor);
        }
        
        return !empty($filtros) ? implode(", ", $filtros) : "Ninguno";
    }
}

// Inicializar y ejecutar el controlador
$exportarPDF = new ExportarPDFController($conn);
$exportarPDF->exportarPDF();
?> 