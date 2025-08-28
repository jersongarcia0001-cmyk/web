<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // ✅ Validación de correo institucional
    if (!preg_match('/^[a-zA-Z0-9._%+-]+@unah\.edu\.hn$/', $email)) {
        die("
        <div class='container error'>
            <h2>⚠️ Error</h2>
            <p>Debes usar tu correo institucional <b>@unah.edu.hn</b></p>
            <a href='login.html'>🔙 Volver</a>
        </div>
        <style>
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background: #0f172a;
                display:flex; justify-content:center; align-items:center;
                height:100vh; margin:0;
            }
            .container {
                background:#1e293b;
                padding:25px; border-radius:12px;
                box-shadow:0 8px 20px rgba(0,0,0,0.3);
                max-width:400px; text-align:center;
                color:#f1f5f9;
            }
            h2 { color:#f87171; margin-bottom:10px; }
            p { color:#e2e8f0; }
            a {
                display:inline-block; margin-top:12px; padding:10px 20px;
                background:#f87171; color:#fff;
                text-decoration:none; border-radius:6px;
                transition:0.3s;
            }
            a:hover { background:#dc2626; }
        </style>
        ");
    }

    // ✅ Generamos OTP
    $otp = rand(100000, 999999);
    $_SESSION['otp'] = $otp;
    $_SESSION['otp_expira'] = time() + 300; // Expira en 5 minutos
    $_SESSION['usuario'] = $usuario;

    // ✅ Interfaz HTML con estilo mejorado
    echo "
    <!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <title>Verificación OTP</title>
        <style>
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background: linear-gradient(135deg, #0f172a, #1e293b, #334155);
                display: flex; justify-content: center; align-items: center;
                height: 100vh; margin: 0;
            }
            .container {
                background: #1e293b;
                padding: 30px;
                border-radius: 15px;
                box-shadow: 0px 8px 25px rgba(0,0,0,0.4);
                text-align: center;
                max-width: 420px;
                color: #f1f5f9;
            }
            h2 {
                color: #38bdf8;
                margin-bottom: 15px;
            }
            p {
                font-size: 15px;
                color: #cbd5e1;
                margin-bottom: 10px;
            }
            b { color: #f8fafc; }
            .otp {
                font-size: 22px;
                font-weight: bold;
                color: #facc15;
                margin: 15px 0;
            }
            a {
                display: inline-block;
                margin-top: 15px;
                padding: 12px 20px;
                background: #38bdf8;
                color: #0f172a;
                font-weight: bold;
                text-decoration: none;
                border-radius: 8px;
                transition: 0.3s;
            }
            a:hover {
                background: #0ea5e9;
                color: #fff;
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <h2>📩 Verificación OTP</h2>
            <p>Se envió un código OTP al correo:</p>
            <p><b>$email</b></p>
            <p class='otp'>🔑 Código OTP (prueba): $otp</p>
            <a href='verificar_otp.php'>➡️ Continuar a Verificación</a>
        </div>
    </body>
    </html>
    ";
} else {
    header("Location: login.html");
    exit();
}
?>
