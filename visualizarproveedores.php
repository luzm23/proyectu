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

// Manejar la actualización del proveedor
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $id = $_POST['id'];
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $negocio = $_POST['negocio'];
    $productos = $_POST['productos'];
    $comentarios = $_POST['comentarios'];

    // Actualizar los datos del proveedor
    $sql = "UPDATE proveedores SET nombres = ?, apellidos = ?, telefono = ?, correo = ?, negocio = ?, productos = ?, comentarios = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssi", $nombres, $apellidos, $telefono, $correo, $negocio, $productos, $comentarios, $id);

    if ($stmt->execute()) {
        echo "<p>Proveedor actualizado con éxito.</p>";
    } else {
        echo "<p>Error al actualizar el proveedor: " . $stmt->error . "</p>";
    }

    $stmt->close();
}

// Manejar la eliminación del proveedor
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $id = $_POST['id'];

    // Eliminar el proveedor
    $sql = "DELETE FROM proveedores WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<p>Proveedor eliminado con éxito.</p>";
    } else {
        echo "<p>Error al eliminar el proveedor: " . $stmt->error . "</p>";
    }

    $stmt->close();
}

// Obtener datos de la tabla proveedores
$sql = "SELECT id, nombres, apellidos, telefono, correo, negocio, productos, comentarios FROM proveedores";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h1>Lista de Proveedores</h1>";
    echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>Nombres</th>
                <th>Apellidos</th>
                <th>Teléfono</th>
                <th>Correo</th>
                <th>Negocio</th>
                <th>Productos en Ventas</th>
                <th>Comentarios</th>
                <th>Acciones</th>
            </tr>";
    while($row = $result->fetch_assoc()) {
        $id = $row["id"];
        echo "<tr>
                <td>" . $row["id"]. "</td>
                <td>" . $row["nombres"]. "</td>
                <td>" . $row["apellidos"]. "</td>
                <td>" . $row["telefono"]. "</td>
                <td>" . $row["correo"]. "</td>
                <td>" . $row["negocio"]. "</td>
                <td>" . $row["productos"]. "</td>
                <td>" . $row["comentarios"]. "</td>
                <td>
                    <form action='' method='post' style='display:inline;'>
                        <input type='hidden' name='id' value='" . $id . "'>
                        <button type='submit' name='edit'>Editar</button>
                    </form>
                    <form action='' method='post' style='display:inline;'>
                        <input type='hidden' name='id' value='" . $id . "'>
                        <button type='submit' name='delete' onclick='return confirm(\"¿Estás seguro de que deseas eliminar este proveedor?\");'>Eliminar</button>
                    </form>
                </td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No hay proveedores registrados.";
}

// Mostrar el formulario de edición si se ha solicitado editar un proveedor
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit'])) {
    $id = $_POST['id'];

    // Obtener los datos del proveedor
    $sql = "SELECT id, nombres, apellidos, telefono, correo, negocio, productos, comentarios FROM proveedores WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $proveedor = $result->fetch_assoc();
    $stmt->close();
    ?>
    <h1>Editar Proveedor</h1>
    <form action="" method="post">
        <input type="hidden" name="id" value="<?php echo $proveedor['id']; ?>">
        <label for="nombres">Nombres:</label>
        <input type="text" id="nombres" name="nombres" value="<?php echo $proveedor['nombres']; ?>" required><br>
        <label for="apellidos">Apellidos:</label>
        <input type="text" id="apellidos" name="apellidos" value="<?php echo $proveedor['apellidos']; ?>" required><br>
        <label for="telefono">Teléfono:</label>
        <input type="text" id="telefono" name="telefono" value="<?php echo $proveedor['telefono']; ?>" required><br>
        <label for="correo">Correo:</label>
        <input type="email" id="correo" name="correo" value="<?php echo $proveedor['correo']; ?>" required><br>
        <label for="negocio">Negocio:</label>
        <input type="text" id="negocio" name="negocio" value="<?php echo $proveedor['negocio']; ?>" required><br>
        <label for="productos">Productos en Ventas:</label>
        <input type="text" id="productos" name="productos" value="<?php echo $proveedor['productos']; ?>" required><br>
        <label for="comentarios">Comentarios:</label>
        <textarea id="comentarios" name="comentarios"><?php echo $proveedor['comentarios']; ?></textarea><br>
        <button type="submit" name="update">Actualizar Proveedor</button>
    </form>
    <a href="pedidos.php">Volver a  provedores</a>
    <?php
}

// Cerrar la conexión
$conn->close();
?>