<?php
/* ==========================================
   LOGOUT.PHP: Cierre de Sesión Seguro
   ========================================== */
session_start();

// Limpiar todas las variables de sesión
$_SESSION = array();

// Destruir la cookie de sesión si existe
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-42000, '/');
}

// Destruir la sesión físicamente en el servidor
session_destroy();

// Redirigir al login
header("Location: login.php?status=loggedout");
exit();
