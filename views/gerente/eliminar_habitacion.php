<?php
/* ==========================================================
   VIEWS/GERENTE/ELIMINAR_HABITACION.PHP: Motor de Borrado
   ========================================================== */
session_start();

// 1. Verificación de seguridad (Solo Gerentes)
if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role_name']) !== 'hotel manager') {
    header("Location: ../../index.php?error=acceso_denegado");
    exit();
}

require_once '../../config/conexion.php';

// 2. Validar ID
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_hab = $_GET['id'];

    try {
        // 3. Ejecutar eliminación
        $stmt = $pdo->prepare("DELETE FROM rooms WHERE id = :id");
        $resultado = $stmt->execute(['id' => $id_hab]);

        if ($resultado) {
            header("Location: index.php?msg=habitacion_eliminada");
            exit();
        }

    } catch (PDOException $e) {
        // Error común: La habitación tiene reservas asociadas (FK)
        header("Location: index.php?error=habitacion_con_reservas");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
