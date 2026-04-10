<?php
/* ==========================================================
   VIEWS/ADMIN/EDITAR.PHP: Formulario de Modificación
   ========================================================== */
session_start();

if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role_name']) !== 'administrator') {
    header("Location: ../../index.php?error=acceso_denegado");
    exit();
}

require_once '../../config/conexion.php';

// 1. Validar que el ID del usuario llegue por la URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id_usuario = $_GET['id'];

try {
    // 2. Obtener datos actuales del usuario
    $stmtUser = $pdo->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
    $stmtUser->execute(['id' => $id_usuario]);
    $user = $stmtUser->fetch();

    if (!$user) {
        header("Location: index.php?error=usuario_no_encontrado");
        exit();
    }

    // 3. Obtener roles para el selector
    $stmtRoles = $pdo->query("SELECT id, name FROM roles ORDER BY id ASC");
    $roles = $stmtRoles->fetchAll();

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario - Hotel PWA</title>
    <link rel="stylesheet" href="../../assets/css/base.css">
    <link rel="stylesheet" href="../../assets/css/layout.css">
    <link rel="stylesheet" href="../../assets/css/componentes/formularios.css">
    <link rel="stylesheet" href="../../assets/css/componentes/botones.css">
</head>
<body>
    <div class="wrapper">
        <aside class="sidebar">
            <div class="sidebar-header">Hotel PWA - Admin</div>
            <nav class="sidebar-nav">
                <a href="index.php" class="sidebar-link active">Gestión de Usuarios</a>
                <a href="../../auth/logout.php" class="sidebar-link" style="margin-top: auto; color: #fca5a5;">Cerrar Sesión</a>
            </nav>
        </aside>

        <div class="main-content">
            <header class="header-top">
                <div style="font-weight: 600;">Modificar Usuario ID: <?php echo $id_usuario; ?></div>
            </header>

            <div class="content-body">
                <div style="margin-bottom: 20px;">
                    <a href="index.php" style="color: var(--color-secundario); font-weight: 600;">&larr; Volver a la lista</a>
                </div>

                <div style="max-width: 600px; background: var(--color-blanco); padding: 30px; border-radius: 8px; box-shadow: var(--sombra-suave); border: 1px solid var(--color-borde);">
                    <form action="procesar_editar.php" method="POST">
                        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">

                        <div class="form-group">
                            <label class="form-label">Nombre Completo</label>
                            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Correo Electrónico</label>
                            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Contraseña (Dejar igual si no se cambia)</label>
                            <input type="text" name="password" class="form-control" value="<?php echo htmlspecialchars($user['password']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Rol del Sistema</label>
                            <select name="role_id" class="form-control" required>
                                <?php foreach ($roles as $rol): ?>
                                    <option value="<?php echo $rol['id']; ?>" <?php echo ($rol['id'] == $user['role_id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($rol['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div style="display: flex; gap: 10px; margin-top: 25px;">
                            <button type="submit" class="btn btn-primario" style="flex: 1;">Actualizar Datos</button>
                            <a href="index.php" class="btn btn-secundario" style="flex: 1; background-color: #64748b;">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
