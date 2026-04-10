<?php
/* ==========================================================
   VIEWS/PROVEEDOR/OFERTAR.PHP: Formulario de Propuesta
   ========================================================== */
session_start();
if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role_name']) !== 'supplier') {
    header("Location: ../../index.php"); exit();
}
require_once '../../config/conexion.php';

$supply_id = $_GET['id'] ?? '';
try {
    $stmt = $pdo->prepare("SELECT * FROM supplies WHERE id = :id AND status = 'Pending' LIMIT 1");
    $stmt->execute(['id' => $supply_id]);
    $item = $stmt->fetch();
    if (!$item) { header("Location: index.php"); exit(); }
} catch (PDOException $e) { die("Error: " . $e->getMessage()); }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Enviar Oferta - Hotel PWA</title>
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
            <header class="header-top"><div style="font-weight: 600;">Propuesta de Suministro</div></header>
            <div class="content-body">
                <div style="max-width: 500px; background: white; padding: 30px; border-radius: 8px; box-shadow: var(--sombra-suave); border: 1px solid var(--color-borde);">
                    <h3 style="color: var(--color-primario); margin-bottom: 10px;"><?php echo htmlspecialchars($item['item_name']); ?></h3>
                    <p style="margin-bottom: 20px; color: #64748b;">Cantidad requerida: <strong><?php echo htmlspecialchars($item['quantity']); ?></strong></p>

                    <form action="procesar_oferta.php" method="POST">
                        <input type="hidden" name="supply_id" value="<?php echo $item['id']; ?>">
                        <div class="form-group">
                            <label class="form-label">Precio de Oferta ($)</label>
                            <input type="number" step="0.01" name="price_offered" class="form-control" placeholder="0.00" required autofocus>
                        </div>
                        <button type="submit" class="btn btn-primario btn-block">Confirmar y Enviar Oferta</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>


