<?php
/* ==========================================================
   VIEWS/ADMIN/INDEX.PHP: Vista Principal del Administrador
   ========================================================== */

// 1. Iniciar el manejo de sesiones
session_start();

// 2. Verificar si el usuario está logueado y si es Administrador
if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role_name']) !== 'administrator') {
    // Si no está logueado o no es admin, redirigir al login o al router
    header("Location: ../../index.php?error=acceso_denegado");
    exit();
}

// 3. Importar la conexión a la base de datos
require_once '../../config/conexion.php';

// 4. Consulta para obtener todos los usuarios y el nombre de su rol
try {
    $stmt = $pdo->prepare("
        SELECT u.id, u.name, u.email, r.name as role_name 
        FROM users u 
        JOIN roles r ON u.role_id = r.id 
        ORDER BY u.id DESC
    ");
    $stmt->execute();
    $usuarios = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error al consultar usuarios: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Gestión de Usuarios</title>
    
    <!-- Invocación de estilos locales (Rutas relativas al directorio raíz) -->
    <link rel="stylesheet" href="../../assets/css/base.css">
    <link rel="stylesheet" href="../../assets/css/layout.css">
    <link rel="stylesheet" href="../../assets/css/componentes/botones.css">
    <link rel="stylesheet" href="../../assets/css/componentes/tablas.css">
</head>
<body>

    <div class="wrapper">
        <!-- Barra Lateral -->
        <aside class="sidebar">
            <div class="sidebar-header">
                Hotel PWA - Admin
            </div>
            <nav class="sidebar-nav">
                <a href="index.php" class="sidebar-link active">Gestión de Usuarios</a>
                <!-- Los demás enlaces se completarán en la barra lateral global -->
                <a href="../../auth/logout.php" class="sidebar-link" style="margin-top: auto; color: #fca5a5;">Cerrar Sesión</a>
            </nav>
        </aside>

        <!-- Contenido Principal -->
        <div class="main-content">
            <!-- Cabecera flotante -->
            <header class="header-top">
                <div style="font-weight: 600; color: var(--color-texto);">
                    Bienvenido, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                </div>
                <div>
                    <span class="badge badge-disponible">Rol: <?php echo htmlspecialchars($_SESSION['role_name']); ?></span>
                </div>
            </header>

            <!-- Cuerpo del contenido -->
            <div class="content-body">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h2 style="color: var(--color-primario);">Gestión de Usuarios</h2>
                    <a href="crear.php" class="btn btn-primario">+ Nuevo Usuario</a>
                </div>

                <!-- Tabla de Usuarios -->
                <table class="tabla-hotelera">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Correo Electrónico</th>
                            <th>Rol Asignado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($usuarios) > 0): ?>
                            <?php foreach ($usuarios as $user): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td>
                                        <span class="badge" style="background-color: #e2e8f0; color: #475569;">
                                            <?php echo htmlspecialchars($user['role_name']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="editar.php?id=<?php echo $user['id']; ?>" class="btn btn-secundario" style="padding: 5px 10px; font-size: 0.85rem;">Editar</a>
                                        <a href="eliminar.php?id=<?php echo $user['id']; ?>" class="btn btn-peligro" style="padding: 5px 10px; font-size: 0.85rem;" onclick="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">Eliminar</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align: center;">No hay usuarios registrados en el sistema.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>
