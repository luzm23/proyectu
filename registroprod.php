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

$action = isset($_POST['action']) ? $_POST['action'] : 'visualizar';
$nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
$tamaño = isset($_POST['tamaño']) ? $_POST['tamaño'] : '';
$precio = isset($_POST['precio']) ? $_POST['precio'] : '';
$cantidad = isset($_POST['cantidad']) ? $_POST['cantidad'] : '';
$id_categoria = isset($_POST['id_categoria']) ? $_POST['id_categoria'] : '';
$id_producto = isset($_POST['id_producto']) ? $_POST['id_producto'] : '';

switch ($action) {
    case 'insertar':
        // Verificar si la categoría existe
        $stmt = $conn->prepare("SELECT id_categoria FROM categoria WHERE id_categoria = ?");
        $stmt->bind_param("i", $id_categoria);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            if (!empty($nombre) && !empty($tamaño) && !empty($precio) && !empty($cantidad)) {
                $stmt = $conn->prepare("INSERT INTO productos (nombre, tamaño, precio, cantidad, id_categoria) VALUES (?, ?, ?, ?, ?)");
                if ($stmt === false) {
                    die("Error al preparar la consulta de inserción de producto: " . $conn->error);
                }
                $stmt->bind_param("ssdii", $nombre, $tamaño, $precio, $cantidad, $id_categoria);
                if ($stmt->execute()) {
                    echo "<script>alert('Producto insertado exitosamente'); window.location.href = 'producto.html';</script>";
                } else {
                    echo "Error insertando el producto: " . $stmt->error;
                }
                $stmt->close();
            } else {
                echo "<script>alert('Error: Todos los campos deben estar llenos.'); window.location.href = 'producto.html';</script>";
            }
        } else {
            echo "<script>alert('Error: La categoría especificada no existe.'); window.location.href = 'producto.html';</script>";
        }

        $stmt->close();
        break;

    case 'visualizar':
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>Visualizar Productos</title>
        </head>
        <body>
        <h2>Productos:</h2>
        <table border='1'>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Tamaño</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>ID Categoría</th>
                <th>Acción</th>
            </tr>
            <?php
            $result = $conn->query("SELECT * FROM productos");
            if ($result === false) {
                die("Error en la consulta: " . $conn->error);
            }

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row["id_producto"]); ?></td>
                        <td><?php echo htmlspecialchars($row["nombre"]); ?></td>
                        <td><?php echo htmlspecialchars($row["tamaño"]); ?></td>
                        <td><?php echo htmlspecialchars($row["precio"]); ?></td>
                        <td><?php echo htmlspecialchars($row["cantidad"]); ?></td>
                        <td><?php echo htmlspecialchars($row["id_categoria"]); ?></td>
                        <td>
                            <form method="POST" action="" style="display:inline;">
                                <input type="hidden" name="id_producto" value="<?php echo $row['id_producto']; ?>">
                                <input type="hidden" name="action" value="editar">
                                <input type="submit" value="Editar">
                            </form>
                           
                        </td>
                    </tr>
                    
                    <?php
                }
            } else {
                echo "<tr><td colspan='7'>No hay productos.</td></tr>";
            }
            ?>
        </table>
        </body>
        </html>
        <a href="producto.html">Volver a productos</a>
        <?php
        break;

    case 'editar':
        if (!empty($id_producto)) {
            // Obtener los datos del producto
            $stmt = $conn->prepare("SELECT * FROM productos WHERE id_producto = ?");
            if ($stmt === false) {
                die('Error al preparar la consulta de selección: ' . $conn->error);
            }
            $stmt->bind_param("i", $id_producto);
            $stmt->execute();
            $result = $stmt->get_result();
            $producto = $result->fetch_assoc();
            $stmt->close();
            ?>
            <!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="UTF-8">
                <title>Editar Producto</title>
            </head>
            <body>
            <h2>Editar Producto</h2>
            <form method="POST" action="">
                <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($producto['nombre']); ?>" required><br>
                <label for="tamaño">Tamaño:</label>
                <input type="text" id="tamaño" name="tamaño" value="<?php echo htmlspecialchars($producto['tamaño']); ?>" required><br>
                <label for="precio">Precio:</label>
                <input type="number" step="0.01" id="precio" name="precio" value="<?php echo htmlspecialchars($producto['precio']); ?>" required><br>
                <label for="cantidad">Cantidad:</label>
                <input type="number" id="cantidad" name="cantidad" value="<?php echo htmlspecialchars($producto['cantidad']); ?>" required><br>
                <label for="id_categoria">ID Categoría:</label>
                <input type="number" id="id_categoria" name="id_categoria" value="<?php echo htmlspecialchars($producto['id_categoria']); ?>" required><br>
                <input type="hidden" name="action" value="actualizar">
                <input type="submit" value="Actualizar">
            </form>
            </body>
            </html>
            <?php
        } else {
            echo "<script>alert('Error: El ID del producto a editar no puede estar vacío.'); window.location.href = 'producto.html';</script>";
        }
        break;

    case 'actualizar':
        if (!empty($id_producto) && !empty($nombre) && !empty($tamaño) && !empty($precio) && !empty($cantidad) && !empty($id_categoria)) {
            // Verificar si la categoría existe
            $stmt = $conn->prepare("SELECT id_categoria FROM categoria WHERE id_categoria = ?");
            $stmt->bind_param("i", $id_categoria);
            $stmt->execute();
            $stmt->store_result();
            
            if ($stmt->num_rows > 0) {
                // Actualizar los datos del producto
                $stmt = $conn->prepare("UPDATE productos SET nombre = ?, tamaño = ?, precio = ?, cantidad = ?, id_categoria = ? WHERE id_producto = ?");
                if ($stmt === false) {
                    die('Error al preparar la consulta de actualización: ' . $conn->error);
                }
                $stmt->bind_param("ssdiii", $nombre, $tamaño, $precio, $cantidad, $id_categoria, $id_producto);
                if ($stmt->execute()) {
                    echo "<script>alert('Producto actualizado exitosamente'); window.location.href = 'registroprod.php';</script>";
                } else {
                    echo "Error actualizando el producto: " . $stmt->error;
                }
                $stmt->close();
            } else {
                echo "<script>alert('Error: La categoría especificada no existe.'); window.location.href = 'producto.html';</script>";
            }
        } else {
            echo "<script>alert('Error: Todos los campos son obligatorios.'); window.location.href = 'producto.html';</script>";
        }
        break;

    default:
        echo "<script>alert('Acción no reconocida.'); window.location.href = 'producto.html';</script>";
        break;
}

// Cerrar la conexión al final del script
$conn->close();
?>
