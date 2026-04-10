<?php
/* ==========================================================
   VIEWS/RECEPCIONISTA/NUEVA_RESERVA.PHP: Registro de Estancia
   ========================================================== */
session_start();

if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role_name']) !== 'receptionist') {
    header("Location: ../../index.php?error=acceso_denegado");
    exit();
}

require_once '../../config/conexion.php';

try {
    // 1. Obtener solo usuarios que son Clientes (Role ID: 4)
    $stmtClientes = $pdo->query("SELECT id, name FROM users WHERE role_id = 4 ORDER BY name ASC");
    $clientes = $stmtClientes->fetchAll();

    // 2. Obtener solo Habitaciones que están Marcadas como 'Available'
    $stmtHab = $pdo->query("SELECT id, room_number, type, price FROM rooms WHERE status = 'Available' ORDER BY room_number ASC");
    $habitaciones = $stmtHab->fetchAll();

} catch (PDOException $e) {
    die("Error al cargar datos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Reserva - Hotel PWA</title>
    <link rel="stylesheet" href="../../assets/css/base.css">
    <link rel="stylesheet" href="../../assets/css/layout.css">
    <link rel="stylesheet" href="../../assets/css/componentes/formularios.css">
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
                <div style="font-weight: 600;">Crear Nueva Reservación</div>
            </header>

            <div class="content-body">
                <div style="margin-bottom: 20px;">
                    <a href="index.php" style="color: var(--color-secundario); font-weight: 600;">&larr; Volver al control</a>
                </div>

                <div style="max-width: 700px; background: var(--color-blanco); padding: 30px; border-radius: 8px; box-shadow: var(--sombra-suave); border: 1px solid var(--color-borde);">
                    
                    <form action="procesar_reserva.php" method="POST">
                        
                        <div class="form-group">
                            <label for="user_id" class="form-label">Seleccionar Cliente</label>
                            <select id="user_id" name="user_id" class="form-control" required>
                                <option value="" disabled selected>Busque al cliente...</option>
                                <?php foreach ($clientes as $cli): ?>
                                    <option value="<?php echo $cli['id']; ?>"><?php echo htmlspecialchars($cli['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="room_id" class="form-label">Habitación Disponible</label>
                            <select id="room_id" name="room_id" class="form-control" required>
                                <option value="" disabled selected>Seleccione habitación...</option>
                                <?php foreach ($habitaciones as $hab): ?>
                                    <option value="<?php echo $hab['id']; ?>">
                                        Hab: <?php echo $hab['room_number']; ?> - <?php echo $hab['type']; ?> ($<?php echo $hab['price']; ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div style="display: flex; gap: 20px;">
                            <div class="form-group" style="flex: 1;">
                                <label for="check_in" class="form-label">Fecha de Entrada</label>
                                <input type="date" id="check_in" name="check_in" class="form-control" required min="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="form-group" style="flex: 1;">
                                <label for="check_out" class="form-label">Fecha de Salida</label>
                                <input type="date" id="check_out" name="check_out" class="form-control" required min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                            </div>
                        </div>

                        <div style="display: flex; gap: 10px; margin-top: 25px;">
                            <button type="submit" class="btn btn-primario" style="flex: 1;">Confirmar Reservación</button>
                            <a href="index.php" class="btn btn-secundario" style="flex: 1; background-color: #64748b;">Cancelar</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
