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

// Manejar eliminación de pedido
if (isset($_POST['action']) && $_POST['action'] == 'eliminar') {
    $id_cliente = $_POST['id_cliente'];

    // Eliminar el pedido
    $sql = "DELETE FROM pedidos WHERE id_clientes=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_cliente);

    if ($stmt->execute()) {
        echo "<p>Pedido eliminado con éxito.</p>";
    } else {
        echo "<p>Error al eliminar el pedido: " . $stmt->error . "</p>";
    }

    $stmt->close();
}

// Manejar edición de pedido
if (isset($_POST['action']) && $_POST['action'] == 'editar') {
    if (isset($_POST['id_cliente'])) {
        $id_cliente = $_POST['id_cliente'];
        $nombre = $_POST['nombre'] ?? '';
        $apellidos = $_POST['apellidos'] ?? '';
        $correo = $_POST['correo'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $negocio = $_POST['negocio'] ?? '';
        $pedidos = $_POST['pedidos'] ?? '';
        $comentarios = $_POST['comentarios'] ?? '';

        // Actualizar el pedido
        $sql = "UPDATE pedidos SET nombre=?, apellidos=?, correo=?, telefono=?, negocio=?, pedidos=?, comentarios=? WHERE id_clientes=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssi", $nombre, $apellidos, $correo, $telefono, $negocio, $pedidos, $comentarios, $id_cliente);

        if ($stmt->execute()) {
            echo "<p>Pedido actualizado con éxito.</p>";
        } else {
            echo "<p>Error al actualizar el pedido: " . $stmt->error . "</p>";
        }

        $stmt->close();
    }
}

// Obtener datos de la tabla pedidos
$sql = "SELECT id_clientes, nombre, apellidos, correo, telefono, negocio, pedidos, comentarios FROM pedidos";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h1>Lista de Pedidos</h1>";
    echo "<table border='1'>
            <tr>
                <th>ID Cliente</th>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Correo</th>
                <th>Teléfono</th>
                <th>Negocio</th>
                <th>Pedidos</th>
                <th>Comentarios</th>
                <th>Acciones</th>
            </tr>";
    while($row = $result->fetch_assoc()) {
        $id_cliente = $row["id_clientes"];
        echo "<tr>
                <td>" . htmlspecialchars($row["id_clientes"]) . "</td>
                <td>" . htmlspecialchars($row["nombre"]) . "</td>
                <td>" . htmlspecialchars($row["apellidos"]) . "</td>
                <td>" . htmlspecialchars($row["correo"]) . "</td>
                <td>" . htmlspecialchars($row["telefono"]) . "</td>
                <td>" . htmlspecialchars($row["negocio"]) . "</td>
                <td>" . htmlspecialchars($row["pedidos"]) . "</td>
                <td>" . htmlspecialchars($row["comentarios"]) . "</td>
                <td>
                    <form action='' method='post' style='display:inline;'>
                        <input type='hidden' name='id_cliente' value='" . htmlspecialchars($id_cliente) . "'>
                        <input type='hidden' name='action' value='editar'>
                        <button type='submit'>Editar</button>
                    </form>
                    <form action='' method='post' style='display:inline;'>
                        <input type='hidden' name='id_cliente' value='" . htmlspecialchars($id_cliente) . "'>
                        <input type='hidden' name='action' value='eliminar'>
                        <button type='submit' onclick='return confirm(\"¿Estás seguro de que deseas eliminar este pedido?\");'>Eliminar</button>
                    </form>
                </td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No hay pedidos registrados.";
}

// Mostrar formulario de edición si se solicita
if (isset($_POST['action']) && $_POST['action'] == 'editar') {
    $id_cliente = $_POST['id_cliente'] ?? '';

    if ($id_cliente) {
        // Obtener los datos del pedido
        $sql = "SELECT * FROM pedidos WHERE id_clientes = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_cliente);
        $stmt->execute();
        $result = $stmt->get_result();
        $pedido = $result->fetch_assoc();
        
        if (!$pedido) {
            die("Pedido no encontrado.");
        }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Pedido</title>
</head>
<body>
    <h1>Editar Pedido</h1>
    <form action="" method="post">
        <input type="hidden" name="id_cliente" value="<?php echo htmlspecialchars($pedido['id_clientes']); ?>">
        <input type="hidden" name="action" value="editar">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($pedido['nombre']); ?>" required><br>
        <label for="apellidos">Apellidos:</label>
        <input type="text" id="apellidos" name="apellidos" value="<?php echo htmlspecialchars($pedido['apellidos']); ?>" required><br>
        <label for="correo">Correo:</label>
        <input type="email" id="correo" name="correo" value="<?php echo htmlspecialchars($pedido['correo']); ?>" required><br>
        <label for="telefono">Teléfono:</label>
        <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($pedido['telefono']); ?>" required><br>
        <label for="negocio">Negocio:</label>
        <input type="text" id="negocio" name="negocio" value="<?php echo htmlspecialchars($pedido['negocio']); ?>" required><br>
        <label for="pedidos">Pedidos:</label>
        <input type="text" id="pedidos" name="pedidos" value="<?php echo htmlspecialchars($pedido['pedidos']); ?>" required><br>
        <label for="comentarios">Comentarios:</label>
        <textarea id="comentarios" name="comentarios"><?php echo htmlspecialchars($pedido['comentarios']); ?></textarea><br>
        <button type="submit">Actualizar</button>
    </form>
    <a href="pedidos.php">Volver a pedidos</a>
</body>
</html>
<?php
    } else {
        echo "No se especificó el ID del cliente para editar.";
    }
}

// Cerrar la conexión
$conn->close();
?>
