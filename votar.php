<?php
session_start();

// Simulación: en login guardaste el usuario en la sesión
// Aquí debería estar: $_SESSION['usuario']
$votante_id = $_SESSION['usuario'] ?? "anonimo";

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "sivot";
$port = 3310;

$conn = new mysqli($host, $user, $pass, $dbname, $port);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $votacion_id = intval($_POST['votacion_id']);
    $candidato_id = intval($_POST['candidato_id']);

    // Validar si ya votó
    $check = $conn->query("SELECT * FROM votos WHERE votante_id='$votante_id' AND votacion_id=$votacion_id");
    if ($check->num_rows > 0) {
        echo "Ya has votado en esta elección.";
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO votos (votante_id, votacion_id, candidato_id) VALUES (?, ?, ?)");
    $stmt->bind_param("sii", $votante_id, $votacion_id, $candidato_id);

    if ($stmt->execute()) {
        echo "✅ Voto registrado correctamente.<br><a href='votaciones.php?id=$votacion_id'>Regresar</a>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
