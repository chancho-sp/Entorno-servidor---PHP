<?php
    class CintaVideo extends Soporte {

        private $duracion;

        public function __construct($titulo, $numero, $precio, $duracion){
            parent::__construct($titulo, $numero, $precio);
            $this->duracion = $duracion;
        }

        public function muestraResumen (){
            echo '<br>Título: ' . $this->titulo . ' <br>Precio: ' . $this->precio . ' Precio sin IVA. Número: ' . $this->numero .
            ' <br>Duración: ' . $this->duracion;
        }
}
?>