<?php
require_once 'config.php';
require_once 'cors.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = htmlspecialchars(trim($_POST['usuario'] ?? ''));
    $password = $_POST['password'] ?? '';
    $correo = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);

    // Validaciones
    if (!$correo) {
        die('Correo electrónico no válido.');
    }

    if (!preg_match('/^[a-zA-Z0-9_]{4,20}$/', $usuario)) {
        die('Nombre de usuario no válido.');
    }

    if (strlen($password) < 6) {
        die('La contraseña debe tener al menos 6 caracteres.');
    }

    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    try {
        // Insertar en usuarios
        $stmt = $pdo->prepare("INSERT INTO usuarios (usuario, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$usuario, $correo, $passwordHash]);
        $usuario_id = $pdo->lastInsertId();

        // Crear token y expiración
        $token = bin2hex(random_bytes(16));
        $expira = date('Y-m-d H:i:s', strtotime('+5 minutes'));

        // Insertar en tabla tokens
        $stmtToken = $pdo->prepare("INSERT INTO tokens (usuario_id, token, expiracion) VALUES (?, ?, ?)");
        $stmtToken->execute([$usuario_id, $token, $expira]);

        echo "Registro exitoso. Tu token: $token";

    } catch (PDOException $e) {
        echo "Error al registrar: " . $e->getMessage();
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <form method="POST" action="">
        <h2>Registro</h2>

        <input type="text" name="usuario" required placeholder="Usuario">
        <input type="password" name="password" required placeholder="Contraseña">
        <input type="email" name="email" required placeholder="Correo electrónico">
        
        <button type="submit">Registrar</button>
        <a href="login.php" class="btn-regresar">← Regresar</a>
    </form>
</body>
</html>
