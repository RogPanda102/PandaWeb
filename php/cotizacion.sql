-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-06-2024 a las 17:57:37
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
-- Base de datos: `cotizacion`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `formulario`
--

CREATE TABLE `formulario` (
  `id_formulario` int(5) NOT NULL,
  `nombre_completo` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `telefono` int(10) NOT NULL,
  `ciudad` varchar(50) NOT NULL,
  `empresa` varchar(50) NOT NULL,
  `sector` varchar(200) NOT NULL,
  `sitioWeb` varchar(2) NOT NULL,
  `dominio` varchar(200) NOT NULL,
  `imagen` varchar(50) NOT NULL,
  `modificaciones` varchar(1000) NOT NULL,
  `fecha_limite` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `presupuesto` int(10) NOT NULL,
  `posible_fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `formulario`
--

INSERT INTO `formulario` (`id_formulario`, `nombre_completo`, `email`, `telefono`, `ciudad`, `empresa`, `sector`, `sitioWeb`, `dominio`, `imagen`, `modificaciones`, `fecha_limite`, `presupuesto`, `posible_fecha`) VALUES
(18, 'mau', 'siri@gmail.com', 2147483647, 'siri', 'siri', 'zxdcfvgb', 'no', '', 'galeria-img/consumible.jpg', '', '2024-05-30 23:05:37', 0, '2024-05-31'),
(19, 'suleyma pichon pichon', 'sule@gmail.com', 2147483647, 'Tlaxcala', 'panda', 'asdfgh', 'no', '', 'galeria-img/guero.jpeg', '', '2024-05-30 23:05:37', 0, '2024-05-31'),
(20, 'suleyma pichon pichon', 'sule@gmail.com', 2147483647, 'Tlaxcala', 'panda', 'szdxfcghjk', 'NO', '', 'galeria-img/mision.jpg', '', '2024-05-30 23:05:37', 0, '2024-05-31'),
(21, 'suleyma pichon pichon', 'sule@gmail.com', 2147483647, 'Tlaxcala', 'panda', 'sdfgh', 'NO', '', 'galeria-img/Captura de pantalla_15-4-2024_162534_l', '', '2024-05-30 23:05:37', 0, '2024-05-31'),
(22, 'suleyma pichon pichon', 'sule@gmail.com', 2147483647, 'Tlaxcala', 'panda', 'sdfgh', 'NO', '', 'galeria-img/Captura de pantalla_15-4-2024_162534_l', '', '2024-05-30 23:05:37', 0, '2024-05-31'),
(23, 'suleyma pichon pichon', 'sule@gmail.com', 2147483647, 'Tlaxcala', 'panda', 'dasdas', 'SI', 'https://web.telegram.org/k/', 'galeria-img/', '', '2024-05-30 23:05:37', 0, '2024-05-31'),
(24, 'suleyma pichon pichon', 'sule@gmail.com', 2147483647, 'Tlaxcala', 'panda', '', 'SI', 'https://web.telegram.org/k/', 'galeria-img/', '', '2024-05-30 23:05:37', 0, '2024-05-31'),
(25, 'prueba 21', 'rosita@gmail.com', 2147483647, 'santa cruz quilehtla', 'pandasoft', 'sdfghnm', 'NO', '', 'galeria-img/1.jpg', '', '0000-00-00 00:00:00', 0, '2024-05-31'),
(26, 'viernes', 'viernes@gmail.com', 2147483647, 'viernes', 'viernes', 'viernes', 'NO', '', 'galeria-img/tigresa.jpeg', '', '0000-00-00 00:00:00', 0, '2024-05-31'),
(27, 'gato', 'gato@gmail.com', 2147483647, 'gato', 'gatocat', 'ASDF', 'SI', 'https://web.telegram.org/k/', 'galeria-img/', '', '2024-06-01 06:00:00', 123, '0000-00-00');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `formulario`
--
ALTER TABLE `formulario`
  ADD PRIMARY KEY (`id_formulario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `formulario`
--
ALTER TABLE `formulario`
  MODIFY `id_formulario` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
