<?php
    $opc = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
        try {
            $dwes = new PDO('mysql:host=localhost;dbname=discografia', 'discografia', 'discografia', $opc);
        } 
        catch (PDOException $e) {
            echo 'Falló la conexión: ' . $e->getMessage();
        }

        if (isset($_GET['cod']) || isset($_POST['album'])) {
            $codigo = $_GET['cod'] ?? $_POST['album'];
            $codigo = intval($codigo);
            $info = $dwes->prepare("SELECT * FROM album WHERE codigo = :codigo");
            $info->execute([':codigo' => $codigo]);
            $album = $info->fetch(PDO::FETCH_ASSOC);
            $codigoAlbum = $codigo;
        }

        

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Recoger y validar datos del formulario
            $usuario = trim($_POST['usuario'] ?? '');
            $password = ($_POST['password'] ?? '');
            $hash = password_hash($password, PASSWORD_DEFAULT);

        // Validar campos mínimos
        if ($usuario === '' || $password === '') {
            $mensaje = 'Por favor rellena todos los campos correctamente.';
        } else {
            // Insertar usuario
            try {
                $insert = $dwes->prepare("INSERT INTO tabla_usuarios (usuario, password) VALUES (:usuario, :password)");
                $insert->execute([
                    ':usuario' => $usuario,
                    ':password' => $hash
                ]);
                header('Location: index.php?ok=1&msg=' . urlencode('Usuario añadido correctamente.'));
                exit();
            } catch (PDOException $e) {
                $mensaje = 'Error al añadir usuario: ' . $e->getMessage();
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<title>Registro de usuario</title>
</head>
<body>

<h2>Registrar nuevo usuario</h2>

<form method="post" action="">
    <label for="usuario">Usuario:</label><br />
    <input type="text" id="usuario" name="usuario" required /><br />
    <label for="password">Contraseña:</label><br />
    <input type="password" id="password" name="password" required /><br />
    <input type="submit" value="Entrar" />
</form>

<a href=index.php>Volver al login</a><br>
</body>
</html>
