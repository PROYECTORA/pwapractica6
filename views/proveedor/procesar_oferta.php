<?php
/* ==========================================================
   VIEWS/PROVEEDOR/PROCESAR_OFERTA.PHP: Motor de Sincronización
   ========================================================== */
session_start();
if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role_name']) !== 'supplier') {
    header("Location: ../../index.php"); exit();
}
require_once '../../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificación exhaustiva de llegada de datos
    $supply_id     = $_POST['supply_id'] ?? null;
    $price_offered = $_POST['price_offered'] ?? null;

    if ($supply_id && $price_offered) {
        try {
            // Actualizamos precio y estado a 'In Progress' para que aparezca en la tabla inferior
            $stmt = $pdo->prepare("
                UPDATE supplies 
                SET status = 'In Progress', 
                    price_offered = :price,
                    cancellation_reason = NULL 
                WHERE id = :id
            ");
            
            $stmt->execute([
                'price' => $price_offered,
                'id'    => $supply_id
            ]);

            header("Location: index.php?status=success");
            exit();

        } catch (PDOException $e) {
            die("Error técnico en la base de datos: " . $e->getMessage());
        }
    } else {
        // Si faltan datos, enviamos el error que viste en tu captura
        header("Location: index.php?error=datos_incompletos");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}

