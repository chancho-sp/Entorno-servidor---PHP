<?php
    $opc = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');

    try {
        // Conexión a la base de datos 'registro'
        $dwes = new PDO('mysql:host=localhost;dbname=registro', 'registro', 'registro', $opc);
    } catch (PDOException $e) {
        echo 'Falló la conexión: ' . $e->getMessage();
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $usuario = trim($_POST['usuario'] ?? '');
        $password = ($_POST['password'] ?? '');
        $hash = password_hash($password, PASSWORD_DEFAULT);

        // Validar campos mínimos
        if ($usuario === '' || $password === '') {
            $mensaje = 'Por favor rellena todos los campos correctamente.';
        } else {
            // Validar imagen
            if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
                $mensaje = 'Error: No se ha subido ninguna imagen o ocurrió un problema.';
            } else {
                $img = $_FILES['imagen'];

                // Comprobar tipo de archivo (seguro)
                $mime = mime_content_type($img['tmp_name']);
                if ($mime !== 'image/jpeg' && $mime !== 'image/png') {
                    $mensaje = 'Error: Solo se permiten imágenes JPG o PNG.';
                    exit();
                }

                // Crear directorio del usuario
                $dirUsuario = __DIR__ . "/img/users/$usuario";
                if (!is_dir($dirUsuario)) {
                    mkdir($dirUsuario, 0777, true);
                }

                // Obtener dimensiones originales
                [$ancho, $alto] = getimagesize($img['tmp_name']);

                // Crear imagen según tipo
                if ($mime === 'image/jpeg') {
                    $src = imagecreatefromjpeg($img['tmp_name']);
                    $ext = 'jpg';
                } else {
                    $src = imagecreatefrompng($img['tmp_name']);
                    $ext = 'png';
                }

                // ===== Versión grande (máximo 360x480) =====
                if ($ancho > 360 || $alto > 480) {
                    $ratio = min(360 / $ancho, 480 / $alto);
                    $nuevoAncho = (int)($ancho * $ratio);
                    $nuevoAlto = (int)($alto * $ratio);
                    $dst = imagescale($src, $nuevoAncho, $nuevoAlto, IMG_BILINEAR_FIXED);
                } else {
                    $dst = $src;
                }

                $pathGrande = "$dirUsuario/avatar_big.$ext";
                if ($mime === 'image/jpeg') {
                    imagejpeg($dst, $pathGrande, 90);
                } else {
                    imagepng($dst, $pathGrande, 9);
                }

                // ===== Versión pequeña (máximo 72x96) =====
                $ratioMini = min(72 / $ancho, 96 / $alto);
                $miniAncho = (int)($ancho * $ratioMini);
                $miniAlto = (int)($alto * $ratioMini);
                $mini = imagescale($src, $miniAncho, $miniAlto, IMG_BILINEAR_FIXED);

                $pathMini = "$dirUsuario/avatar_small.$ext";
                if ($mime === 'image/jpeg') {
                    imagejpeg($mini, $pathMini, 90);
                } else {
                    imagepng($mini, $pathMini, 9);
                }

                // Liberar memoria
                imagedestroy($src);
                if ($dst !== $src) imagedestroy($dst);
                imagedestroy($mini);

                // Ruta guardada en BD
                $rutaBD = "img/users/$usuario/avatar_big.$ext";

                // Insertar usuario en la BD
                try {
                    $insert = $dwes->prepare("INSERT INTO tabla_usuarios (usuario, password, imagen) VALUES (:usuario, :password, :imagen)");
                    $insert->execute([
                        ':usuario' => $usuario,
                        ':password' => $hash,
                        ':imagen' => $rutaBD
                    ]);

                    header('Location: index.php?ok=1&msg=' . urlencode('Usuario añadido correctamente.'));
                    exit();
                } catch (PDOException $e) {
                    $mensaje = 'Error al añadir usuario: ' . $e->getMessage();
                }
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

<?php if (!empty($mensaje)) echo "<p style='color:red;'>$mensaje</p>"; ?>

<form method="post" action="" enctype="multipart/form-data">
    <label for="usuario">Usuario:</label><br />
    <input type="text" id="usuario" name="usuario" required /><br />
    
    <label for="password">Contraseña:</label><br />
    <input type="password" id="password" name="password" required /><br />

    <label for="imagen">Imagen de perfil (.jpg o .png):</label><br />
    <input type="file" id="imagen" name="imagen" accept=".jpg,.jpeg,.png" required /><br /><br />

    <input type="submit" value="Registrar" />
</form>

<a href="index.php">Volver al login</a><br>
</body>
</html>
