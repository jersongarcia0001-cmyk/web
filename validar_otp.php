<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $otp_ingresado = $_POST['otp_ingresado'];

    if (isset($_SESSION['otp']) && isset($_SESSION['otp_expira'])) {
        if (time() > $_SESSION['otp_expira']) {
            echo "<p>❌ El código OTP ha expirado. Intente iniciar sesión de nuevo.</p>";
            echo "<a href='login.html'>Volver al login</a>";
            session_destroy();
            exit();
        }

        if ($otp_ingresado == $_SESSION['otp']) {
            echo "<script>window.location.href='panel.html';</script>";
            exit();
        } else {
            echo "<p>❌ Código OTP incorrecto.</p>";
            echo "<a href='verificar_otp.php'>Intentar de nuevo</a>";
        }
    } else {
        header("Location: login.html");
        exit();
    }
} else {
    header("Location: login.html");
    exit();
}
?>
