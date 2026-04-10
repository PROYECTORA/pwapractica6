<?php
/* ==========================================================
   VIEWS/GERENTE/INDEX.PHP: Panel de Gestión de Habitaciones
   ========================================================== */

session_start();

// 1. Verificación de seguridad (Solo Gerentes)
if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role_name']) !== 'hotel manager') {
    header("Location: ../../index.php?error=acceso_denegado");
    exit();
}

require_once '../../config/conexion.php';

// 2. Consulta para obtener las habitaciones
try {
    $stmt = $pdo->prepare("SELECT * FROM rooms ORDER BY room_number ASC");
    $stmt->execute();
    $habitaciones = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error al consultar habitaciones: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Habitaciones - Hotel PWA</title>
    
    <link rel="stylesheet" href="../../assets/css/base.css">
    <link rel="stylesheet" href="../../assets/css/layout.css">
    <link rel="stylesheet" href="../../assets/css/componentes/botones.css">
    <link rel="stylesheet" href="../../assets/css/componentes/tablas.css">
</head>
<body>

    <div class="wrapper">
        <aside class="sidebar">
            <div class="sidebar-header">Hotel PWA - Gerencia</div>
            <nav class="sidebar-nav">
                <a href="index.php" class="sidebar-link active">Habitaciones y Precios</a>
                <a href="../../auth/logout.php" class="sidebar-link" style="margin-top: auto; color: #fca5a5;">Cerrar Sesión</a>
            </nav>
        </aside>

        <div class="main-content">
            <header class="header-top">
                <div style="font-weight: 600;">Bienvenido, <?php echo htmlspecialchars($_SESSION['user_name']); ?></div>
                <span class="badge badge-disponible">Gerencia</span>
            </header>

            <div class="content-body">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h2 style="color: var(--color-primario);">Inventario de Habitaciones</h2>
                    <a href="crear_habitacion.php" class="btn btn-primario">+ Añadir Habitación</a>
                </div>

                <table class="tabla-hotelera">
                    <thead>
                        <tr>
                            <th>Nº</th>
                            <th>Tipo</th>
                            <th>Precio/Noche</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($habitaciones) > 0): ?>
                            <?php foreach ($habitaciones as $room): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($room['room_number']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($room['type']); ?></td>
                                    <td>$<?php echo number_format($room['price'], 2); ?></td>
                                    <td>
                                        <?php if ($room['status'] === 'Available'): ?>
                                            <span class="badge badge-disponible">Disponible</span>
                                        <?php else: ?>
                                            <span class="badge badge-ocupado">Ocupada</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="editar_habitacion.php?id=<?php echo $room['id']; ?>" class="btn btn-secundario" style="padding: 5px 10px;">Editar</a>
                                        <a href="eliminar_habitacion.php?id=<?php echo $room['id']; ?>" class="btn btn-peligro" style="padding: 5px 10px;" onclick="return confirm('¿Eliminar esta habitación?');">Eliminar</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5" style="text-align: center;">No hay habitaciones registradas.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>
