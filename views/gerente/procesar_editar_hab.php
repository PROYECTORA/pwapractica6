<?php
/* ==========================================================
   VIEWS/GERENTE/PROCESAR_EDITAR_HAB.PHP: Motor de Update
   ========================================================== */
session_start();

if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role_name']) !== 'hotel manager') {
    header("Location: ../../index.php?error=acceso_denegado");
    exit();
}

require_once '../../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id          = $_POST['id'];
    $room_number = trim($_POST['room_number']);
    $type        = $_POST['type'];
    $price       = $_POST['price'];
    $status      = $_POST['status'];

    if (!empty($id) && !empty($room_number) && !empty($type) && !empty($price)) {
        try {
            $stmt = $pdo->prepare("
                UPDATE rooms 
                SET room_number = :rn, type = :t, price = :p, status = :s 
                WHERE id = :id
            ");
            
            $stmt->execute([
                'rn' => $room_number,
                't'  => $type,
                'p'  => $price,
                's'  => $status,
                'id' => $id
            ]);

            header("Location: index.php?msg=habitacion_actualizada");
            exit();

        } catch (PDOException $e) {
            die("Error al actualizar: " . $e->getMessage());
        }
    }
}
header("Location: index.php");
exit();
