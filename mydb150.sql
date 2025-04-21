-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 18-04-2024 a las 16:48:19
-- Versión del servidor: 8.0.31
-- Versión de PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `mydb`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `disponibilidad`
--

CREATE TABLE `disponibilidad` (
  `ID_Disponibilidad` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `disponibilidad`
--

INSERT INTO `disponibilidad` (`ID_Disponibilidad`) VALUES
('Disponible'),
('No disponible');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_persona`
--

CREATE TABLE `estado_persona` (
  `idEstado_Persona` int NOT NULL,
  `Descripcion_Estado_Persona` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado_persona`
--

INSERT INTO `estado_persona` (`idEstado_Persona`, `Descripcion_Estado_Persona`) VALUES
(1, 'Activo'),
(2, 'Inactivo'),
(3, 'Finalizado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_proyecto`
--

CREATE TABLE `estado_proyecto` (
  `idEstado_proyecto` int NOT NULL,
  `Nombre_estado_proyecto` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado_proyecto`
--

INSERT INTO `estado_proyecto` (`idEstado_proyecto`, `Nombre_estado_proyecto`) VALUES
(1, 'Sin iniciar'),
(2, 'Rechazado'),
(3, 'Aprobado'),
(4, 'Incompleto'),
(5, 'Completo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_tarea`
--

CREATE TABLE `estado_tarea` (
  `idEstado_tarea` int NOT NULL,
  `Nombre_Estado_tarea` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado_tarea`
--

INSERT INTO `estado_tarea` (`idEstado_tarea`, `Nombre_Estado_tarea`) VALUES
(1, 'Sin iniciar'),
(2, 'Rechazado'),
(3, 'Aprobado'),
(4, 'Incompleto'),
(5, 'Completo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_usuario`
--

CREATE TABLE `estado_usuario` (
  `IDEstado_usuario` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado_usuario`
--

INSERT INTO `estado_usuario` (`IDEstado_usuario`) VALUES
('Activo'),
('Inactivo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

CREATE TABLE `persona` (
  `Cedula` int NOT NULL,
  `Nombre_Persona` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Apellido1_Persona` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Apellido2_Persona` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Telefono` int NOT NULL,
  `Correo_Electronico` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Estado_Persona_idEstado_Persona` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `persona`
--

INSERT INTO `persona` (`Cedula`, `Nombre_Persona`, `Apellido1_Persona`, `Apellido2_Persona`, `Telefono`, `Correo_Electronico`, `Estado_Persona_idEstado_Persona`) VALUES
(123555, 'Jennifer', 'Ruiz', 'Zapata', 84523021, 'jenni@gmail.com', 1),
(123556, 'Alexandra', 'Ruiz', 'Zapata', 84523021, 'ale@gmail.com', 2),
(117960015, 'Olda', 'Bustillos', 'Ortega', 87654789, 'obustilloso@alpha.com', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `presupuesto`
--

CREATE TABLE `presupuesto` (
  `idPresupuesto` int NOT NULL,
  `Monto` decimal(10,0) NOT NULL,
  `DescripcionP` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Proyecto_idproyecto` int NOT NULL,
  `Tarea_idTarea` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `presupuesto`
--

INSERT INTO `presupuesto` (`idPresupuesto`, `Monto`, `DescripcionP`, `Proyecto_idproyecto`, `Tarea_idTarea`) VALUES
(2, 123, 'Prueba', 1, 20),
(3, 456, 'Descripción Prueba', 1, 21),
(4, 332, 'Presupuesto para Mantenimiento', 1, 21),
(14, 12, 'asdf', 1, 20),
(15, 50, 'asdf', 1, 23);

--
-- Disparadores `presupuesto`
--
DELIMITER $$
CREATE TRIGGER `Actualizar_Monto_Activo_Delete` AFTER DELETE ON `presupuesto` FOR EACH ROW BEGIN
    DECLARE total_monto_presupuesto DECIMAL(10, 2);
    DECLARE total_monto_utilizado DECIMAL(10, 2);
    DECLARE nuevo_monto_activo DECIMAL(10, 2);

    -- Obtener el Monto_Presupuesto actual del proyecto
    SELECT Monto_Presupuesto INTO total_monto_presupuesto
    FROM proyecto
    WHERE idproyecto = OLD.Proyecto_idproyecto;

    -- Calcular la suma total de Montos utilizados para el proyecto
    SELECT COALESCE(SUM(Monto), 0) INTO total_monto_utilizado
    FROM presupuesto
    WHERE Proyecto_idproyecto = OLD.Proyecto_idproyecto;

    -- Calcular el nuevo Monto_Activo después de la eliminación
    SET nuevo_monto_activo = total_monto_presupuesto - total_monto_utilizado;

    -- Actualizar el Monto_Activo en la tabla Proyecto
    UPDATE proyecto
    SET Monto_Activo = nuevo_monto_activo
    WHERE idproyecto = OLD.Proyecto_idproyecto;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `Actualizar_Monto_Activo_Después_Update` AFTER UPDATE ON `presupuesto` FOR EACH ROW BEGIN
    DECLARE total_monto_presupuesto DECIMAL(10, 2);
    DECLARE total_monto_utilizado DECIMAL(10, 2);
    DECLARE nuevo_monto_activo DECIMAL(10, 2);

    -- Obtener el Monto_Presupuesto actual del proyecto
    SELECT Monto_Presupuesto INTO total_monto_presupuesto
    FROM proyecto
    WHERE idproyecto = NEW.Proyecto_idproyecto;

    -- Calcular la suma total de Montos utilizados para el proyecto
    SELECT COALESCE(SUM(Monto), 0) INTO total_monto_utilizado
    FROM presupuesto
    WHERE Proyecto_idproyecto = NEW.Proyecto_idproyecto;

    -- Calcular el nuevo Monto_Activo después de la actualización
    SET nuevo_monto_activo = total_monto_presupuesto - total_monto_utilizado;

    -- Validar que el nuevo Monto_Activo no sea menor que 0
    IF nuevo_monto_activo < 0 THEN
        -- Generar un error personalizado utilizando una señal
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Sobrepaso el presupuesto. El Monto_Activo no puede ser menor que 0.';
    ELSE
        -- Actualizar el Monto_Activo en la tabla Proyecto
        UPDATE proyecto
        SET Monto_Activo = nuevo_monto_activo
        WHERE idproyecto = NEW.Proyecto_idproyecto;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `Actualizar_Monto_Activo_Insert` AFTER INSERT ON `presupuesto` FOR EACH ROW BEGIN
    DECLARE total_monto_presupuesto DECIMAL(10, 2);
    DECLARE total_monto_utilizado DECIMAL(10, 2);
    DECLARE nuevo_monto_activo DECIMAL(10, 2);

    -- Obtener el Monto_Presupuesto actual del proyecto
    SELECT Monto_Presupuesto INTO total_monto_presupuesto
    FROM proyecto
    WHERE idproyecto = NEW.Proyecto_idproyecto;

    -- Calcular la suma total de Montos utilizados para el proyecto
    SELECT COALESCE(SUM(Monto), 0) INTO total_monto_utilizado
    FROM presupuesto
    WHERE Proyecto_idproyecto = NEW.Proyecto_idproyecto;

    -- Calcular el nuevo Monto_Activo después de la inserción
    SET nuevo_monto_activo = total_monto_presupuesto - total_monto_utilizado;

    -- Actualizar el Monto_Activo en la tabla Proyecto
    UPDATE proyecto
    SET Monto_Activo = nuevo_monto_activo
    WHERE idproyecto = NEW.Proyecto_idproyecto;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `Validar_Monto_Activo_Insert` BEFORE INSERT ON `presupuesto` FOR EACH ROW BEGIN
    DECLARE total_monto_presupuesto DECIMAL(10, 2);
    DECLARE total_monto_utilizado DECIMAL(10, 2);
    DECLARE nuevo_monto_activo DECIMAL(10, 2);

    -- Obtener el Monto_Presupuesto actual del proyecto
    SELECT Monto_Presupuesto INTO total_monto_presupuesto
    FROM proyecto
    WHERE idproyecto = NEW.Proyecto_idproyecto;

    -- Calcular la suma total de Montos utilizados para el proyecto
    SELECT COALESCE(SUM(Monto), 0) INTO total_monto_utilizado
    FROM presupuesto
    WHERE Proyecto_idproyecto = NEW.Proyecto_idproyecto;

    -- Calcular el nuevo Monto_Activo después de la inserción
    SET nuevo_monto_activo = total_monto_presupuesto - total_monto_utilizado;

    -- Verificar si el Monto que se va a insertar es mayor que el Monto_Activo
    IF NEW.Monto > nuevo_monto_activo THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'El Monto ingresado sobrepaso el presupuesto del proyecto';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyecto`
--

CREATE TABLE `proyecto` (
  `idproyecto` int NOT NULL,
  `Nombre` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Lider_proyecto` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Monto_Presupuesto` decimal(10,2) NOT NULL,
  `Monto_Activo` int DEFAULT NULL,
  `Fecha_creacion` date NOT NULL,
  `Fecha_entrega` date NOT NULL,
  `Tipo_Proyecto_Tipo_de_Proyecto` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Estado_proyecto_idEstado_proyecto` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proyecto`
--

INSERT INTO `proyecto` (`idproyecto`, `Nombre`, `Descripcion`, `Lider_proyecto`, `Monto_Presupuesto`, `Monto_Activo`, `Fecha_creacion`, `Fecha_entrega`, `Tipo_Proyecto_Tipo_de_Proyecto`, `Estado_proyecto_idEstado_proyecto`) VALUES
(1, 'Hotel Rio Celeste', 'prueba', 'Karla Orozco Padilla', 1000.00, 27, '2024-04-14', '2024-04-20', 'Marketing y promoción', 2),
(2, 'DD', 'DD', 'DD', 30000.00, 30000, '2024-04-14', '2024-04-20', 'Implementación', 4),
(3, 'Prueba ', 'fgsfgsd', 'Jennii', 4000.00, 4000, '2024-04-16', '2024-04-24', 'Conferencia', 3),
(4, 'Test', 'bfcbn', 'Iris', 789520.00, 789520, '2024-04-16', '2024-04-18', 'Capacitación', 4);

--
-- Disparadores `proyecto`
--
DELIMITER $$
CREATE TRIGGER `Actualizar_Monto_Activo_Before_Update_Proyecto` BEFORE UPDATE ON `proyecto` FOR EACH ROW BEGIN
    DECLARE total_monto_utilizado DECIMAL(10, 2);
    DECLARE nuevo_monto_activo DECIMAL(10, 2);

    -- Calcular la suma total de Montos utilizados para el proyecto
    SELECT COALESCE(SUM(Monto), 0) INTO total_monto_utilizado
    FROM presupuesto
    WHERE Proyecto_idproyecto = NEW.idproyecto;

    -- Calcular el nuevo Monto_Activo basado en el nuevo valor de Monto_Presupuesto
    SET nuevo_monto_activo = NEW.Monto_Presupuesto - total_monto_utilizado;

    -- Actualizar el Monto_Activo en la tabla Proyecto
    SET NEW.Monto_Activo = nuevo_monto_activo;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyecto_tiene_personas`
--

CREATE TABLE `proyecto_tiene_personas` (
  `ID_Proyecto_tiene_Persona` int NOT NULL,
  `Proyecto_idproyecto` int NOT NULL,
  `Persona_Cedula` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proyecto_tiene_personas`
--

INSERT INTO `proyecto_tiene_personas` (`ID_Proyecto_tiene_Persona`, `Proyecto_idproyecto`, `Persona_Cedula`) VALUES
(78, 4, 123555),
(79, 4, 123556),
(77, 3, 117960015);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recurso`
--

CREATE TABLE `recurso` (
  `idRecurso` int NOT NULL,
  `Nombre` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Cantidad_Recurso` int DEFAULT NULL,
  `Disponibilidad_ID_Disponibilidad` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Tipo_Recurso_idTipo_Recurso` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `recurso`
--

INSERT INTO `recurso` (`idRecurso`, `Nombre`, `Cantidad_Recurso`, `Disponibilidad_ID_Disponibilidad`, `Tipo_Recurso_idTipo_Recurso`) VALUES
(9, 'Computadora ', 7, 'Disponible', 'Tecnologico'),
(10, 'Iphone ', 10, 'Disponible', 'Tecnologico');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol_usuario`
--

CREATE TABLE `rol_usuario` (
  `Tipo_usuario` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='	';

--
-- Volcado de datos para la tabla `rol_usuario`
--

INSERT INTO `rol_usuario` (`Tipo_usuario`) VALUES
('Administrador'),
('Colaborador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tarea`
--

CREATE TABLE `tarea` (
  `idTarea` int NOT NULL,
  `Descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Fecha_inicio` date NOT NULL,
  `Fecha_fin` date NOT NULL,
  `Usuario_idUsuario` int DEFAULT NULL,
  `Estado_tarea_idEstado_tarea` int NOT NULL,
  `Proyecto_idproyecto` int NOT NULL,
  `Recurso_idRecurso` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tarea`
--

INSERT INTO `tarea` (`idTarea`, `Descripcion`, `Fecha_inicio`, `Fecha_fin`, `Usuario_idUsuario`, `Estado_tarea_idEstado_tarea`, `Proyecto_idproyecto`, `Recurso_idRecurso`) VALUES
(20, 'Pruebaa', '2024-04-15', '2024-04-15', 1, 1, 1, 9),
(21, 'Domingo ', '2024-04-16', '2024-04-20', 1, 4, 1, 10),
(23, 'prueba07', '2024-04-15', '2024-04-16', 1, 5, 1, 10),
(36, 'Prueba_Tarea', '2024-04-16', '2024-05-01', 1, 1, 3, 10);

--
-- Disparadores `tarea`
--
DELIMITER $$
CREATE TRIGGER `actualizar_recurso_de_tarea` AFTER UPDATE ON `tarea` FOR EACH ROW BEGIN
    DECLARE nuevo_recurso_id INT;
    DECLARE viejo_recurso_id INT;

    -- Obtener el ID del recurso nuevo y viejo (antes y después de la actualización)
    SELECT NEW.Recurso_idRecurso, OLD.Recurso_idRecurso INTO nuevo_recurso_id, viejo_recurso_id;

    -- Verificar si el recurso ha sido cambiado en la tarea
    IF nuevo_recurso_id <> viejo_recurso_id THEN
        -- Decrementar la cantidad del nuevo recurso (si se asigna un nuevo recurso)
        IF nuevo_recurso_id IS NOT NULL THEN
            UPDATE recurso
            SET Cantidad_Recurso = Cantidad_Recurso - 1
            WHERE idRecurso = nuevo_recurso_id;
        END IF;

        -- Incrementar la cantidad del recurso anterior (si se elimina un recurso asignado)
        IF viejo_recurso_id IS NOT NULL THEN
            UPDATE recurso
            SET Cantidad_Recurso = Cantidad_Recurso + 1
            WHERE idRecurso = viejo_recurso_id;
        END IF;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `devolver_recurso_tarea` AFTER DELETE ON `tarea` FOR EACH ROW BEGIN
    DECLARE recurso_id INT;

    -- Obtener el ID del recurso asociado a la tarea eliminada
    SET recurso_id = OLD.Recurso_idRecurso;

    -- Verificar si el recurso_id obtenido es válido (diferente de NULL)
    IF recurso_id IS NOT NULL THEN
        -- Incrementar la cantidad del recurso devuelto en 1
        UPDATE recurso
        SET Cantidad_Recurso = Cantidad_Recurso + 1
        WHERE idRecurso = recurso_id;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `utilizar_recurso` AFTER INSERT ON `tarea` FOR EACH ROW BEGIN
    DECLARE recurso_cantidad INT;

    -- Verificar si el Recurso_idRecurso no es nulo
    IF NEW.Recurso_idRecurso IS NOT NULL THEN
        -- Obtener la cantidad actual del recurso asociado a la nueva tarea
        SELECT Cantidad_Recurso INTO recurso_cantidad
        FROM recurso
        WHERE idRecurso = NEW.Recurso_idRecurso;

        -- Verificar si hay suficiente cantidad disponible
        IF recurso_cantidad > 0 THEN
            -- Actualizar la cantidad del recurso restando 1
            UPDATE recurso
            SET Cantidad_Recurso = Cantidad_Recurso - 1
            WHERE idRecurso = NEW.Recurso_idRecurso;
        ELSE
            -- Si la cantidad es 0, lanzar un error o mensaje (según tus requisitos)
            SIGNAL SQLSTATE '45000' 
            SET MESSAGE_TEXT = 'No hay recursos Disponibles';
        END IF;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tarea_tiene_personas`
--

CREATE TABLE `tarea_tiene_personas` (
  `ID_Tarea_tiene_Persona` int NOT NULL,
  `Tarea_idtarea` int NOT NULL,
  `Persona_Cedula` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tarea_tiene_personas`
--

INSERT INTO `tarea_tiene_personas` (`ID_Tarea_tiene_Persona`, `Tarea_idtarea`, `Persona_Cedula`) VALUES
(9, 36, 117960015),
(10, 36, 117960015);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_proyecto`
--

CREATE TABLE `tipo_proyecto` (
  `Tipo_de_Proyecto` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_proyecto`
--

INSERT INTO `tipo_proyecto` (`Tipo_de_Proyecto`) VALUES
('Capacitación'),
('Conferencia'),
('Construcción'),
('Desarrollo de Software'),
('Implementación'),
('Marketing y promoción'),
('Talento Humano');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_recurso`
--

CREATE TABLE `tipo_recurso` (
  `idTipo_Recurso` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_recurso`
--

INSERT INTO `tipo_recurso` (`idTipo_Recurso`) VALUES
('Financiero'),
('Humano'),
('Infraestructura'),
('Tecnologico');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `idUsuario` int NOT NULL,
  `Nombre_usuario` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Fecha_creacion` date NOT NULL,
  `Contrasena` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Rol_usuario_Tipo_usuario` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Estado_usuario_IDEstado_usuario` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Persona_Cedula` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`idUsuario`, `Nombre_usuario`, `Fecha_creacion`, `Contrasena`, `Rol_usuario_Tipo_usuario`, `Estado_usuario_IDEstado_usuario`, `Persona_Cedula`) VALUES
(1, 'root', '2024-04-14', 'Aa123456789!', 'Colaborador', 'Activo', 117960015),
(2, 'olda', '2024-04-16', '1234', 'Administrador', 'Activo', 123555);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `disponibilidad`
--
ALTER TABLE `disponibilidad`
  ADD PRIMARY KEY (`ID_Disponibilidad`);

--
-- Indices de la tabla `estado_persona`
--
ALTER TABLE `estado_persona`
  ADD PRIMARY KEY (`idEstado_Persona`);

--
-- Indices de la tabla `estado_proyecto`
--
ALTER TABLE `estado_proyecto`
  ADD PRIMARY KEY (`idEstado_proyecto`);

--
-- Indices de la tabla `estado_tarea`
--
ALTER TABLE `estado_tarea`
  ADD PRIMARY KEY (`idEstado_tarea`);

--
-- Indices de la tabla `estado_usuario`
--
ALTER TABLE `estado_usuario`
  ADD PRIMARY KEY (`IDEstado_usuario`);

--
-- Indices de la tabla `persona`
--
ALTER TABLE `persona`
  ADD PRIMARY KEY (`Cedula`),
  ADD KEY `fk_Persona_Estado_Persona1_idx` (`Estado_Persona_idEstado_Persona`);

--
-- Indices de la tabla `presupuesto`
--
ALTER TABLE `presupuesto`
  ADD PRIMARY KEY (`idPresupuesto`,`Proyecto_idproyecto`),
  ADD KEY `fk_Presupuesto_Proyecto1_idx` (`Proyecto_idproyecto`),
  ADD KEY `fk_Presupuesto_Tarea1_idx` (`Tarea_idTarea`);

--
-- Indices de la tabla `proyecto`
--
ALTER TABLE `proyecto`
  ADD PRIMARY KEY (`idproyecto`),
  ADD KEY `fk_Proyecto_Tipo_Proyecto1_idx` (`Tipo_Proyecto_Tipo_de_Proyecto`),
  ADD KEY `fk_Proyecto_Estado_proyecto1_idx` (`Estado_proyecto_idEstado_proyecto`);

--
-- Indices de la tabla `proyecto_tiene_personas`
--
ALTER TABLE `proyecto_tiene_personas`
  ADD PRIMARY KEY (`ID_Proyecto_tiene_Persona`,`Proyecto_idproyecto`,`Persona_Cedula`),
  ADD KEY `fk_Proyecto_has_Persona_Persona1_idx` (`Persona_Cedula`),
  ADD KEY `fk_Proyecto_has_Persona_Proyecto1_idx` (`Proyecto_idproyecto`);

--
-- Indices de la tabla `recurso`
--
ALTER TABLE `recurso`
  ADD PRIMARY KEY (`idRecurso`),
  ADD KEY `fk_Recurso_Disponibilidad1_idx` (`Disponibilidad_ID_Disponibilidad`),
  ADD KEY `fk_Recurso_Tipo_Recurso1_idx` (`Tipo_Recurso_idTipo_Recurso`);

--
-- Indices de la tabla `rol_usuario`
--
ALTER TABLE `rol_usuario`
  ADD PRIMARY KEY (`Tipo_usuario`);

--
-- Indices de la tabla `tarea`
--
ALTER TABLE `tarea`
  ADD PRIMARY KEY (`idTarea`),
  ADD KEY `fk_Tarea_Usuario1_idx` (`Usuario_idUsuario`),
  ADD KEY `fk_Tarea_Estado_tarea1_idx` (`Estado_tarea_idEstado_tarea`),
  ADD KEY `fk_Tarea_Proyecto1_idx` (`Proyecto_idproyecto`),
  ADD KEY `fk_Tarea_Recurso1_idx` (`Recurso_idRecurso`);

--
-- Indices de la tabla `tarea_tiene_personas`
--
ALTER TABLE `tarea_tiene_personas`
  ADD PRIMARY KEY (`ID_Tarea_tiene_Persona`,`Tarea_idtarea`,`Persona_Cedula`),
  ADD KEY `Tarea_idtarea` (`Tarea_idtarea`),
  ADD KEY `Persona_Cedula` (`Persona_Cedula`);

--
-- Indices de la tabla `tipo_proyecto`
--
ALTER TABLE `tipo_proyecto`
  ADD PRIMARY KEY (`Tipo_de_Proyecto`);

--
-- Indices de la tabla `tipo_recurso`
--
ALTER TABLE `tipo_recurso`
  ADD PRIMARY KEY (`idTipo_Recurso`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`idUsuario`),
  ADD KEY `fk_Usuario_Rol_usuario1_idx` (`Rol_usuario_Tipo_usuario`),
  ADD KEY `fk_Usuario_Estado_usuario1_idx` (`Estado_usuario_IDEstado_usuario`),
  ADD KEY `fk_Usuario_Persona1_idx` (`Persona_Cedula`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `presupuesto`
--
ALTER TABLE `presupuesto`
  MODIFY `idPresupuesto` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `proyecto`
--
ALTER TABLE `proyecto`
  MODIFY `idproyecto` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `proyecto_tiene_personas`
--
ALTER TABLE `proyecto_tiene_personas`
  MODIFY `ID_Proyecto_tiene_Persona` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT de la tabla `recurso`
--
ALTER TABLE `recurso`
  MODIFY `idRecurso` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `tarea`
--
ALTER TABLE `tarea`
  MODIFY `idTarea` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=608;

--
-- AUTO_INCREMENT de la tabla `tarea_tiene_personas`
--
ALTER TABLE `tarea_tiene_personas`
  MODIFY `ID_Tarea_tiene_Persona` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idUsuario` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `persona`
--
ALTER TABLE `persona`
  ADD CONSTRAINT `fk_Persona_Estado_Persona1` FOREIGN KEY (`Estado_Persona_idEstado_Persona`) REFERENCES `estado_persona` (`idEstado_Persona`);

--
-- Filtros para la tabla `presupuesto`
--
ALTER TABLE `presupuesto`
  ADD CONSTRAINT `fk_Presupuesto_Proyecto1` FOREIGN KEY (`Proyecto_idproyecto`) REFERENCES `proyecto` (`idproyecto`),
  ADD CONSTRAINT `fk_Presupuesto_Tarea1` FOREIGN KEY (`Tarea_idTarea`) REFERENCES `tarea` (`idTarea`);

--
-- Filtros para la tabla `proyecto`
--
ALTER TABLE `proyecto`
  ADD CONSTRAINT `fk_Proyecto_Estado_proyecto1` FOREIGN KEY (`Estado_proyecto_idEstado_proyecto`) REFERENCES `estado_proyecto` (`idEstado_proyecto`),
  ADD CONSTRAINT `fk_Proyecto_Tipo_Proyecto1` FOREIGN KEY (`Tipo_Proyecto_Tipo_de_Proyecto`) REFERENCES `tipo_proyecto` (`Tipo_de_Proyecto`);

--
-- Filtros para la tabla `proyecto_tiene_personas`
--
ALTER TABLE `proyecto_tiene_personas`
  ADD CONSTRAINT `fk_Proyecto_has_Persona_Persona1` FOREIGN KEY (`Persona_Cedula`) REFERENCES `persona` (`Cedula`),
  ADD CONSTRAINT `fk_Proyecto_has_Persona_Proyecto1` FOREIGN KEY (`Proyecto_idproyecto`) REFERENCES `proyecto` (`idproyecto`);

--
-- Filtros para la tabla `recurso`
--
ALTER TABLE `recurso`
  ADD CONSTRAINT `fk_Recurso_Disponibilidad1` FOREIGN KEY (`Disponibilidad_ID_Disponibilidad`) REFERENCES `disponibilidad` (`ID_Disponibilidad`),
  ADD CONSTRAINT `fk_Recurso_Tipo_Recurso1` FOREIGN KEY (`Tipo_Recurso_idTipo_Recurso`) REFERENCES `tipo_recurso` (`idTipo_Recurso`);

--
-- Filtros para la tabla `tarea`
--
ALTER TABLE `tarea`
  ADD CONSTRAINT `fk_Tarea_Estado_tarea1` FOREIGN KEY (`Estado_tarea_idEstado_tarea`) REFERENCES `estado_tarea` (`idEstado_tarea`),
  ADD CONSTRAINT `fk_Tarea_Proyecto1` FOREIGN KEY (`Proyecto_idproyecto`) REFERENCES `proyecto` (`idproyecto`),
  ADD CONSTRAINT `fk_Tarea_Recurso1` FOREIGN KEY (`Recurso_idRecurso`) REFERENCES `recurso` (`idRecurso`),
  ADD CONSTRAINT `fk_Tarea_Usuario1` FOREIGN KEY (`Usuario_idUsuario`) REFERENCES `usuario` (`idUsuario`);

--
-- Filtros para la tabla `tarea_tiene_personas`
--
ALTER TABLE `tarea_tiene_personas`
  ADD CONSTRAINT `tarea_tiene_personas_ibfk_1` FOREIGN KEY (`Tarea_idtarea`) REFERENCES `tarea` (`idTarea`),
  ADD CONSTRAINT `tarea_tiene_personas_ibfk_2` FOREIGN KEY (`Persona_Cedula`) REFERENCES `persona` (`Cedula`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `fk_Usuario_Estado_usuario1` FOREIGN KEY (`Estado_usuario_IDEstado_usuario`) REFERENCES `estado_usuario` (`IDEstado_usuario`),
  ADD CONSTRAINT `fk_Usuario_Persona1` FOREIGN KEY (`Persona_Cedula`) REFERENCES `persona` (`Cedula`),
  ADD CONSTRAINT `fk_Usuario_Rol_usuario1` FOREIGN KEY (`Rol_usuario_Tipo_usuario`) REFERENCES `rol_usuario` (`Tipo_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
