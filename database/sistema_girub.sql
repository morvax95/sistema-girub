-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-04-2019 a las 05:28:36
-- Versión del servidor: 5.6.21
-- Versión de PHP: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";



/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `sistema_girub`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `acceso`
--

CREATE TABLE IF NOT EXISTS `acceso` (
`id` bigint(20) NOT NULL,
  `menu_id` bigint(20) DEFAULT NULL,
  `usuario_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=170 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `acceso`
--

INSERT INTO `acceso` (`id`, `menu_id`, `usuario_id`) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 3, 1),
(4, 4, 1),
(5, 5, 1),
(6, 6, 1),
(7, 7, 1),
(8, 8, 1),
(9, 9, 1),
(10, 10, 1),
(11, 11, 1),
(12, 12, 1),
(13, 13, 1),
(14, 14, 1),
(15, 15, 1),
(16, 16, 1),
(17, 17, 1),
(18, 18, 1),
(19, 19, 1),
(20, 20, 1),
(21, 21, 1),
(22, 22, 1),
(23, 23, 1),
(24, 24, 1),
(25, 25, 1),
(26, 26, 1),
(114, 1, 3),
(115, 7, 3),
(116, 2, 3),
(117, 8, 3),
(118, 9, 3),
(119, 4, 3),
(120, 11, 3),
(121, 12, 3),
(122, 6, 3),
(123, 17, 3),
(124, 31, 3),
(125, 33, 3),
(126, 18, 3),
(127, 34, 3),
(128, 1, 2),
(129, 7, 2),
(130, 2, 2),
(131, 8, 2),
(132, 9, 2),
(133, 4, 2),
(134, 11, 2),
(135, 12, 2),
(136, 1, 5),
(137, 7, 5),
(138, 2, 5),
(139, 8, 5),
(140, 9, 5),
(141, 33, 5),
(142, 18, 5),
(143, 34, 5),
(144, 1, 6),
(145, 7, 6),
(146, 2, 6),
(147, 8, 6),
(148, 9, 6),
(149, 33, 6),
(150, 18, 6),
(151, 34, 6),
(152, 2, 7),
(153, 8, 7),
(154, 9, 7),
(155, 1, 4),
(156, 7, 4),
(157, 2, 4),
(158, 8, 4),
(159, 9, 4),
(160, 6, 4),
(161, 17, 4),
(162, 28, 4),
(163, 31, 4),
(164, 33, 4),
(165, 18, 4),
(166, 34, 4),
(167, 2, 8),
(168, 8, 8),
(169, 9, 8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `almacen`
--

CREATE TABLE IF NOT EXISTS `almacen` (
`id` bigint(20) NOT NULL,
  `descripcion` text,
  `estado` smallint(6) DEFAULT NULL,
  `tipo_almacen` smallint(6) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `almacen`
--

INSERT INTO `almacen` (`id`, `descripcion`, `estado`, `tipo_almacen`) VALUES
(2, 'Almacen fabrica', 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `caja`
--

CREATE TABLE IF NOT EXISTS `caja` (
`id` bigint(20) NOT NULL,
  `descripcion` text,
  `sucursal_id` bigint(20) NOT NULL,
  `estado` smallint(6) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `caja`
--

INSERT INTO `caja` (`id`, `descripcion`, `sucursal_id`, `estado`) VALUES
(1, 'APERTURA CAJA', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargo`
--

CREATE TABLE IF NOT EXISTS `cargo` (
`id` bigint(20) NOT NULL,
  `descripcion` text
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `cargo`
--

INSERT INTO `cargo` (`id`, `descripcion`) VALUES
(1, 'ADMINISTRADOR'),
(2, 'VENDEDOR'),
(3, 'OTRO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria_interna`
--

CREATE TABLE IF NOT EXISTS `categoria_interna` (
`id` bigint(20) NOT NULL,
  `descripcion` text
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `categoria_interna`
--

INSERT INTO `categoria_interna` (`id`, `descripcion`) VALUES
(1, 'CATEGORIA 1'),
(2, 'GASEOSAS');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cierre_sesion`
--

CREATE TABLE IF NOT EXISTS `cierre_sesion` (
`id` bigint(20) NOT NULL,
  `fecha` datetime DEFAULT NULL,
  `sesion_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `cierre_sesion`
--

INSERT INTO `cierre_sesion` (`id`, `fecha`, `sesion_id`) VALUES
(1, '2019-02-01 03:50:18', 1),
(2, '2019-02-01 10:48:07', 2),
(3, '2019-02-02 10:02:36', 3),
(4, '2019-02-02 10:04:09', 4),
(5, '2019-02-02 10:17:06', 5),
(6, '2019-02-02 11:02:49', 6),
(7, '2019-02-02 11:18:29', 7),
(8, '2019-02-02 11:51:36', 8),
(9, '2019-02-03 10:57:31', 9),
(10, '2019-02-03 22:33:42', 10),
(11, '2019-02-03 23:20:37', 11),
(12, '2019-02-03 23:23:30', 12),
(13, '2019-02-03 23:27:53', 13),
(14, '2019-02-03 23:31:20', 14),
(15, '2019-02-03 23:31:45', 15),
(16, '2019-02-03 23:36:20', 16),
(17, '2019-02-03 23:38:28', 17),
(18, '2019-02-04 14:29:19', 18),
(19, '2019-02-04 14:34:07', 19),
(20, '2019-02-04 14:35:16', 20),
(21, '2019-02-04 15:07:10', 21),
(22, '2019-02-04 15:09:08', 22),
(23, '2019-02-05 12:13:52', 23),
(24, '2019-02-05 16:02:03', 24),
(25, '2019-02-05 19:43:47', 25),
(26, '2019-02-05 19:46:57', 26),
(27, '2019-02-05 19:56:44', 27),
(28, '2019-02-06 15:15:16', 28),
(29, '2019-02-06 15:18:18', 29),
(30, '2019-02-06 15:21:01', 30),
(31, '2019-02-06 15:37:20', 31),
(32, '2019-02-06 19:58:48', 32),
(33, '2019-02-07 11:18:48', 33),
(34, '2019-02-07 16:38:55', 34),
(35, '2019-02-07 16:43:47', 35),
(36, '2019-02-07 17:04:19', 36),
(37, '2019-02-08 01:45:38', 37),
(38, '2019-02-11 10:28:42', 38),
(39, '2019-02-11 10:31:34', 39),
(40, '2019-02-11 10:32:38', 40),
(41, '2019-02-11 10:34:30', 41),
(42, '2019-02-17 14:13:42', 42),
(43, '2019-02-17 16:24:54', 43),
(44, '2019-02-19 19:51:33', 44),
(45, '2019-03-24 14:19:44', 45),
(46, '2019-04-08 13:51:49', 46),
(47, '2019-04-10 06:48:19', 47),
(48, '2019-04-10 13:50:43', 48),
(49, '2019-04-10 14:06:05', 49),
(50, '2019-04-10 14:12:47', 50),
(51, '2019-04-11 22:29:00', 51),
(52, '2019-04-11 22:59:11', 52),
(53, '2019-04-11 23:00:28', 53),
(54, '2019-04-11 23:01:55', 54),
(55, '2019-04-11 23:02:29', 55),
(56, '2019-04-11 23:02:47', 56),
(57, '2019-04-11 23:03:10', 57),
(58, '2019-04-11 23:04:49', 58),
(59, '2019-04-11 23:17:32', 59);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE IF NOT EXISTS `cliente` (
`id` bigint(20) NOT NULL,
  `ci_nit` text,
  `nombre_cliente` text,
  `telefono` text,
  `fecha_nacimiento` datetime DEFAULT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  `trabajo` text,
  `estado` smallint(6) DEFAULT NULL,
  `correo` text,
  `direccion` text,
  `usuario_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`id`, `ci_nit`, `nombre_cliente`, `telefono`, `fecha_nacimiento`, `fecha_registro`, `fecha_modificacion`, `trabajo`, `estado`, `correo`, `direccion`, `usuario_id`) VALUES
(1, '11386004 sc', 'ALICIA ROCABADO VARGAS', '787879899', NULL, '2018-08-18 00:33:27', '2019-01-30 20:50:48', '', 1, 'alicia@gmail.com', 'AV BENI TERCER ANILLO', 1),
(2, '78787965 sc', 'JUANA SEAGARRA SOLIZ', '78798795', NULL, '2018-08-20 12:19:45', '2019-01-09 18:27:28', '', 1, 'juanita_soliz_45@outlook.com', 'CALLE ANTONIO DE ROJAS 3ER ANILLO', 1),
(3, '76543421 sc', 'CARMEN SOTO PEREIRA', '784545445', NULL, '2018-08-21 18:31:04', '2019-02-05 11:19:45', '', 1, 'carmen_soto@gmail.com', 'CALLE 9', 1),
(4, '787545442 sc', 'MIKAELA SOZA', '7854544', NULL, '2018-11-07 17:27:39', '2019-01-09 18:28:44', '', 1, 'mikaela123@gmail.com', 'AVENIDA SANTA CRUZ N.-567', 1),
(5, '313454546 sc', 'FERNANDA CONTRERAS BARROSO', '70208802', NULL, '2018-11-07 17:45:02', '2019-01-09 18:31:36', '', 1, 'fernanda_barroso@gmail.com', 'AV. CENTINELAS DEL CHACO N.-89', 1),
(6, '987854321 sc', 'JAVIER PEÑA ALVAREZ', '69095743', NULL, '2018-12-21 09:28:14', '2019-01-09 18:26:28', '', 1, 'javi_alvarez@gmail.com', 'CALLE BUENOS AIRES', 1),
(7, '655654456 sc', 'XIMENA MOLINA FLORES', '65765444', NULL, '2018-12-21 10:19:27', '2019-01-09 18:30:09', '', 1, 'ximena_flores@gmail.com', 'CALLE 98 5TO ANILLO ', 1),
(8, '1214548 cbba', 'FABIOLA BALTAZAR', '767657657', NULL, '2018-12-21 10:25:12', '2019-01-09 18:25:39', '', 1, 'fabiola1999@gmail.com', 'CALLE AYACUCHO ESQUINA ARENALES', 1),
(9, '56667886 sc', 'EDUARDO MEDINA TORRICO', '76543333', NULL, '2018-12-21 10:36:05', '2019-01-09 18:30:52', '', 1, 'eduardo_medina@gmail.com', 'CALLE PUERTO RICO N.-90', 1),
(10, '784544456 sc', 'CARLA MENDOZA CUELLAR', '787875465', NULL, '2018-12-23 02:00:20', '2019-02-05 11:20:59', '', 1, 'carla@gmail.com', 'AV BENI', 1),
(11, '787445454 sc', 'YERALDINE GONZALES', '789745489', NULL, '2018-12-23 19:17:10', '2019-01-09 18:29:19', '', 1, 'yeraldine_gonzales@gmail.com', 'CALLE COLOMBIA N.-89', 1),
(12, '78887445 lpz', 'MARIA LOPEZ LOPEZ', '64545446', NULL, '2018-12-24 03:36:19', '2019-02-05 11:23:18', '', 1, 'maria_lopez123@hotmail.com', 'CALLE TOBOROCHI ESQUINA CALLE 5', 1),
(13, '78787878', 'FLAVIA SANCHEZ', '7676776', NULL, '2019-01-15 20:32:19', '2019-01-29 11:15:09', '', 1, 'RENAN@GMAIL.COM', 'AV. BENI N.695', 1),
(14, '11386005 sc', 'JUAN CARLOS  MORALES', '755412111', NULL, '2019-01-29 12:50:51', '2019-02-05 11:24:08', '', 1, 'juan@gmail.com', 'AV PARAGUA N.3830', 1),
(15, '63978781 sc', 'KEYLA ARLET PINTO', '76765765', NULL, '2019-01-29 21:42:25', '2019-02-05 11:22:58', '', 1, 'keyla@gmail.com', 'CALLE MIRAFLORES 45', 1),
(16, '4578454', 'JUANA SEAGARRA SOLIZ', '69095754', NULL, '2019-02-04 20:52:40', '2019-02-01 03:45:23', '', 1, '', '', NULL),
(17, '745884255 sc', 'NAOMY MOJICA FLORES', '657757669', NULL, '2019-02-04 20:52:40', '2019-02-05 11:19:14', '', 1, 'namy@gmail.com', 'BARRIO PALMASOLA', 1),
(18, '11386005 sc', 'FERNANDA RIOS GAMARRA', '69095758', NULL, '2019-02-04 11:01:13', '2019-02-05 11:19:59', '', 1, 'fernanda@gmail.com', 'AV BENI', 1),
(19, '413860055 sc', 'JUANA PADILLA MERIDA', '755846121', NULL, '2019-02-04 11:02:00', '2019-02-05 11:02:00', '', 1, 'fernanda@gmail.com', 'CALLE TOBOROCHI', 1),
(20, '121254545 lp', 'SOLEDAD PEREZ DAVALOS', '690442447', NULL, '2019-02-04 11:03:03', '2019-02-05 11:03:03', '', 1, 'soledad@gmail.com', 'CALLE BUENOS AIRES 694', 1),
(21, '785454445 SC', 'MARIA DE LOS ANGELES PONCE', '697454111', NULL, '2019-02-04 11:06:25', '2019-02-05 11:06:25', '', 1, 'angela@gmail.com', 'CALLE MIRAFLORES 45', 1),
(22, '4121244544', 'JUAN JOSE SOLETO JIMENEZ', '69512124512', NULL, '2019-02-04 11:16:24', '2019-02-05 11:20:18', '', 1, 'juanjose@gmail.com', 'AV. PARAGUA', 1),
(23, '7687686', 'ELIANA SIUAREZ', '7676766', NULL, '2019-02-05 19:44:54', '2019-02-05 19:44:54', '', 1, '', '', 1),
(24, '765765', 'JUAN CARLOS', '7676868686', NULL, '2019-04-08 13:46:37', '2019-04-08 13:46:37', '', 1, NULL, '', 1),
(25, '7867867866', 'ARIEL', '787687', NULL, '2019-04-10 13:54:16', '2019-04-10 13:54:16', '', 1, NULL, '', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `color`
--

CREATE TABLE IF NOT EXISTS `color` (
`id` bigint(20) NOT NULL,
  `descripcion` text
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `color`
--

INSERT INTO `color` (`id`, `descripcion`) VALUES
(1, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra`
--

CREATE TABLE IF NOT EXISTS `compra` (
`id` bigint(20) NOT NULL,
  `fecha_compra` datetime DEFAULT NULL,
  `observacion` text,
  `subtotal` decimal(20,0) DEFAULT NULL,
  `descuento` decimal(20,0) DEFAULT NULL,
  `monto_total` decimal(20,0) DEFAULT NULL,
  `estado` smallint(6) DEFAULT NULL,
  `proveedor_id` bigint(20) DEFAULT NULL,
  `usuario_id` bigint(20) DEFAULT NULL,
  `sucursal_id` bigint(20) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `nro_fiscal` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_caja`
--

CREATE TABLE IF NOT EXISTS `detalle_caja` (
`id` bigint(20) NOT NULL,
  `caja_id` bigint(20) DEFAULT NULL,
  `sucursal_id` bigint(20) DEFAULT NULL,
  `usuario_id` bigint(20) DEFAULT NULL,
  `fecha_apertura` datetime DEFAULT NULL,
  `monto_apertura` decimal(20,2) DEFAULT NULL,
  `fecha_cierre` datetime DEFAULT NULL,
  `monto_cierre` decimal(20,2) DEFAULT NULL,
  `estado` text
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `detalle_caja`
--

INSERT INTO `detalle_caja` (`id`, `caja_id`, `sucursal_id`, `usuario_id`, `fecha_apertura`, `monto_apertura`, `fecha_cierre`, `monto_cierre`, `estado`) VALUES
(1, 1, 1, 1, '2019-02-01 00:00:00', '500.00', NULL, NULL, '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_compra`
--

CREATE TABLE IF NOT EXISTS `detalle_compra` (
`id` bigint(20) NOT NULL,
  `producto_id` bigint(20) DEFAULT NULL,
  `compra_id` bigint(20) DEFAULT NULL,
  `unidad_id` bigint(20) DEFAULT NULL,
  `cantidad` bigint(20) DEFAULT NULL,
  `precio_compra` decimal(20,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_producto_ingreso`
--

CREATE TABLE IF NOT EXISTS `detalle_producto_ingreso` (
`id` bigint(20) NOT NULL,
  `ingreso_id` bigint(20) NOT NULL,
  `producto_id` bigint(20) NOT NULL,
  `cantidad` bigint(20) DEFAULT NULL,
  `cantidad_produccion` bigint(20) NOT NULL,
  `cantidad_ingresada` bigint(20) NOT NULL,
  `precio_compra` bigint(20) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `detalle_producto_ingreso`
--

INSERT INTO `detalle_producto_ingreso` (`id`, `ingreso_id`, `producto_id`, `cantidad`, `cantidad_produccion`, `cantidad_ingresada`, `precio_compra`) VALUES
(1, 1, 1, 10, 0, 10, 50),
(2, 2, 2, 5, 0, 5, 150),
(3, 1, 1, 25, 0, 25, 12),
(4, 1, 2, 25, 0, 25, 10),
(5, 1, 3, 30, 0, 30, 11),
(6, 2, 4, 40, 0, 40, 15);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_proforma`
--

CREATE TABLE IF NOT EXISTS `detalle_proforma` (
`id` bigint(20) NOT NULL,
  `proforma_id` bigint(20) DEFAULT NULL,
  `producto_id` bigint(20) DEFAULT NULL,
  `talla_id` bigint(20) DEFAULT NULL,
  `color_id` bigint(20) DEFAULT NULL,
  `cantidad` bigint(20) DEFAULT NULL,
  `precio_venta` decimal(20,2) DEFAULT NULL,
  `inventario_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `detalle_proforma`
--

INSERT INTO `detalle_proforma` (`id`, `proforma_id`, `producto_id`, `talla_id`, `color_id`, `cantidad`, `precio_venta`, `inventario_id`) VALUES
(1, 1, 1, 26, 1, 100, '12.00', NULL),
(2, 1, 2, 26, 1, 10, '10.00', NULL),
(3, 1, 3, 26, 1, 10, '11.00', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_salida_inventario`
--

CREATE TABLE IF NOT EXISTS `detalle_salida_inventario` (
  `id` bigint(20) DEFAULT NULL,
  `salida_inventario_id` bigint(20) NOT NULL,
  `producto_inventario_id` bigint(20) NOT NULL,
  `cantidad` bigint(20) DEFAULT NULL,
  `estado` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_venta`
--

CREATE TABLE IF NOT EXISTS `detalle_venta` (
`id` bigint(20) NOT NULL,
  `venta_id` bigint(20) DEFAULT NULL,
  `producto_id` bigint(20) DEFAULT NULL,
  `talla_id` bigint(20) DEFAULT NULL,
  `color_id` bigint(20) DEFAULT NULL,
  `cantidad` bigint(20) DEFAULT NULL,
  `cantidad_produccion` bigint(20) NOT NULL,
  `precio_venta` decimal(20,2) DEFAULT NULL,
  `estado_entrega` bigint(20) NOT NULL,
  `inventario_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `detalle_venta`
--

INSERT INTO `detalle_venta` (`id`, `venta_id`, `producto_id`, `talla_id`, `color_id`, `cantidad`, `cantidad_produccion`, `precio_venta`, `estado_entrega`, `inventario_id`) VALUES
(1, 1, 1, 26, 1, 1, 0, '12.00', 5, 1),
(2, 1, 2, 26, 1, 1, 0, '10.00', 5, 2),
(3, 2, 3, 26, 1, 2, 0, '11.00', 5, 3),
(4, 3, 4, 26, 1, 2, 0, '15.00', 5, 4),
(5, 3, 3, 26, 1, 1, 0, '11.00', 5, 3),
(6, 4, 1, 26, 1, 1, 0, '12.00', 5, 1),
(7, 4, 4, 26, 1, 1, 0, '15.00', 5, 4),
(8, 5, 3, 26, 1, 2, 0, '11.00', 5, 3),
(9, 5, 1, 26, 1, 1, 0, '12.00', 5, 1),
(10, 6, 2, 26, 1, 4, 0, '10.00', 5, 2),
(11, 7, 1, 26, 1, 1, 0, '12.00', 5, 1),
(12, 7, 3, 26, 1, 2, 0, '11.00', 5, 3);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `deudas_por_cobrar`
--
CREATE TABLE IF NOT EXISTS `deudas_por_cobrar` (
`id` bigint(20)
,`fecha` datetime
,`cliente_id` bigint(20)
,`nombre_cliente` text
,`total` decimal(20,2)
,`total_pagado` decimal(42,2)
,`saldo` decimal(43,2)
,`estado` text
,`nro_venta` bigint(20)
);
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `egreso_caja`
--

CREATE TABLE IF NOT EXISTS `egreso_caja` (
`id` bigint(20) NOT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `fecha_egreso` datetime DEFAULT NULL,
  `detalle` text,
  `monto` decimal(20,2) DEFAULT NULL,
  `estado` smallint(6) DEFAULT NULL,
  `tipo_egreso_id` bigint(20) DEFAULT NULL,
  `sucursal_id` bigint(20) DEFAULT NULL,
  `usuario_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `egreso_caja`
--

INSERT INTO `egreso_caja` (`id`, `fecha_registro`, `fecha_egreso`, `detalle`, `monto`, `estado`, `tipo_egreso_id`, `sucursal_id`, `usuario_id`) VALUES
(1, '2019-02-01 00:00:00', '2019-02-01 00:00:00', '', '6000.00', 1, 3, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `egreso_compra`
--

CREATE TABLE IF NOT EXISTS `egreso_compra` (
  `compra_id` bigint(20) NOT NULL,
  `egreso_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `escenario`
--

CREATE TABLE IF NOT EXISTS `escenario` (
`id` int(11) NOT NULL,
  `nombre_escenario` text COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` text COLLATE utf8_spanish_ci NOT NULL,
  `numeroJugadores` int(11) NOT NULL,
  `fecha_registro` datetime NOT NULL,
  `fecha_modificacion` datetime NOT NULL,
  `estado` smallint(6) NOT NULL,
  `tipo_escenario_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `escenario`
--

INSERT INTO `escenario` (`id`, `nombre_escenario`, `descripcion`, `numeroJugadores`, `fecha_registro`, `fecha_modificacion`, `estado`, `tipo_escenario_id`) VALUES
(1, 'CANCHA SAN PABLO II', 'CANCHA SIN TECHO DE 5 JUGADORES', 5, '2019-02-07 00:00:00', '2019-02-07 11:16:38', 1, 1),
(2, 'CANCHA SAN ANDITA', 'CANCHA DE CÉSPED SINTETICO', 7, '2019-02-07 00:00:00', '2019-02-07 11:17:49', 1, 1),
(4, 'CANCHA EL PARAISO', 'CANCHA POLIFUNCIONAL V', 8, '2019-02-07 10:35:40', '2019-02-07 11:18:01', 1, 2),
(5, 'CANCHA SAN IGNACIO', 'CANCHA POR ESTRENAR', 6, '2019-02-07 11:05:31', '0000-00-00 00:00:00', 1, 2),
(6, 'CANCHA MUNICIPAL', 'NINGUNA', 8, '2019-04-10 06:45:41', '0000-00-00 00:00:00', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `forma_pago`
--

CREATE TABLE IF NOT EXISTS `forma_pago` (
`id` bigint(20) NOT NULL,
  `descripcion` text,
  `estado` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingreso_caja`
--

CREATE TABLE IF NOT EXISTS `ingreso_caja` (
`id` bigint(20) NOT NULL,
  `fecha_ingreso` datetime DEFAULT NULL,
  `detalle` text,
  `monto` decimal(20,2) DEFAULT NULL,
  `estado` smallint(6) DEFAULT NULL,
  `tipo_ingreso_id` bigint(20) DEFAULT NULL,
  `sucursal_id` bigint(20) DEFAULT NULL,
  `usuario_id` bigint(20) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `ingreso_caja`
--

INSERT INTO `ingreso_caja` (`id`, `fecha_ingreso`, `detalle`, `monto`, `estado`, `tipo_ingreso_id`, `sucursal_id`, `usuario_id`, `fecha_registro`) VALUES
(1, '2019-02-05 00:00:00', 'Ingreso por venta generado automaticamente', '22.00', 1, 3, 1, 1, '2019-02-05 00:00:00'),
(2, '2019-02-05 00:00:00', 'Ingreso por venta generado automaticamente', '10.00', 1, 3, 1, 1, '2019-02-05 00:00:00'),
(3, '2019-02-05 00:00:00', 'Ingreso por venta generado automaticamente', '45.00', 1, 3, 1, 1, '2019-02-05 00:00:00'),
(4, '2019-02-05 00:00:00', '', '6000.00', 1, 5, 1, 1, '2019-02-05 00:00:00'),
(5, '2019-02-05 00:00:00', 'Ingreso por pago de deuda', '10.00', 1, 5, 1, 1, '2019-02-05 00:00:00'),
(6, '2019-02-05 00:00:00', 'Ingreso por venta generado automaticamente', '27.00', 1, 3, 1, 1, '2019-02-05 00:00:00'),
(7, '2019-02-05 00:00:00', 'Ingreso por venta generado automaticamente', '34.00', 1, 3, 1, 4, '2019-02-05 00:00:00'),
(8, '2019-04-08 00:00:00', 'Ingreso por venta generado automaticamente', '30.00', 1, 3, 1, 1, '2019-04-08 00:00:00'),
(9, '2019-04-10 00:00:00', 'Ingreso por venta generado automaticamente', '24.00', 1, 3, 1, 1, '2019-04-10 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingreso_inventario`
--

CREATE TABLE IF NOT EXISTS `ingreso_inventario` (
`id` bigint(20) NOT NULL,
  `fecha_ingreso` datetime DEFAULT NULL,
  `observacion` text,
  `estado_producto` smallint(6) NOT NULL,
  `estado` smallint(6) DEFAULT NULL,
  `forma_ingreso` text,
  `almacen_id` bigint(20) DEFAULT NULL,
  `usuario_id` bigint(20) DEFAULT NULL,
  `sucursal_id` bigint(20) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `ingreso_inventario`
--

INSERT INTO `ingreso_inventario` (`id`, `fecha_ingreso`, `observacion`, `estado_producto`, `estado`, `forma_ingreso`, `almacen_id`, `usuario_id`, `sucursal_id`, `fecha_registro`) VALUES
(1, '2019-02-05 00:00:00', '', 1, 1, 'Ingreso de producto', 2, 1, 1, '2019-02-05 14:12:49'),
(2, '2019-02-05 00:00:00', '', 1, 1, 'Ingreso de producto', 2, 1, 1, '2019-02-05 14:34:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingreso_venta`
--

CREATE TABLE IF NOT EXISTS `ingreso_venta` (
  `venta_id` bigint(20) NOT NULL,
  `ingreso_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `ingreso_venta`
--

INSERT INTO `ingreso_venta` (`venta_id`, `ingreso_id`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 6),
(5, 7),
(6, 8),
(7, 9);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inicio_sesion`
--

CREATE TABLE IF NOT EXISTS `inicio_sesion` (
`id` bigint(20) NOT NULL,
  `fecha` datetime DEFAULT NULL,
  `usuario_id` bigint(20) DEFAULT NULL,
  `impresora_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `inicio_sesion`
--

INSERT INTO `inicio_sesion` (`id`, `fecha`, `usuario_id`, `impresora_id`) VALUES
(1, '2019-02-01 03:14:37', 1, NULL),
(2, '2019-02-01 10:41:12', 1, NULL),
(3, '2019-02-01 10:50:01', 1, NULL),
(4, '2019-02-02 10:02:41', 4, NULL),
(5, '2019-02-02 10:04:13', 1, NULL),
(6, '2019-02-02 10:41:50', 1, NULL),
(7, '2019-02-02 11:04:55', 1, NULL),
(8, '2019-02-02 11:22:48', 1, NULL),
(9, '2019-02-03 10:52:43', 1, NULL),
(10, '2019-02-03 21:58:56', 1, NULL),
(11, '2019-02-03 22:34:28', 1, NULL),
(12, '2019-02-03 23:20:41', 4, NULL),
(13, '2019-02-03 23:23:37', 1, NULL),
(14, '2019-02-03 23:27:57', 4, NULL),
(15, '2019-02-03 23:31:24', 1, NULL),
(16, '2019-02-03 23:31:50', 4, NULL),
(17, '2019-02-03 23:36:24', 1, NULL),
(18, '2019-02-04 14:22:05', 1, NULL),
(19, '2019-02-04 14:29:29', 4, NULL),
(20, '2019-02-04 14:34:11', 1, NULL),
(21, '2019-02-04 14:35:23', 4, NULL),
(22, '2019-02-04 15:07:16', 1, NULL),
(23, '2019-02-05 10:58:32', 1, NULL),
(24, '2019-02-05 14:11:16', 1, NULL),
(25, '2019-02-05 19:43:26', 1, NULL),
(26, '2019-02-05 19:43:58', 4, NULL),
(27, '2019-02-05 19:47:02', 1, NULL),
(28, '2019-02-06 14:22:31', 1, NULL),
(29, '2019-02-06 15:15:34', 1, NULL),
(30, '2019-02-06 15:18:23', 1, NULL),
(31, '2019-02-06 15:21:05', 1, NULL),
(32, '2019-02-06 19:30:38', 1, NULL),
(33, '2019-02-07 08:58:28', 1, NULL),
(34, '2019-02-07 16:13:27', 1, NULL),
(35, '2019-02-07 16:39:02', 1, NULL),
(36, '2019-02-07 16:50:24', 1, NULL),
(37, '2019-02-08 00:54:00', 1, NULL),
(38, '2019-02-08 01:45:47', 1, NULL),
(39, '2019-02-11 10:30:31', 1, NULL),
(40, '2019-02-11 10:31:53', 8, NULL),
(41, '2019-02-11 10:32:46', 1, NULL),
(42, '2019-02-17 13:53:13', 1, NULL),
(43, '2019-02-17 16:22:37', 1, NULL),
(44, '2019-02-19 19:44:51', 1, NULL),
(45, '2019-03-24 14:06:44', 1, NULL),
(46, '2019-04-08 13:41:11', 1, NULL),
(47, '2019-04-10 06:42:41', 1, NULL),
(48, '2019-04-10 13:37:15', 1, NULL),
(49, '2019-04-10 13:50:52', 1, NULL),
(50, '2019-04-10 14:06:42', 1, NULL),
(51, '2019-04-11 22:04:36', 1, NULL),
(52, '2019-04-11 22:29:52', 1, NULL),
(53, '2019-04-11 22:59:58', 1, NULL),
(54, '2019-04-11 23:00:40', 1, NULL),
(55, '2019-04-11 23:02:01', 4, NULL),
(56, '2019-04-11 23:02:35', 1, NULL),
(57, '2019-04-11 23:02:52', 1, NULL),
(58, '2019-04-11 23:03:16', 4, NULL),
(59, '2019-04-11 23:04:55', 1, NULL),
(60, '2019-04-11 23:17:38', 1, NULL);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `inventario`
--
CREATE TABLE IF NOT EXISTS `inventario` (
`producto_id` bigint(20)
,`id_talla` bigint(20)
,`id_color` bigint(20)
,`id_almacen` bigint(20)
,`codigo_barra` text
,`codigo_alterno` text
,`nombre_item` text
,`tipo_producto` text
,`almacen` text
,`talla` text
,`color` text
,`stock_minimo` bigint(20)
,`precio_venta` decimal(20,2)
,`id_sucursal` bigint(20)
,`sucursal` text
,`unidad` text
,`cantidad` decimal(41,0)
);
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario_compra`
--

CREATE TABLE IF NOT EXISTS `inventario_compra` (
  `compra_id` bigint(20) NOT NULL,
  `ingreso_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `inventario_producto`
--
CREATE TABLE IF NOT EXISTS `inventario_producto` (
`producto_id` bigint(20)
,`id_talla` bigint(20)
,`id_color` bigint(20)
,`id_almacen` bigint(20)
,`codigo_barra` text
,`codigo_alterno` text
,`nombre_item` text
,`tipo_producto` text
,`almacen` text
,`talla` text
,`color` text
,`stock_minimo` bigint(20)
,`precio_venta` decimal(20,2)
,`id_sucursal` bigint(20)
,`sucursal` text
,`unidad` text
,`cantidad` bigint(20)
,`cantidad_produccion` bigint(20)
);
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marca`
--

CREATE TABLE IF NOT EXISTS `marca` (
`id` bigint(20) NOT NULL,
  `descripcion` text
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `marca`
--

INSERT INTO `marca` (`id`, `descripcion`) VALUES
(6, 'DICARP'),
(7, 'DUOFLEX');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu`
--

CREATE TABLE IF NOT EXISTS `menu` (
`id` bigint(20) NOT NULL,
  `parent` bigint(20) DEFAULT NULL,
  `name` text,
  `icon` text,
  `slug` text,
  `number` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `menu`
--

INSERT INTO `menu` (`id`, `parent`, `name`, `icon`, `slug`, `number`) VALUES
(1, NULL, 'REGISTROS', 'fa fa-address-book', 'Item-1', 1),
(2, NULL, 'VENTAS', 'fa fa-list', 'Item-1', 2),
(4, NULL, 'INVENTARIO', 'fa fa-line-chart', 'Item-1', 3),
(5, NULL, 'CONFIGURACION', 'fa fa-building-o', 'Item-1', 7),
(6, NULL, 'REPORTES', 'fa fa-area-chart', 'Item-1', 6),
(7, 1, 'Nuevo cliente', 'fa fa-circle-o', 'cliente', 1),
(8, 2, 'Nueva venta', 'fa fa-circle-o', 'venta', 1),
(9, 2, 'Consulta venta', 'fa fa-circle-o', 'consultar_venta', 2),
(11, 4, 'Nuevo producto', 'fa fa-circle-o', 'producto', 1),
(12, 4, 'Gestionar Ingreso', 'fa fa-circle-o', 'inventario', 2),
(16, 5, 'Usuario', 'fa fa-circle-o', 'usuario', 2),
(17, 6, 'Rep. Ventas', 'fa fa-circle-o', 'reporte/reporte_venta', 1),
(18, 33, 'Nueva proforma', 'fa fa-circle-o', 'proforma', 1),
(19, NULL, 'FLUJO CAJA', 'fa fa-dollar', 'Item-1', 5),
(20, 19, 'Registrar ingreso', 'fa fa-circle-o', 'ingreso', 1),
(21, 19, 'Registrar egreso', 'fa fa-circle-o', 'egreso', 2),
(22, 19, 'Gestionar Caja', 'fa fa-circle-o', 'caja', 3),
(23, 19, 'Flujo Caja', 'fa fa-circle-o', 'flujo_caja', 4),
(24, NULL, 'PAGOS DEUDAS', 'fa fa-university', 'Item-1', 6),
(25, 24, 'Historial Pagos', 'fa fa-circle-o', 'historial_pago', 2),
(26, 24, 'Pago Deudas', 'fa fa-circle-o', 'pago/listar', 1),
(28, 6, 'Rep. clientes', 'fa fa-circle-o', 'reporte/reporte_clientes', 1),
(31, 6, 'Rep. Deudas', 'fa fa-circle-o', 'reporte/reporte_deudas', 3),
(32, 5, 'Empresa', 'fa fa-circle-o', 'sucursal', 2),
(33, NULL, 'PROFORMA', 'fa fa-file-text', 'Item-1', 4),
(34, 33, 'Consulta Proforma', 'fa fa-circle-o', 'consultar_proforma', 2),
(35, NULL, 'BACKUP', 'fa fa-download', 'Item-1', 9),
(36, 35, 'Generar Backup', 'fa fa-circle-o', 'backup/database_backup', 1),
(37, 3777, 'RESERVA', 'fa fa-futbol-o', 'Item-1', 8),
(38, 37, 'Nuevo Escenario', 'fa fa-circle-o', 'escenario', 1),
(39, 37, 'Nueva Reserva', 'fa fa-circle-o', 'reserva', 2),
(40, 37, 'Consulta Reserva', 'fa fa-circle-o', 'consultar_reserva', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nota_venta`
--

CREATE TABLE IF NOT EXISTS `nota_venta` (
`id` bigint(20) NOT NULL,
  `venta_id` bigint(20) DEFAULT NULL,
  `nro_nota` bigint(20) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `nota_venta`
--

INSERT INTO `nota_venta` (`id`, `venta_id`, `nro_nota`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 3),
(4, 4, 4),
(5, 5, 5),
(6, 6, 6),
(7, 7, 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE IF NOT EXISTS `producto` (
`id` bigint(20) NOT NULL,
  `codigo_barra` text,
  `codigo_alterno` text NOT NULL,
  `nombre_item` text,
  `precio_venta` decimal(20,2) DEFAULT NULL,
  `precio_compra` decimal(20,2) DEFAULT NULL,
  `stock_minimo` bigint(20) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  `estado` smallint(6) DEFAULT NULL,
  `tipo_item_id` bigint(20) DEFAULT NULL,
  `unidad_medida` text,
  `unidad_compra` text,
  `dimension` text,
  `descripcion` text,
  `marca_id` bigint(20) DEFAULT NULL,
  `color_id` bigint(20) DEFAULT NULL,
  `talla_id` bigint(20) DEFAULT NULL,
  `categoria_interna_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id`, `codigo_barra`, `codigo_alterno`, `nombre_item`, `precio_venta`, `precio_compra`, `stock_minimo`, `fecha_registro`, `fecha_modificacion`, `estado`, `tipo_item_id`, `unidad_medida`, `unidad_compra`, `dimension`, `descripcion`, `marca_id`, `color_id`, `talla_id`, `categoria_interna_id`) VALUES
(1, '112233', '', 'SODA COCA COLA', '12.00', '10.00', 1, '2019-02-05 11:46:46', NULL, 1, 2, NULL, '1', NULL, '', 6, 1, 26, 2),
(2, '11223', '', 'SODA FANTA', '10.00', '9.00', 1, '2019-02-05 11:59:29', NULL, 1, 2, NULL, '1', NULL, '', 6, 1, 26, 2),
(3, '119645', '', 'GASEOSA ACUARIUS', '11.00', '10.00', 1, '2019-02-05 11:59:57', NULL, 1, 2, NULL, '1', NULL, '', 6, 1, 26, 2),
(4, '112265', '', 'BLACK', '15.00', '13.00', 1, '2019-02-05 12:00:21', NULL, 1, 2, NULL, '1', NULL, '', 6, 1, 26, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_inventario`
--

CREATE TABLE IF NOT EXISTS `producto_inventario` (
`id` bigint(20) NOT NULL,
  `cantidad_ingresada` bigint(20) DEFAULT NULL,
  `ingreso_id` bigint(20) DEFAULT NULL,
  `producto_id` bigint(20) DEFAULT NULL,
  `precio_venta` decimal(20,2) DEFAULT NULL,
  `cantidad` bigint(20) DEFAULT NULL,
  `cantidad_produccion` bigint(20) NOT NULL,
  `unidad_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `producto_inventario`
--

INSERT INTO `producto_inventario` (`id`, `cantidad_ingresada`, `ingreso_id`, `producto_id`, `precio_venta`, `cantidad`, `cantidad_produccion`, `unidad_id`) VALUES
(1, 25, 1, 1, '12.00', 21, 0, 1),
(2, 25, 1, 2, '10.00', 20, 0, 1),
(3, 30, 1, 3, '11.00', 23, 0, 1),
(4, 40, 2, 4, '15.00', 37, 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proforma`
--

CREATE TABLE IF NOT EXISTS `proforma` (
`id` bigint(20) NOT NULL,
  `fecha` date DEFAULT NULL,
  `hora` text,
  `subtotal` decimal(20,2) DEFAULT NULL,
  `descuento` decimal(20,2) DEFAULT NULL,
  `total` decimal(20,2) DEFAULT NULL,
  `cliente_id` bigint(20) DEFAULT NULL,
  `nro_proforma` bigint(20) DEFAULT NULL,
  `estado` smallint(6) DEFAULT NULL,
  `tipo_venta` text,
  `sucursal_id` bigint(20) DEFAULT NULL,
  `usuario_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `proforma`
--

INSERT INTO `proforma` (`id`, `fecha`, `hora`, `subtotal`, `descuento`, `total`, `cliente_id`, `nro_proforma`, `estado`, `tipo_venta`, `sucursal_id`, `usuario_id`) VALUES
(1, '2019-02-05', '14:53:46', '1410.00', '0.00', '1410.00', 14, 1, 1, 'nota', 1, 1);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `proforma_emitida`
--
CREATE TABLE IF NOT EXISTS `proforma_emitida` (
`estado` smallint(6)
,`id` bigint(20)
,`fecha` date
,`hora` text
,`subtotal` decimal(20,2)
,`descuento` decimal(20,2)
,`total` decimal(20,2)
,`nombre_usuario` text
,`nro_proforma` bigint(20)
,`ci_nit` text
,`nombre_cliente` text
,`nit` text
,`nombre_empresa` text
,`sucursal` text
,`direccion` text
,`telefono` text
,`email` text
);
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE IF NOT EXISTS `proveedor` (
`id` bigint(20) NOT NULL,
  `nombre` text,
  `telefono` text,
  `direccion` text,
  `estado` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salida_inventario`
--

CREATE TABLE IF NOT EXISTS `salida_inventario` (
`id` bigint(20) NOT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `observacion` text,
  `estado` smallint(6) DEFAULT NULL,
  `tipo_salida_inventario_id` bigint(20) DEFAULT NULL,
  `almacen_origen_id` bigint(20) DEFAULT NULL,
  `almacen_destino_id` bigint(20) DEFAULT NULL,
  `sucursal_id` bigint(20) DEFAULT NULL,
  `fecha_salida` datetime DEFAULT NULL,
  `usuario_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sucursal`
--

CREATE TABLE IF NOT EXISTS `sucursal` (
`id` bigint(20) NOT NULL,
  `nit` text,
  `nombre_empresa` text,
  `sucursal` text,
  `estado` smallint(6) DEFAULT NULL,
  `direccion` text,
  `telefono` text,
  `email` text,
  `nombre_impuesto` text
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `sucursal`
--

INSERT INTO `sucursal` (`id`, `nit`, `nombre_empresa`, `sucursal`, `estado`, `direccion`, `telefono`, `email`, `nombre_impuesto`) VALUES
(1, ' 4577400010', 'GIRUB', 'CASA MATRIZ', 1, 'AV PARAGUA 3ER ANILLO ', '69095753 - 62021407', 'girub@gmail.com', 'casa matriz');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `talla`
--

CREATE TABLE IF NOT EXISTS `talla` (
`id` bigint(20) NOT NULL,
  `descripcion` text
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `talla`
--

INSERT INTO `talla` (`id`, `descripcion`) VALUES
(26, 'GIRUB');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_escenario`
--

CREATE TABLE IF NOT EXISTS `tipo_escenario` (
`id` int(11) NOT NULL,
  `nombre` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha_registro` datetime NOT NULL,
  `estado` smallint(6) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `tipo_escenario`
--

INSERT INTO `tipo_escenario` (`id`, `nombre`, `fecha_registro`, `estado`) VALUES
(1, 'Césped Sintético', '2019-02-07 00:00:00', 1),
(2, 'Césped Natural', '2019-02-07 00:00:00', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_ingreso_egreso`
--

CREATE TABLE IF NOT EXISTS `tipo_ingreso_egreso` (
`id` bigint(20) NOT NULL,
  `descripcion` text,
  `estado` smallint(6) DEFAULT NULL,
  `tipo_dato` bit(1) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tipo_ingreso_egreso`
--

INSERT INTO `tipo_ingreso_egreso` (`id`, `descripcion`, `estado`, `tipo_dato`) VALUES
(3, 'PAGO POR ALQUILER', 1, b'0'),
(5, 'POR PRESTACIÓN DE TRANSPORTE', 1, b'1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_item`
--

CREATE TABLE IF NOT EXISTS `tipo_item` (
`id` bigint(20) NOT NULL,
  `descripcion` text,
  `estado` smallint(6) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tipo_item`
--

INSERT INTO `tipo_item` (`id`, `descripcion`, `estado`) VALUES
(1, 'Producto', 1),
(2, 'Materia Prima', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_salida_inventario`
--

CREATE TABLE IF NOT EXISTS `tipo_salida_inventario` (
`id` bigint(20) NOT NULL,
  `nombre` text,
  `descripcion` text,
  `fecha_registro` datetime DEFAULT NULL,
  `estado` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidad_medida`
--

CREATE TABLE IF NOT EXISTS `unidad_medida` (
`id` bigint(20) NOT NULL,
  `descripcion` text,
  `abreviatura` text
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `unidad_medida`
--

INSERT INTO `unidad_medida` (`id`, `descripcion`, `abreviatura`) VALUES
(1, 'PIEZAS', 'PZA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
`id` bigint(20) NOT NULL,
  `ci` text,
  `nombre_usuario` text,
  `telefono` text,
  `cargo` bigint(20) DEFAULT NULL,
  `usuario` text,
  `clave` text,
  `activo` smallint(6) DEFAULT NULL,
  `estado` smallint(6) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `ci`, `nombre_usuario`, `telefono`, `cargo`, `usuario`, `clave`, `activo`, `estado`) VALUES
(1, '11386005', 'admin', '62021407', 1, 'admin', '$2y$10$9kbE0OVkWXln4OqOIi7I2eiPUlatlS0n6J9c36Vs3/ntQ3gTe/UXi', 1, 1),
(2, '123456', 'ELIANA HURTADO', '787987785', 2, 'eliana', '$2y$10$sX0g/3gxRdNVuUYDMlSdTOJY/QPZQnMCqJ6kra4h31sFObyJnqhLG', 0, 1),
(3, '78885546 sc', 'RAUL PEREZ', '7879878', 2, 'raul', '$2y$10$Ayxnm3Y8ljcZYLUFg7zOEeJhhvc2yeejFLQaxiTxBS82F2NnA4uIO', 0, 1),
(4, '645454544 sc', 'JUAN MENDOZA', '69095753', 2, 'juan', '$2y$10$D/7lgcvXHWEg0b97645DK.xZoVgAUyOgfrVfxx1.4JOqbaBknzML2', 0, 1),
(5, '654544', 'MARIA SOLIZ', '78745544', 2, 'maria', '$2y$10$WDISHIn5XaKFsby5kiU3jeGfGaRCR.pVKOokdAeGb.ur6koJlt7AG', 0, 1),
(6, '64541444', 'BRENDA VARGAS', '6477455', 2, 'brenda', '$2y$10$5WJegjqahZLWr/HVxZlofeDn/LJWuK7.HffDMBcUvG2cF1h.k1bhO', 0, 1),
(7, '65657575', 'SAMUEL', '7687686', 2, 'samuel', '$2y$10$KXfc.piV7MPJ5XEIrFCj2.C5pH.7W4kY8Wel6zajsZUpe6hOEcYKq', 0, 0),
(8, '767676766', 'CARLA PERES', '7678686', 2, 'carla', '$2y$10$GRSqjM3wF9vaUzJebch9gO4pqBX1DhaXq.rRKoKfIJLIaZZ12LxKG', 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_sucursal`
--

CREATE TABLE IF NOT EXISTS `usuario_sucursal` (
  `usuario_id` bigint(20) DEFAULT NULL,
  `sucursal_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `usuario_sucursal`
--

INSERT INTO `usuario_sucursal` (`usuario_id`, `sucursal_id`) VALUES
(1, 1),
(3, 1),
(2, 1),
(5, 1),
(6, 1),
(7, 1),
(4, 1),
(8, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta`
--

CREATE TABLE IF NOT EXISTS `venta` (
`id` bigint(20) NOT NULL,
  `fecha` datetime DEFAULT NULL,
  `hora` text,
  `subtotal` decimal(20,2) DEFAULT NULL,
  `descuento` decimal(20,2) DEFAULT NULL,
  `total` decimal(20,2) DEFAULT NULL,
  `cliente_id` bigint(20) DEFAULT NULL,
  `nro_venta` bigint(20) DEFAULT NULL,
  `estado_venta` smallint(6) NOT NULL,
  `estado` smallint(6) DEFAULT NULL,
  `tipo_venta` text,
  `sucursal_id` bigint(20) DEFAULT NULL,
  `usuario_id` bigint(20) DEFAULT NULL,
  `nota` text
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `venta`
--

INSERT INTO `venta` (`id`, `fecha`, `hora`, `subtotal`, `descuento`, `total`, `cliente_id`, `nro_venta`, `estado_venta`, `estado`, `tipo_venta`, `sucursal_id`, `usuario_id`, `nota`) VALUES
(1, '2019-02-05 14:25:11', '14:25:11', '22.00', '0.00', '22.00', 1, 1, 0, 1, 'nota', 1, 1, '\r\n                                     '),
(2, '2019-02-05 14:33:21', '14:33:21', '22.00', '2.00', '20.00', 5, 1, 0, 1, 'nota', 1, 1, 'monto cancelado          '),
(3, '2019-02-05 14:35:25', '14:35:25', '41.00', '0.00', '41.00', 6, 1, 0, 1, 'nota', 1, 1, '\r\n                                     '),
(4, '2019-02-05 14:58:04', '14:58:04', '27.00', '0.00', '27.00', 21, 1, 0, 1, 'nota', 1, 1, '\r\n                                     '),
(5, '2019-02-05 19:45:43', '19:45:43', '34.00', '0.00', '34.00', 23, 1, 0, 1, 'nota', 1, 4, '\r\n                                     '),
(6, '2019-04-08 13:47:48', '13:47:48', '40.00', '10.00', '30.00', 24, 1, 0, 1, 'nota', 1, 1, '\r\n                                     '),
(7, '2019-04-10 13:55:17', '13:55:17', '34.00', '10.00', '24.00', 25, 1, 0, 1, 'nota', 1, 1, '\r\n                                     ');

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `ventas_emitidas`
--
CREATE TABLE IF NOT EXISTS `ventas_emitidas` (
`id` bigint(20)
,`fecha` datetime
,`subtotal` decimal(20,2)
,`descuento` decimal(20,2)
,`total` decimal(20,2)
,`cliente_id` bigint(20)
,`nro_venta` bigint(20)
,`estado` smallint(6)
,`sucursal_id` bigint(20)
,`usuario_id` bigint(20)
,`tipo_venta` text
,`hora` text
,`nombre_cliente` text
,`ci_nit` text
,`nro_nota` bigint(20)
,`forma_pago` text
);
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta_pago`
--

CREATE TABLE IF NOT EXISTS `venta_pago` (
`id` bigint(20) NOT NULL,
  `venta_id` bigint(20) DEFAULT NULL,
  `forma_pago` text,
  `banco` text,
  `nro_cuenta` text,
  `nro_cheque` text,
  `fecha_pago` date DEFAULT NULL,
  `vencimiento` text,
  `monto` decimal(20,2) DEFAULT NULL,
  `saldo` decimal(20,2) DEFAULT NULL,
  `estado` text,
  `fecha_registro` date DEFAULT NULL,
  `descripcion` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `venta_pago`
--

INSERT INTO `venta_pago` (`id`, `venta_id`, `forma_pago`, `banco`, `nro_cuenta`, `nro_cheque`, `fecha_pago`, `vencimiento`, `monto`, `saldo`, `estado`, `fecha_registro`, `descripcion`) VALUES
(1, 1, 'Efectivo', NULL, NULL, NULL, NULL, NULL, '22.00', '0.00', 'Cancelado', '2019-02-05', 'forma_pago_efectivo'),
(2, 2, 'Plazo', NULL, NULL, NULL, '2019-02-05', NULL, '10.00', '10.00', 'Cancelado', '2019-02-05', 'forma_pago_efectivo'),
(3, 3, 'Plazo', NULL, NULL, NULL, '2019-02-05', NULL, '41.00', '0.00', 'Cancelado', '2019-02-05', 'forma_pago_efectivo'),
(4, 2, 'Plazo', NULL, NULL, NULL, '2019-02-05', NULL, '10.00', '0.00', 'Cancelado', '2019-02-05', ''),
(5, 4, 'Plazo', NULL, NULL, NULL, '2019-02-05', NULL, '27.00', '0.00', 'Debe', '2019-02-05', 'forma_pago_efectivo'),
(6, 5, 'Efectivo', NULL, NULL, NULL, NULL, NULL, '34.00', '0.00', 'Cancelado', '2019-02-05', 'forma_pago_efectivo'),
(7, 6, 'Efectivo', NULL, NULL, NULL, NULL, NULL, '30.00', '0.00', 'Cancelado', '2019-04-08', 'forma_pago_efectivo'),
(8, 7, 'Efectivo', NULL, NULL, NULL, NULL, NULL, '24.00', '0.00', 'Cancelado', '2019-04-10', 'forma_pago_efectivo');

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_proforma`
--
CREATE TABLE IF NOT EXISTS `vista_proforma` (
`id` bigint(20)
,`codigo_barra` text
,`nombre_item` text
,`stock_minimo` bigint(20)
,`precio_venta` decimal(20,2)
,`sucursal` varchar(11)
,`id_talla` bigint(20)
,`id_color` bigint(20)
,`color` text
,`talla` text
);
-- --------------------------------------------------------

--
-- Estructura para la vista `deudas_por_cobrar`
--
DROP TABLE IF EXISTS `deudas_por_cobrar`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `deudas_por_cobrar` AS select `v`.`id` AS `id`,`v`.`fecha` AS `fecha`,`c`.`id` AS `cliente_id`,`c`.`nombre_cliente` AS `nombre_cliente`,`v`.`total` AS `total`,sum(`p`.`monto`) AS `total_pagado`,(`v`.`total` - sum(`p`.`monto`)) AS `saldo`,`p`.`estado` AS `estado`,`v`.`nro_venta` AS `nro_venta` from ((`cliente` `c` join `venta` `v` on((`c`.`id` = `v`.`cliente_id`))) join `venta_pago` `p` on((`v`.`id` = `p`.`venta_id`))) where ((`p`.`forma_pago` = 'plazo') and (`p`.`estado` <> 'Cancelado')) group by `v`.`id`,`c`.`id`,`p`.`estado`,`v`.`fecha`,`c`.`nombre_cliente`,`v`.`total`,`v`.`nro_venta`;

-- --------------------------------------------------------

--
-- Estructura para la vista `inventario`
--
DROP TABLE IF EXISTS `inventario`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `inventario` AS select `p`.`id` AS `producto_id`,`t`.`id` AS `id_talla`,`c`.`id` AS `id_color`,`a`.`id` AS `id_almacen`,`p`.`codigo_barra` AS `codigo_barra`,`p`.`codigo_alterno` AS `codigo_alterno`,`p`.`nombre_item` AS `nombre_item`,`ti`.`descripcion` AS `tipo_producto`,`a`.`descripcion` AS `almacen`,`t`.`descripcion` AS `talla`,`c`.`descripcion` AS `color`,`p`.`stock_minimo` AS `stock_minimo`,`p`.`precio_venta` AS `precio_venta`,`s`.`id` AS `id_sucursal`,`s`.`sucursal` AS `sucursal`,`u`.`descripcion` AS `unidad`,sum(`pi`.`cantidad`) AS `cantidad` from ((((((((`tipo_item` `ti` join `producto` `p` on((`ti`.`id` = `p`.`tipo_item_id`))) join `producto_inventario` `pi` on((`p`.`id` = `pi`.`producto_id`))) join `talla` `t` on((`p`.`talla_id` = `t`.`id`))) join `color` `c` on((`p`.`color_id` = `c`.`id`))) join `ingreso_inventario` `i` on((`pi`.`ingreso_id` = `i`.`id`))) join `almacen` `a` on((`i`.`almacen_id` = `a`.`id`))) join `sucursal` `s` on((`i`.`sucursal_id` = `s`.`id`))) join `unidad_medida` `u` on((`pi`.`unidad_id` = `u`.`id`))) where (`p`.`estado` = 1) group by `p`.`id`,`t`.`id`,`c`.`id`,`a`.`id`,`p`.`codigo_barra`,`p`.`codigo_alterno`,`p`.`nombre_item`,`ti`.`descripcion`,`a`.`descripcion`,`t`.`descripcion`,`c`.`descripcion`,`p`.`stock_minimo`,`p`.`precio_venta`,`s`.`id`,`s`.`sucursal`,`u`.`descripcion`;

-- --------------------------------------------------------

--
-- Estructura para la vista `inventario_producto`
--
DROP TABLE IF EXISTS `inventario_producto`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `inventario_producto` AS select `p`.`id` AS `producto_id`,`t`.`id` AS `id_talla`,`c`.`id` AS `id_color`,`a`.`id` AS `id_almacen`,`p`.`codigo_barra` AS `codigo_barra`,`p`.`codigo_alterno` AS `codigo_alterno`,`p`.`nombre_item` AS `nombre_item`,`ti`.`descripcion` AS `tipo_producto`,`a`.`descripcion` AS `almacen`,`t`.`descripcion` AS `talla`,`c`.`descripcion` AS `color`,`p`.`stock_minimo` AS `stock_minimo`,`p`.`precio_venta` AS `precio_venta`,`s`.`id` AS `id_sucursal`,`s`.`sucursal` AS `sucursal`,`u`.`descripcion` AS `unidad`,`pi`.`cantidad` AS `cantidad`,`pi`.`cantidad_produccion` AS `cantidad_produccion` from ((((((((`tipo_item` `ti` join `producto` `p` on((`ti`.`id` = `p`.`tipo_item_id`))) join `producto_inventario` `pi` on((`p`.`id` = `pi`.`producto_id`))) join `talla` `t` on((`p`.`talla_id` = `t`.`id`))) join `color` `c` on((`p`.`color_id` = `c`.`id`))) join `ingreso_inventario` `i` on((`pi`.`ingreso_id` = `i`.`id`))) join `almacen` `a` on((`i`.`almacen_id` = `a`.`id`))) join `sucursal` `s` on((`i`.`sucursal_id` = `s`.`id`))) join `unidad_medida` `u` on((`pi`.`unidad_id` = `u`.`id`))) where ((`p`.`estado` = 1) and (`pi`.`cantidad` >= 0) and (`pi`.`cantidad_produccion` >= 0));

-- --------------------------------------------------------

--
-- Estructura para la vista `proforma_emitida`
--
DROP TABLE IF EXISTS `proforma_emitida`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `proforma_emitida` AS select `v`.`estado` AS `estado`,`v`.`id` AS `id`,`v`.`fecha` AS `fecha`,`v`.`hora` AS `hora`,`v`.`subtotal` AS `subtotal`,`v`.`descuento` AS `descuento`,`v`.`total` AS `total`,`u`.`nombre_usuario` AS `nombre_usuario`,`v`.`nro_proforma` AS `nro_proforma`,`c`.`ci_nit` AS `ci_nit`,`c`.`nombre_cliente` AS `nombre_cliente`,`s`.`nit` AS `nit`,`s`.`nombre_empresa` AS `nombre_empresa`,`s`.`sucursal` AS `sucursal`,`s`.`direccion` AS `direccion`,`s`.`telefono` AS `telefono`,`s`.`email` AS `email` from (((`cliente` `c` join `proforma` `v` on((`c`.`id` = `v`.`cliente_id`))) join `sucursal` `s` on((`v`.`sucursal_id` = `s`.`id`))) join `usuario` `u` on((`v`.`usuario_id` = `u`.`id`))) where (`v`.`estado` = 1) group by `v`.`id`;

-- --------------------------------------------------------

--
-- Estructura para la vista `ventas_emitidas`
--
DROP TABLE IF EXISTS `ventas_emitidas`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `ventas_emitidas` AS select `v`.`id` AS `id`,`v`.`fecha` AS `fecha`,`v`.`subtotal` AS `subtotal`,`v`.`descuento` AS `descuento`,`v`.`total` AS `total`,`v`.`cliente_id` AS `cliente_id`,`v`.`nro_venta` AS `nro_venta`,`v`.`estado` AS `estado`,`v`.`sucursal_id` AS `sucursal_id`,`v`.`usuario_id` AS `usuario_id`,`v`.`tipo_venta` AS `tipo_venta`,`v`.`hora` AS `hora`,`c`.`nombre_cliente` AS `nombre_cliente`,`c`.`ci_nit` AS `ci_nit`,`n`.`nro_nota` AS `nro_nota`,`vpa`.`forma_pago` AS `forma_pago` from (((`nota_venta` `n` join `venta` `v`) join `cliente` `c`) join `venta_pago` `vpa`) where ((`n`.`venta_id` = `v`.`id`) and (`v`.`cliente_id` = `c`.`id`) and (`v`.`id` = `vpa`.`venta_id`)) group by `v`.`id`;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_proforma`
--
DROP TABLE IF EXISTS `vista_proforma`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_proforma` AS select `p`.`id` AS `id`,`p`.`codigo_barra` AS `codigo_barra`,`p`.`nombre_item` AS `nombre_item`,`p`.`stock_minimo` AS `stock_minimo`,`p`.`precio_venta` AS `precio_venta`,'CASA MATRIZ' AS `sucursal`,`t`.`id` AS `id_talla`,`c`.`id` AS `id_color`,`c`.`descripcion` AS `color`,`t`.`descripcion` AS `talla` from ((`producto` `p` join `color` `c` on((`c`.`id` = `p`.`color_id`))) join `talla` `t` on((`t`.`id` = `p`.`talla_id`)));

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `acceso`
--
ALTER TABLE `acceso`
 ADD PRIMARY KEY (`id`), ADD KEY `menu_id` (`menu_id`), ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `almacen`
--
ALTER TABLE `almacen`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `caja`
--
ALTER TABLE `caja`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cargo`
--
ALTER TABLE `cargo`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `categoria_interna`
--
ALTER TABLE `categoria_interna`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cierre_sesion`
--
ALTER TABLE `cierre_sesion`
 ADD PRIMARY KEY (`id`), ADD KEY `sesion_id` (`sesion_id`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `color`
--
ALTER TABLE `color`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `compra`
--
ALTER TABLE `compra`
 ADD PRIMARY KEY (`id`), ADD KEY `proveedor_id` (`proveedor_id`), ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `detalle_caja`
--
ALTER TABLE `detalle_caja`
 ADD PRIMARY KEY (`id`), ADD KEY `caja_id` (`caja_id`), ADD KEY `sucursal_id` (`sucursal_id`), ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
 ADD PRIMARY KEY (`id`), ADD KEY `compra_id` (`compra_id`), ADD KEY `producto_id` (`producto_id`), ADD KEY `unidad_id` (`unidad_id`);

--
-- Indices de la tabla `detalle_producto_ingreso`
--
ALTER TABLE `detalle_producto_ingreso`
 ADD PRIMARY KEY (`id`), ADD KEY `id` (`id`), ADD KEY `id_2` (`id`);

--
-- Indices de la tabla `detalle_proforma`
--
ALTER TABLE `detalle_proforma`
 ADD PRIMARY KEY (`id`), ADD KEY `producto_id` (`producto_id`), ADD KEY `proforma_id` (`proforma_id`);

--
-- Indices de la tabla `detalle_salida_inventario`
--
ALTER TABLE `detalle_salida_inventario`
 ADD PRIMARY KEY (`salida_inventario_id`,`producto_inventario_id`), ADD KEY `id` (`id`), ADD KEY `id_2` (`id`);

--
-- Indices de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
 ADD PRIMARY KEY (`id`), ADD KEY `producto_id` (`producto_id`), ADD KEY `venta_id` (`venta_id`);

--
-- Indices de la tabla `egreso_caja`
--
ALTER TABLE `egreso_caja`
 ADD PRIMARY KEY (`id`), ADD KEY `tipo_egreso_id` (`tipo_egreso_id`);

--
-- Indices de la tabla `egreso_compra`
--
ALTER TABLE `egreso_compra`
 ADD PRIMARY KEY (`compra_id`,`egreso_id`), ADD KEY `compra_id` (`compra_id`), ADD KEY `egreso_id` (`egreso_id`);

--
-- Indices de la tabla `escenario`
--
ALTER TABLE `escenario`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `forma_pago`
--
ALTER TABLE `forma_pago`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ingreso_caja`
--
ALTER TABLE `ingreso_caja`
 ADD PRIMARY KEY (`id`), ADD KEY `tipo_ingreso_id` (`tipo_ingreso_id`);

--
-- Indices de la tabla `ingreso_inventario`
--
ALTER TABLE `ingreso_inventario`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ingreso_venta`
--
ALTER TABLE `ingreso_venta`
 ADD PRIMARY KEY (`venta_id`,`ingreso_id`), ADD KEY `ingreso_id` (`ingreso_id`), ADD KEY `venta_id` (`venta_id`);

--
-- Indices de la tabla `inicio_sesion`
--
ALTER TABLE `inicio_sesion`
 ADD PRIMARY KEY (`id`), ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `marca`
--
ALTER TABLE `marca`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `menu`
--
ALTER TABLE `menu`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `nota_venta`
--
ALTER TABLE `nota_venta`
 ADD PRIMARY KEY (`id`), ADD KEY `venta_id` (`venta_id`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
 ADD PRIMARY KEY (`id`), ADD KEY `categoria_interna_id` (`categoria_interna_id`), ADD KEY `marca_id` (`marca_id`), ADD KEY `tipo_item_id` (`tipo_item_id`), ADD KEY `color_id` (`color_id`), ADD KEY `talla_id` (`talla_id`);

--
-- Indices de la tabla `producto_inventario`
--
ALTER TABLE `producto_inventario`
 ADD PRIMARY KEY (`id`), ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `proforma`
--
ALTER TABLE `proforma`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `salida_inventario`
--
ALTER TABLE `salida_inventario`
 ADD PRIMARY KEY (`id`), ADD KEY `id` (`id`);

--
-- Indices de la tabla `sucursal`
--
ALTER TABLE `sucursal`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `talla`
--
ALTER TABLE `talla`
 ADD KEY `id` (`id`);

--
-- Indices de la tabla `tipo_escenario`
--
ALTER TABLE `tipo_escenario`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipo_ingreso_egreso`
--
ALTER TABLE `tipo_ingreso_egreso`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipo_item`
--
ALTER TABLE `tipo_item`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipo_salida_inventario`
--
ALTER TABLE `tipo_salida_inventario`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `unidad_medida`
--
ALTER TABLE `unidad_medida`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
 ADD PRIMARY KEY (`id`), ADD KEY `cargo` (`cargo`);

--
-- Indices de la tabla `usuario_sucursal`
--
ALTER TABLE `usuario_sucursal`
 ADD KEY `sucursal_id` (`sucursal_id`), ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `venta`
--
ALTER TABLE `venta`
 ADD PRIMARY KEY (`id`), ADD KEY `cliente_id` (`cliente_id`), ADD KEY `sucursal_id` (`sucursal_id`), ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `venta_pago`
--
ALTER TABLE `venta_pago`
 ADD PRIMARY KEY (`id`), ADD KEY `venta_id` (`venta_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `acceso`
--
ALTER TABLE `acceso`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=170;
--
-- AUTO_INCREMENT de la tabla `almacen`
--
ALTER TABLE `almacen`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `caja`
--
ALTER TABLE `caja`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `cargo`
--
ALTER TABLE `cargo`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `categoria_interna`
--
ALTER TABLE `categoria_interna`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `cierre_sesion`
--
ALTER TABLE `cierre_sesion`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=60;
--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT de la tabla `color`
--
ALTER TABLE `color`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `compra`
--
ALTER TABLE `compra`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `detalle_caja`
--
ALTER TABLE `detalle_caja`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `detalle_producto_ingreso`
--
ALTER TABLE `detalle_producto_ingreso`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT de la tabla `detalle_proforma`
--
ALTER TABLE `detalle_proforma`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT de la tabla `egreso_caja`
--
ALTER TABLE `egreso_caja`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `escenario`
--
ALTER TABLE `escenario`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT de la tabla `forma_pago`
--
ALTER TABLE `forma_pago`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `ingreso_caja`
--
ALTER TABLE `ingreso_caja`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT de la tabla `ingreso_inventario`
--
ALTER TABLE `ingreso_inventario`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `inicio_sesion`
--
ALTER TABLE `inicio_sesion`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=61;
--
-- AUTO_INCREMENT de la tabla `marca`
--
ALTER TABLE `marca`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT de la tabla `menu`
--
ALTER TABLE `menu`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=41;
--
-- AUTO_INCREMENT de la tabla `nota_venta`
--
ALTER TABLE `nota_venta`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `producto_inventario`
--
ALTER TABLE `producto_inventario`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `proforma`
--
ALTER TABLE `proforma`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `salida_inventario`
--
ALTER TABLE `salida_inventario`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `sucursal`
--
ALTER TABLE `sucursal`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `talla`
--
ALTER TABLE `talla`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT de la tabla `tipo_escenario`
--
ALTER TABLE `tipo_escenario`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `tipo_ingreso_egreso`
--
ALTER TABLE `tipo_ingreso_egreso`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `tipo_item`
--
ALTER TABLE `tipo_item`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `tipo_salida_inventario`
--
ALTER TABLE `tipo_salida_inventario`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `unidad_medida`
--
ALTER TABLE `unidad_medida`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT de la tabla `venta`
--
ALTER TABLE `venta`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT de la tabla `venta_pago`
--
ALTER TABLE `venta_pago`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
