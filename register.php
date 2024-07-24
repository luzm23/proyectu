<?php
// Incluir archivo de configuración de la base de datos
include_once 'config.php';

// Iniciar la sesión para almacenar mensajes
session_start();

// Obtener datos del formulario de registro
$user = mysqli_real_escape_string($conn, $_POST['username']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$pass = mysqli_real_escape_string($conn, $_POST['password']);
$user_type = mysqli_real_escape_string($conn, $_POST['user_type']); // Obtener tipo de usuario

// Hash de la contraseña
$hashed_password = password_hash($pass, PASSWORD_DEFAULT);

// Verificar la conexión a la base de datos
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si el usuario o correo ya existe en la base de datos
$sql = "SELECT * FROM usuarios WHERE username='$user' OR email='$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<script>alert('El usuario o correo electrónico ya está registrado.'); window.location.href = 'register.html';</script>";
  
    exit();
}

// Contar el número de administradores actuales
$sql = "SELECT COUNT(*) as total_admins FROM usuarios WHERE user_type='administrador'";
$result = $conn->query($sql);

// Verificar si la consulta fue exitosa
if ($result === FALSE) {
    die("Error en la consulta SQL: " . $conn->error);
}

$row = $result->fetch_assoc();
$total_admins = $row['total_admins'];

// Limitar el número de administradores a 3
if ($user_type == 'administrador' && $total_admins >= 3) {
    echo "<script>alert('Ya hay 3 administradores registrados. No se pueden registrar más administradores'); window.location.href = 'register.html';</script>";
   

    exit();
} else {
    // Preparar la consulta SQL para insertar un nuevo usuario
    $sql = "INSERT INTO usuarios (username, email, password, user_type) VALUES ('$user', '$email', '$hashed_password', '$user_type')";

    // Ejecutar la consulta y verificar si fue exitosa
    if ($conn->query($sql) === TRUE) {
        // Redirigir al usuario después de un registro exitoso
        echo "<script>alert('registro exitoso'); window.location.href = 'login.html';</script>";
   
        exit();
    } else {
        echo "Error al registrar usuario: " . $conn->error;
    }
}

// Cerrar la conexión
$conn->close();