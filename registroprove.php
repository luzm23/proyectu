<?php
$servername = "localhost";
$username = "root"; // Cambia "usuario" por tu nombre de usuario
$password = ""; // Cambia "contraseña" por tu contraseña
$dbname = "artesanias"; // Cambia "nombre_de_la_base_de_datos" por el nombre de tu base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}


// Obtener datos del formulario
$nombres = $_POST['nombres'];
$apellidos = $_POST['apellidos'];
$telefono = $_POST['telefono'];
$correo = $_POST['correo'];
$negocio = $_POST['negocio'];
$productos = $_POST['productos'];
$comentarios = $_POST['comentarios'];

// Preparar y vincular
$stmt = $conn->prepare("INSERT INTO proveedores (nombres, apellidos, telefono, correo, negocio, productos, comentarios) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssss", $nombres, $apellidos, $telefono, $correo, $negocio, $productos, $comentarios);

// Ejecutar la consulta
if ($stmt->execute()) {
    echo  "<script>alert('Proveedor registrado con éxito.'); location.href='visualizarproveedores.php';</script>";
    
} else {
    echo "Error: " . $stmt->error;
}

// Cerrar la conexión
$stmt->close();
$conn->close();
?>
