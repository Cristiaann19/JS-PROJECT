<?php
    class Empleado {
        //Atributos
        private $idEmpleado;
        private $nombreEmpleado;
        private $apellidoPaternoE;
        private $apellidoMaternoE;
        private $telefono;
        private $salario;
        private $cargo;
        private $estadoEmpleado;

        //Constructor 
        public function __construct($nombreEmpleado, $apellidoPaternoE, $apellidoMaternoE, $telefono, $salario, $cargo, $estadoEmpleado){
            $this->$nombreEmpleado = $nombreEmpleado;
            $this->$apellidoPaternoE = $apellidoPaternoE;
            $this->$apellidoMaternoE = $apellidoMaternoE;
            $this->$telefono = $telefono;
            $this->$salario = $salario;
            $this->$cargo = $cargo;
            $this->$estadoEmpleado = $estadoEmpleado;
        }

        //Getters
        public function getNombreE(){
            return $this->$nombreEmpleado;
        }

        public function getApellidoPaternoE(){
            return $this->$apellidoPaternoE;
        }

        public function getApellidoMaternoE(){
            return $this->$apellidoMaternoE;
        }

        public function getTelefono(){
            return $this->$telefono;
        }

        public function getSalario(){
            return $this->$salario;
        }

        public function getCargo(){
            return $this->$cargo;
        }

        public function getEstadoEmpleado(){
            return $this->$estadoEmpleado;
        }

        //Setters
        public function setNombreEmpleado($nombreEmpleado){
            $this->$nombreEmpleado = $nombreEmpleado;
        }

        public function setApllidoPaternoE($apellidoPaternoE){
            $this->$apellidoPaternoE = $apellidoPaternoE;
        }

        public function setApllidoMaternoE($apellidoMaternoE){
            $this->$apellidoMaternoE = $apellidoMaternoE;
        }

        public function setTelefono($telefono) {
            $this->$telefono = $telefono;
        }
    }    
?>
