<?php
/* ==========================================================
   VIEWS/RECEPCIONISTA/VER_RESERVA.PHP: Detalle de Estancia
   ========================================================== */
session_start();

if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role_name']) !== 'receptionist') {
    header("Location: ../../index.php?error=acceso_denegado");
    exit();
}

require_once '../../config/conexion.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id_reserva = $_GET['id'];

try {
    // Consulta detallada uniendo tablas de usuarios y habitaciones
    $stmt = $pdo->prepare("
        SELECT b.*, u.name as customer_name, u.email as customer_email, 
               r.room_number, r.type as room_type, r.price
        FROM bookings b
        JOIN users u ON b.user_id = u.id
        JOIN rooms r ON b.room_id = r.id
        WHERE b.id = :id
        LIMIT 1
    ");
    $stmt->execute(['id' => $id_reserva]);
    $reserva = $stmt->fetch();

    if (!$reserva) {
        header("Location: index.php?error=reserva_no_encontrada");
        exit();
    }
} catch (PDOException $e) {
    die("Error al cargar la reserva: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle de Reserva - Hotel PWA</title>
    <link rel="stylesheet" href="../../assets/css/base.css">
    <link rel="stylesheet" href="../../assets/css/layout.css">
    <link rel="stylesheet" href="../../assets/css/componentes/botones.css">
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
                <div style="font-weight: 600;">Reserva ID: #<?php echo $reserva['id']; ?></div>
            </header>

            <div class="content-body">
                <div style="margin-bottom: 20px;">
                    <a href="index.php" style="color: var(--color-secundario); font-weight: 600;">&larr; Volver al control</a>
                </div>

                <div style="background: white; padding: 30px; border-radius: 8px; box-shadow: var(--sombra-suave); border: 1px solid var(--color-borde); display: flex; gap: 40px;">
                    
                    <!-- Datos del Cliente -->
                    <div style="flex: 1;">
                        <h3 style="color: var(--color-primario); margin-bottom: 15px; border-bottom: 2px solid var(--color-fondo); padding-bottom: 10px;">Información del Huésped</h3>
                        <p><strong>Nombre:</strong> <?php echo htmlspecialchars($reserva['customer_name']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($reserva['customer_email']); ?></p>
                    </div>

                    <!-- Datos de la Estancia -->
                    <div style="flex: 1;">
                        <h3 style="color: var(--color-primario); margin-bottom: 15px; border-bottom: 2px solid var(--color-fondo); padding-bottom: 10px;">Detalles de Habitación</h3>
                        <p><strong>Nº Habitación:</strong> <?php echo htmlspecialchars($reserva['room_number']); ?></p>
                        <p><strong>Tipo:</strong> <?php echo htmlspecialchars($reserva['room_type']); ?></p>
                        <p><strong>Costo por noche:</strong> $<?php echo number_format($reserva['price'], 2); ?></p>
                    </div>

                    <!-- Datos de Fecha -->
                    <div style="flex: 1;">
                        <h3 style="color: var(--color-primario); margin-bottom: 15px; border-bottom: 2px solid var(--color-fondo); padding-bottom: 10px;">Fechas Programadas</h3>
                        <p><strong>Check-In:</strong> <?php echo $reserva['check_in']; ?></p>
                        <p><strong>Check-Out:</strong> <?php echo $reserva['check_out']; ?></p>
                        <p><strong>Estado:</strong> 
                            <span class="badge" style="background-color: #dcfce7; color: #15803d;">
                                <?php echo strtoupper($reserva['status']); ?>
                            </span>
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</body>
</html>
