<?php
    function sumar($a, $b) {
        if(!is_numeric($a)||!is_numeric($b)){
            throw new exception("Las variables deben ser números");
        }
        return $a + $b;
    }

    try{
        $resultado=sumar(7,9);
        echo "Estamos sumando números, el resultado es $resultado.<br>";
    }
    catch(Exception $e){
        echo 'Message: ' .$e->getMessage();
    }
    try{
        $resultado=sumar(5,"chancho");
        echo "Estamos sumando números, el resultado es $resultado.<br>";
    }
    catch(Exception $e){
        echo 'Message: ' .$e->getMessage();
    }
?>