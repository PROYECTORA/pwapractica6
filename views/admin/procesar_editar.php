<?php
/* ==========================================================
   VIEWS/ADMIN/PROCESAR_EDITAR.PHP: Motor de Actualización
   ========================================================== */
session_start();

if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role_name']) !== 'administrator') {
    header("Location: ../../index.php?error=acceso_denegado");
    exit();
}

require_once '../../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id       = $_POST['id'];
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role_id  = $_POST['role_id'];

    if (!empty($id) && !empty($name) && !empty($email) && !empty($role_id)) {
        try {
            $stmt = $pdo->prepare("
                UPDATE users 
                SET name = :name, email = :email, password = :password, role_id = :role_id 
                WHERE id = :id
            ");
            
            $stmt->execute([
                'name'     => $name,
                'email'    => $email,
                'password' => $password,
                'role_id'  => $role_id,
                'id'       => $id
            ]);

            header("Location: index.php?msg=usuario_actualizado");
            exit();

        } catch (PDOException $e) {
            die("Error al actualizar: " . $e->getMessage());
        }
    }
}
header("Location: index.php");
exit();
