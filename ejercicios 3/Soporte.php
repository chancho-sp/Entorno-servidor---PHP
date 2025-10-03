<?php
    class Soporte {

        const VAT = 0.21;
        public $titulo;
        protected $numero;
        private $precio;

        public function __set ($propiedad, $valor){
            $this->$propiedad = $valor;
        }

        public function __get ($propiedad){
            return $this->$propiedad;
        }

        public function __construct($titulo, $numero, $precio){
            $this->titulo = $titulo;
            $this->numero = $numero;
            $this->precio = $precio;
        }

        public function getPrecio (){
            return $this->precio;
        }

        public function getPrecioConIVA (){
            $precioIVA = $this->precio*(1+self::VAT);
            return number_format($precioIVA, 2);
        }
 
        public function getNumero (){
            return $this->numero;
        }

        public function muestraResumen (){
            echo '<br>Título: ' . $this->titulo . ' Precio: ' . $this->precio . ' Precio sin IVA. Número: ' . $this->numero;
        }
}

?>
