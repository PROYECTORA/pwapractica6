<?php
/* ==========================================================
   VIEWS/PROVEEDOR/INDEX.PHP: Portal de Gestión Logística
   ========================================================== */
session_start();

// 1. Verificación de seguridad (Solo Proveedores)
if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role_name']) !== 'supplier') {
    header("Location: ../../index.php?error=acceso_denegado");
    exit();
}

require_once '../../config/conexion.php';

try {
    // 2. Consulta de Necesidades Actuales (Estado: Pending)
    $stmtPending = $pdo->query("SELECT * FROM supplies WHERE status = 'Pending' ORDER BY id DESC");
    $necesidades = $stmtPending->fetchAll();

    // 3. Consulta de Productos Ofertados (Estado diferente a Pending)
    $stmtOffered = $pdo->query("SELECT * FROM supplies WHERE status != 'Pending' ORDER BY id DESC");
    $ofertados = $stmtOffered->fetchAll();

} catch (PDOException $e) {
    die("Error crítico al cargar suministros: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal de Proveedores - Hotel PWA</title>
    
    <!-- Estilos locales modulares -->
    <link rel="stylesheet" href="../../assets/css/base.css">
    <link rel="stylesheet" href="../../assets/css/layout.css">
    <link rel="stylesheet" href="../../assets/css/componentes/botones.css">
    <link rel="stylesheet" href="../../assets/css/componentes/tablas.css">
</head>
<body>

    <div class="wrapper">
        <!-- Barra Lateral -->
        <aside class="sidebar">
            <div class="sidebar-header">Logística Hotel</div>
            <nav class="sidebar-nav">
                <a href="index.php" class="sidebar-link active">Panel de Suministros</a>
                <a href="../../auth/logout.php" class="sidebar-link" style="margin-top: auto; color: #fca5a5;">Cerrar Sesión</a>
            </nav>
        </aside>

        <!-- Contenido Principal -->
        <div class="main-content">
            <!-- Cabecera -->
            <header class="header-top">
                <div style="font-weight: 600; color: var(--color-texto);">
                    Bienvenido, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                </div>
                <span class="badge" style="background-color: #fef3c7; color: #92400e;">Socio Comercial</span>
            </header>

            <div class="content-body">
                
                <!-- SECCIÓN 1: REQUERIMIENTOS DEL HOTEL -->
                <h2 style="color: var(--color-primario); margin-bottom: 20px;">Requerimientos del Hotel</h2>
                <table class="tabla-hotelera" style="margin-bottom: 50px;">
                    <thead>
                        <tr>
                            <th>Artículo / Insumo</th>
                            <th>Cantidad Requerida</th>
                            <th>Estado</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($necesidades) > 0): ?>
                            <?php foreach ($necesidades as $item): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($item['item_name']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                                    <td><span class="badge" style="background-color: #fef9c3; color: #854d0e;">PENDIENTE</span></td>
                                    <td>
                                        <a href="ofertar.php?id=<?php echo $item['id']; ?>" class="btn btn-primario" style="padding: 5px 15px; font-size: 0.85rem;">Enviar Oferta</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" style="text-align: center;">No hay nuevos requerimientos en este momento.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- SECCIÓN 2: MIS PRODUCTOS OFERTADOS -->
                <h2 style="color: var(--color-primario); margin-bottom: 20px;">Mis Productos Ofertados</h2>
                <table class="tabla-hotelera">
                    <thead>
                        <tr style="background-color: #475569;">
                            <th>Artículo / Insumo</th>
                            <th>Cantidad</th>
                            <th>Precio Ofertado</th>
                            <th>Estado de Gestión</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($ofertados) > 0): ?>
                            <?php foreach ($ofertados as $offered): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($offered['item_name']); ?></td>
                                    <td><?php echo htmlspecialchars($offered['quantity']); ?></td>
                                    <td><strong>$<?php echo number_format($offered['price_offered'], 2); ?></strong></td>
                                    <td>
                                        <?php 
                                            $st = strtolower($offered['status']);
                                            $label = ($st == 'in progress') ? 'OFERTA ENVIADA' : 'ENTREGADO';
                                            $color = ($st == 'in progress') ? '#dbeafe; color: #1e40af;' : '#dcfce7; color: #15803d;';
                                        ?>
                                        <span class="badge" style="background-color: <?php echo $color; ?>; padding: 5px 10px;">
                                            <?php echo $label; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div style="display: flex; gap: 5px;">
                                            <?php if ($st == 'in progress'): ?>
                                                <a href="editar_oferta.php?id=<?php echo $offered['id']; ?>" class="btn btn-secundario" style="padding: 5px 8px; font-size: 0.75rem; background-color: var(--color-accent);">
                                                    Mejorar
                                                </a>
                                                <a href="confirmar_eliminar.php?id=<?php echo $offered['id']; ?>" class="btn btn-peligro" style="padding: 5px 8px; font-size: 0.75rem;">
                                                    Retirar
                                                </a>
                                            <?php else: ?>
                                                <span style="color: #94a3b8; font-size: 0.85rem; font-style: italic;">Completado</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5" style="text-align: center;">Aún no has realizado ofertas al hotel.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>

</body>
</html>




