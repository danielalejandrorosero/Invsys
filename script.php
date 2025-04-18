<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

// URL para iniciar sesión
$urlLogin = "http://192.168.0.18/InventoryManagementSystem/app/Controller/usuarios/sesionController.php";

// URL para agregar productos
$urlAgregarProducto = "http://192.168.0.18/InventoryManagementSystem/app/Controller/productos/agregarProductoController.php";

// Datos de inicio de sesión
$loginData = [
    "login" => "1",
    "nombreUsuario" => "root", // Usuario proporcionado
    "password" => "2212"       // Contraseña proporcionada
];

// Archivo de cookies para mantener la sesión
$cookieFile = "cookie.txt";

// Paso 1: Iniciar sesión
echo "Intentando iniciar sesión...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $urlLogin);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($loginData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile); // Guardar cookies
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Seguir redirecciones

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Verificar si el inicio de sesión fue exitoso
if ($httpCode === 200) {
    echo "Inicio de sesión exitoso.\n";
} else {
    echo "Error al iniciar sesión. Código HTTP: $httpCode\n";
    echo "Respuesta: $response\n";
    curl_close($ch);
    exit;
}

// Paso 2: Agregar productos
$totalProductos = 1000; // Cambia este valor según la cantidad deseada
for ($i = 0; $i < $totalProductos; $i++) {
    echo "Intentando agregar producto $i...\n";
    $productoData = [
        "agregarProducto" => "1",
        "nombre" => "Producto " . $i,
        "codigo" => "COD" . rand(1000, 9999) . $i,
        "sku" => "SKU" . rand(1000, 9999) . $i,
        "descripcion" => "Descripción del producto " . $i,
        "precio_compra" => rand(10, 100), // Precio de compra aleatorio
        "precio_venta" => rand(101, 200), // Precio de venta aleatorio
        "id_unidad_medida" => 1,          // ID válido de unidad de medida
        "stock_minimo" => rand(1, 10),    // Stock mínimo aleatorio
        "stock_maximo" => rand(11, 100),  // Stock máximo aleatorio
        "id_categoria" => 1,              // ID válido de categoría
        "id_proveedor" => 1               // ID válido de proveedor
    ];

    curl_setopt($ch, CURLOPT_URL, $urlAgregarProducto);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($productoData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile); // Usar cookies guardadas

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($httpCode === 200) {
        echo "Producto $i agregado correctamente.\n";
    } else {
        echo "Error al agregar el producto $i. Código HTTP: $httpCode\n";
        echo "Respuesta: $response\n";
    }
}

// Cerrar cURL
curl_close($ch);
echo "Script finalizado.\n";
?>