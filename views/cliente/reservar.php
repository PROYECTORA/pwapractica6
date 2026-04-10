<?php
/* ==========================================================
   VIEWS/CLIENTE/RESERVAR.PHP: Confirmación de Estancia
   ========================================================== */
session_start();

if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role_name']) !== 'customer') {
    header("Location: ../../index.php?error=acceso_denegado");
    exit();
}

require_once '../../config/conexion.php';

if (!isset($_GET['room_id']) || empty($_GET['room_id'])) {
    header("Location: index.php");
    exit();
}

$room_id = $_GET['room_id'];

try {
    // Obtener datos de la habitación seleccionada
    $stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = :id AND status = 'Available' LIMIT 1");
    $stmt->execute(['id' => $room_id]);
    $room = $stmt->fetch();

    if (!$room) {
        header("Location: index.php?error=no_disponible");
        exit();
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Confirmar Reserva - Hotel PWA</title>
    <link rel="stylesheet" href="../../assets/css/base.css">
    <link rel="stylesheet" href="../../assets/css/layout.css">
    <link rel="stylesheet" href="../../assets/css/componentes/formularios.css">
    <link rel="stylesheet" href="../../assets/css/componentes/botones.css">
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
                <div style="font-weight: 600;">Confirmar Reservación: Habitación <?php echo $room['room_number']; ?></div>
            </header>

            <div class="content-body">
                <div style="margin-bottom: 20px;">
                    <a href="index.php" style="color: var(--color-secundario); font-weight: 600;">&larr; Volver al catálogo</a>
                </div>

                <div style="max-width: 500px; background: white; padding: 30px; border-radius: 8px; box-shadow: var(--sombra-suave); border: 1px solid var(--color-borde);">
                    <h3 style="color: var(--color-primario); margin-bottom: 10px;"><?php echo $room['type']; ?></h3>
                    <p style="margin-bottom: 20px; color: #64748b;">Precio por noche: <strong>$<?php echo $room['price']; ?></strong></p>

                    <form action="procesar_autoreserva.php" method="POST">
                        <input type="hidden" name="room_id" value="<?php echo $room['id']; ?>">
                        
                        <div class="form-group">
                            <label class="form-label">Fecha de Entrada</label>
                            <input type="date" name="check_in" class="form-control" required min="<?php echo date('Y-m-d'); ?>">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Fecha de Salida</label>
                            <input type="date" name="check_out" class="form-control" required min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                        </div>

                        <button type="submit" class="btn btn-primario btn-block" style="margin-top: 20px;">
                            Confirmar y Reservar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
