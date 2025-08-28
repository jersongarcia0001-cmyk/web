<?php
session_start();

$otp_input = $_POST['otp_input'];

if (isset($_SESSION['otp']) && isset($_SESSION['otp_expiration'])) {
    if (time() > $_SESSION['otp_expiration']) {
        echo "❌ El código ha expirado. Por favor, vuelve a iniciar sesión.";
        session_destroy();
    } elseif ($otp_input == $_SESSION['otp']) {
        echo "✅ Verificación correcta. Bienvenido, " . $_SESSION['usuario'];
        // Aquí rediriges al panel de votación
        // header("Location: panel.html");
    } else {
        echo "❌ Código incorrecto. Intenta nuevamente.";
    }
} else {
    echo "No se encontró ningún código activo. Vuelve a iniciar sesión.";
}
?>
