<?php
    require_once 'auth.php';
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
    $titulo = trim($_POST['titulo'] ?? '');
    $posicion = intval($_POST['posicion'] ?? 0);
    $duracion = $_POST['duracion'] ?? '';
    $genero = $_POST['genero'] ?? '';

    // Validar campos mínimos
    if ($titulo === '' || $posicion <= 0 || $duracion === '' || $genero === '') {
        $mensaje = 'Por favor rellena todos los campos correctamente.';
    } else {
        // Insertar la nueva canción
        try {
            $insert = $dwes->prepare("INSERT INTO cancion (titulo, album, posicion, duracion, genero) VALUES (:titulo, :album, :posicion, :duracion, :genero)");
            $insert->execute([
                ':titulo' => $titulo,
                ':album' => $codigo,
                ':posicion' => $posicion,
                ':duracion' => $duracion,
                ':genero' => $genero
            ]);
            $mensaje = "Canción '$titulo' añadida correctamente al álbum '{$album['titulo']}'.";
        } catch (PDOException $e) {
            // Si hay error (por ejemplo clave primaria duplicada), mostrar mensaje
            $mensaje = 'Error al añadir la canción: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Añadir canción al álbum <?= htmlspecialchars($album['titulo']) ?></title>
</head>
<body>
    <h2>Añadir canción al álbum: <?= htmlspecialchars($album['titulo']) ?></h2>
    <?php if (isset($mensaje)) : ?>
        <p style="color: green; font-weight: bold;"><?= htmlspecialchars($mensaje) ?></p>
    <?php endif; ?>

    <form action="cancionnueva.php" method="post">
        <!-- Enviamos el código del álbum oculto para que sepa a qué álbum se añade -->
        <input type="hidden" name="album" value="<?= htmlspecialchars($codigoAlbum) ?>" />

        <label for="titulo">Título:</label><br />
        <input type="text" id="titulo" name="titulo" required maxlength="50" /><br /><br />

        <label for="posicion">Posición en el álbum (número):</label><br />
        <input type="number" id="posicion" name="posicion" required min="1" /><br /><br />

        <label for="duracion">Duración (HH:MM:SS):</label><br />
        <input type="text" id="duracion" name="duracion" required pattern="^\d{2}:\d{2}:\d{2}$" placeholder="00:03:45" /><br /><br />

        <label for="genero">Género:</label><br />
        <select id="genero" name="genero" required>
            <option value="">--Seleccione--</option>
            <option value="Acustica">Acústica</option>
            <option value="BSO">BSO</option>
            <option value="Blues">Blues</option>
            <option value="Folk">Folk</option>
            <option value="Jazz">Jazz</option>
            <option value="New age">New age</option>
            <option value="Pop">Pop</option>
            <option value="Rock">Rock</option>
            <option value="Electronica">Electrónica</option>
        </select><br /><br />

        <input type="submit" value="Añadir canción" />
    </form>

    <p><a href="album.php?cod=<?= urlencode($codigoAlbum) ?>">Volver al álbum</a></p>
</body>
</html>