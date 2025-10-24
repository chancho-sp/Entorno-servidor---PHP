<?php
    $opc = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
    try {
        $dwes = new PDO('mysql:host=localhost;dbname=discografia', 'discografia', 'discografia', $opc);
    } 
    catch (PDOException $e) {
        echo 'Falló la conexión: ' . $e->getMessage();
    }

    if (isset($_GET['cod'])) {
        $codigo = $_GET['cod'];
        $resultado = $dwes->query("SELECT * FROM cancion WHERE album = '$codigo' ORDER BY posicion");
        $info = $dwes->query("SELECT * FROM album WHERE codigo = '$codigo'");
    }

    $album = $info->fetchAll(PDO::FETCH_ASSOC);
    $canciones = $resultado->fetchAll(PDO::FETCH_ASSOC);

    var_dump ($album);
    foreach ($canciones as $fila) {
        echo '<br>';
        echo 'Título: ' . $fila['titulo'] . '<br>';
        echo 'Álbum: ' . $fila['album'] . '<br>';
        echo 'Posición: ' . $fila['posicion'] . '<br>';
        echo 'Duración: ' . $fila['duracion'] . '<br>';
        echo 'Género: ' . $fila['genero'] . '<br>';
    }

    echo '<a href="cancionnueva.php?cod=' . $fila['album'] . '">Añadir canción</a><br>';
    echo '<a href="borraralbum.php?cod=' . $fila['album'] . '">Borrar álbum</a><br>';
?>
