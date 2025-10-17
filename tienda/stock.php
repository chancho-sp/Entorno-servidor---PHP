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

    if (isset($_GET['cod'])) {
        $codigo = $_GET['cod'];
        $productosCantidad = $dwes->query("SELECT tienda, unidades FROM stock WHERE producto = '$codigo'");
    }
        
    if($productosCantidad)
        echo '<br>Numero de filas '. $productosCantidad->num_rows . '<br>';

    while ($fila = $productosCantidad->fetch_array()) {
        echo '<br>';
        echo 'Tienda: ' . $fila['tienda'] . '<br>';
        echo 'Unidades: ' . $fila['unidades'] . '<br><br>';
    }
?>
