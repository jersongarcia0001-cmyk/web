<?php
$host = "localhost";
$usuario = "root";   // o el usuario que uses en MySQL Workbench
$clave = "";         // pon la contraseña de tu MySQL si tienes
$puerto = 3310;
$bd = "sivot_db";

$conexion = new mysqli($host, $usuario, $clave, $bd, $puerto);

if ($conexion->connect_error) {
    die("❌ Error de conexión: " . $conexion->connect_error);
}
?>
