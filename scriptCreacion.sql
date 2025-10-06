-- create database barberia;
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
    dni char(8) not null unique,
    apellidoPaternoE varchar(50) not null, 
    apellidoMaternoE varchar(50) not null, 
    telefono char(9) not null unique, 
    salario decimal (10,2) not null, 
    cargo enum('Barbero', 'Recepcionista', 'Administrador'),
    estadoE enum('Activo', 'Inactivo'),
    generoE enum('Masculino', 'Femenino'),
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

-- LLENADO DE LA BD
-- 1. PRIMERO: Insertar datos en la tabla Cliente
INSERT INTO Cliente (nombreCliente, apellidoPaterno, apellidoMaterno, telefono, email) VALUES
('Carlos', 'García', 'López', '987654321', 'carlos.garcia@email.com'),
('Miguel', 'Rodríguez', 'Fernández', '976543210', 'miguel.rodriguez@email.com'),
('José', 'Martínez', 'Sánchez', '965432109', 'jose.martinez@email.com'),
('Luis', 'Hernández', 'Ruiz', '954321098', 'luis.hernandez@email.com'),
('David', 'González', 'Díaz', '943210987', 'david.gonzalez@email.com'),
('Antonio', 'Pérez', 'Moreno', '932109876', 'antonio.perez@email.com'),
('Francisco', 'López', 'Muñoz', '921098765', 'francisco.lopez@email.com'),
('Javier', 'Sánchez', 'Álvarez', '910987654', 'javier.sanchez@email.com'),
('Rafael', 'Díaz', 'Romero', '909876543', 'rafael.diaz@email.com'),
('Alejandro', 'Moreno', 'Alonso', '998765432', 'alejandro.moreno@email.com');

-- 2. SEGUNDO: Insertar datos en la tabla Empleado
INSERT INTO Empleado (nombreEmpleado, dni, apellidoPaternoE, apellidoMaternoE, telefono, salario, cargo, estadoE, generoE) VALUES
('Pedro', '12345678', 'Ramírez', 'Torres', '987123456', 2500.00, 'Barbero', 'Activo', 'Masculino'),
('Juan', '87654321', 'Vásquez', 'Castillo', '976234567', 2800.00, 'Barbero', 'Activo', 'Masculino'),
('Eduardo', '11223344', 'Silva', 'Mendoza', '965345678', 2600.00, 'Barbero', 'Activo', 'Masculino'),
('Ana', '44332211', 'Flores', 'Jiménez', '954456789', 1800.00, 'Recepcionista', 'Activo', 'Femenino'),
('María', '55667788', 'Vargas', 'Herrera', '943567890', 1900.00, 'Recepcionista', 'Activo', 'Femenino'),
('Roberto', '88776655', 'Campos', 'Navarro', '932678901', 3500.00, 'Administrador', 'Activo', 'Masculino'),
('Carmen', '99887766', 'Ramos', 'Ortega', '921789012', 2400.00, 'Barbero', 'Activo', 'Femenino'),
('Fernando', '66554433', 'Cruz', 'Guerrero', '910890123', 1850.00, 'Recepcionista', 'Activo', 'Masculino');

-- 3. TERCERO: Insertar datos en la tabla Servicio
INSERT INTO Servicio (nombreServicio, descripcion, precio) VALUES
('Corte clasico', 'Corte tradicional con tijeras y máquina', 20.00),
('Corte moderno', 'Corte personalizado con asesoría de estilo', 35.00),
('Afeitado barba y bigote', 'Afeitado tradicional con navaja y toallas calientes', 25.00);

-- 3.1 INSERTAR DATOS EN LA TABLA SERVICIO (ADICIONALES)
INSERT INTO Servicio (nombreServicio, descripcion, precio) VALUES
('Taper Fade', 'Degradado limpio y moderno.', 28.00),
('Low Fade', 'Degradado bajo y preciso.', 27.00),
('Mohicano', 'Corte con estilo atrevido.', 32.00),
('Mid Fade', 'Degradado medio con precisión.', 29.00),
('Mod Cut', 'Estilo moderno y elegante.', 30.00);

