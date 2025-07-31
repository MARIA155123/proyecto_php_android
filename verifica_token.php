<?php
session_start();
require_once 'config.php';

$token = $_SESSION['token'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE token = ?");
$stmt->execute([$token]);
$user = $stmt->fetch();

if (!$user) {
    echo "Token inválido. Inicia sesión.";
    exit;
}

if (strtotime($user['token_expiracion']) < time()) {
    echo "Token expirado. Inicia sesión.";
    exit;
}

echo "Token válido. Usuario: " . $user['usuario'];
?>
