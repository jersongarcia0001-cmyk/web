<?php
session_start();

// Datos del login
$usuario = $_POST['usuario'];
$password = $_POST['password'];
$email = $_POST['email'];

// Validar que el correo sea institucional (@unah.edu.hn)
if (preg_match("/@unah\.edu\.hn$/", $email)) {
    // Guardar sesión
    $_SESSION['usuario'] = $usuario;
    $_SESSION['email'] = $email;

    // Redirigir a las votaciones
    header("Location: votaciones.php");
    exit();
} else {
    echo "<h2>❌ Error: Debes usar tu correo institucional (@unah.edu.hn)</h2>";
    echo "<a href='login.html'>Volver al login</a>";
}
?>