-- 3.2 INSERTAR DESPUES DE EL 3 Y 3.1
ALTER TABLE Servicio ADD imagenURL VARCHAR(255);
UPDATE Servicio SET imagenURL = 'https://i.pinimg.com/736x/ba/5d/e9/ba5de9c144330c53f43289bb1f6fcbbe.jpg' WHERE nombreServicio = 'Corte clasico';
UPDATE Servicio SET imagenURL = 'https://i.pinimg.com/736x/87/48/03/8748031d6e694fb31b0ad85bf3e7c849.jpg' WHERE nombreServicio = 'Corte moderno';
UPDATE Servicio SET imagenURL = 'https://i.pinimg.com/1200x/91/d6/03/91d6037c183ccc9644cdd59a70857524.jpg' WHERE nombreServicio = 'Afeitado barba y bigote';
UPDATE Servicio SET imagenURL = 'https://cdn.shopify.com/s/files/1/0029/0868/4397/files/Low_Taper_Fade_Fluffy_Hair.png?v=1747859628' WHERE nombreServicio = 'Taper Fade';
UPDATE Servicio SET imagenURL = 'https://cdn.shopify.com/s/files/1/0029/0868/4397/files/Low-Drop-Fade_600x600.webp?v=1750682896' where nombreServicio = 'Low Fade';
UPDATE Servicio SET imagenURL = 'https://i.pinimg.com/736x/fb/f7/78/fbf778533f579cbf36c72abbaed7cf3c.jpg' where nombreServicio = 'Mohicano';
UPDATE Servicio SET imagenURL = 'https://cdn.shopify.com/s/files/1/0029/0868/4397/files/Mid-Fade-Haircut_600x600.webp?v=1751273586' where nombreServicio = 'Mid Fade';
UPDATE Servicio SET imagenURL = 'https://media.gqmagazine.fr/photos/66bb2d2aabbfb9fd16c03ab3/1:1/w_1498,h_1498,c_limit/ModCut.jpg' where nombreServicio = 'Mod Cut';

ALTER TABLE Servicio ADD estadoS enum('Activo', 'Inactivo');
UPDATE Servicio SET estadoS = 'Activo' WHERE nombreServicio = 'Corte clasico';
UPDATE Servicio SET estadoS = 'Activo' WHERE nombreServicio = 'Corte moderno';
UPDATE Servicio SET estadoS = 'Activo' WHERE nombreServicio = 'Afeitado barba y bigote';
UPDATE Servicio SET estadoS = 'Activo' WHERE nombreServicio = 'Taper Fade';
UPDATE Servicio SET estadoS = 'Activo' where nombreServicio = 'Low Fade';
UPDATE Servicio SET estadoS = 'Activo' where nombreServicio = 'Mohicano';
UPDATE Servicio SET estadoS = 'Activo' where nombreServicio = 'Mid Fade';
UPDATE Servicio SET estadoS = 'Activo' where nombreServicio = 'Mod Cut';


-- 4. CUARTO: Insertar datos en la tabla Barbero
INSERT INTO Barbero (idEmpleado, especialidad) VALUES
(1, 'Cortes clásicos y modernos'),
(2, 'Afeitado tradicional y barba'),
(3, 'Cortes juveniles y degradados'),
(7, 'Peinados y tratamientos capilares');

-- 5. QUINTO: Insertar datos en la tabla Recepcionista
INSERT INTO Recepcionista (idEmpleado, turno) VALUES
(4, 'Mañana'),
(5, 'Tarde'),
(8, 'Noche');

-- 6. SEXTO: Insertar datos en la tabla Administrador
INSERT INTO Administrador (idEmpleado) VALUES
(6);

-- 7. SÉPTIMO: Insertar datos en la tabla Usuario
INSERT INTO Usuario (idEmpleado, nombreUsuario, contraseña) VALUES
(1, 'pedro_barbero', 'pedro123'),
(2, 'juan_barbero', 'juan456'),
(3, 'eduardo_barbero', 'edu789'),
(4, 'ana_recep', 'ana321'),
(5, 'maria_recep', 'maria654'),
(6, 'roberto_admin', 'admin987'),
(7, 'carmen_barbero', 'carmen159'),
(8, 'fernando_recep', 'fer753');

