<?php


// Incluir archivo de configuración de la base de datos
include_once 'config.php';

// Iniciar la sesión para almacenar mensajes
session_start();

// Obtener datos del formulario de inicio de sesión
$user = mysqli_real_escape_string($conn, $_POST['username']);
$pass = mysqli_real_escape_string($conn, $_POST['password']);

// Preparar la consulta SQL para buscar el usuario
$sql = "SELECT * FROM usuarios WHERE username='$user'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // El usuario existe, obtener los datos
    $row = $result->fetch_assoc();
    $hashed_password = $row['password'];
    $user_type = $row['user_type'];

    // Verificar la contraseña
    if (password_verify($pass, $hashed_password)) {
        // La contraseña es correcta, iniciar sesión
        $_SESSION['message'] = "La contraseña es correcta.";
        $_SESSION['username'] = $user;
        $_SESSION['user_type'] = $user_type;

        // Redirigir según el tipo de usuario
        if ($user_type == 'administrador') {
            header("Location: ARTESANIAS ALEBRIJES.html");
        } else {
            header("Location: catalogo.php");
        }
        exit();
    } else {
        echo "<script>alert('contraseña incorrecta'); window.location.href = 'login.html';</script>";
        // La contraseña es incorrecta
       
        exit();
    }
} else {
    // El usuario no existe
    echo "<script>alert('Usuario no encontrado'); window.location.href = 'login.html';</script>";
    exit();
}

// Cerrar la conexión
$conn->close();
?>




