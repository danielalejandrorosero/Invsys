<?php



require_once 'db_config.php';



$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

if ($conn->connect_error) {
    die("Conexion fallida: " . $conn->connect_error);
}
?>