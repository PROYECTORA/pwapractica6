<?php
/* ==========================================================
   VIEWS/RECEPCIONISTA/INDEX.PHP: Control de Reservaciones
   ========================================================== */
session_start();

if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role_name']) !== 'receptionist') {
    header("Location: ../../index.php?error=acceso_denegado");
    exit();
}

require_once '../../config/conexion.php';

try {
    $stmt = $pdo->prepare("
        SELECT b.id, u.name as customer_name, r.room_number, b.check_in, b.check_out, b.status
        FROM bookings b
        JOIN users u ON b.user_id = u.id
        JOIN rooms r ON b.room_id = r.id
        ORDER BY b.check_in DESC
    ");
    $stmt->execute();
    $reservas = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error al consultar reservas: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Reservas - Hotel PWA</title>
    <link rel="stylesheet" href="../../assets/css/base.css">
    <link rel="stylesheet" href="../../assets/css/layout.css">
    <link rel="stylesheet" href="../../assets/css/componentes/botones.css">
    <link rel="stylesheet" href="../../assets/css/componentes/tablas.css">
</head>
<body>

    <div class="wrapper">
        <aside class="sidebar">
            <div class="sidebar-header">Hotel PWA - Recepción</div>
            <nav class="sidebar-nav">
                <a href="index.php" class="sidebar-link active">Control de Reservas</a>
                <a href="../../auth/logout.php" class="sidebar-link" style="margin-top: auto; color: #fca5a5;">Cerrar Sesión</a>
            </nav>
        </aside>

        <div class="main-content">
            <header class="header-top">
                <div style="font-weight: 600;">Bienvenido, <?php echo htmlspecialchars($_SESSION['user_name']); ?></div>
                <span class="badge" style="background-color: #fef9c3; color: #854d0e;">Recepcionista</span>
            </header>

            <div class="content-body">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h2 style="color: var(--color-primario);">Estado de Reservaciones</h2>
                    <a href="nueva_reserva.php" class="btn btn-primario">+ Crear Reservación</a>
                </div>

                <table class="tabla-hotelera">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Hab.</th>
                            <th>Check-In</th>
                            <th>Check-Out</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($reservas) > 0): ?>
                            <?php foreach ($reservas as $res): ?>
                                <tr>
                                    <td><?php echo $res['id']; ?></td>
                                    <td><?php echo htmlspecialchars($res['customer_name']); ?></td>
                                    <td><strong><?php echo htmlspecialchars($res['room_number']); ?></strong></td>
                                    <td><?php echo $res['check_in']; ?></td>
                                    <td><?php echo $res['check_out']; ?></td>
                                    <td>
                                        <?php 
                                            // Traducción dinámica de estados
                                            $estado_db = strtolower($res['status']);
                                            switch($estado_db) {
                                                case 'confirmed': $txt = 'CONFIRMADA'; $clase = 'badge-disponible'; break;
                                                case 'pending':   $txt = 'PENDIENTE';  $clase = 'badge-ocupado'; break;
                                                case 'cancelled': $txt = 'CANCELADA';  $clase = 'badge-ocupado'; break;
                                                default:          $txt = strtoupper($estado_db); $clase = ''; break;
                                            }
                                        ?>
                                        <span class="badge <?php echo $clase; ?>">
                                            <?php echo $txt; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="ver_reserva.php?id=<?php echo $res['id']; ?>" class="btn btn-secundario" style="padding: 5px 10px;">Ver</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="7" style="text-align: center;">No hay reservas registradas actualmente.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>
