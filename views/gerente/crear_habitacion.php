<?php
/* ==========================================================
   VIEWS/GERENTE/CREAR_HABITACION.PHP: Registro de Inventario
   ========================================================== */
session_start();

// 1. Verificación de seguridad (Solo Gerentes)
if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role_name']) !== 'hotel manager') {
    header("Location: ../../index.php?error=acceso_denegado");
    exit();
}

require_once '../../config/conexion.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Añadir Habitación - Hotel PWA</title>
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
                <div style="font-weight: 600;">Añadir Nueva Habitación</div>
            </header>

            <div class="content-body">
                <div style="margin-bottom: 20px;">
                    <a href="index.php" style="color: var(--color-secundario); font-weight: 600;">&larr; Volver al inventario</a>
                </div>

                <div style="max-width: 600px; background: var(--color-blanco); padding: 30px; border-radius: 8px; box-shadow: var(--sombra-suave); border: 1px solid var(--color-borde);">
                    
                    <form action="procesar_crear_hab.php" method="POST">
                        
                        <div class="form-group">
                            <label for="room_number" class="form-label">Número de Habitación</label>
                            <input type="text" id="room_number" name="room_number" class="form-control" placeholder="Ej. 101, 202-A" required autofocus>
                        </div>

                        <div class="form-group">
                            <label for="type" class="form-label">Tipo de Habitación</label>
                            <select id="type" name="type" class="form-control" required>
                                <option value="" disabled selected>Seleccione tipo...</option>
                                <option value="Sencilla">Sencilla</option>
                                <option value="Doble">Doble</option>
                                <option value="Suite">Suite</option>
                                <option value="Presidencial">Presidencial</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="price" class="form-label">Precio por Noche ($)</label>
                            <input type="number" step="0.01" id="price" name="price" class="form-control" placeholder="0.00" required>
                        </div>

                        <div class="form-group">
                            <label for="status" class="form-label">Estado Inicial</label>
                            <select id="status" name="status" class="form-control" required>
                                <option value="Available">Disponible</option>
                                <option value="Occupied">Ocupada</option>
                                <option value="Maintenance">Mantenimiento</option>
                            </select>
                        </div>

                        <div style="display: flex; gap: 10px; margin-top: 25px;">
                            <button type="submit" class="btn btn-primario" style="flex: 1;">Registrar Habitación</button>
                            <a href="index.php" class="btn btn-secundario" style="flex: 1; background-color: #64748b;">Cancelar</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
