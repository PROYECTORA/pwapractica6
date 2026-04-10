<?php
/* ==========================================================
   VIEWS/ADMIN/CREAR.PHP: Formulario de Registro de Usuarios
   ========================================================== */

session_start();

// 1. Verificación estricta de sesión y Rol de Administrador
if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role_name']) !== 'administrator') {
    header("Location: ../../index.php?error=acceso_denegado");
    exit();
}

require_once '../../config/conexion.php';

// 2. Consulta para obtener los roles disponibles en la base de datos
try {
    $stmt = $pdo->query("SELECT id, name FROM roles ORDER BY id ASC");
    $roles = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error al cargar los roles: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Usuario - Hotel PWA</title>
    
    <!-- Estilos locales modulares -->
    <link rel="stylesheet" href="../../assets/css/base.css">
    <link rel="stylesheet" href="../../assets/css/layout.css">
    <link rel="stylesheet" href="../../assets/css/componentes/formularios.css">
    <link rel="stylesheet" href="../../assets/css/componentes/botones.css">
</head>
<body>

    <div class="wrapper">
        <!-- Barra Lateral Manual (Hasta que hagamos includes) -->
        <aside class="sidebar">
            <div class="sidebar-header">Hotel PWA - Admin</div>
            <nav class="sidebar-nav">
                <a href="index.php" class="sidebar-link active">Gestión de Usuarios</a>
                <a href="../../auth/logout.php" class="sidebar-link" style="margin-top: auto; color: #fca5a5;">Cerrar Sesión</a>
            </nav>
        </aside>

        <!-- Contenido Principal -->
        <div class="main-content">
            <header class="header-top">
                <div style="font-weight: 600; color: var(--color-texto);">
                    Registrar Nuevo Usuario
                </div>
            </header>

            <div class="content-body">
                <!-- Botón para volver atrás -->
                <div style="margin-bottom: 20px;">
                    <a href="index.php" style="color: var(--color-secundario); font-weight: 600;">&larr; Volver a la lista</a>
                </div>

                <!-- Tarjeta contenedora del Formulario -->
                <div style="max-width: 600px; background: var(--color-blanco); padding: 30px; border-radius: 8px; box-shadow: var(--sombra-suave); border: 1px solid var(--color-borde);">
                    
                    <h3 style="color: var(--color-primario); margin-bottom: 20px;">Datos del Usuario</h3>

                    <!-- El formulario apunta a procesar_crear.php que haremos en el siguiente paso -->
                    <form action="procesar_crear.php" method="POST">
                        
                        <div class="form-group">
                            <label for="name" class="form-label">Nombre Completo</label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="Ej. Juan Pérez" required autofocus>
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="usuario@hotel.com" required>
                        </div>

                        <div class="form-group">
                            <label for="password" class="form-label">Contraseña inicial</label>
                            <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                        </div>

                        <div class="form-group">
                            <label for="role_id" class="form-label">Rol del Sistema</label>
                            <select id="role_id" name="role_id" class="form-control" required style="cursor: pointer;">
                                <option value="" disabled selected>Selecciona un rol...</option>
                                <?php foreach ($roles as $rol): ?>
                                    <option value="<?php echo $rol['id']; ?>">
                                        <?php echo htmlspecialchars($rol['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div style="display: flex; gap: 10px; margin-top: 25px;">
                            <button type="submit" class="btn btn-primario" style="flex: 1;">Guardar Usuario</button>
                            <a href="index.php" class="btn btn-secundario" style="flex: 1; background-color: #64748b;">Cancelar</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
