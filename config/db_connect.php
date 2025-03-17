<?php

date_default_timezone_set('America/Bogota'); // Configurar la zona horaria global

require_once 'db_config.php';

$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

if ($conn->connect_error) {
    die("Conexion fallida: " . $conn->connect_error);
}
?>
