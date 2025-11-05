-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 05-11-2025 a las 01:25:15
-- Versión del servidor: 11.8.3-MariaDB-log
-- Versión de PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `u162024603_miBaseDeDatos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CondicionIVA`
--

CREATE TABLE `CondicionIVA` (
  `idIVA` int(11) NOT NULL,
  `tipoIVA` varchar(50) NOT NULL,
  `porcentaje` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `CondicionIVA`
--

INSERT INTO `CondicionIVA` (`idIVA`, `tipoIVA`, `porcentaje`) VALUES
(1, 'General', 21.00),
(2, 'Reducido', 10.50),
(3, 'Aumentado', 27.00),
(4, 'Exento', 0.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Proveedores`
--

CREATE TABLE `Proveedores` (
  `CodProveedor` int(11) NOT NULL,
  `RazonSocial` varchar(100) NOT NULL,
  `CUIT` varchar(20) NOT NULL,
  `idIVA` int(11) NOT NULL,
  `SaldoCuentaCorriente` decimal(15,2) DEFAULT 0.00,
  `CertificadosCalidad` longblob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `Proveedores`
--

INSERT INTO `Proveedores` (`CodProveedor`, `RazonSocial`, `CUIT`, `idIVA`, `SaldoCuentaCorriente`, `CertificadosCalidad`) VALUES
(1, 'YPF', '30-70700001-0', 1, 150000.00, NULL),
(2, 'Arcor', '30-70700002-9', 2, 80000.00, NULL),
(3, 'Molinos Río de la Plata', '30-70700003-8', 3, 95000.00, NULL),
(4, 'Telecom', '30-70700004-7', 4, 60000.00, NULL),
(5, 'Tenaris', '30-70700005-6', 1, 200000.00, NULL),
(6, 'Coca-Cola', '30-70700006-5', 2, 120000.00, NULL),
(7, 'Quilmes', '30-70700007-4', 3, 110000.00, NULL),
(8, 'Mercado Libre', '30-70700008-3', 4, 175000.00, NULL),
(9, 'Aerolíneas Argentinas', '30-70700009-2', 1, 220000.00, NULL),
(10, 'Techint Ingeniería y Construcción', '30-70700010-1', 2, 135000.00, NULL),
(11, 'Sancor', '30-70700011-0', 1, 90000.00, NULL),
(12, 'Petrobras', '30-70700012-9', 2, 185000.00, NULL),
(13, 'Samsung Argentina', '30-70700013-8', 3, 142000.00, NULL),
(14, 'Claro', '30-70700014-7', 4, 97000.00, NULL),
(15, 'Ford Argentina', '30-70700015-6', 1, 125000.00, NULL),
(16, 'PepsiCo', '30-70700016-5', 2, 138000.00, NULL),
(17, 'Unilever', '30-70700017-4', 3, 99000.00, NULL),
(18, 'Globant', '30-70700018-3', 4, 158000.00, NULL),
(19, 'Arsat', '30-70700019-2', 1, 87000.00, NULL),
(20, 'Banco Nación', '30-70700020-1', 2, 210000.00, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Usuarios`
--

CREATE TABLE `Usuarios` (
  `idUsuario` int(11) NOT NULL,
  `login` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `clave` varchar(64) NOT NULL,
  `contadorSesion` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `Usuarios`
--

INSERT INTO `Usuarios` (`idUsuario`, `login`, `apellido`, `nombre`, `clave`, `contadorSesion`) VALUES
(1, 'usuario1', 'Nicolás', 'Branca', 'e2c865db4162bed963bfaa9ef6ac18f0b0e5f8fadaf9963c0b4d7c52b064bfa4', 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `CondicionIVA`
--
ALTER TABLE `CondicionIVA`
  ADD PRIMARY KEY (`idIVA`);

--
-- Indices de la tabla `Proveedores`
--
ALTER TABLE `Proveedores`
  ADD PRIMARY KEY (`CodProveedor`),
  ADD UNIQUE KEY `CUIT` (`CUIT`),
  ADD KEY `idIVA` (`idIVA`);

--
-- Indices de la tabla `Usuarios`
--
ALTER TABLE `Usuarios`
  ADD PRIMARY KEY (`idUsuario`),
  ADD UNIQUE KEY `login` (`login`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `CondicionIVA`
--
ALTER TABLE `CondicionIVA`
  MODIFY `idIVA` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `Proveedores`
--
ALTER TABLE `Proveedores`
  MODIFY `CodProveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `Usuarios`
--
ALTER TABLE `Usuarios`
  MODIFY `idUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `Proveedores`
--
ALTER TABLE `Proveedores`
  ADD CONSTRAINT `Proveedores_ibfk_1` FOREIGN KEY (`idIVA`) REFERENCES `CondicionIVA` (`idIVA`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
