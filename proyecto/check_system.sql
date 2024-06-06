-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-05-2023 a las 15:29:53
-- Versión del servidor: 10.4.27-MariaDB
-- Versión de PHP: 8.0.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `check_system`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargo`
--

CREATE TABLE `cargo` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cargo`
--

INSERT INTO `cargo` (`id`, `descripcion`) VALUES
(1, 'Administrador'),
(2, 'Usuario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial`
--

CREATE TABLE `historial` (
  `codigo_producto` int(11) NOT NULL,
  `nombre_producto` varchar(255) NOT NULL,
  `caja_facturada` int(11) NOT NULL,
  `caja_recibida` int(11) DEFAULT 0,
  `precio_unidades` float NOT NULL,
  `producto_recibido` varchar(255) DEFAULT 'not',
  `fecha_entrada` date NOT NULL,
  `unidades_caja` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `historial`
--

INSERT INTO `historial` (`codigo_producto`, `nombre_producto`, `caja_facturada`, `caja_recibida`, `precio_unidades`, `producto_recibido`, `fecha_entrada`, `unidades_caja`) VALUES
(1, 'coca-cola', 2, 2, 0.59, 'ok', '2023-05-11', 6),
(2, 'sprite', 1, 1, 0.59, 'ok', '2023-05-11', 6),
(4, 'pepsi', 3, 0, 0.59, 'not', '2023-05-11', 6),
(6, 'aquarius', 2, 0, 0.59, 'not', '2023-05-11', 6),
(8, 'bezoya', 0, 1, 0.59, 'new', '2023-05-15', 6),
(10, 'nestea', 4, 3, 0.59, 'ok', '2023-05-11', 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos_comprobados`
--

CREATE TABLE `productos_comprobados` (
  `nombre_producto` varchar(255) DEFAULT NULL,
  `caja_recibida` int(11) DEFAULT 0,
  `codigo_producto_factura` int(11) DEFAULT NULL,
  `codigo_producto_tienda` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos_factura`
--

CREATE TABLE `productos_factura` (
  `codigo_producto` int(11) NOT NULL,
  `nombre_producto` varchar(255) NOT NULL,
  `unidades_caja` int(11) NOT NULL,
  `precio_unidades` float NOT NULL,
  `caja_facturada` int(11) NOT NULL,
  `fecha_entrada` date NOT NULL,
  `producto_recibido` varchar(255) DEFAULT 'not',
  `caja_recibida` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos_factura`
--

INSERT INTO `productos_factura` (`codigo_producto`, `nombre_producto`, `unidades_caja`, `precio_unidades`, `caja_facturada`, `fecha_entrada`, `producto_recibido`, `caja_recibida`) VALUES
(1, 'coca-cola', 6, 0.59, 2, '2023-05-11', 'not', 0),
(2, 'sprite', 6, 0.59, 1, '2023-05-11', 'not', 0),
(4, 'pepsi', 6, 0.59, 3, '2023-05-11', 'not', 0),
(6, 'aquarius', 6, 0.59, 2, '2023-05-11', 'not', 0),
(10, 'nestea', 6, 0.59, 4, '2023-05-11', 'not', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos_tienda`
--

CREATE TABLE `productos_tienda` (
  `codigo_producto` int(11) NOT NULL,
  `nombre_producto` varchar(255) NOT NULL,
  `unidades_tienda` int(11) NOT NULL,
  `precio_unidades` float NOT NULL,
  `unidades_caja` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos_tienda`
--

INSERT INTO `productos_tienda` (`codigo_producto`, `nombre_producto`, `unidades_tienda`, `precio_unidades`, `unidades_caja`) VALUES
(1, 'coca-cola', 0, 0.59, 6),
(2, 'sprite', 10, 0.59, 6),
(3, 'fanta', 0, 0.59, 6),
(4, 'pepsi', 0, 0.59, 6),
(5, 'red-bull', 0, 0.59, 6),
(6, 'aquarius', 36, 0.59, 6),
(7, 'font-vella', 0, 0.59, 6),
(8, 'bezoya', 0, 0.59, 6),
(9, 'aquarel', 0, 0.59, 6),
(10, 'nestea', 0, 0.59, 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `usuario` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `id_cargo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`usuario`, `pass`, `nombre`, `id_cargo`) VALUES
('yuri', '0811', 'Yuri', 1),
('paul', '1234', 'Paul', 2);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cargo`
--
ALTER TABLE `cargo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `historial`
--
ALTER TABLE `historial`
  ADD PRIMARY KEY (`codigo_producto`,`fecha_entrada`);

--
-- Indices de la tabla `productos_comprobados`
--
ALTER TABLE `productos_comprobados`
  ADD KEY `codigo_producto_factura` (`codigo_producto_factura`),
  ADD KEY `codigo_producto_tienda` (`codigo_producto_tienda`);

--
-- Indices de la tabla `productos_factura`
--
ALTER TABLE `productos_factura`
  ADD PRIMARY KEY (`codigo_producto`);

--
-- Indices de la tabla `productos_tienda`
--
ALTER TABLE `productos_tienda`
  ADD PRIMARY KEY (`codigo_producto`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`pass`),
  ADD KEY `fk_usuario_cargo` (`id_cargo`);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `productos_comprobados`
--
ALTER TABLE `productos_comprobados`
  ADD CONSTRAINT `productos_comprobados_ibfk_1` FOREIGN KEY (`codigo_producto_factura`) REFERENCES `productos_factura` (`codigo_producto`),
  ADD CONSTRAINT `productos_comprobados_ibfk_2` FOREIGN KEY (`codigo_producto_tienda`) REFERENCES `productos_tienda` (`codigo_producto`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `fk_usuario_cargo` FOREIGN KEY (`id_cargo`) REFERENCES `cargo` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
