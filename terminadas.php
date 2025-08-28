<?php
$hoy = date("Y-m-d");
$sql = "SELECT * FROM votaciones WHERE fecha_fin < '$hoy'";
$result = $conexion->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<div class='card'>";
        echo "<h3>" . $row['titulo'] . "</h3>";
        echo "<p>Finalizó el: " . $row['fecha_fin'] . "</p>";
        echo "<a href='detalle_votacion.php?id=" . $row['id'] . "'>Ver resultados</a>";
        echo "</div>";
    }
} else {
    echo "<div class='card'><p>No hay votaciones terminadas.</p></div>";
}
?>
