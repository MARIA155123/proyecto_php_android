<?php
// =============================
//  Constantes de seguridad JWT
// =============================
if (!defined('JWT_SECRET')) {
    define('JWT_SECRET', 'mi_clave_secreta_super_segura'); // Cámbiala por una clave segura real
}

if (!defined('JWT_ALG')) {
    define('JWT_ALG', 'HS256');
}

// =============================
// Configuración segura de sesiones
// =============================
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_strict_mode', 1);
    session_start();
}

// =============================
//  Configuración de la base de datos
// =============================
$host = 'localhost';
$db   = 'secure_db'; // Cambia esto por el nombre real de tu base de datos
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false, // Recomendado para seguridad
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // Puedes loguear el error en producción
    die("Error de conexión a la base de datos: " . $e->getMessage());
}
?>
