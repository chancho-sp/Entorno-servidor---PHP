<?php
require_once 'auth.php';

$opc = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
try {
    $dwes = new PDO('mysql:host=localhost;dbname=discografia', 'discografia', 'discografia', $opc);
} 
catch (PDOException $e) {
    die('Falló la conexión: ' . $e->getMessage());
}

// Comprobar que se recibe código de álbum
if (!isset($_GET['cod']) || empty($_GET['cod'])) {
    header('Location: indiceAlbumes.php?error=' . urlencode('No se indicó el álbum.'));
    exit();
}

$codigo = intval($_GET['cod']);

// Obtener información del álbum
$stmtAlbum = $dwes->prepare("SELECT * FROM album WHERE codigo = :codigo");
$stmtAlbum->execute([':codigo' => $codigo]);
$album = $stmtAlbum->fetch(PDO::FETCH_ASSOC);

// Si no existe ese álbum
if (!$album) {
    header('Location: indiceAlbumes.php?error=' . urlencode('Álbum no encontrado.'));
    exit();
}

// Obtener canciones del álbum
$stmtCanciones = $dwes->prepare("SELECT * FROM cancion WHERE album = :codigo ORDER BY posicion");
$stmtCanciones->execute([':codigo' => $codigo]);
$canciones = $stmtCanciones->fetchAll(PDO::FETCH_ASSOC);

// Mostrar información del álbum
echo "<h2>Álbum: {$album['titulo']}</h2>";
echo "Discográfica: {$album['discografica']}<br>";
echo "Formato: {$album['formato']}<br>";
echo "Fecha lanzamiento: {$album['fechaLanzamiento']}<br><br>";

// Mostrar canciones (si existen)
if ($canciones) {
    echo "<h3>Canciones:</h3>";
    foreach ($canciones as $fila) {
        echo 'Título: ' . htmlspecialchars($fila['titulo']) . '<br>';
        echo 'Posición: ' . htmlspecialchars($fila['posicion']) . '<br>';
        echo 'Duración: ' . htmlspecialchars($fila['duracion']) . '<br>';
        echo 'Género: ' . htmlspecialchars($fila['genero']) . '<br>';
        echo '<hr>';
    }
} else {
    echo "<p>Este álbum no tiene canciones registradas.</p>";
}

// Mostrar enlaces (usando $album['codigo'], no $fila)
echo '<a href="cancionnueva.php?cod=' . $album['codigo'] . '">Añadir canción</a><br>';
echo '<a href="borraralbum.php?cod=' . $album['codigo'] . '">Borrar álbum</a><br>';
echo '<a href="indiceAlbumes.php">Volver al índice</a><br>';
?>