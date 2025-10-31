<?php
    require_once 'auth.php';
    $opc = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
    try {
        $dwes = new PDO('mysql:host=localhost;dbname=discografia', 'discografia', 'discografia', $opc);
    } 
    catch (PDOException $e) {
        echo 'Falló la conexión: ' . $e->getMessage();
    }

    if (!isset($_GET['cod']) || empty($_GET['cod'])) {
    // Si no se recibe código, redirige a index con error
    header('Location: indiceAlbumes.php?error=' . urlencode('No se indicó el álbum a borrar.'));
    exit();
    }

    $codigo = intval($_GET['cod']);

    try {
        $dwes->beginTransaction();

        // Borrar canciones asociadas
        $borrarCanciones = $dwes->prepare("DELETE FROM cancion WHERE album = :codigo");
        $borrarCanciones->execute([':codigo' => $codigo]);

        // Borrar álbum
        $borrarAlbum = $dwes->prepare("DELETE FROM album WHERE codigo = :codigo");
        $borrarAlbum->execute([':codigo' => $codigo]);

        $dwes->commit();

        // Redirige a index con mensaje de éxito
        header('Location: indiceAlbumes.php?ok=1&msg=' . urlencode('Álbum y canciones borrados correctamente.'));
        exit();

    } 
    catch (PDOException $e) {
        $dwes->rollBack();
        // Redirige a album.php con error
        header('Location: indiceAlbumes.php?cod=' . $codigo . '&error=' . urlencode('Error al borrar: ' . $e->getMessage()));
        exit();
    }