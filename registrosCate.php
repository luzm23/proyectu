<?php
$servername = "localhost";
$username = "root"; // Cambia "root" por tu nombre de usuario
$password = ""; // Cambia "" por tu contraseña
$dbname = "artesanias"; // Cambia "artesanias" por el nombre de tu base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$action = isset($_POST['action']) ? $_POST['action'] : '';
$nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
$categoria_id = isset($_POST['id']) ? $_POST['id'] : '';

switch ($action) {
    case 'insertar':
        if (!empty($nombre)) {
            $stmt = $conn->prepare("INSERT INTO categoria (nombre) VALUES (?)");
            if ($stmt === false) {
                die("Error en la preparación de la consulta: " . $conn->error);
            }
            $stmt->bind_param("s", $nombre);
            if ($stmt->execute()) {
                echo "<script>alert('Categoría insertada exitosamente'); location.href='categoria.html';</script>";
            } else {
                echo "Error insertando la categoría: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error: El nombre de la categoría no puede estar vacío.";
        }
        break;

    case 'visualizar':
        $result = $conn->query("SELECT id_categoria, nombre FROM categoria");
        if ($result === false) {
            die("Error en la consulta: " . $conn->error);
        }
        if ($result->num_rows > 0) {
            echo "<h1>Lista de Categorías</h1>";
            echo "<table border='1'>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </tr>";
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row["id_categoria"]) . "</td>
                        <td>" . htmlspecialchars($row["nombre"]) . "</td>
                        <td>
                            <form method='POST' action=''>
                                <input type='hidden' name='action' value='eliminar'>
                                <input type='hidden' name='id' value='" . htmlspecialchars($row["id_categoria"]) . "'>
                                <input type='submit' value='Eliminar'>
                            </form>
                        </td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "No hay categorías registradas.";
        }
        break;

    case 'eliminar':
        if (!empty($categoria_id)) {
            // Preparar la consulta SQL
            $stmt = $conn->prepare("DELETE FROM categoria WHERE id_categoria = ?");
            
            if ($stmt === false) {
                die('Error al preparar la consulta: ' . $conn->error);
            }

            // Vincular parámetros
            $stmt->bind_param("i", $categoria_id);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                echo "<script>alert('Categoría eliminada exitosamente'); location.href='categoria.html';</script>";
            } else {
                echo "Error eliminando la categoría: " . $stmt->error;
            }

            // Cerrar la sentencia preparada
            $stmt->close();
        } else {
            echo "Error: ID de la categoría a eliminar no puede estar vacío.";
        }
        break;
}

$conn->close();
?>