-- 8. OCTAVO: Insertar datos en la tabla Reserva
INSERT INTO Reserva (idCliente, idBarbero, idServicio, fechaReserva, hora, estado) VALUES
(1, 1, 1, '2025-09-10', '09:00:00', 'Completada'),
(2, 2, 3, '2025-09-10', '10:30:00', 'Completada'),
(3, 3, 2, '2025-09-11', '14:00:00', 'Completada'),
(4, 1, 1, '2025-09-11', '16:30:00', 'Completada'),
(5, 4, 2, '2025-09-12', '11:00:00', 'Completada'),
(6, 2, 3, '2025-09-12', '15:45:00', 'Completada'),
(7, 3, 1, '2025-09-13', '10:15:00', 'Completada'),
(8, 1, 2, '2025-09-13', '12:30:00', 'Completada'),
(9, 4, 3, '2025-09-14', '09:45:00', 'Completada'),
(10, 2, 1, '2025-09-14', '17:00:00', 'Completada'),
(1, 3, 2, '2025-09-05', '08:30:00', 'Completada'),
(2, 1, 3, '2025-09-06', '13:15:00', 'Completada'),
(3, 4, 1, '2025-09-07', '18:00:00', 'Completada'),
(4, 2, 2, '2025-09-08', '11:45:00', 'Completada'),
(5, 3, 3, '2025-09-09', '16:15:00', 'Completada'),
(6, 1, 1, '2025-08-30', '10:00:00', 'Completada'),
(7, 2, 2, '2025-08-31', '14:30:00', 'Completada'),
(8, 4, 3, '2025-09-01', '12:00:00', 'Completada'),
(9, 3, 1, '2025-09-02', '15:30:00', 'Completada'),
(10, 1, 2, '2025-09-03', '09:15:00', 'Completada');

-- 9. NOVENO: Insertar datos en la tabla Pago (AL FINAL)
INSERT INTO Pago (idReserva, montoPago, metodo, fechaPago) VALUES
(1, 20.00, 'Efectivo', '2025-09-10 09:30:00'),      -- Corte clásico
(2, 25.00, 'Tarjeta', '2025-09-10 11:00:00'),       -- Afeitado barba y bigote
(3, 35.00, 'Yape', '2025-09-11 14:30:00'),          -- Corte moderno
(4, 20.00, 'Efectivo', '2025-09-11 17:00:00'),      -- Corte clásico
(5, 35.00, 'Tarjeta', '2025-09-12 11:30:00'),       -- Corte moderno
(6, 25.00, 'Plin', '2025-09-12 16:15:00'),          -- Afeitado barba y bigote
(7, 20.00, 'Yape', '2025-09-13 10:45:00'),          -- Corte clásico
(8, 35.00, 'Efectivo', '2025-09-13 13:00:00'),      -- Corte moderno
(9, 25.00, 'Tarjeta', '2025-09-14 10:15:00'),       -- Afeitado barba y bigote
(10, 20.00, 'Efectivo', '2025-09-14 17:30:00'),     -- Corte clásico
(11, 35.00, 'Yape', '2025-09-05 09:00:00'),         -- Corte moderno
(12, 25.00, 'Plin', '2025-09-06 13:45:00'),         -- Afeitado barba y bigote
(13, 20.00, 'Tarjeta', '2025-09-07 18:30:00'),      -- Corte clásico
(14, 35.00, 'Efectivo', '2025-09-08 12:15:00'),     -- Corte moderno
(15, 25.00, 'Yape', '2025-09-09 16:45:00'),         -- Afeitado barba y bigote
(16, 20.00, 'Tarjeta', '2025-08-30 10:30:00'),      -- Corte clásico
(17, 35.00, 'Efectivo', '2025-08-31 15:00:00'),     -- Corte moderno
(18, 25.00, 'Plin', '2025-09-01 12:30:00'),         -- Afeitado barba y bigote
(19, 20.00, 'Yape', '2025-09-02 16:00:00'),         -- Corte clásico
(20, 35.00, 'Efectivo', '2025-09-03 09:45:00');     -- Corte moderno
