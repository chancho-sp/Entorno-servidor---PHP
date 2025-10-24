<?php
    $opc = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
    try {
        $dwes = new PDO('mysql:host=localhost;dbname=discografia', 'discografia', 'discografia', $opc);
    } 
    catch (PDOException $e) {
        echo 'Falló la conexión: ' . $e->getMessage();
    }

    $resultados = [];
    $mensaje = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $busqueda = trim($_POST['busqueda'] ?? '');
        $tipo = $_POST['tipo'] ?? '';
        $genero = $_POST['genero'] ?? '';

        if ($busqueda === '' || $tipo === '' || $genero === '') {
            $mensaje = 'Por favor completa todos los campos.';
    } 
    else {
        try {
            // Construimos la consulta según la opción elegida
            switch ($tipo) {
                case 'cancion':
                    $sql = "SELECT c.titulo AS cancion, a.titulo AS album, c.genero, c.duracion 
                            FROM cancion c
                            JOIN album a ON c.album = a.codigo
                            WHERE LOWER(c.titulo) LIKE LOWER(:busqueda)
                              AND c.genero = :genero
                            ORDER BY a.titulo, c.posicion";
                    break;

                case 'album':
                    $sql = "SELECT c.titulo AS cancion, a.titulo AS album, c.genero, c.duracion 
                            FROM cancion c
                            JOIN album a ON c.album = a.codigo
                            WHERE LOWER(a.titulo) LIKE LOWER(:busqueda)
                              AND c.genero = :genero
                            ORDER BY a.titulo, c.posicion";
                    break;

                case 'ambos':
                    $sql = "SELECT c.titulo AS cancion, a.titulo AS album, c.genero, c.duracion 
                            FROM cancion c
                            JOIN album a ON c.album = a.codigo
                            WHERE (LOWER(c.titulo) LIKE LOWER(:busqueda) OR LOWER(a.titulo) LIKE LOWER(:busqueda))
                              AND c.genero = :genero
                            ORDER BY a.titulo, c.posicion";
                    break;

                default:
                    $mensaje = 'Opción de búsqueda no válida.';
                    $sql = '';
                    break;
            }

            if ($sql !== '') {
                $stmt = $dwes->prepare($sql);
                $stmt->execute([
                    ':busqueda' => '%' . $busqueda . '%',
                    ':genero' => $genero
                ]);
                $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (!$resultados) {
                    $mensaje = "No se encontraron resultados para '$busqueda' en $tipo con género '$genero'.";
                }
            }
        } catch (PDOException $e) {
            $mensaje = 'Error en la búsqueda: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Buscar canciones</title>
</head>
<body>
    <h2>Búsqueda de canciones</h2>
    <?php if ($mensaje): ?>
        <p style="color:red; font-weight:bold;"><?= htmlspecialchars($mensaje) ?></p>
    <?php endif; ?>

    <?php if (!empty($resultados)): ?>
        <table border="1" cellpadding="5" cellspacing="0">
            <tr>
                <th>Título canción</th>
                <th>Álbum</th>
                <th>Género</th>
                <th>Duración</th>
            </tr>
            <?php foreach ($resultados as $fila): ?>
                <tr>
                    <td><?= htmlspecialchars($fila['cancion']) ?></td>
                    <td><?= htmlspecialchars($fila['album']) ?></td>
                    <td><?= htmlspecialchars($fila['genero']) ?></td>
                    <td><?= htmlspecialchars($fila['duracion']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <form action="canciones.php" method="post">
        
        <label for="busqueda">Texto a buscar:</label><br />
        <input type="search" id="busqueda" name="busqueda" required maxlength="50" /><br /><br />

        <label for="tipo">Buscar en:</label><br />
        <input type="radio" id="tipo" name="tipo" value="cancion">Títulos de canción
        <input type="radio" id="tipo" name="tipo" value="album">Nombres de álbum
        <input type="radio" id="tipo" name="tipo" value="ambos">Ambos
        <br /><br />

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

        <input type="submit" value="Buscar" />
    </form>

</body>
</html>