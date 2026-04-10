<?php
/* ==========================================================
   VIEWS/RECEPCIONISTA/PROCESAR_RESERVA.PHP: Lógica de Estancia
   ========================================================== */
session_start();

if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role_name']) !== 'receptionist') {
    header("Location: ../../index.php?error=acceso_denegado");
    exit();
}

require_once '../../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id  = $_POST['user_id'];
    $room_id  = $_POST['room_id'];
    $check_in = $_POST['check_in'];
    $check_out= $_POST['check_out'];

    if (!empty($user_id) && !empty($room_id) && !empty($check_in) && !empty($check_out)) {
        
        try {
            // INICIO DE TRANSACCIÓN: Asegura que si falla un paso, no se guarde nada.
            $pdo->beginTransaction();

            // 1. Insertar la reserva en la tabla bookings
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

            // 3. Confirmar todos los cambios
            $pdo->commit();

            header("Location: index.php?msg=reserva_exitosa");
            exit();

        } catch (PDOException $e) {
            // Si algo falla, deshacer los cambios
            $pdo->rollBack();
            die("Error crítico en la reserva: " . $e->getMessage());
        }

    } else {
        header("Location: nueva_reserva.php?error=campos_vacios");
        exit();
    }
} else {
    header("Location: nueva_reserva.php");
    exit();
}
