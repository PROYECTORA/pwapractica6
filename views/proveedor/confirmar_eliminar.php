<?php
session_start();
if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role_name']) !== 'supplier') {
    header("Location: ../../index.php"); exit();
}
$supply_id = $_GET['id'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Retirar Oferta - Hotel PWA</title>
    <link rel="stylesheet" href="../../assets/css/base.css">
    <link rel="stylesheet" href="../../assets/css/layout.css">
    <link rel="stylesheet" href="../../assets/css/componentes/formularios.css">
    <link rel="stylesheet" href="../../assets/css/componentes/botones.css">
</head>
<body>
    <div class="wrapper">
        <div class="main-content" style="margin-left:0; width:100%; display:flex; justify-content:center; align-items:center;">
            <div style="max-width: 500px; background: white; padding: 40px; border-radius: 8px; box-shadow: var(--sombra-suave); border: 1px solid var(--color-borde);">
                <h2 style="color: #ef4444; margin-bottom: 15px;">¿Retirar esta oferta?</h2>
                <p style="margin-bottom: 20px; color: #64748b;">El insumo volverá a estar disponible para otros proveedores.</p>
                
                <form action="procesar_eliminar_oferta.php" method="POST">
                    <input type="hidden" name="supply_id" value="<?php echo htmlspecialchars($supply_id); ?>">
                    
                    <div class="form-group">
                        <label class="form-label">Motivo de la cancelación / Observación</label>
                        <textarea name="reason" class="form-control" style="height: 100px;" placeholder="Ej: Falta de stock, ajuste de costos..." required autofocus></textarea>
                    </div>

                    <div style="display: flex; gap: 10px;">
                        <button type="submit" class="btn btn-peligro" style="flex: 1;">Confirmar Retiro</button>
                        <a href="index.php" class="btn btn-secundario" style="flex: 1;">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
