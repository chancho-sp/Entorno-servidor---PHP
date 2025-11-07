<?php
$opc = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
$usuarioCookie = $_COOKIE['usuario'] ?? null;
$message = '';

try {
    $dwes = new PDO('mysql:host=localhost;dbname=registro', 'registro', 'registro', $opc);
} 
catch (PDOException $e) {
    echo 'Falló la conexión: ' . $e->getMessage();
    exit;
}

// Si no hay usuario logueado, redirigir a index
if (!$usuarioCookie) {
    header('Location: index.php?msg=' . urlencode('Debes iniciar sesión primero.'));
    exit;
}

// Obtener datos del usuario
try {
    $stmt = $dwes->prepare("SELECT usuario, imagen FROM tabla_usuarios WHERE usuario = :usuario LIMIT 1");
    $stmt->execute([':usuario' => $usuarioCookie]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        $message = 'Usuario no encontrado.';
    }
} catch (PDOException $e) {
    $message = 'Error al obtener los datos del usuario: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<title>Perfil de Usuario</title>
</head>
<body>

<div class="container">
    <h2>Perfil de Usuario</h2>

    <?php if ($message): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php else: ?>
        <p><strong>Nombre de usuario:</strong> <?= htmlspecialchars($usuario['usuario']) ?></p>
        <?php if (!empty($usuario['imagen'])): ?>
            <?php
                $rutaImagen = str_replace('avatar_small', 'avatar_big', $usuario['imagen']);
                if (!file_exists(__DIR__ . '/' . $rutaImagen)) {
                    $rutaImagen = $usuario['imagen'];
                }
            ?>
            <img src="<?= htmlspecialchars($rutaImagen) ?>" alt="Avatar Grande" class="avatar">
        <?php else: ?>
            <p><em>Este usuario no tiene imagen de perfil.</em></p>
        <?php endif; ?>
    <?php endif; ?>

    <div class="actions">
        <a href="index.php" class="button">Volver al inicio</a>
        <a href="index.php?logout=1" class="button logout">Cerrar sesión</a>
    </div>
</div>

</body>
</html>
