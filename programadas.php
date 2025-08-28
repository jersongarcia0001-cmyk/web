<?php
$hoy = date("Y-m-d");
$sql = "SELECT * FROM votaciones WHERE fecha_inicio > '$hoy'";
$result = $conexion->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<div class='card'>";
        echo "<h3>" . $row['titulo'] . "</h3>";
        echo "<p>Comienza el: " . $row['fecha_inicio'] . "</p>";
        echo "</div>";
    }
} else {
    echo "<div class='card'><p>No hay votaciones programadas.</p></div>";
}
?>
