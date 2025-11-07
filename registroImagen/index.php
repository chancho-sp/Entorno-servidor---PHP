<?php
$opc = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
$message = '';
$showLoginForm = true;
$usuarioCookie = $_COOKIE['usuario'] ?? null;

try {
    //Conectamos a la BD
    $dwes = new PDO('mysql:host=localhost;dbname=registro', 'registro', 'registro', $opc);
} 
catch (PDOException $e) {
    echo 'Falló la conexión: ' . $e->getMessage();
    exit;
}

//Logout
if (isset($_GET['logout'])) {
    setcookie('usuario', '', time() - 3600); // eliminar cookie
    unset($_COOKIE['usuario']);
    $message = 'Has cerrado sesión correctamente.';
    $showLoginForm = true;
}

//Si hay cookie activa, no mostrar formulario
elseif ($usuarioCookie && !isset($_POST['usuario'])) {
    $showLoginForm = false;
}

//Procesar login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($usuario === '' || $password === '') {
        $message = 'Por favor completa usuario y contraseña.';
    } else {
        try {
            // Buscar usuario
            $stmt = $dwes->prepare("SELECT password FROM tabla_usuarios WHERE usuario = :usuario LIMIT 1");
            $stmt->execute([':usuario' => $usuario]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                if (password_verify($password, $row['password'])) {
                    // Login correcto
                    setcookie('usuario', $usuario, time() + 3600);
                    $usuarioCookie = $usuario;
                    $showLoginForm = false;
                    $message = "¡Login correcto! Bienvenido, $usuario.";
                } else {
                    $message = "Contraseña incorrecta.";
                }
            } else {
                $message = "Usuario no encontrado.";
            }
        } catch (PDOException $e) {
            $message = "Error de conexión o consulta: " . $e->getMessage();
        }
    }
}

// Si hay usuario logueado, obtener su imagen pequeña
$imagenUsuario = null;
if ($usuarioCookie) {
    try {
        $stmt = $dwes->prepare("SELECT imagen FROM tabla_usuarios WHERE usuario = :usuario LIMIT 1");
        $stmt->execute([':usuario' => $usuarioCookie]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row && !empty($row['imagen'])) {
            
            $imagenUsuario = str_replace('avatar_big', 'avatar_small', $row['imagen']);
            if (!file_exists(__DIR__ . '/' . $imagenUsuario)) {
                // Si no existe la pequeña, usamos la grande
                $imagenUsuario = $row['imagen'];
            }
        }
    } catch (PDOException $e) {

    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<title>Pantalla de Login</title>
</head>
<body>

<header class="header">
    <h2>Pantalla de Login</h2>
    <?php if (!$showLoginForm && $usuarioCookie): ?> 
        <div>
            <span><?= htmlspecialchars($usuarioCookie) ?></span>
            <?php if ($imagenUsuario): ?>
                <img src="<?= htmlspecialchars($imagenUsuario) ?>">
            <?php endif; ?>
            <a class="logout" href="?logout=1">Cerrar sesión</a>
        </div>
    <?php endif; ?>
</header>

<h2>Iniciar sesión</h2>

<?php if ($message): ?>
    <div class="message <?= strpos($message, 'correcto') !== false ? 'success' : 'error' ?>">
        <?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?>

<?php if (!$showLoginForm): ?>
    <p>Bienvenido<?= $usuarioCookie ? ', ' . htmlspecialchars($usuarioCookie) : '' ?>.</p>
    <a href="perfil.php">Ver perfil de usuario</a><br>
<?php else: ?>
    <form method="post" action="">
        <label for="usuario">Usuario:</label><br />
        <input type="text" id="usuario" name="usuario" required /><br />
        <label for="password">Contraseña:</label><br />
        <input type="password" id="password" name="password" required /><br />
        <input type="submit" value="Entrar" />
    </form>
<?php endif; ?>

<br>
<a href="registrar.php">Registrar nuevo usuario</a><br>
</body>
</html>
