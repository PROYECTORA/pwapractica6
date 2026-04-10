<?php
/* ==========================================
   PROCESAR_LOGIN.PHP: Validación de Credenciales
   ========================================== */

// 1. Iniciar sesión para persistir datos
session_start();

// 2. Importar la conexión a la base de datos
require_once '../config/conexion.php';

// 3. Verificar que los datos lleguen por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        try {
            // 4. Consulta preparada para buscar al usuario y su rol
            $stmt = $pdo->prepare("
                SELECT u.id, u.name, u.password, u.role_id, r.name as role_name 
                FROM users u 
                JOIN roles r ON u.role_id = r.id 
                WHERE u.email = :email 
                LIMIT 1
            ");
            $stmt->execute(['email' => $email]);
            $usuario = $stmt->fetch();

            // 5. Verificación de coincidencia
            if ($usuario && $password === $usuario['password']) {
                // Login exitoso: Guardamos datos en la sesión
                $_SESSION['user_id'] = $usuario['id'];
                $_SESSION['user_name'] = $usuario['name'];
                $_SESSION['role_id'] = $usuario['role_id'];
                $_SESSION['role_name'] = $usuario['role_name'];

                // Redirección temporal al index para verificar éxito
                header("Location: ../index.php?status=success");
                exit();
            } else {
                // Credenciales incorrectas
                header("Location: login.php?error=1");
                exit();
            }

        } catch (PDOException $e) {
            die("Error en la autenticación: " . $e->getMessage());
        }
    } else {
        header("Location: login.php?error=empty");
        exit();
    }
} else {
    // Si intentan entrar directo al archivo, redirigir al login
    header("Location: login.php");
    exit();
}
