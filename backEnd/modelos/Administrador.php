<?php
class Administrador extends Empleado {
    //Atributos
    private $idAdministrador;

    //Constructor
    public function __construct($nombreEmpleado, $apellidoPaternoE, $apellidoMaternoE, $telefono, $salario, $cargo, $estadoEmpleado) {
        parent::__construct($nombreEmpleado, $apellidoPaternoE, $apellidoMaternoE, $telefono, $salario, $cargo, $estadoEmpleado);
    }

    //Getters y Setters para idAdministrador
    public function getIdAdministrador() {
        return $this->idAdministrador;
    }

    public function setIdAdministrador($idAdministrador) {
        $this->idAdministrador = $idAdministrador;
    }
}
?>
