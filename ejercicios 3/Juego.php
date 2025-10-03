<?php
    class Juego extends Soporte {

        public $consola;
        private $minNumJugadores;
        private $maxNumJugadores;

        public function __construct($titulo, $numero, $precio, $consola, $minNumJugadores, $maxNumJugadores){
            parent::__construct($titulo, $numero, $precio);
            $this->consola = $consola;
            $this->minNumJugadores = $minNumJugadores;
            $this->maxNumJugadores = $maxNumJugadores;
        }

        public function muestraResumen (){
            echo '<br>Película en DVD: ' . $this->titulo . ' <br>Precio: ' . $this->precio . ' Precio sin IVA.';

            if ($this->minNumJugadores == 1 && $this->maxNumJugadores == 1){
                echo '<br>Para un jugador';
            }
            else {
                echo '<br>Jugadores mínimos: ' . $this->minNumJugadores . ' <br>Jugadores máximos: ' . $this->maxNumJUgadores;
            }
        }
        
        public function muestraJugadoresPosibles (){
            echo '<br>Jugadores mínimos: ' . $this->minNumJUgadores . ' <br>Jugadores máximos: ' . $this->maxNumJUgadores;
        }
}
?>