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

// Manejar el registro de pedido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario con valores predeterminados vacíos
    $nombre = $_POST['nombre'] ?? '';
    $apellidos = $_POST['apellidos'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $negocio = $_POST['negocio'] ?? '';
    $pedidos = $_POST['pedidos'] ?? '';
    $comentarios = $_POST['comentarios'] ?? '';

    // Validar que los campos no estén vacíos
    if (empty($nombre) || empty($apellidos) || empty($correo) || empty($telefono) || empty($negocio) || empty($pedidos)) {
        die("Todos los campos son obligatorios.");
    }

    // Preparar la consulta SQL para insertar el pedido
    $sql = "INSERT INTO pedidos (nombre, apellidos, correo, telefono, negocio, pedidos, comentarios) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }

    // Asociar parámetros
    $stmt->bind_param("sssssss", $nombre, $apellidos, $correo, $telefono, $negocio, $pedidos, $comentarios);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "<p>Pedido registrado con éxito.</p>";
    } else {
        echo "<p>Error al registrar el pedido: " . $stmt->error . "</p>";
    }

    // Cerrar el statement
    $stmt->close();
}

// Cerrar la conexión
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Pedido</title>
</head>
<body>
    <h1>Registrar Pedido</h1>
    <form action="" method="post">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required><br>
        <label for="apellidos">Apellidos:</label>
        <input type="text" id="apellidos" name="apellidos" required><br>
        <label for="correo">Correo:</label>
        <input type="email" id="correo" name="correo" required><br>
        <label for="telefono">Teléfono:</label>
        <input type="text" id="telefono" name="telefono" required><br>
        <label for="negocio">Negocio:</label>
        <input type="text" id="negocio" name="negocio" required><br>
        <label for="pedidos">Pedidos:</label>
        <input type="text" id="pedidos" name="pedidos" required><br>
        <label for="comentarios">Comentarios:</label>
        <textarea id="comentarios" name="comentarios"></textarea><br>
        <button type="submit">Registrar Pedido</button>
    </form>
    <a href="visualizarpedido.php">visualizar la lista de pedidos</a>
</body>
</html>
