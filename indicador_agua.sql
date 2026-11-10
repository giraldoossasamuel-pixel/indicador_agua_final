-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-11-2026 a las 20:13:22
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
-- Base de datos: `indicador_agua`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contactos`
--

CREATE TABLE `contactos` (
  `id_contacto` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `nombre` varchar(80) NOT NULL,
  `correo` varchar(120) NOT NULL,
  `asunto` varchar(120) NOT NULL,
  `mensaje` text NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `contactos`
--

INSERT INTO `contactos` (`id_contacto`, `id_usuario`, `nombre`, `correo`, `asunto`, `mensaje`, `fecha_registro`) VALUES
(1, 1, 'tomas', 'tomasj@gmail.com', 'bien', 'bien', '2026-10-01 23:44:57'),
(2, NULL, 'Tomas Alberto', 'jimenezmoralestomasalberto@ieru.edu.co', 'mal gasto huerta', 'ddd', '2026-11-03 20:37:11');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `galeria`
--

CREATE TABLE `galeria` (
  `id_imagen` int(11) NOT NULL,
  `titulo` varchar(120) NOT NULL,
  `ruta_imagen` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `galeria`
--

INSERT INTO `galeria` (`id_imagen`, `titulo`, `ruta_imagen`, `descripcion`, `fecha_registro`) VALUES
(1, 'Huerta sostenible', 'imagenes/imagen-huerta.jpg', 'Huerta con cultivos saludables y prácticas sostenibles.', '2026-10-01 23:42:24'),
(2, 'Riego por goteo', 'imagenes/imagen-riego-goteo.jpg', 'Sistema de riego localizado.', '2026-10-01 23:42:25'),
(3, 'Sensor de humedad', 'imagenes/imagen-sensor-humedad.jpg', 'Sensor para observar la humedad del suelo.', '2026-10-01 23:42:25'),
(4, 'Monitoreo inteligente', 'imagenes/imagen-monitoreo-inteligente.jpg', 'Tecnología aplicada al seguimiento de la huerta.', '2026-10-01 23:42:25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mediciones`
--

CREATE TABLE `mediciones` (
  `id_medicion` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `consumo_litros` decimal(8,2) NOT NULL,
  `humedad` decimal(5,2) NOT NULL,
  `agua_ahorrada` decimal(8,2) NOT NULL,
  `eficiencia` decimal(5,2) NOT NULL,
  `fecha_medicion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `mediciones`
--

INSERT INTO `mediciones` (`id_medicion`, `id_usuario`, `consumo_litros`, `humedad`, `agua_ahorrada`, `eficiencia`, `fecha_medicion`) VALUES
(1, 1, 42.50, 62.00, 18.40, 78.00, '2026-09-29 08:00:00'),
(2, 1, 45.20, 65.00, 19.80, 80.00, '2026-09-29 09:00:00'),
(3, 1, 47.10, 68.00, 20.50, 82.00, '2026-09-29 10:00:00'),
(4, 1, 48.50, 67.00, 21.30, 82.00, '2026-09-29 11:00:00'),
(5, 1, 46.80, 70.00, 22.10, 84.00, '2026-09-29 12:00:00'),
(6, 1, 44.60, 72.00, 23.00, 86.00, '2026-09-29 13:00:00'),
(7, 1, 58.30, 78.00, 21.50, 76.00, '2026-10-01 13:44:21'),
(8, 1, 58.10, 80.00, 14.40, 93.00, '2026-10-01 13:47:54'),
(9, 1, 48.70, 68.00, 12.70, 94.00, '2026-10-01 15:06:19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(120) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `rol` enum('usuario','admin') NOT NULL DEFAULT 'usuario'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `correo`, `clave`, `fecha_registro`, `rol`) VALUES
(1, 'tomas', 'tomasj@gmail.com', '$2y$10$3JfxWb/fKCsV4qvn/cU2Yu7ALsVPMaGn0wEjSU0hf9taWN79LHsf.', '2026-10-01 23:43:46', 'admin'),
(2, 'Giraldo Ossa Samuel', 'giraldoossasamuel@ieru.edu.co', '$2y$10$dxDmwHnC1XVc5Sr.80lrVeObJAxxKHpG3hEJVmim5INgENJLlXD3K', '2026-10-02 00:37:10', 'usuario');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `contactos`
--
ALTER TABLE `contactos`
  ADD PRIMARY KEY (`id_contacto`),
  ADD KEY `idx_contacto_usuario` (`id_usuario`);

--
-- Indices de la tabla `galeria`
--
ALTER TABLE `galeria`
  ADD PRIMARY KEY (`id_imagen`);

--
-- Indices de la tabla `mediciones`
--
ALTER TABLE `mediciones`
  ADD PRIMARY KEY (`id_medicion`),
  ADD KEY `idx_fecha_medicion` (`fecha_medicion`),
  ADD KEY `idx_medicion_usuario` (`id_usuario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `contactos`
--
ALTER TABLE `contactos`
  MODIFY `id_contacto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `galeria`
--
ALTER TABLE `galeria`
  MODIFY `id_imagen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `mediciones`
--
ALTER TABLE `mediciones`
  MODIFY `id_medicion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `contactos`
--
ALTER TABLE `contactos`
  ADD CONSTRAINT `fk_contactos_usuarios` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `mediciones`
--
ALTER TABLE `mediciones`
  ADD CONSTRAINT `fk_mediciones_usuarios` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
