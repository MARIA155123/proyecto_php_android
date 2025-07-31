<?php
session_start();
require_once 'config.php';
require_once 'cors.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($email) && !empty($password)) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user) {
                if ($user['bloqueado_hasta'] && strtotime($user['bloqueado_hasta']) > time()) {
                    $error = "Usuario bloqueado. Intenta después de: " . $user['bloqueado_hasta'];
                } else {
                    if (password_verify($password, $user['password'])) {
                        // Resetear intentos
                        $pdo->prepare("UPDATE usuarios SET intentos_fallidos = 0, bloqueado_hasta = NULL WHERE id = ?")
                            ->execute([$user['id']]);

                        // Eliminar tokens antiguos
                        $pdo->prepare("DELETE FROM tokens WHERE usuario_id = ?")->execute([$user['id']]);

                        // Crear token nuevo
                        $token = bin2hex(random_bytes(16));
                        $expira = date('Y-m-d H:i:s', strtotime('+5 minutes'));

                        $stmtToken = $pdo->prepare("INSERT INTO tokens (usuario_id, token, expiracion) VALUES (?, ?, ?)");
                        $stmtToken->execute([$user['id'], $token, $expira]);

                        // Guardar en sesión
                        $_SESSION['usuario'] = $user['usuario'];
                        $_SESSION['usuario_id'] = $user['id'];
                        $_SESSION['token'] = $token;

                        header("Location: dashboard.php");
                        exit;
                    } else {
                        $intentos = $user['intentos_fallidos'] + 1;
                        $bloqueo = $intentos >= 3 ? date('Y-m-d H:i:s', strtotime('+3 minutes')) : null;

                        $pdo->prepare("UPDATE usuarios SET intentos_fallidos = ?, bloqueado_hasta = ? WHERE id = ?")
                            ->execute([$intentos, $bloqueo, $user['id']]);

                        $error = $intentos >= 3
                            ? "Usuario bloqueado por 3 minutos."
                            : "Contraseña incorrecta.";
                    }
                }
            } else {
                $error = "Usuario no encontrado.";
            }
        } catch (PDOException $e) {
            $error = "Error en la base de datos: " . $e->getMessage();
        }
    } else {
        $error = "Por favor, completa todos los campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <form method="post">
        <h2>Iniciar Sesión</h2>

        <?php if (!empty($error)): ?>
            <p class="error" style="color: red; text-align: center;">
                <?= htmlspecialchars($error) ?>
            </p>
        <?php endif; ?>

        <input type="email" name="email" placeholder="Correo electrónico" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit">Ingresar</button>
        <a href="register.php" class="btn-regresar">← Registrarse</a>
    </form>
</body>
</html>
