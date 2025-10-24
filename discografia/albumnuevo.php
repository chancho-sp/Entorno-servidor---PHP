<?php
    $opc = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
    $mensaje = '';
    try {
        $dwes = new PDO('mysql:host=localhost;dbname=discografia', 'discografia', 'discografia', $opc);
    } 
    catch (PDOException $e) {
        echo 'Falló la conexión: ' . $e->getMessage();
    } 

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger y validar datos del formulario
    $titulo = trim($_POST['titulo'] ?? '');
    $discografica = trim($_POST['discografica'] ?? '');
    $formato = $_POST['formato'] ?? '';
    $fechaLanzamiento = $_POST['fechaLanzamiento'] ?? '';
    $fechaCompra = $_POST['fechaCompra'] ?? '';
    $precio = $_POST['precio'] ?? '';

    // Validar campos mínimos
    if (
        $titulo === '' || 
        $discografica === '' || 
        $formato === ''  || 
        $fechaLanzamiento === '' || 
        $fechaCompra === '' || 
        $precio === '' || 
        !is_numeric($precio)
    ) {
        $mensaje = 'Por favor rellena todos los campos correctamente (el precio debe ser numérico).';
    } else {
        try {
            // Insertar el nuevo álbum
            $insert = $dwes->prepare("
                INSERT INTO album (titulo, discografica, formato, fechaLanzamiento, fechaCompra, precio)
                VALUES (:titulo, :discografica, :formato, :fechaLanzamiento, :fechaCompra, :precio)
            ");
            $insert->execute([
                ':titulo' => $titulo,
                ':discografica' => $discografica,
                ':formato' => $formato,
                ':fechaLanzamiento' => $fechaLanzamiento,
                ':fechaCompra' => $fechaCompra,
                ':precio' => $precio
            ]);

            // Redirigir si todo fue bien
            header("Location: index.php?ok=1");
            exit;

        } catch (PDOException $e) {
            $mensaje = 'Error al añadir el álbum: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Añadir álbum nuevo</title>
</head>
<body>
    <h2>Añadir álbum nuevo</h2>

    <?php if ($mensaje): ?>
        <p style="color: red; font-weight: bold;"><?= htmlspecialchars($mensaje) ?></p>
    <?php endif; ?>

    <form action="albumnuevo.php" method="post">

        <label for="titulo">Título:</label><br />
        <input type="text" id="titulo" name="titulo" required maxlength="50" /><br /><br />

        <label for="discografica">Discográfica:</label><br />
        <input type="text" id="discografica" name="discografica" required maxlength="50" /><br /><br />

        <label for="formato">Formato:</label><br />
        <select id="formato" name="formato" required>
            <option value="">--Seleccione--</option>
            <option value="cd">CD</option>
            <option value="mp3">MP3</option>
            <option value="vinilo">Vinilo</option>
            <option value="dvd">DVD</option>
        </select><br /><br />

        <label for="fechaLanzamiento">Fecha de lanzamiento:</label><br />
        <input type="date" id="fechaLanzamiento" name="fechaLanzamiento" required /><br /><br />

        <label for="fechaCompra">Fecha de compra:</label><br />
        <input type="date" id="fechaCompra" name="fechaCompra" required /><br /><br />

        <label for="precio">Precio (€):</label><br />
        <input type="number" id="precio" name="precio" step="0.01" min="0" required /><br /><br />

        <input type="submit" value="Añadir álbum" />
    </form>

</body>
</html>