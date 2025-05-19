<?php
require_once '../../Models/stock/stock.php';
require_once '../../../config/cargarConfig.php';

// Obtener filtros de la URL
$filtros = isset($_GET['filtro']) ? json_decode(urldecode($_GET['filtro']), true) : [];
$almacen = isset($filtros['almacen']) ? $filtros['almacen'] : null;
$categoria = isset($filtros['categoria']) ? $filtros['categoria'] : null;
$proveedor = isset($filtros['proveedor']) ? $filtros['proveedor'] : null;

// Obtener los datos de la base de datos
$stockModel = new Stock($conn);
$resultado = $stockModel->generarReporte($almacen, $categoria, $proveedor);

// Convertir los datos en array asociativo
$datos = [];
if ($resultado && $resultado instanceof mysqli_result) {
    while ($fila = $resultado->fetch_assoc()) {
        $datos[] = $fila;
    }
    $resultado->free();
} else {
    $_SESSION['error'] = 'No se encontraron datos para exportar a Excel';
    header('Location: ../../Views/stock/reporteStockVista.php');
    exit();
}

// Verificar si hay datos
if (empty($datos)) {
    $_SESSION['error'] = 'No hay datos para exportar a Excel';
    header('Location: ../../Views/stock/reporteStockVista.php');
    exit();
}

// Pasar los datos a JSON
$jsonDatos = json_encode($datos);

// Ruta del script Python
$pythonScript = '../../../scriptsPython/exportarExcel.py';

// Verificar si el script Python existe
if (!file_exists($pythonScript)) {
    $_SESSION['error'] = 'Error: No se encontró el script Python para generar el Excel';
    header('Location: ../../Views/stock/reporteStockVista.php');
    exit();
}

// Comando para ejecutar Python
$command = "python " . escapeshellarg($pythonScript) . " " . escapeshellarg($jsonDatos) . " 2>&1";
$output = shell_exec($command);

// Registrar la salida para depuración
error_log("Salida del comando Python: " . $output);

// Verificar si Python está instalado
$pythonCheck = shell_exec('python --version');
if (!$pythonCheck) {
    $_SESSION['error'] = 'Error: Python no está instalado o no está disponible en el PATH del sistema';
    header('Location: ../../Views/stock/reporteStockVista.php');
    exit();
}

// Verificar si se generó el archivo Excel
$excelPath = trim($output);

// Verificar si la salida contiene un mensaje de error
if (strpos($output, 'Error') !== false) {
    error_log("Error detectado en la salida de Python: " . $output);
    
    // Verificar si es un error de módulo pandas
    if (strpos($output, "ModuleNotFoundError: No module named 'pandas'") !== false) {
        $_SESSION['error'] = 'Error al generar el archivo Excel: El módulo pandas no está instalado. Por favor, contacte al administrador del sistema para instalar pandas con el comando: pip install pandas';
    } else {
        $_SESSION['error'] = 'Error al generar el archivo Excel: ' . $output;
    }
    
    header('Location: ../../Views/stock/reporteStockVista.php');
    exit();
}

// Verificar si el archivo existe
if ($excelPath && file_exists($excelPath)) {
    // Verificar que el archivo tenga un tamaño válido
    $fileSize = filesize($excelPath);
    if ($fileSize <= 0) {
        error_log("Archivo Excel generado con tamaño cero: " . $excelPath);
        $_SESSION['error'] = 'El archivo Excel generado está vacío';
        header('Location: ../../Views/stock/reporteStockVista.php');
        exit();
    }
    
    // Enviar el archivo al navegador
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="reporte_stock.xlsx"');
    header('Content-Length: ' . $fileSize);
    readfile($excelPath);
    
    // Eliminar el archivo temporal
    unlink($excelPath);
    exit();
} else {
    error_log("Error al generar Excel. Comando: " . $command . ", Salida: " . $output . ", Ruta del archivo: " . $excelPath);
    $_SESSION['error'] = 'Error al generar el archivo Excel. Por favor, contacte al administrador.';
    header('Location: ../../Views/stock/reporteStockVista.php');
    exit();
}
?>