<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Verificación OTP</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #003366;
      color: #fff;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .box {
      background: #fff;
      color: #000;
      padding: 30px;
      border-radius: 10px;
      text-align: center;
      max-width: 400px;
    }
    input {
      width: 80%;
      padding: 10px;
      margin: 10px 0;
      border-radius: 5px;
      border: 1px solid #ccc;
      text-align: center;
      font-size: 1.2em;
    }
    button {
      padding: 12px 20px;
      background: #003366;
      color: #FFD700;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 1em;
    }
    button:hover {
      background: #FFD700;
      color: #003366;
    }
  </style>
</head>
<body>
  <div class="box">
    <h2>Verificación de Seguridad</h2>
    <p>Ingrese el código OTP enviado a su correo institucional</p>
    <form action="validar_otp.php" method="POST">
      <input type="text" name="otp_ingresado" placeholder="Código OTP" required>
      <button type="submit">Verificar</button>
    </form>
  </div>
</body>
</html>
