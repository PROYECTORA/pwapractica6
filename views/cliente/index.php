<?php
/* ==========================================================
   VIEWS/CLIENTE/INDEX.PHP: Portal del Cliente
   ========================================================== */
session_start();

// 1. Verificación de seguridad (Solo Clientes)
if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role_name']) !== 'customer') {
    header("Location: ../../index.php?error=acceso_denegado");
    exit();
}

require_once '../../config/conexion.php';

$user_id = $_SESSION['user_id'];

try {
    // 2. Consulta de Mis Reservas (Filtrado por el ID del usuario en sesión)
    $stmtMyBookings = $pdo->prepare("
        SELECT b.id, r.room_number, r.type, b.check_in, b.check_out, b.status
        FROM bookings b
        JOIN rooms r ON b.room_id = r.id
        WHERE b.user_id = :uid
        ORDER BY b.check_in DESC
    ");
    $stmtMyBookings->execute(['uid' => $user_id]);
    $mis_reservas = $stmtMyBookings->fetchAll();

    // 3. Consulta de Habitaciones Disponibles para reservar
    $stmtAvail = $pdo->query("SELECT * FROM rooms WHERE status = 'Available' LIMIT 6");
    $habitaciones_libres = $stmtAvail->fetchAll();

} catch (PDOException $e) {
    die("Error al cargar datos del cliente: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Portal - Hotel PWA</title>
    <link rel="stylesheet" href="../../assets/css/base.css">
    <link rel="stylesheet" href="../../assets/css/layout.css">
    <link rel="stylesheet" href="../../assets/css/componentes/botones.css">
    <link rel="stylesheet" href="../../assets/css/componentes/tablas.css">
</head>
<body>

    <div class="wrapper">
        <aside class="sidebar">
            <div class="sidebar-header">Mi Cuenta Hotel</div>
            <nav class="sidebar-nav">
                <a href="index.php" class="sidebar-link active">Mis Reservaciones</a>
                <a href="../../auth/logout.php" class="sidebar-link" style="margin-top: auto; color: #fca5a5;">Cerrar Sesión</a>
            </nav>
        </aside>

        <div class="main-content">
            <header class="header-top">
                <div style="font-weight: 600;">Bienvenido, <?php echo htmlspecialchars($_SESSION['user_name']); ?></div>
                <span class="badge" style="background-color: #dbeafe; color: #1e40af;">Huésped Distinguido</span>
            </header>

            <div class="content-body">
                
                <!-- Sección 1: Mis Reservas -->
                <h2 style="color: var(--color-primario); margin-bottom: 20px;">Mis Reservas Actuales</h2>
                <table class="tabla-hotelera" style="margin-bottom: 40px;">
                    <thead>
                        <tr>
                            <th>Habitación</th>
                            <th>Tipo</th>
                            <th>Entrada</th>
                            <th>Salida</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($mis_reservas) > 0): ?>
                            <?php foreach ($mis_reservas as $res): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($res['room_number']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($res['type']); ?></td>
                                    <td><?php echo $res['check_in']; ?></td>
                                    <td><?php echo $res['check_out']; ?></td>
                                    <td>
                                        <?php 
                                            $estado_db = strtolower($res['status']);
                                            switch($estado_db) {
                                                case 'confirmed': $txt = 'CONFIRMADA'; $clase = 'badge-disponible'; break;
                                                case 'pending':   $txt = 'PENDIENTE';  $clase = 'badge-ocupado'; break;
                                                case 'cancelled': $txt = 'CANCELADA';  $clase = 'badge-ocupado'; break;
                                                default:          $txt = strtoupper($estado_db); $clase = ''; break;
                                            }
                                        ?>
                                        <span class="badge <?php echo $clase; ?>"><?php echo $txt; ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5" style="text-align: center;">Aún no tienes reservaciones registradas.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- Sección 2: Ofertas de Habitaciones -->
                <h2 style="color: var(--color-primario); margin-bottom: 20px;">Habitaciones Disponibles Ahora</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px;">
                    <?php foreach ($habitaciones_libres as $hab): ?>
                        <div style="background: white; padding: 20px; border-radius: 8px; border: 1px solid var(--color-borde); box-shadow: var(--sombra-suave);">
                            <h3 style="color: var(--color-secundario);">Hab: <?php echo $hab['room_number']; ?></h3>
                            <p style="font-size: 0.9rem; color: #64748b;"><?php echo $hab['type']; ?></p>
                            <p style="font-weight: 700; margin: 10px 0; font-size: 1.2rem;">$<?php echo $hab['price']; ?> <span style="font-size: 0.8rem; font-weight: 400;">/ noche</span></p>
                            <a href="reservar.php?room_id=<?php echo $hab['id']; ?>" class="btn btn-primario btn-block" style="font-size: 0.85rem;">Reservar Ahora</a>
                        </div>
                    <?php endforeach; ?>
                </div>

            </div>
        </div>
    </div>

</body>
</html>
