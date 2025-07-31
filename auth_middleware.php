<?php
session_start();
require_once 'jwt_utils.php';

// Simulación de base de datos
$usuarios = [
    'admin' => password_hash('1234', PASSWORD_DEFAULT)
];

$usuario = $_POST['usuario'] ?? '';
$password = $_POST['password'] ?? '';

if (isset($usuarios[$usuario]) && password_verify($password, $usuarios[$usuario])) {
    $user_id = 1; // En sistema real obtienes el ID desde la base de datos

    $jwt = generarJWT($user_id, $email);

    // Guardar en sesión y cookie
    $_SESSION['jwt'] = $jwt;
    setcookie('jwt_token', $jwt, time() + 3600, '/', '', false, true);

    header('Location: dashboard.php');
    exit;
} else {
    echo "Usuario o contraseña incorrectos.";
}
