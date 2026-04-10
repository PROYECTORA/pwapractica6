<?php
/* ==========================================================
   VIEWS/GERENTE/EDITAR_HABITACION.PHP: Modificación de Hab.
   ========================================================== */
session_start();

if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role_name']) !== 'hotel manager') {
    header("Location: ../../index.php?error=acceso_denegado");
    exit();
}

require_once '../../config/conexion.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id_hab = $_GET['id'];

try {
    $stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = :id LIMIT 1");
    $stmt->execute(['id' => $id_hab]);
    $room = $stmt->fetch();

    if (!$room) {
        header("Location: index.php?error=no_encontrada");
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
    <title>Editar Habitación - Hotel PWA</title>
    <link rel="stylesheet" href="../../assets/css/base.css">
    <link rel="stylesheet" href="../../assets/css/layout.css">
    <link rel="stylesheet" href="../../assets/css/componentes/formularios.css">
    <link rel="stylesheet" href="../../assets/css/componentes/botones.css">
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
                <div style="font-weight: 600;">Editar Habitación: <?php echo htmlspecialchars($room['room_number']); ?></div>
            </header>

            <div class="content-body">
                <div style="margin-bottom: 20px;">
                    <a href="index.php" style="color: var(--color-secundario); font-weight: 600;">&larr; Volver al inventario</a>
                </div>

                <div style="max-width: 600px; background: var(--color-blanco); padding: 30px; border-radius: 8px; box-shadow: var(--sombra-suave); border: 1px solid var(--color-borde);">
                    <form action="procesar_editar_hab.php" method="POST">
                        <input type="hidden" name="id" value="<?php echo $room['id']; ?>">

                        <div class="form-group">
                            <label class="form-label">Número de Habitación</label>
                            <input type="text" name="room_number" class="form-control" value="<?php echo htmlspecialchars($room['room_number']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Tipo</label>
                            <select name="type" class="form-control" required>
                                <option value="Sencilla" <?php echo ($room['type'] == 'Sencilla') ? 'selected' : ''; ?>>Sencilla</option>
                                <option value="Doble" <?php echo ($room['type'] == 'Doble') ? 'selected' : ''; ?>>Doble</option>
                                <option value="Suite" <?php echo ($room['type'] == 'Suite') ? 'selected' : ''; ?>>Suite</option>
                                <option value="Presidencial" <?php echo ($room['type'] == 'Presidencial') ? 'selected' : ''; ?>>Presidencial</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Precio por Noche</label>
                            <input type="number" step="0.01" name="price" class="form-control" value="<?php echo $room['price']; ?>" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Estado</label>
                            <select name="status" class="form-control" required>
                                <option value="Available" <?php echo ($room['status'] == 'Available') ? 'selected' : ''; ?>>Disponible</option>
                                <option value="Occupied" <?php echo ($room['status'] == 'Occupied') ? 'selected' : ''; ?>>Ocupada</option>
                                <option value="Maintenance" <?php echo ($room['status'] == 'Maintenance') ? 'selected' : ''; ?>>Mantenimiento</option>
                            </select>
                        </div>

                        <div style="display: flex; gap: 10px; margin-top: 25px;">
                            <button type="submit" class="btn btn-primario" style="flex: 1;">Actualizar Habitación</button>
                            <a href="index.php" class="btn btn-secundario" style="flex: 1; background-color: #64748b;">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
