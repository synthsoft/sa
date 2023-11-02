<?php
// Inicia la sesión
session_start();

// Destruye todas las variables de sesión
session_destroy();

// Redirige al usuario a una página de inicio de sesión u otra página después de cerrar sesión
header("Location: login_index.php"); // Cambia "inicio_sesion.php" al archivo que desees
exit;
?>