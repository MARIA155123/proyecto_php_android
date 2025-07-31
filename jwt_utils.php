<?php
require 'vendor/autoload.php'; // Asegúrate de haber ejecutado: composer require firebase/php-jwt

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Definir la clave secreta solo si no ha sido definida antes
if (!defined('JWT_SECRET')) {
    define('JWT_SECRET', 'clave_secreta_segura'); // Reemplaza por una clave real y segura
}

// Función para generar un JWT
function generarJWT($user_id, $email) {
    $payload = [
        'iss' => 'tuapp',             // Emisor
        'iat' => time(),              // Tiempo en que se emitió
        'exp' => time() + 3600,       // Expira en 1 hora
        'user_id' => $user_id,
        'email' => $email
    ];

    return JWT::encode($payload, JWT_SECRET, 'HS256');
}

// Función para verificar un JWT
function verifyJWT($jwt) {
    try {
        $decoded = JWT::decode($jwt, new Key(JWT_SECRET, 'HS256'));
        return (array) $decoded;
    } catch (Exception $e) {
        return false;
    }
}
