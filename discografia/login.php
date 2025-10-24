<?php
    $opc = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
    $message = '';
    $showLoginForm = true;
    $usuarioCookie = $_COOKIE['usuario'] ?? null;
    
    try {
        $dwes = new PDO('mysql:host=localhost;dbname=discografia', 'discografia', 'discografia', $opc);
    } 
    catch (PDOException $e) {
        echo 'Falló la conexión: ' . $e->getMessage();
    }

    if ($usuarioCookie && !isset($_POST['respuesta_cookie']) && !isset($_POST['usuario'])) {
        $showLoginForm = false;
    } 

    else if (isset($_POST['respuesta_cookie'])) {
    
        if ($_POST['respuesta_cookie'] === 'si') {
            // Acceso correcto directo con cookie
            $message = "Acceso correcto, bienvenido $usuarioCookie.";
            $showLoginForm = false;
        } 
    
        else {
            // Borrar cookie y mostrar formulario login
            setcookie('usuario', '', time() - 3600, '/', '', false, true);
            $message = "Cookie borrada. Por favor, inicia sesión.";
            $showLoginForm = true;
        }
    } 


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $usuario = $_POST['usuario'] ?? '';
        $password = $_POST['password'] ?? '';

    if ($usuario === '' || $password === '') {
        $message = 'Por favor completa usuario y contraseña.';
    } 
    else {
        try {
            $stmt = $dwes->prepare("SELECT password FROM tabla_usuarios WHERE usuario = :usuario LIMIT 1");
            $stmt->execute([':usuario' => $usuario]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {

                if (password_verify($password, $row['password'])) {
                    $message = "¡Login correcto! Bienvenido, $usuario.";
                    setcookie('usuario', $usuario, time()+3600);
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

    
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<title>Pantalla de Login</title>
</head>
<body>

<h2>Iniciar sesión</h2>

<?php if ($message): ?>
    <div class="message <?= strpos($message, 'correcto') !== false ? 'success' : 'error' ?>">
        <?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?>

<?php if (!$showLoginForm && $usuarioCookie && !isset($_POST['respuesta_cookie'])): ?>
    <form method="post" action="">
        <p>¿Quieres iniciar sesión como <strong><?= htmlspecialchars($usuarioCookie) ?></strong>?</p>
        <button type="submit" name="respuesta_cookie" value="si">Sí</button>
        <button type="submit" name="respuesta_cookie" value="no">No</button>
    </form>

<?php elseif ($showLoginForm): ?>
    <form method="post" action="">
        <label for="usuario">Usuario:</label><br />
        <input type="text" id="usuario" name="usuario" required /><br />
        <label for="password">Contraseña:</label><br />
        <input type="password" id="password" name="password" required /><br />
        <input type="submit" value="Entrar" />
    </form>
<?php endif; ?>

</body>
</html>
