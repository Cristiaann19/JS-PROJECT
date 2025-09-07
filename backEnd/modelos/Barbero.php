<?php 
    class Barbero extends Empleado {
        //Atributos 
        private $idBarbero;
        private $especialidad;

        //Constructor
        public function __construct($nombreEmpleado, $apellidoPaternoE, $apellidoMaternoE, $telefono, $salario, $cargo, $estadoEmpleado, $especialidad) {
            parent::__construct($nombreEmpleado, $apellidoPaternoE, $apellidoMaternoE, $telefono, $salario, $cargo, $estadoEmpleado);
            $this->especialidad = $especialidad;
        }

        // Getter y Setter
        public function getEspecialidad(){ 
            return $this->especialidad; 
        }

        public function setEspecialidad($especialidad) {         
            $this->especialidad = $especialidad; 
        }
    }
?>
