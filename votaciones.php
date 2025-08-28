<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    die("Acceso denegado. Inicia sesión.");
}

// 🔹 Simulación de votaciones
$votaciones = [
    "activas" => [
        ["id" => 1, "titulo" => "Elección de Representantes Estudiantiles", "foto" => "f1.png"],
        ["id" => 2, "titulo" => "Elección de Decano de Ingeniería", "foto" => "f2.jpg"],
        ["id" => 3, "titulo" => "Elección de Comité Cultural", "foto" => "f3.jpg"]
    ],
    "programadas" => [
        ["id" => 4, "titulo" => "Elección de Consejo Universitario", "fecha" => "2025-08-30", "foto" => "f4.jpg"],
        ["id" => 5, "titulo" => "Elección de Rector", "fecha" => "2025-09-01", "foto" => "f5.png"]
    ],
    "terminadas" => [
        ["id" => 6, "titulo" => "Elección de Comité de Deportes", "fecha" => "2025-08-15", "foto" => "f6.png"]
    ]
];

$seccion = $_GET['seccion'] ?? "inicio";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Votaciones</title>
    <style>
        body { 
            margin: 0; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: #eef1f8; 
            color: #333;
            animation: fadeIn 1s ease-in;
        }
        .container { display: flex; height: 100vh; }

        /* 📌 Menú lateral */
        .menu { 
            width: 260px; 
            background: linear-gradient(180deg, #1a237e, #283593); 
            color: white; 
            padding: 25px; 
            box-shadow: 3px 0 10px rgba(0,0,0,0.2);
            animation: slideInLeft 0.8s ease;
        }
        .menu h2 { text-align: center; margin-bottom: 35px; font-size: 22px; }
        .menu a { 
            display: block; 
            padding: 12px 15px; 
            color: white; 
            text-decoration: none; 
            margin-bottom: 12px; 
            border-radius: 10px; 
            font-weight: 500;
            transition: all 0.3s ease; 
        }
        .menu a:hover { 
            background: rgba(255,255,255,0.2); 
            transform: translateX(5px); 
        }

        /* 📌 Contenido */
        .content { 
            flex-grow: 1; 
            padding: 35px; 
            overflow-y: auto; 
            animation: fadeInUp 1s ease;
        }
        .content h1 {
            font-size: 26px;
            color: #1a237e;
            margin-bottom: 25px;
            border-bottom: 3px solid #1a237e;
            display: inline-block;
            padding-bottom: 5px;
        }

        /* 📌 Inicio */
        .inicio { 
            max-width: 900px; 
            margin: auto; 
            text-align: center; 
            animation: fadeIn 1.2s ease;
        }
        .inicio img.logo { 
            width: 130px; 
            margin: 0 auto 25px auto; 
            display: block; 
            animation: bounce 2s infinite;
        }
        .inicio h1 { 
            color: #1a237e; 
            margin-bottom: 20px; 
            font-size: 28px; 
            animation: fadeInDown 1s ease;
        }
        .inicio p { 
            font-size: 17px; 
            color: #444; 
            text-align: justify; 
            line-height: 1.7; 
            margin-bottom: 22px; 
            animation: fadeIn 1.5s ease;
        }
        .inicio .info-img { 
            width: 100%; 
            max-height: 300px; 
            object-fit: cover; 
            border-radius: 14px; 
            margin: 18px 0; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            transition: transform 0.4s ease; 
        }
        .inicio .info-img:hover {
            transform: scale(1.03);
        }

        /* 📌 Tarjetas de votaciones */
        .votaciones { 
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); 
            gap: 25px; 
        }
        .card {
            background: white; 
            border-radius: 15px; 
            box-shadow: 0 5px 15px rgba(0,0,0,0.15);
            overflow: hidden; 
            transition: all 0.3s ease;
            animation: fadeInUp 1s ease;
        }
        .card:hover { 
            transform: translateY(-8px); 
            box-shadow: 0 8px 20px rgba(0,0,0,0.25); 
        }
        .card img { width: 100%; height: 190px; object-fit: cover; }
        .card-body { padding: 18px; text-align: center; }
        .card h3 { margin: 12px 0; font-size: 19px; color: #1a237e; font-weight: bold; }

        /* 📌 Botón */
        .btn {
            display: inline-block; 
            margin-top: 12px; 
            padding: 12px 22px; 
            background: linear-gradient(45deg, #3949ab, #1a237e);
            color: white; 
            border: none; 
            border-radius: 10px; 
            text-decoration: none; 
            font-weight: bold; 
            font-size: 15px;
            cursor: pointer; 
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
            animation: pulse 2s infinite;
        }
        .btn:hover { 
            background: linear-gradient(45deg, #1a237e, #3949ab); 
            transform: scale(1.05); 
        }

        /* 📌 Mensaje vacío */
        .info { 
            text-align: center; 
            margin-top: 60px; 
            font-size: 18px; 
            color: #666; 
            animation: fadeIn 1.2s ease;
        }

        /* 🔹 Animaciones */
        @keyframes fadeIn { from {opacity:0;} to {opacity:1;} }
        @keyframes fadeInUp { from {opacity:0; transform:translateY(20px);} to {opacity:1; transform:translateY(0);} }
        @keyframes fadeInDown { from {opacity:0; transform:translateY(-20px);} to {opacity:1; transform:translateY(0);} }
        @keyframes slideInLeft { from {opacity:0; transform:translateX(-50px);} to {opacity:1; transform:translateX(0);} }
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-10px); }
            60% { transform: translateY(-5px); }
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="menu">
        <h2>Menú</h2>
        <a href="votaciones.php?seccion=inicio">🏠 Inicio</a>
        <a href="votaciones.php?seccion=activas">✅ Votaciones Activas</a>
        <a href="votaciones.php?seccion=programadas">⏳ Programadas</a>
        <a href="votaciones.php?seccion=terminadas">📌 Terminadas</a>
    </div>
    <div class="content">
        <?php if ($seccion === "inicio"): ?>
            <div class="inicio">
                <img src="logoo.png" alt="Logo Institucional" class="logo">
                <h1>Bienvenido al Sistema de Votaciones</h1>
                <p>
                    Este sistema ha sido desarrollado para garantizar un proceso electoral transparente, seguro 
                    y accesible para toda la comunidad universitaria. Aquí podrás emitir tu voto de manera digital 
                    y confiable, asegurando que cada estudiante tenga la oportunidad de participar en la toma de decisiones.
                </p>
                <img src="fotoo.jpeg" alt="Imagen informativa" class="info-img">
                <p>
                    Las votaciones están organizadas en tres secciones principales: 
                    <b>Votaciones Activas</b>, donde puedes ejercer tu derecho al voto; 
                    <b>Programadas</b>, que muestran las elecciones próximas; y 
                    <b>Terminadas</b>, que almacenan los procesos ya concluidos.
                </p>
                <p>
                    Recuerda que cada estudiante solo puede votar <b>una vez por elección</b>, 
                    garantizando la igualdad y transparencia en los resultados.
                </p>
            </div>

        <?php elseif (isset($votaciones[$seccion])): ?>
            <h1><?php echo ucfirst($seccion); ?></h1>
            <div class="votaciones">
                <?php foreach ($votaciones[$seccion] as $v): ?>
                    <div class="card">
                        <img src="<?php echo $v['foto']; ?>" alt="Imagen de <?php echo $v['titulo']; ?>">
                        <div class="card-body">
                            <h3><?php echo $v['titulo']; ?></h3>
                            <?php if ($seccion === "programadas"): ?>
                                <p><b>Comienza:</b> <?php echo $v['fecha']; ?></p>
                            <?php elseif ($seccion === "terminadas"): ?>
                                <p><b>Terminó:</b> <?php echo $v['fecha']; ?></p>
                            <?php endif; ?>
                            <?php if ($seccion === "activas"): ?>
                                <a href="detalle_votacion.php?id=<?php echo $v['id']; ?>" class="btn">Entrar a Votación</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="info">No hay votaciones en este momento.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
