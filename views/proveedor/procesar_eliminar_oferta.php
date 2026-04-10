<?php
session_start();
require_once '../../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['supply_id'];
    $reason = trim($_POST['reason']);

    if (!empty($id) && !empty($reason)) {
        try {
            $stmt = $pdo->prepare("
                UPDATE supplies 
                SET status = 'Pending', 
                    price_offered = 0.00, 
                    cancellation_reason = :reason 
                WHERE id = :id
            ");
            $stmt->execute(['reason' => $reason, 'id' => $id]);
            header("Location: index.php?status=oferta_retirada");
            exit();
        } catch (PDOException $e) { die("Error: " . $e->getMessage()); }
    }
}
header("Location: index.php");
exit();
