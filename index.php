<?php
/* ==========================================
   INDEX.PHP: Enrutador Central por Roles
   ========================================== */

// 1. Iniciar el manejo de sesiones
session_start();

// 2. Verificar si el usuario NO está logueado
if (!isset($_SESSION['user_id'])) {
    // Si no hay sesión, enviarlo al formulario de acceso
    header("Location: auth/login.php");
    exit();
}

/**
 * 3. Si hay sesión, determinar el redireccionamiento por Rol.
 * El nombre del rol se convierte a minúsculas para coincidir con las carpetas.
 * Roles esperados: 'Administrator', 'Hotel Manager', 'Receptionist', 'Customer', 'Supplier'
 */
$role = strtolower($_SESSION['role_name']);

switch ($role) {
    case 'administrator':
        header("Location: views/admin/index.php");
        break;
        
    case 'hotel manager':
        header("Location: views/gerente/index.php");
        break;
        
    case 'receptionist':
        header("Location: views/recepcionista/index.php");
        break;
        
    case 'customer':
        header("Location: views/cliente/index.php");
        break;
        
    case 'supplier':
        header("Location: views/proveedor/index.php");
        break;
        
    default:
        // En caso de un rol no definido, cerrar sesión por seguridad
        header("Location: auth/logout.php?error=rol_no_autorizado");
        break;
}

// Finalizar la ejecución del script tras la redirección
exit();
?>
