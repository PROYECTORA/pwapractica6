<?php
/* ==========================================
   INDEX.PHP: Enrutador Central por Roles
   Actualizado: Validación Robusta de Acceso
   ========================================== */

// 1. Iniciar el manejo de sesiones
session_start();

// 2. Verificar si el usuario NO está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

/**
 * 3. Determinar el redireccionamiento por Rol.
 * Usamos trim() para eliminar espacios invisibles que puedan venir de la BD.
 */
$role = strtolower(trim($_SESSION['role_name']));

switch ($role) {
    case 'administrator':
    case 'admin':
        header("Location: views/admin/index.php");
        break;
        
    case 'hotel manager':
    case 'gerente':
        header("Location: views/gerente/index.php");
        break;
        
    case 'receptionist':
    case 'recepcionista':
        header("Location: views/recepcionista/index.php");
        break;
        
    case 'customer':
    case 'cliente':
        header("Location: views/cliente/index.php");
        break;
        
    case 'supplier':
    case 'proveedor':
        header("Location: views/proveedor/index.php");
        break;
        
    default:
        // Si el rol no coincide, cerramos sesión y mostramos cuál es el rol fallido
        header("Location: auth/logout.php?error=rol_no_autorizado&rol_detectado=" . urlencode($role));
        break;
}

exit();
?>
