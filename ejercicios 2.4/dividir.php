<?php
    class numerosException extends Exception{
        public function errorMessage(){
            $errorMsg = 'error en la línea'.$this->getLine().' en '.$this->getFile().': <b>'.$this->getMessage().'</b> los parámetros deben ser números. <br>';
            return $errorMsg;
        }
    }
    class denominadorException extends Exception{
        public function errorMessage(){
            $errorMsg = 'error en la línea'.$this->getLine().' en '.$this->getFile().': <b>'.$this->getMessage().'</b> el denominador no puede ser 0. <br>';
            return $errorMsg;
        }
    }
    function dividir($a, $b) {
        if(!is_numeric($a)||!is_numeric($b)){
            throw new numerosException;
        }
        else if($b==0){
            throw new denominadorException;
        }
        return $a / $b;
    }

    try{
        $resultado=dividir(20,"chancho");
        echo "Estamos dividiendo números, el resultado es $resultado.<br>";
    }
    catch(numerosException $e){
        echo 'Message: ' .$e->errorMessage();
    }
    catch(denominadorException $e){
        echo 'Message: ' .$e->errorMessage();
    }
    try{
        $resultado=dividir(10,0);
        echo "Estamos dividiendo números, el resultado es $resultado.<br>";
    }
    catch(numerosException $e){
        echo 'Message: ' .$e->errorMessage();
    }
    catch(denominadorException $e){
        echo 'Message: ' .$e->errorMessage();
    }
?>