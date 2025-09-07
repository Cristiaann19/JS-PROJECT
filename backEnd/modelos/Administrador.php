<?php
    class Administrador extends Empleado {
        //Atributos
        private $idAdministrador;

        //Constructor
        public function __construct($nombreEmpleado, $apellidoPaternoE, $apellidoMaternoE, $telefono, $salario, $cargo, $estadoEmpleado, $especialidad) {
            parent::__construct($nombreEmpleado, $apellidoPaternoE, $apellidoMaternoE, $telefono, $salario, $cargo, $estadoEmpleado);
        }
    }
?>
