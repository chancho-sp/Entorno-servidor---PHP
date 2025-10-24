<?php
    $opc = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
    try {
        $dwes = new PDO('mysql:host=localhost;dbname=discografia', 'discografia', 'discografia', $opc);
    } 
    catch (PDOException $e) {
        echo 'Falló la conexión: ' . $e->getMessage();
    }

    $resultado = $dwes->query('SELECT * FROM album;');

    $album = $resultado->fetchAll(PDO::FETCH_ASSOC);
    foreach ($album as $fila) {
        echo '<br>';
        echo 'Código: ' . $fila['codigo'] . '<br>';
        echo 'Título: ' . $fila['titulo'] . '<br>';
        echo 'Discográfica: ' . $fila['discografica'] . '<br>';
        echo 'Formato: ' . $fila['formato'] . '<br>';
        echo 'Fecha de lanzamiento: ' . $fila['fechaLanzamiento'] . '<br>';
        echo 'Fecha de compra: ' . $fila['fechaCompra'] . '<br>';
        echo 'Precio: ' . $fila['precio'] . '<br>';
        echo '<a href="album.php?cod=' . $fila['codigo'] . '">Ver canciones</a><br>';
        
    }
    echo '<a href="albumnuevo.php">Añadir álbum</a><br>';
    echo '<a href="canciones.php">Buscar canción</a><br>';
?>
