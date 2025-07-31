<?php
session_start();
require_once 'config.php';
require_once 'cors.php';

// Verifica que el usuario haya iniciado sesión
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

$mensajeArchivo = '';

// Subida del archivo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['archivo'])) {
    $archivo = $_FILES['archivo'];
    $ext = pathinfo($archivo['name'], PATHINFO_EXTENSION);
    $permitidas = ['jpg', 'png', 'pdf'];

    if (in_array(strtolower($ext), $permitidas) && $archivo['size'] < 5 * 1024 * 1024) {
        $nombreOriginal = basename($archivo['name']);
        $nombreSeguro = uniqid() . '.' . $ext;
        $directorio = 'uploads';

        if (!is_dir($directorio)) {
            mkdir($directorio, 0755, true);
        }

        $rutaCompleta = $directorio . "/" . $nombreSeguro;

        if (move_uploaded_file($archivo['tmp_name'], $rutaCompleta)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO archivos (usuario_id, nombre_archivo, ruta_archivo, tipo_archivo, tamano, subido_en)
                                       VALUES (?, ?, ?, ?, ?, NOW())");
                $stmt->execute([
                    $_SESSION['usuario_id'],
                    $nombreOriginal,
                    $rutaCompleta,
                    $archivo['type'],
                    $archivo['size']
                ]);
                $mensajeArchivo = "<p style='color:green;'>Archivo subido correctamente.</p>";
            } catch (PDOException $e) {
                $mensajeArchivo = "<p style='color:red;'>Error en BD: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
        } else {
            $mensajeArchivo = "<p style='color:red;'>Error al mover el archivo.</p>";
        }
    } else {
        $mensajeArchivo = "<p style='color:red;'>Archivo no permitido o demasiado grande (máx. 5 MB).</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Subir Archivo</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Bienvenido, <?= htmlspecialchars($_SESSION['usuario']) ?></h2>

    <form method="post" enctype="multipart/form-data">
        <label>Selecciona un archivo (jpg, png, pdf, máx. 5MB):</label><br><br>
        <input type="file" name="archivo" required><br><br>
        <button type="submit">Subir</button>
    </form>

    <?= $mensajeArchivo ?>

    <br><br>
    <h3>Tus archivos subidos</h3>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Tipo</th>
                <th>Tamaño</th>
                <th>Subido en</th>
                <th>Descargar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            try {
                $stmt = $pdo->prepare("SELECT * FROM archivos WHERE usuario_id = ? ORDER BY subido_en DESC");
                $stmt->execute([$_SESSION['usuario_id']]);
                $archivos = $stmt->fetchAll();

                if ($archivos) {
                    foreach ($archivos as $archivo) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($archivo['nombre_archivo']) . "</td>";
                        echo "<td>" . htmlspecialchars($archivo['tipo_archivo']) . "</td>";
                        echo "<td>" . number_format($archivo['tamano'] / 1024, 2) . " KB</td>";
                        echo "<td>" . htmlspecialchars($archivo['subido_en']) . "</td>";
                        echo "<td><a href='" . htmlspecialchars($archivo['ruta_archivo']) . "' download>Descargar</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No has subido archivos aún.</td></tr>";
                }
            } catch (PDOException $e) {
                echo "<tr><td colspan='5'>Error al cargar archivos: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <br><br>
    <a href="logout.php">Cerrar sesión</a>
</body>
</html>
