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

// Capturar datos del formulario
$nombre_cliente = $_POST['nombre_cliente'];
$fecha = $_POST['fecha'];
$nombre_personal = $_POST['nombre_personal'];
$telefono_empresa = $_POST['telefono_empresa'];
$domicilio_empresa = $_POST['domicilio_empresa'];
$total = $_POST['total'];

// Insertar datos en la tabla 'tickets'
$sql = "INSERT INTO tickets (nombre_cliente, fecha, nombre_personal, telefono_empresa, domicilio_empresa, total)
VALUES ('$nombre_cliente', '$fecha', '$nombre_personal', '$telefono_empresa', '$domicilio_empresa', '$total')";

if ($conn->query($sql) !== TRUE) {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Obtener el ID del último ticket insertado
$ticket_id = $conn->insert_id;

// Insertar los productos en la tabla 'productos_ticket'
$productos = $_POST['producto'];
$cantidades = $_POST['cantidad'];
$productos_info = array();

for ($i = 0; $i < count($productos); $i++) {
    $producto_id = $productos[$i];
    $cantidad = $cantidades[$i];

    // Obtener el nombre y el precio del producto desde la tabla 'PRODUCTOS'
    $sql_producto_info = "SELECT nombre, precio FROM PRODUCTOS WHERE id_producto = '$producto_id'";
    $result_producto_info = $conn->query($sql_producto_info);

    if ($result_producto_info->num_rows > 0) {
        $row = $result_producto_info->fetch_assoc();
        $nombre_producto = $row['nombre'];
        $precio = $row['precio'];

        $sql_producto = "INSERT INTO productos_ticket (ticket_id, producto_id, cantidad, precio)
        VALUES ('$ticket_id', '$producto_id', '$cantidad', '$precio')";

        if ($conn->query($sql_producto) !== TRUE) {
            echo "Error: " . $sql_producto . "<br>" . $conn->error;
        }

        // Actualizar la cantidad en la tabla 'PRODUCTOS'
        $sql_update_producto = "UPDATE PRODUCTOS SET cantidad = cantidad - '$cantidad' WHERE id_producto = '$producto_id'";

        if ($conn->query($sql_update_producto) !== TRUE) {
            echo "Error: " . $sql_update_producto . "<br>" . $conn->error;
        }

        // Guardar la información del producto para imprimir el ticket
        $productos_info[] = array(
            'nombre' => $nombre_producto,
            'cantidad' => $cantidad,
            'precio' => $precio
        );
    } else {
        echo "Error: Producto no encontrado.<br>";
    }
}

// Generar ticket
echo "
<html>
<head>
    <title>Ticket de Compra</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        .ticket { width: 300px; padding: 20px; border: 1px solid #000; margin: auto; }
        .ticket h2 { text-align: center; }
        .ticket p { margin: 0; }
        .ticket img { width: 100px; height: auto; display: block; margin: 0 auto 10px; }
        .button { padding: 10px 20px; background-color: #4CAF50; color: white; border: none; cursor: pointer; }
        .button:hover { background-color: #45a049; }
    </style>
    <script>
        function imprimirTicket() {
            window.print();
        }
    </script>
</head>
<body>
    <div class='ticket'>
        <h2><img src='ima/log.png' width='100px' alt='Logo de la Empresa'></h2>
        <p><strong>Nombre del Cliente:</strong> $nombre_cliente</p>
        <p><strong>Fecha:</strong> $fecha</p>
        <p><strong>Nombre del Personal:</strong> $nombre_personal</p>
        <p><strong>Teléfono de la Empresa:</strong> $telefono_empresa</p>
        <p><strong>Domicilio de la Empresa:</strong> $domicilio_empresa</p>";

foreach ($productos_info as $producto) {
    echo "
        <p><strong>Producto:</strong> " . $producto['nombre'] . "</p>
        <p><strong>Cantidad:</strong> " . $producto['cantidad'] . "</p>
        <p><strong>Precio Unitario:</strong> $" . number_format($producto['precio'], 2) . "</p>";
}

echo "
        <p><strong>Total:</strong> $" . number_format($total, 2) . "</p>
    </div>
    <button class='button' onclick='imprimirTicket()'>Imprimir Ticket</button>
</body>
</html>";

$conn->close();
?>
