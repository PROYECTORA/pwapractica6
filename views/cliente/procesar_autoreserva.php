<?php
/* ==========================================================
   VIEWS/CLIENTE/PROCESAR_AUTORESERVA.PHP: Motor de Reservas
   ========================================================== */
session_start();

// 1. Verificación de seguridad
if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role_name']) !== 'customer') {
    header("Location: ../../index.php?error=acceso_denegado");
    exit();
}

require_once '../../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Tomamos el ID del cliente directamente de la sesión por seguridad
    $user_id  = $_SESSION['user_id'];
    $room_id  = $_POST['room_id'];
    $check_in = $_POST['check_in'];
    $check_out= $_POST['check_out'];

    if (!empty($room_id) && !empty($check_in) && !empty($check_out)) {
        
        try {
            $pdo->beginTransaction();

            // 1. Insertar la reserva (Estado 'Confirmed' por defecto para autoreservas)
            $stmtBooking = $pdo->prepare("
                INSERT INTO bookings (user_id, room_id, check_in, check_out, status) 
                VALUES (:u, :r, :cin, :cout, 'Confirmed')
            ");
            $stmtBooking->execute([
                'u'    => $user_id,
                'r'    => $room_id,
                'cin'  => $check_in,
                'cout' => $check_out
            ]);

            // 2. Actualizar el estado de la habitación a 'Occupied'
            $stmtRoom = $pdo->prepare("UPDATE rooms SET status = 'Occupied' WHERE id = :rid");
            $stmtRoom->execute(['rid' => $room_id]);

            $pdo->commit();

            // Redirigir al panel del cliente con mensaje de éxito
            header("Location: index.php?status=reserva_ok");
            exit();

        } catch (PDOException $e) {
            $pdo->rollBack();
            die("Error al procesar su reserva: " . $e->getMessage());
        }

    } else {
        header("Location: index.php?error=datos_incompletos");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
