-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-05-2025 a las 06:09:23
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `plataforma_gestion_tareas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auditoria`
--

CREATE TABLE `auditoria` (
  `id` int(11) NOT NULL,
  `usuario` varchar(100) NOT NULL,
  `accion` varchar(50) NOT NULL,
  `tabla_afectada` varchar(100) NOT NULL,
  `registro_id` int(11) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `detalle` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `auditoria`
--

INSERT INTO `auditoria` (`id`, `usuario`, `accion`, `tabla_afectada`, `registro_id`, `fecha`, `detalle`) VALUES
(1, '123', 'INSERT', 'personas', 1, '2025-05-10 18:08:32', 'Nuevo usuario registrado: asfas dasd, DNI: 1231231234'),
(2, 'farid123123', 'INSERT', 'personas', 3, '2025-05-10 18:29:00', 'Nuevo usuario registrado: sadsadf asdfasdfasdf, DNI: 213123123'),
(3, '321', 'INSERT', 'personas', 4, '2025-05-10 18:32:14', 'Nuevo usuario registrado: sdfsdg dfgsd, DNI: fgsdfgsdfg'),
(4, 'fariduser', 'INSERT', 'personas', 6, '2025-05-10 19:26:52', 'Nuevo usuario registrado: Farid Farfan, DNI: 1233456768'),
(5, 'karen2321', 'INSERT', 'personas', 7, '2025-05-11 04:05:32', 'Nuevo usuario registrado: Karen Cuervo, DNI: 820398784');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_tareas`
--

CREATE TABLE `historial_tareas` (
  `id` int(11) NOT NULL,
  `id_tarea` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `accion` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `historial_tareas`
--

INSERT INTO `historial_tareas` (`id`, `id_tarea`, `id_usuario`, `accion`, `descripcion`, `fecha`) VALUES
(1, 1, 1, 'Asignación', 'Tarea asignada automáticamente al usuario con DNI fgsdfgsdfg', '2025-05-10 19:26:52'),
(2, 3, 4, 'Actualización', 'Tarea actualizada por el usuario ID 4', '2025-05-10 21:45:11'),
(3, 3, 4, 'Cambio de estado', 'Estado cambiado a \'Completado\'', '2025-05-10 21:57:02'),
(4, 3, 4, 'Cambio de estado', 'Estado cambiado a \'En progreso\'', '2025-05-10 21:57:04'),
(5, 3, 4, 'Cambio de estado', 'Estado cambiado a \'Completado\'', '2025-05-10 21:57:04'),
(6, 3, 4, 'Cambio de estado', 'Estado cambiado a \'Completado\'', '2025-05-10 21:57:06'),
(7, 3, 4, 'Cambio de estado', 'Estado cambiado a \'En progreso\'', '2025-05-10 21:57:07'),
(8, 3, 4, 'Cambio de estado', 'Estado cambiado a \'Completado\'', '2025-05-10 21:57:52'),
(9, 4, 4, 'Cambio de estado', 'Estado cambiado a \'En progreso\'', '2025-05-10 21:58:19'),
(10, 3, 4, 'Cambio de estado', 'Estado cambiado a \'En progreso\'', '2025-05-10 21:59:10'),
(11, 3, 4, 'Cambio de estado', 'Estado cambiado a \'Completado\'', '2025-05-10 21:59:11'),
(12, 3, 4, 'Cambio de estado', 'Estado cambiado a \'En progreso\'', '2025-05-10 21:59:12'),
(13, 3, 4, 'Cambio de estado', 'Estado cambiado a \'En progreso\'', '2025-05-10 21:59:23'),
(14, 3, 4, 'Cambio de estado', 'Estado cambiado a \'Completado\'', '2025-05-10 21:59:37'),
(15, 4, 4, 'Cambio de estado', 'Estado cambiado a \'Completado\'', '2025-05-10 21:59:38'),
(16, 6, 4, 'Cambio de estado', 'Estado cambiado a \'En progreso\'', '2025-05-10 22:54:35'),
(17, 3, 4, 'Actualización', 'Tarea actualizada por el usuario ID 4', '2025-05-10 23:04:47'),
(18, 3, 4, 'Actualización', 'Tarea actualizada por el usuario ID 4', '2025-05-10 23:04:53'),
(19, 3, 4, 'Cambio de estado', 'Estado cambiado a \'Completado\'', '2025-05-10 23:04:56'),
(20, 6, 4, 'Cambio de estado', 'Estado cambiado a \'Completado\'', '2025-05-10 23:04:56'),
(21, 7, 4, 'Cambio de estado', 'Estado cambiado a \'Completado\'', '2025-05-10 23:04:57'),
(22, 5, 4, 'Actualización', 'Tarea actualizada por el usuario ID 4', '2025-05-10 23:05:02'),
(23, 8, 4, 'Actualización', 'Tarea actualizada por el usuario ID 4', '2025-05-11 00:39:39'),
(24, 8, 4, 'Actualización', 'Tarea actualizada por el usuario ID 4', '2025-05-11 00:39:43'),
(25, 8, 4, 'Actualización', 'Tarea actualizada por el usuario ID 4', '2025-05-11 00:39:47'),
(26, 1, 1, 'Actualización', 'Tarea actualizada por el usuario ID 1', '2025-05-11 00:40:28'),
(27, 1, 1, 'Actualización', 'Tarea actualizada por el usuario ID 1', '2025-05-11 00:40:41'),
(28, 1, 4, 'Cambio de estado', 'Estado cambiado a \'Completado\'', '2025-05-11 00:43:01'),
(29, 1, 4, 'Cambio de estado', 'Estado cambiado a \'En progreso\'', '2025-05-11 00:43:08'),
(30, 1, 4, 'Cambio de estado', 'Estado cambiado a \'Completado\'', '2025-05-11 00:43:26'),
(31, 1, 4, 'Cambio de estado', 'Estado cambiado a \'En progreso\'', '2025-05-11 00:55:47'),
(32, 9, 4, 'Actualización', 'Tarea actualizada por el usuario ID 4', '2025-05-11 00:59:46'),
(33, 1, 4, 'Cambio de estado', 'Estado cambiado a \'Completado\'', '2025-05-11 01:02:30'),
(34, 5, 4, 'Cambio de estado', 'Estado cambiado a \'Completado\'', '2025-05-11 01:02:34'),
(35, 8, 4, 'Cambio de estado', 'Estado cambiado a \'Completado\'', '2025-05-11 01:02:38'),
(36, 9, 4, 'Cambio de estado', 'Estado cambiado a \'Completado\'', '2025-05-11 01:02:42'),
(37, 1, 4, 'Cambio de estado', 'Estado cambiado a \'En progreso\'', '2025-05-11 01:48:33'),
(38, 1, 4, 'Cambio de estado', 'Estado cambiado a \'En progreso\'', '2025-05-11 01:48:37'),
(39, 1, 4, 'Cambio de estado', 'Estado cambiado a \'En progreso\'', '2025-05-11 01:48:41'),
(40, 1, 4, 'Cambio de estado', 'Estado cambiado a \'En progreso\'', '2025-05-11 01:48:45'),
(41, 1, 4, 'Cambio de estado', 'Estado cambiado a \'En progreso\'', '2025-05-11 01:48:49'),
(42, 1, 4, 'Cambio de estado', 'Estado cambiado a \'En progreso\'', '2025-05-11 01:48:54'),
(43, 11, 4, 'Cambio de estado', 'Estado cambiado a \'Completado\'', '2025-05-11 01:57:02'),
(44, 10, 4, 'actualización', 'El usuario sdfsdg dfgsd actualizó la tarea: sdfasdf', '2025-05-11 02:05:04'),
(45, 15, 1, 'creada', NULL, '2025-05-11 03:27:11'),
(46, 16, 4, 'creación', 'El usuario sdfsdg dfgsd creó la tarea: SISAS NO', '2025-05-11 03:42:44'),
(47, 22, 7, 'creación', 'El usuario Karen Cuervo creó la tarea: Cambios en formulario', '2025-05-11 04:06:15'),
(48, 22, 7, 'Cambio de estado', 'Estado cambiado a \'En progreso\'', '2025-05-11 04:06:24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personas`
--

CREATE TABLE `personas` (
  `id_persona` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `dni` varchar(20) NOT NULL,
  `fecha_nac` date NOT NULL,
  `correo` varchar(150) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `personas`
--

INSERT INTO `personas` (`id_persona`, `nombre`, `apellido`, `dni`, `fecha_nac`, `correo`, `username`, `password`, `estado`) VALUES
(1, 'asfas', 'dasd', '1231231234', '2025-05-08', 'faridgomez2320@gmail.om', '123', '$2y$10$NLpPDRAWKHqr3d8Pvt.Vn.dxW045lsdt1undM40zh7rscf.TXw3de', 1),
(3, 'sadsadf', 'asdfasdfasdf', '213123123', '2025-05-07', 'faridgomez2320@gmail.om', 'farid123123', '$2y$10$FHAedzazb9Xqhp0GuNw.SOVRYYOY4r2rYgbYXVqNVZY2ieblmye3i', 1),
(4, 'sdfsdg', 'dfgsd', 'fgsdfgsdfg', '2025-05-19', 'fard@gmail.com', '321', '$2y$10$i16CXdxTged8rALhAxojfuLJwlFVRHgb/i7a8SAg21KL3pHhW3J/C', 1),
(6, 'Farid', 'Farfan', '1233456768', '2000-01-01', 'farid@example.com', 'fariduser', '$2y$10$e0NR0Z5r7ZpZK0z6Z5Z5Z.5Z5Z5Z5Z5Z5Z5Z5Z5Z5Z5Z5Z5Z5Z5Z5Z5Z5Z5Z5Z', 1),
(7, 'Karen', 'Cuervo', '820398784', '2025-05-12', 'karen@gmail.com', 'karen2321', '$2y$10$I0DvBgdefB7o9pSUeMLq1.CfMG.yPvdWqorfisJGX.YXqj6H9jMJS', 1);

--
-- Disparadores `personas`
--
DELIMITER $$
CREATE TRIGGER `tr_personas_delete` AFTER DELETE ON `personas` FOR EACH ROW BEGIN
  INSERT INTO auditoria (tabla_afectada, accion, registro_id, usuario, detalle)
  VALUES (
    'personas',
    'DELETE',
    OLD.id_persona,
    OLD.username,
    CONCAT('Usuario eliminado: ', OLD.nombre, ' ', OLD.apellido, ', DNI: ', OLD.dni)
  );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tr_personas_insert` AFTER INSERT ON `personas` FOR EACH ROW BEGIN
  INSERT INTO auditoria (tabla_afectada, accion, registro_id, usuario, detalle)
  VALUES (
    'personas',
    'INSERT',
    NEW.id_persona,
    NEW.username,
    CONCAT('Nuevo usuario registrado: ', NEW.nombre, ' ', NEW.apellido, ', DNI: ', NEW.dni)
  );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tr_personas_update` AFTER UPDATE ON `personas` FOR EACH ROW BEGIN
  INSERT INTO auditoria (tabla_afectada, accion, registro_id, usuario, detalle)
  VALUES (
    'personas',
    'UPDATE',
    NEW.id_persona,
    NEW.username,
    CONCAT('Modificado: de ', OLD.nombre, ' ', OLD.apellido, ' a ', NEW.nombre, ' ', NEW.apellido)
  );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tareas`
--

CREATE TABLE `tareas` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `estado` enum('Pendiente','En progreso','Completado') DEFAULT 'Pendiente',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_limite` date DEFAULT NULL,
  `creado_por` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tareas`
--

INSERT INTO `tareas` (`id`, `titulo`, `descripcion`, `estado`, `fecha_creacion`, `fecha_limite`, `creado_por`) VALUES
(1, 'sadaasd', 'Leer y comentar el documento actualizado', 'En progreso', '2025-05-10 19:13:01', '2025-05-20', NULL),
(3, 'Revisar documentación', 'Revisar y actualizar la documentación del proyecto', 'Completado', '2025-05-10 20:22:35', '2025-05-15', 4),
(4, 'fg', 'dsfgsdfg', 'Completado', '2025-05-10 21:17:40', '2025-05-10', 4),
(5, 'asd', 'asdasd', 'Completado', '2025-05-10 22:08:11', '2025-05-09', 4),
(6, 'dfgsdf', 'gsdfgsdfg', 'Completado', '2025-05-10 22:46:05', '2025-05-15', 4),
(7, 'Ticket para oferta', 'asdasf', 'Completado', '2025-05-10 22:46:41', '2025-05-16', 4),
(8, 'FARID', 'ASDFASD', 'Completado', '2025-05-11 00:34:04', '2025-05-14', 4),
(9, 'PRUEBA 2', 'asfasdasd', 'Completado', '2025-05-11 00:59:28', '2025-05-15', 4),
(10, 'sdfasdf', 'sisasno', 'Pendiente', '2025-05-11 01:11:06', '2025-05-16', 4),
(11, 'PRUEBA 4', 'asdasd', 'Completado', '2025-05-11 01:11:40', '2025-05-19', 1),
(13, 'Diseñar interfaz de usuario', 'Crear el prototipo en Figma', 'Pendiente', '2025-05-11 03:24:54', '2025-05-15', 1),
(14, 'Diseñar interfaz de usuario', 'Crear el prototipo en Figma', 'Pendiente', '2025-05-11 03:25:08', '2025-05-15', 1),
(15, 'Diseñar interfaz de usuario', 'Crear el prototipo en Figma', 'Pendiente', '2025-05-11 03:27:11', '2025-05-15', 1),
(16, 'SISAS NO', 'GDGSD', 'Pendiente', '2025-05-11 03:42:40', '2025-05-08', 4),
(17, 'Revisar documentación', 'Leer y comentar el documento técnico', 'Pendiente', '2025-05-11 03:44:14', '2025-05-15', 1),
(18, 'Revisar documentación', 'Leer y comentar el documento técnico', 'Pendiente', '2025-05-11 03:46:38', '2025-05-15', 1),
(19, 'Revisar documentación', 'Leer y comentar el documento técnico', 'Pendiente', '2025-05-11 03:49:00', '2025-05-15', 1),
(20, 'Revisar documentación', 'Leer y comentar el documento técnico', 'Pendiente', '2025-05-11 03:49:21', '2025-05-15', 1),
(21, 'prueba endpoint', 'Leer y comentar el documento técnico', 'Pendiente', '2025-05-11 03:49:30', '2025-05-15', 1),
(22, 'Cambios en formulario', 'ajustar campos', 'En progreso', '2025-05-11 04:06:11', '2025-05-09', 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tarea_usuario`
--

CREATE TABLE `tarea_usuario` (
  `id` int(11) NOT NULL,
  `id_tarea` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tarea_usuario`
--

INSERT INTO `tarea_usuario` (`id`, `id_tarea`, `id_usuario`) VALUES
(1, NULL, NULL),
(3, 3, 4),
(4, 4, 4),
(5, 5, 4),
(6, 6, 4),
(7, 6, 1),
(8, 6, 6),
(9, 7, 4),
(11, 3, 1),
(12, 3, 3),
(13, 5, 6),
(14, 8, 4),
(19, 1, 4),
(20, 9, 4),
(22, 9, 1),
(23, 10, 4),
(24, 11, 1),
(26, 11, 4),
(27, 1, 1),
(28, 1, 3),
(29, 5, 3),
(30, 16, 4),
(31, 22, 7);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `auditoria`
--
ALTER TABLE `auditoria`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `historial_tareas`
--
ALTER TABLE `historial_tareas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_tarea` (`id_tarea`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `personas`
--
ALTER TABLE `personas`
  ADD PRIMARY KEY (`id_persona`),
  ADD UNIQUE KEY `dni` (`dni`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indices de la tabla `tareas`
--
ALTER TABLE `tareas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `creado_por` (`creado_por`);

--
-- Indices de la tabla `tarea_usuario`
--
ALTER TABLE `tarea_usuario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_tarea` (`id_tarea`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `auditoria`
--
ALTER TABLE `auditoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `historial_tareas`
--
ALTER TABLE `historial_tareas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT de la tabla `personas`
--
ALTER TABLE `personas`
  MODIFY `id_persona` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `tareas`
--
ALTER TABLE `tareas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `tarea_usuario`
--
ALTER TABLE `tarea_usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `historial_tareas`
--
ALTER TABLE `historial_tareas`
  ADD CONSTRAINT `historial_tareas_ibfk_1` FOREIGN KEY (`id_tarea`) REFERENCES `tareas` (`id`),
  ADD CONSTRAINT `historial_tareas_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `personas` (`id_persona`);

--
-- Filtros para la tabla `tareas`
--
ALTER TABLE `tareas`
  ADD CONSTRAINT `tareas_ibfk_1` FOREIGN KEY (`creado_por`) REFERENCES `personas` (`id_persona`);

--
-- Filtros para la tabla `tarea_usuario`
--
ALTER TABLE `tarea_usuario`
  ADD CONSTRAINT `tarea_usuario_ibfk_1` FOREIGN KEY (`id_tarea`) REFERENCES `tareas` (`id`),
  ADD CONSTRAINT `tarea_usuario_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `personas` (`id_persona`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
