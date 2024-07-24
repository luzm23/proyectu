<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "artesanias";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$sql = "SELECT id_producto, nombre, precio FROM PRODUCTOS";
$result = $conn->query($sql);

$productos = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $productos[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($productos);

$conn->close();
?>
