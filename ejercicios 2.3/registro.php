<?php
    $nombre= $apellidos= $username= $pass= $cpass= $email= $fecha= $gender= "";
    $error_nombre= $error_apellidos= $error_username= $error_pass= $error_cpass= $error_email= $error_fecha= $error_gender= $error_acepto="";
    $mostrar_formulario=true;
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recogemos y validamos cada campo (solo si están en POST)

            // Nombre
            if (empty($_POST['nombre'])) {
                $error_nombre = "El nombre es obligatorio.";
            } else {
                $nombre = trim($_POST['nombre']);
            }

            // Apellidos
            if (empty($_POST['apellidos'])) {
                $error_apellidos = "Los apellidos son obligatorios.";
            } else {
                $apellidos = trim($_POST['apellidos']);
            }

            // Usuario
            if (empty($_POST['username'])) {
                $error_username = "El usuario es obligatorio.";
            } else {
                $username = trim($_POST['username']);
            }

            // Password
            if (empty($_POST['pass'])) {
                $error_pass = "La contraseña es obligatoria.";
            } else {
                $pass = $_POST['pass'];
            }

            // Confirmar password
            if (empty($_POST['cpass'])) {
                $error_cpass = "Debe confirmar la contraseña.";
            } else {
                $cpass = $_POST['cpass'];
                if ($pass !== $cpass) {
                    $error_cpass = "Las contraseñas no coinciden.";
                }
            }

            // Email
            if (empty($_POST['email'])) {
                $error_email = "El email es obligatorio.";
            } else {
                $email = trim($_POST['email']);
                // Validar formato muy básico con filter_var
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $error_email = "Formato de email inválido.";
                }
            }

            // Fecha nacimiento
            if (empty($_POST['fecha'])) {
                $error_fecha = "La fecha de nacimiento es obligatoria.";
            } else {
                $fecha = $_POST['fecha'];
            }

            // Género
            if (empty($_POST['gender'])) {
                $error_gender = "Debe seleccionar un género.";
            } else {
                $gender = $_POST['gender'];
            }

            // Aceptar condiciones (ojo, el checkbox debe tener name para que se detecte)
            if (!isset($_POST['acepto'])) {
                $error_acepto = "Debe aceptar las condiciones.";
            }

            // Si no hay errores, formulario válido
            if (
                !$error_nombre && !$error_apellidos && !$error_username && 
                !$error_pass && !$error_cpass && !$error_email && 
                !$error_fecha && !$error_gender && !$error_acepto
            ) {
                $mostrar_formulario = false; // No mostrar formulario, mostrar mensaje éxito
            }
    }
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Presentación</title>
</head>
<body>
<p>Soy José Miguel Seara Martínez, alumno de segundo de DAW. El año pasado aprendimos java, bases de datos SQL y HTML y CSS.</p>
<a href="/tecnologias.html">Ir a tecnologías</a>
<a href="/rrss.html">Ir a redes sociales</a>
<a href="mailto:josseamar@alu.edu.gva.es">Escríbeme</a>
<?php if ($mostrar_formulario): ?>
    <form action="#" method="post">
        Nombre: <input type="text" name="nombre" id="nombre" value="<?= htmlspecialchars($nombre) ?>">
        <span style="color:red"><?= $error_nombre ?></span><br>

        Apellidos: <input type="text" name="apellidos" id="apellidos" value="<?= htmlspecialchars($apellidos) ?>">
        <span style="color:red"><?= $error_apellidos ?></span><br>

        Usuario: <input type="text" name="username" id="username" value="<?= htmlspecialchars($username) ?>">
        <span style="color:red"><?= $error_username ?></span><br>

        Password: <input type="password" name="pass" id="pass" value="">
        <span style="color:red"><?= $error_pass ?></span><br>

        Confirmar password: <input type="password" name="cpass" id="cpass" value="">
        <span style="color:red"><?= $error_cpass ?></span><br>

        Email: <input type="email" name="email" id="email" value="<?= htmlspecialchars($email) ?>">
        <span style="color:red"><?= $error_email ?></span><br>

        Fecha nacimiento: <input type="date" name="fecha" id="fecha" value="<?= htmlspecialchars($fecha) ?>">
        <span style="color:red"><?= $error_fecha ?></span><br>

        Género: 
        <input type="radio" name="gender" value="hombre" <?= ($gender == "hombre") ? "checked" : "" ?>> Hombre 
        <input type="radio" name="gender" value="mujer" <?= ($gender == "mujer") ? "checked" : "" ?>> Mujer
        <span style="color:red"><?= $error_gender ?></span><br>

        Acepto las condiciones <input type="checkbox" name="acepto" <?= isset($_POST['acepto']) ? 'checked' : '' ?>>
        <span style="color:red"><?= $error_acepto ?></span><br>

        Acepto publicidad <input type="checkbox" name="publicidad" <?= isset($_POST['publicidad']) ? 'checked' : '' ?>><br><br>

        <input type="submit" value="Enviar">
    </form>
<?php else: ?>
    <h2 style="color:green;">¡Formulario enviado correctamente!</h2>
<?php endif; ?>
</body>
</html>
