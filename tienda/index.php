<?php
    $dwes = mysqli_connect('localhost', 'dwes', 'dwes', 'tienda');
    if ($dwes->connect_errno != null) {
        echo 'Error conectando a la base de datos: ';
        echo '$dwes->connect_error';
        exit();
    }
    
    else{
        echo 'Conexion correcta';
    }

    $resultado = $dwes->query('SELECT * FROM producto;');
    if($resultado)
        echo '<br>Numero de filas '. $resultado->num_rows . '<br>';

    while ($stock = $resultado->fetch_array()) {
        echo '<br>';
        echo 'Código: ' . $stock['cod'] . '<br>';
        echo 'Nombre corto: ' . $stock['nombre_corto'] . '<br>';
        echo 'Descripción: ' . $stock['descripcion'] . '<br>';
        echo 'PVP: ' . $stock['PVP'] . '<br>';
        echo 'Familia: ' . $stock['familia'] . '<br>';
        echo '<a href="stock.php?cod=' . $stock['cod'] . '">Ver stock</a><br>';
    }
?>
