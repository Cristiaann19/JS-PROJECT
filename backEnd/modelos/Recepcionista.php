<?php 
    class Recepcionista extends Empleado {
        //Atributos 
        private $idRecepcionista;
        private $turno;

        //Constructor
        public function __construct($nombreEmpleado, $apellidoPaternoE, $apellidoMaternoE, $telefono, $salario, $cargo, $estadoEmpleado, $turno) {
            parent::__construct($nombreEmpleado, $apellidoPaternoE, $apellidoMaternoE, $telefono, $salario, $cargo, $estadoEmpleado);
            $this->turno = $turno;
        }

        //Getters y Setters
        public function getTurno() {
            return $this->turno;
        }

        public function setTurno($turno) {
            $this->turno = $turno;
        }
    }
?>