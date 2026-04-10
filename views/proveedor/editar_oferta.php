<?php
session_start();
if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role_name']) !== 'supplier') {
    header("Location: ../../index.php?error=acceso_denegado");
    exit();
}
require_once '../../config/conexion.php';

$supply_id = $_GET['id'] ?? '';
try {
    $stmt = $pdo->prepare("SELECT * FROM supplies WHERE id = :id AND status = 'In Progress' LIMIT 1");
    $stmt->execute(['id' => $supply_id]);
    $item = $stmt->fetch();
    if (!$item) { header("Location: index.php?error=no_editable"); exit(); }
} catch (PDOException $e) { die("Error: " . $e->getMessage()); }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mejorar Oferta - Hotel PWA</title>
    <link rel="stylesheet" href="../../assets/css/base.css">
    <link rel="stylesheet" href="../../assets/css/layout.css">
    <link rel="stylesheet" href="../../assets/css/componentes/formularios.css">
    <link rel="stylesheet" href="../../assets/css/componentes/botones.css">
</head>
<body>
    <div class="wrapper">
        <aside class="sidebar">
            <div class="sidebar-header">Logística Hotel</div>
            <nav class="sidebar-nav">
                <a href="index.php" class="sidebar-link active">Panel de Suministros</a>
                <a href="../../auth/logout.php" class="sidebar-link" style="margin-top: auto; color: #fca5a5;">Cerrar Sesión</a>
            </nav>
        </aside>
        <div class="main-content">
            <header class="header-top"><div style="font-weight: 600;">Mejorar Propuesta</div></header>
            <div class="content-body">
                <div style="max-width: 500px; background: white; padding: 30px; border-radius: 8px; box-shadow: var(--sombra-suave); border: 1px solid var(--color-borde);">
                    <h3 style="color: var(--color-primario);"><?php echo htmlspecialchars($item['item_name']); ?></h3>
                    <p style="margin-bottom: 15px;">Cantidad: <?php echo htmlspecialchars($item['quantity']); ?></p>
                    
                    <form action="procesar_editar_oferta.php" method="POST">
                        <input type="hidden" name="supply_id" value="<?php echo $item['id']; ?>">
                        <div class="form-group">
                            <label class="form-label">Precio Ofertado Anteriormente</label>
                            <input type="text" class="form-control" value="$<?php echo number_format($item['price_offered'], 2); ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nuevo Precio Mejorado ($)</label>
                            <input type="number" step="0.01" name="price_offered" class="form-control" value="<?php echo $item['price_offered']; ?>" required autofocus>
                        </div>
                        <button type="submit" class="btn btn-secundario btn-block">Actualizar y Mejorar Precio</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

