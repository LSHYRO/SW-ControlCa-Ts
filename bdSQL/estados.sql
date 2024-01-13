-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-12-2023 a las 22:14:26
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bd-controlescolar`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados`
--
/*
CREATE TABLE `estados` (
  `idEstado` bigint(20) UNSIGNED NOT NULL,
  `estado` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
*/
--
-- Volcado de datos para la tabla `estados`
--

INSERT INTO `estados` (`idEstado`, `estado`, `created_at`, `updated_at`) VALUES
(1, 'Aguascalientes', '2023-12-10 08:04:00', '2023-12-10 08:04:00'),
(2, 'Baja California', '2023-12-10 08:04:00', '2023-12-10 08:04:00'),
(3, 'Baja California Sur', '2023-12-10 08:04:00', '2023-12-10 08:04:00'),
(4, 'Campeche', '2023-12-10 08:04:00', '2023-12-10 08:04:00'),
(5, 'Coahuila de Zaragoza', '2023-12-10 08:04:00', '2023-12-10 08:04:00'),
(6, 'Colima', '2023-12-10 08:04:00', '2023-12-10 08:04:00'),
(7, 'Chiapas', '2023-12-10 08:04:00', '2023-12-10 08:04:00'),
(8, 'Chihuahua', '2023-12-10 08:04:00', '2023-12-10 08:04:00'),
(9, 'Ciudad de Mexico', '2023-12-10 08:04:00', '2023-12-10 08:04:00'),
(10, 'Durango', '2023-12-10 08:04:00', '2023-12-10 08:04:00'),
(11, 'Guanajuato', '2023-12-10 08:04:00', '2023-12-10 08:04:00'),
(12, 'Guerrero', '2023-12-10 08:04:00', '2023-12-10 08:04:00'),
(13, 'Hidalgo', '2023-12-10 08:04:00', '2023-12-10 08:04:00'),
(14, 'Jalisco', '2023-12-10 08:04:00', '2023-12-10 08:04:00'),
(15, 'Mexico', '2023-12-10 08:04:00', '2023-12-10 08:04:00'),
(16, 'Michoacan de Ocampo', '2023-12-10 08:04:00', '2023-12-10 08:04:00'),
(17, 'Morelos', '2023-12-10 08:04:00', '2023-12-10 08:04:00'),
(18, 'Nayarit', '2023-12-10 08:04:00', '2023-12-10 08:04:00'),
(19, 'Nuevo Leon', '2023-12-10 08:04:00', '2023-12-10 08:04:00'),
(20, 'Oaxaca', '2023-12-10 08:04:00', '2023-12-10 08:04:00'),
(21, 'Puebla', '2023-12-10 08:04:00', '2023-12-10 08:04:00'),
(22, 'Queretaro', '2023-12-10 08:04:00', '2023-12-10 08:04:00'),
(23, 'Quintana Roo', '2023-12-10 08:04:00', '2023-12-10 08:04:00'),
(24, 'San Luis Potosi', '2023-12-10 08:04:00', '2023-12-10 08:04:00'),
(25, 'Sinaloa', '2023-12-10 08:04:00', '2023-12-10 08:04:00'),
(26, 'Sonora', '2023-12-10 08:04:00', '2023-12-10 08:04:00'),
(27, 'Tabasco', '2023-12-10 08:04:00', '2023-12-10 08:04:00'),
(28, 'Tamaulipas', '2023-12-10 08:04:00', '2023-12-10 08:04:00'),
(29, 'Tlaxcala', '2023-12-10 08:04:00', '2023-12-10 08:04:00'),
(30, 'Veracruz de Ignacio de la Llave', '2023-12-10 08:04:00', '2023-12-10 08:04:00'),
(31, 'Yucatan', '2023-12-10 08:04:00', '2023-12-10 08:04:00'),
(32, 'Zacatecas', '2023-12-10 08:04:00', '2023-12-10 08:04:00');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `estados`
--
ALTER TABLE `estados`
  ADD PRIMARY KEY (`idEstado`),
  ADD UNIQUE KEY `estados_estado_unique` (`estado`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `estados`
--
ALTER TABLE `estados`
  MODIFY `idEstado` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
