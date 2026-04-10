<?php
/* ==========================================================
   VIEWS/PROVEEDOR/PROCESAR_EDITAR_OFERTA.PHP: Mejora de Precio
   ========================================================== */
session_start();
if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role_name']) !== 'supplier') {
    header("Location: ../../index.php"); exit();
}
require_once '../../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $supply_id     = $_POST['supply_id'];
    $price_offered = $_POST['price_offered'];

    if (!empty($supply_id) && !empty($price_offered)) {
        try {
            $stmt = $pdo->prepare("UPDATE supplies SET price_offered = :p WHERE id = :id");
            $stmt->execute(['p' => $price_offered, 'id' => $supply_id]);
            header("Location: index.php?status=precio_mejorado");
            exit();
        } catch (PDOException $e) { die("Error: " . $e->getMessage()); }
    }
}
header("Location: index.php");
exit();

