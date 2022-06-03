-- Creamos la base de datos
CREATE DATABASE `shopybook` DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci;
USE `shopybook`;

-- Creamos las tablas
CREATE TABLE `shopybook`.`categoria` (
`id` INT(3) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`nombre` VARCHAR( 100 ) UNIQUE
);

CREATE TABLE `shopybook`.`libro` (
`id` INT( 4 ) NOT NULL AUTO_INCREMENT PRIMARY KEY,
`isbn` BIGINT( 13 ) NOT NULL UNIQUE ,
`titulo` VARCHAR( 255 ) NOT NULL ,
`autor` VARCHAR( 255 ) NULL ,
`imagen` VARCHAR( 512 )  NULL ,
`descripcion` VARCHAR( 512 )  NULL ,
`valoracion` FLOAT( 10 ) NULL ,
`stock` INT(100) NULL ,
`precio` FLOAT( 10 ) NOT NULL, 
`categoriaID` INT(3) 
);

CREATE TABLE `shopybook`.`compra` (
`id` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY,
`titulo` VARCHAR( 255 ) NOT NULL ,
`imagen` VARCHAR( 512 )  NULL ,
`cantidad` INT(10)  NULL ,
`precio` FLOAT( 10 ) NOT NULL,
`libroID` INT( 4 ),
`pedidoID` INT( 10 )
);

CREATE TABLE `shopybook`.`pedido` (
`id` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY,
`fecha` DATE NOT NULL ,
`total` FLOAT( 10 ) NOT NULL ,
`nombre` VARCHAR( 255 ) NOT NULL ,
`apellidos` VARCHAR( 255 ) NOT NULL ,
`direccion` VARCHAR( 255 )  NOT NULL ,
`num_tarjeta` VARCHAR( 512 ) NOT NULL ,
`fecha_cad` DATE NOT NULL ,
`cvs` INT( 3 ) NOT NULL ,
`estado` VARCHAR(255) NULL,
`usuarioID` INT( 10 ) 
);

-- Creamos la tabla usuarios
CREATE TABLE `shopybook`.`usuarios` (
`id` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`usuario` VARCHAR(255) NOT NULL UNIQUE,
`contrasena` VARCHAR(255) NOT NULL,
`dni` VARCHAR( 9 ) NULL,
`nombre` VARCHAR( 255 )NULL ,
`apellido_1` VARCHAR( 255 ) NULL,
`apellido_2` VARCHAR( 255 ) NULL ,
`direccion` VARCHAR( 255 ) NULL,
`imagen_perfil` VARCHAR( 512 ) NULL,
`telefono` INT(9) NULL
);

