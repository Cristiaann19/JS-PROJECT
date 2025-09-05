create database barberia;
use barberia;

-- Tabla clientes
create table Cliente (
	idCliente int auto_increment unique, 
    nombreCliente varchar(30) not null,
    apellidoPaterno varchar(50) not null, 
    apellidoMaterno varchar(50) not null, 
    telefono char(9) not null unique, 
    email char(100) unique,
    primary key(idCliente)
);

-- Tabla empleados 
create table Empleado (
	idEmpleado int auto_increment unique, 
    nombreEmpleado varchar(30) not null, 
    apellidoPaternoE varchar(50) not null, 
    apellidoMaternoE varchar(50) not null, 
    telefono char(9) not null unique, 
    salario decimal (10,2) not null, 
    cargo enum('Barbero', 'Recepcionista', 'Administrador'),
    estadoE enum('Activo', 'Inactivo'),
    primary key (idEmpleado)
);

-- Tabla barbero 
create table Barbero (
	idBarbero int auto_increment unique, 
    idEmpleado int not null unique, 
    especialidad varchar(50) not null,
    primary key (idBarbero), 
    foreign key (idEmpleado) references Empleado(idEmpleado)
);

-- Tabla recepcionistas 
create table Recepcionista (
	idRecepcionista int auto_increment unique,
    idEmpleado int not null unique,
    turno enum('Mañana', 'Tarde', 'Noche') not null,
    primary key (idRecepcionista),
    foreign key (idEmpleado) references Empleado(idEmpleado)
);

-- Tabla administrador 
create table Administrador (
	idAdministrador int auto_increment unique, 
    idEmpleado int not null unique, 
    primary key (idAdministrador), 
    foreign key (idEmpleado) references Empleado(idEmpleado)
);

-- Tabla usuarios 
create table Usuario (
	idUsuario int auto_increment not null unique, 
    idEmpleado int not null unique, 
    nombreUsuario varchar(30) not null unique,
    contraseña varchar(30) not null, 
    primary key (idUsuario), 
    foreign key (idEmpleado) references Empleado(idEmpleado)
);

-- Tabla servicios 
create table Servicio (
	idServicio int auto_increment not null unique, 
	nombreServicio varchar(100) not null unique,
    descripcion text not null, 
    precio decimal(10,2),
    primary key (idServicio)
);

-- Tabla reserva
create table Reserva (
	idReserva int auto_increment not null unique, 
    idCliente int not null, 
    idBarbero int not null, 
    idServicio int not null, 
    fechaReserva date not null,
    hora time not null,
    estado enum('Confirmada', 'Completada', 'Cancelada') default 'Confirmada',
	primary key (idReserva), 
    foreign key (idCliente) references Cliente(idCliente), 
    foreign key (idBarbero) references Barbero(idBarbero),
    foreign key (idServicio) references Servicio(idServicio),
    unique (idCliente, idBarbero, fechaReserva, hora)
);

-- Tabla pago 
create table Pago (
	idPago int auto_increment not null unique, 
    idReserva int not null unique, 
    montoPago decimal(10,2),
	metodo enum('Efectivo', 'Tarjeta', 'Yape', 'Plin') not null, 
    fechaPago datetime not null,
    primary key (idPago), 
    foreign key (idReserva) references Reserva(idReserva)
);