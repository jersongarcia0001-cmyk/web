<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "sistema_votaciones";
$port = 3310;

// Conexión
$conn = new mysqli($servername, $username, $password, $database, $port);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Crear nueva votación
if (isset($_POST['crear_votacion'])) {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];

    $sql = "INSERT INTO votaciones (titulo, descripcion, fecha_inicio, fecha_fin, estado) 
            VALUES ('$titulo', '$descripcion', '$fecha_inicio', '$fecha_fin', 'programada')";
    $conn->query($sql);
}

// Crear nuevo candidato
if (isset($_POST['crear_candidato'])) {
    $votacion_id = $_POST['votacion_id'];
    $nombre = $_POST['nombre'];

    // Subir foto
    $foto = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $foto = "uploads/" . basename($_FILES['foto']['name']);
        move_uploaded_file($_FILES['foto']['tmp_name'], $foto);
    }

    $sql = "INSERT INTO candidatos (votacion_id, nombre, foto) 
            VALUES ('$votacion_id', '$nombre', '$foto')";
    $conn->query($sql);
}

// Obtener votaciones
$votaciones = $conn->query("SELECT * FROM votaciones ORDER BY fecha_inicio DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel de Administración - SiVot</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 20px;
      background: #f5f5f5;
    }
    h1 {
      text-align: center;
      color: #003366;
    }
    .form-box {
      background: white;
      padding: 20px;
      margin-bottom: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.2);
    }
    label {
      font-weight: bold;
      display: block;
      margin-top: 10px;
    }
    input, textarea, select {
      width: 100%;
      padding: 8px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }
    button {
      margin-top: 15px;
      padding: 10px;
      background: #003366;
      color: #FFD700;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }
    button:hover {
      background: #FFD700;
      color: #003366;
    }
    .listado {
      background: #fff;
      padding: 20px;
      border-radius: 10px;
    }
    .votacion {
      border-bottom: 1px solid #ddd;
      padding: 10px 0;
    }
  </style>
</head>
<body>

  <h1>Panel de Administración - SiVot</h1>

  <!-- Crear nueva votación -->
  <div class="form-box">
    <h2>Crear nueva votación</h2>
    <form method="POST">
      <label>Título:</label>
      <input type="text" name="titulo" required>

      <label>Descripción:</label>
      <textarea name="descripcion" required></textarea>

      <label>Fecha de inicio:</label>
      <input type="date" name="fecha_inicio" required>

      <label>Fecha de fin:</label>
      <input type="date" name="fecha_fin" required>

      <button type="submit" name="crear_votacion">Crear Votación</button>
    </form>
  </div>

  <!-- Crear nuevo candidato -->
  <div class="form-box">
    <h2>Agregar candidato a votación</h2>
    <form method="POST" enctype="multipart/form-data">
      <label>Seleccionar votación:</label>
      <select name="votacion_id" required>
        <?php while ($v = $votaciones->fetch_assoc()): ?>
          <option value="<?= $v['id'] ?>"><?= $v['titulo'] ?> (<?= $v['fecha_inicio'] ?> - <?= $v['fecha_fin'] ?>)</option>
        <?php endwhile; ?>
      </select>

      <label>Nombre del candidato:</label>
      <input type="text" name="nombre" required>

      <label>Foto del candidato:</label>
      <input type="file" name="foto" accept="image/*">

      <button type="submit" name="crear_candidato">Agregar Candidato</button>
    </form>
  </div>

  <!-- Listado de votaciones -->
  <div class="listado">
    <h2>Votaciones existentes</h2>
    <?php
    $votaciones = $conn->query("SELECT * FROM votaciones ORDER BY fecha_inicio DESC");
    while ($v = $votaciones->fetch_assoc()):
    ?>
      <div class="votacion">
        <strong><?= $v['titulo'] ?></strong> (<?= $v['estado'] ?>)<br>
        <?= $v['descripcion'] ?><br>
        Desde <?= $v['fecha_inicio'] ?> hasta <?= $v['fecha_fin'] ?>
      </div>
    <?php endwhile; ?>
  </div>

</body>
</html>
