<?php
/* ==========================================
   CONEXION.PHP: Configuración de Base de Datos
   ========================================== */

// Parámetros de conexión para entorno Local (XAMPP)
$host = '127.0.0.1'; // Es más rápido que usar 'localhost' en algunos entornos
$db   = 'pwapractica6';
$user = 'root';      // Usuario por defecto de XAMPP
$pass = '';          // XAMPP por defecto no tiene contraseña para root
$charset = 'utf8mb4'; // Soporte completo para caracteres especiales

// Construcción del Data Source Name (DSN)
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Opciones de configuración para el driver de PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lanza excepciones en caso de error
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Devuelve los datos como arreglos asociativos
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Desactiva la emulación para mayor seguridad nativa
];

try {
    // Inicialización de la instancia PDO
    $pdo = new PDO($dsn, $user, $pass, $options);
    
    // NOTA: Para verificar visualmente que todo funciona en este paso, 
    // puedes descomentar la siguiente línea. Si sale en pantalla al cargar, la conexión es un éxito.
    // echo "Conexión exitosa a la base de datos pwapractica6.";
    
} catch (\PDOException $e) {
    // Si la conexión falla, detiene el script y muestra el error
    die("Error crítico de conexión a la base de datos: " . $e->getMessage());
}
?>
