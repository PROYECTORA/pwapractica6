<?php
/* ==========================================================
   VIEWS/ADMIN/ELIMINAR.PHP: Motor de Borrado de Usuarios
   ========================================================== */
session_start();

// 1. Verificación de seguridad estricta
if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role_name']) !== 'administrator') {
    header("Location: ../../index.php?error=acceso_denegado");
    exit();
}

require_once '../../config/conexion.php';

// 2. Validar que el ID llegue por la URL y no esté vacío
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_usuario = $_GET['id'];

    // 3. Evitar que el administrador se elimine a sí mismo por accidente
    if ($id_usuario == $_SESSION['user_id']) {
        header("Location: index.php?error=auto_eliminacion");
        exit();
    }

    try {
        // 4. Ejecución de la sentencia preparada para eliminar
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
        $resultado = $stmt->execute(['id' => $id_usuario]);

        if ($resultado) {
            // Éxito: Redirigir con mensaje confirmando el borrado
            header("Location: index.php?msg=usuario_eliminado");
            exit();
        }

    } catch (PDOException $e) {
        // Manejo de errores (ej. si el usuario tiene reservas asociadas por integridad referencial)
        header("Location: index.php?error=integridad_referencial");
        exit();
    }
} else {
    // Si no hay ID, volver a la lista
    header("Location: index.php");
    exit();
}
