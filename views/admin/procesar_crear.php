<?php
/* ==========================================================
   VIEWS/ADMIN/PROCESAR_CREAR.PHP: Lógica de Guardado
   ========================================================== */

session_start();

// 1. Verificación de seguridad (Solo Administradores)
if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role_name']) !== 'administrator') {
    header("Location: ../../index.php?error=acceso_denegado");
    exit();
}

require_once '../../config/conexion.php';

// 2. Verificar que la petición sea POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Captura y limpieza básica de datos
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role_id  = $_POST['role_id'];

    // 3. Validación de campos obligatorios
    if (!empty($name) && !empty($email) && !empty($password) && !empty($role_id)) {
        
        try {
            // 4. Verificar si el correo ya existe para evitar duplicados
            $checkEmail = $pdo->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
            $checkEmail->execute(['email' => $email]);
            
            if ($checkEmail->fetch()) {
                // El correo ya está registrado
                header("Location: crear.php?error=email_existe");
                exit();
            }

            // 5. Inserción del nuevo usuario
            // Nota: Se guarda en texto plano por consistencia con tu script inicial.
            $stmt = $pdo->prepare("
                INSERT INTO users (name, email, password, role_id) 
                VALUES (:name, :email, :password, :role_id)
            ");

            $resultado = $stmt->execute([
                'name'     => $name,
                'email'    => $email,
                'password' => $password,
                'role_id'  => $role_id
            ]);

            if ($resultado) {
                // Éxito: Redirigir a la lista con mensaje de éxito
                header("Location: index.php?msg=usuario_creado");
                exit();
            }

        } catch (PDOException $e) {
            die("Error al insertar el usuario: " . $e->getMessage());
        }

    } else {
        // Campos vacíos
        header("Location: crear.php?error=campos_vacios");
        exit();
    }
} else {
    // Intento de acceso directo sin POST
    header("Location: crear.php");
    exit();
}
