<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    die("Acceso denegado. Inicia sesión.");
}

$usuario = $_SESSION['usuario'];

// 🔹 Simulación de candidatos por votación
$candidatos = [
    1 => [
        ["nombre" => "María López", "foto" => "img/maria.jpg", "desc" => "Estudiante de Derecho, con experiencia en liderazgo estudiantil."],
        ["nombre" => "Carlos Ramírez", "foto" => "img/carlos.jpg", "desc" => "Estudiante de Ingeniería, promotor de proyectos de innovación."],
        ["nombre" => "Ana Torres", "foto" => "img/ana.jpg", "desc" => "Estudiante de Medicina, defensora de la salud universitaria."],
        ["nombre" => "Pedro Gómez", "foto" => "img/pedro.jpg", "desc" => "Estudiante de Ciencias Sociales, activo en voluntariado."],
        ["nombre" => "Lucía Martínez", "foto" => "img/lucia.jpg", "desc" => "Estudiante de Arte, impulsora de actividades culturales."],
        ["nombre" => "Jorge Díaz", "foto" => "img/jorge.jpg", "desc" => "Estudiante de Administración, con propuestas de emprendimiento."],
        ["nombre" => "Sofía Herrera", "foto" => "img/sofia.jpg", "desc" => "Estudiante de Psicología, enfocada en el bienestar estudiantil."]
    ],
    2 => [
        ["nombre" => "Dr. Ricardo Salinas", "foto" => "img/salinas.jpg", "desc" => "Profesor de Ingeniería Civil, con 20 años de experiencia."],
        ["nombre" => "Ing. Paula Méndez", "foto" => "img/paula.jpg", "desc" => "Docente en Ingeniería Eléctrica, promotora de igualdad de género."],
        ["nombre" => "Ing. Luis Fernández", "foto" => "img/luis.jpg", "desc" => "Coordinador de proyectos tecnológicos en la facultad."],
        ["nombre" => "Ing. Gabriela Suazo", "foto" => "img/gabriela.jpg", "desc" => "Impulsora de programas de innovación educativa."],
        ["nombre" => "Dr. Hernán Pineda", "foto" => "img/hernan.jpg", "desc" => "Docente e investigador en Ingeniería Mecánica."],
        ["nombre" => "Ing. Camila Ordoñez", "foto" => "img/camila.jpg", "desc" => "Apasionada por la sostenibilidad y energías renovables."]
    ],
    3 => [
        ["nombre" => "Juan Morales", "foto" => "img/juan.jpg", "desc" => "Estudiante de Historia, promotor de actividades culturales."],
        ["nombre" => "Daniela Ruiz", "foto" => "img/daniela.jpg", "desc" => "Estudiante de Literatura, ganadora de premios de escritura."],
        ["nombre" => "Andrés Castro", "foto" => "img/andres.jpg", "desc" => "Estudiante de Música, defensor del arte en la universidad."],
        ["nombre" => "Paola Martínez", "foto" => "img/paola.jpg", "desc" => "Estudiante de Teatro, con amplia experiencia en organización."],
        ["nombre" => "Felipe Soto", "foto" => "img/felipe.jpg", "desc" => "Estudiante de Filosofía, apasionado por la crítica social."],
        ["nombre" => "Carolina Pérez", "foto" => "img/carolina.jpg", "desc" => "Estudiante de Comunicación, con experiencia en medios."],
        ["nombre" => "Miguel Ángel Reyes", "foto" => "img/miguel.jpg", "desc" => "Estudiante de Danza, reconocido por su trayectoria artística."]
    ]
];

$id = $_GET['id'] ?? null;

if (!$id || !isset($candidatos[$id])) {
    die("Votación no encontrada.");
}

// 🔹 Conexión a MySQL (solo para guardar votos)
$conn = new mysqli("localhost", "root", "", "sivot", 3310);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// 🔹 Validar si ya votó
$yaVoto = false;
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['candidato'])) {
    $candidato = $_POST['candidato'];

    $check = $conn->prepare("SELECT * FROM votos WHERE id_votacion=? AND usuario=?");
    $check->bind_param("is", $id, $usuario);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $yaVoto = true;
    } else {
        $stmt = $conn->prepare("INSERT INTO votos (id_votacion, usuario, candidato) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $id, $usuario, $candidato);
        $stmt->execute();
        echo "<script>alert('¡Tu voto fue registrado con éxito!'); window.location='votaciones.php?seccion=activas';</script>";
        exit;
    }
}

// 🔹 Obtener resultados para la gráfica
$datosGrafica = [];
$sql = $conn->prepare("SELECT candidato, COUNT(*) as votos FROM votos WHERE id_votacion=? GROUP BY candidato");
$sql->bind_param("i", $id);
$sql->execute();
$res = $sql->get_result();
while ($row = $res->fetch_assoc()) {
    $datosGrafica[] = $row;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle Votación</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 20px; }
        h1 { text-align: center; margin-bottom: 20px; }
        .candidatos { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; }
        .candidato {
            background: white; border-radius: 10px; padding: 15px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2); text-align: center;
        }
        .candidato img { width: 100%; max-height: 200px; object-fit: cover; border-radius: 10px; }
        .candidato h3 { margin: 10px 0 5px; }
        .candidato p { font-size: 14px; color: #555; }
        .btn-votar {
            margin-top: 10px; padding: 10px 20px; background: #1a237e; color: white;
            border: none; border-radius: 5px; cursor: pointer;
        }
        .btn-votar:hover { background: #3949ab; }
        .grafica { max-width: 600px; margin: 40px auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 6px rgba(0,0,0,0.2); }
    </style>
</head>
<body>
    <h1><?php echo $id == 1 ? "Elección de Representantes Estudiantiles" : ($id == 2 ? "Elección de Decano de Ingeniería" : "Elección de Comité Cultural"); ?></h1>

    <?php if ($yaVoto): ?>
        <p style="color:red; text-align:center;"><b>Ya has votado en esta elección.</b></p>
    <?php else: ?>
        <form method="POST">
            <div class="candidatos">
                <?php foreach ($candidatos[$id] as $c): ?>
                    <div class="candidato">
                        <img src="<?php echo $c['foto']; ?>" alt="Foto de <?php echo $c['nombre']; ?>">
                        <h3><?php echo $c['nombre']; ?></h3>
                        <p><?php echo $c['desc']; ?></p>
                        <button type="submit" name="candidato" value="<?php echo $c['nombre']; ?>" class="btn-votar">Votar</button>
                    </div>
                <?php endforeach; ?>
            </div>
        </form>
    <?php endif; ?>

    <!-- 🔹 Gráfica de votos -->
    <div class="grafica">
        <h2 style="text-align:center;">Resultados de la votación</h2>
        <canvas id="graficaVotos"></canvas>
    </div>

    <script>
        const ctx = document.getElementById('graficaVotos').getContext('2d');
        const data = {
            labels: <?php echo json_encode(array_column($datosGrafica, 'candidato')); ?>,
            datasets: [{
                label: 'Votos',
                data: <?php echo json_encode(array_column($datosGrafica, 'votos')); ?>,
                backgroundColor: [
                    '#FF6384','#36A2EB','#FFCE56','#4BC0C0','#9966FF','#FF9F40','#66BB6A'
                ]
            }]
        };

        new Chart(ctx, {
            type: 'pie',
            data: data
        });
    </script>
</body>
</html>
