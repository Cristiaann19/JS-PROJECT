<?php
    class Servicio {
        //Atributos
        private $idServicio;
        private $nombreServicio;
        private $descripcion;
        private $precio;
        
        //Constructor
        public function __construct($idServicio, $nombreServicio, $descripcion, $precio) {
            $this->idServicio = $idServicio;
            $this->nombreServicio = $nombreServicio;
            $this->descripcion = $descripcion;
            $this->precio = $precio;
        }

        //Getters
        public function getIdServicio() {
            return $this->idServicio;
        }

        public function getNombreServicio() {
            return $this->nombreServicio;
        }

        public function getDescripcion() {
            return $this->descripcion;
        }

        public function getPrecio() {
            return $this->precio;
        }

        //Setters
        public function setIdServicio($idServicio) {
            $this->idServicio = $idServicio;
        }

        public function setNombreServicio($nombreServicio) {
            $this->nombreServicio = $nombreServicio;
        }

        public function setDescripcion($descripcion) {
            $this->descripcion = $descripcion;
        }

        public function setPrecio($precio) {
            if ($precio >= 0) {
                $this->precio = $precio;
            }
        }
    }
?>
