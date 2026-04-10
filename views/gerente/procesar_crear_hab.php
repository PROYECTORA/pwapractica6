<?php
/* ==========================================================
   VIEWS/GERENTE/PROCESAR_CREAR_HAB.PHP: Motor de Inventario
   ========================================================== */
session_start();

// 1. Verificación de seguridad (Solo Gerentes)
if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role_name']) !== 'hotel manager') {
    header("Location: ../../index.php?error=acceso_denegado");
    exit();
}

require_once '../../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Captura de datos
    $room_number = trim($_POST['room_number']);
    $type        = $_POST['type'];
    $price       = $_POST['price'];
    $status      = $_POST['status'];

    // 2. Validación de campos
    if (!empty($room_number) && !empty($type) && !empty($price) && !empty($status)) {
        
        try {
            // 3. Verificar si el número de habitación ya existe
            $checkRoom = $pdo->prepare("SELECT id FROM rooms WHERE room_number = :rn LIMIT 1");
            $checkRoom->execute(['rn' => $room_number]);
            
            if ($checkRoom->fetch()) {
                header("Location: crear_habitacion.php?error=numero_duplicado");
                exit();
            }

            // 4. Inserción en la base de datos
            $stmt = $pdo->prepare("
                INSERT INTO rooms (room_number, type, price, status) 
                VALUES (:room_number, :type, :price, :status)
            ");

            $resultado = $stmt->execute([
                'room_number' => $room_number,
                'type'        => $type,
                'price'       => $price,
                'status'      => $status
            ]);

            if ($resultado) {
                header("Location: index.php?msg=habitacion_creada");
                exit();
            }

        } catch (PDOException $e) {
            die("Error al registrar habitación: " . $e->getMessage());
        }

    } else {
        header("Location: crear_habitacion.php?error=campos_vacios");
        exit();
    }
} else {
    header("Location: crear_habitacion.php");
    exit();
}
