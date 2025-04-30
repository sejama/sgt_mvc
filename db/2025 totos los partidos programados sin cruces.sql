-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 30-04-2025 a las 12:02:52
-- Versión del servidor: 10.11.10-MariaDB-log
-- Versión de PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `u283477853_sgt`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cancha`
--

CREATE TABLE `cancha` (
  `id` int(11) NOT NULL,
  `sede_id` int(11) DEFAULT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cancha`
--

INSERT INTO `cancha` (`id`, `sede_id`, `nombre`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, 1, 'Cancha 1', 'Cancha 1', '2025-04-28 21:13:08', '2025-04-28 21:13:08'),
(2, 1, 'Cancha 2', 'Cancha 2', '2025-04-28 21:13:18', '2025-04-28 21:13:18'),
(3, 1, 'Cancha 3', '', '2025-04-28 21:13:26', '2025-04-28 21:13:26'),
(4, 2, 'Cancha 1', 'Cancha 1', '2025-04-28 21:14:15', '2025-04-28 21:14:15'),
(5, 2, 'Cancha 2', 'Cancha 2', '2025-04-28 21:14:25', '2025-04-28 21:14:25'),
(6, 3, 'Cancha 1', 'Cancha 1', '2025-04-28 21:14:55', '2025-04-28 21:14:55'),
(7, 3, 'Cancha 2', 'Cancha 2', '2025-04-28 21:15:08', '2025-04-28 21:15:08'),
(8, 3, 'Cancha 3', 'Cancha 3', '2025-04-28 23:46:50', '2025-04-28 23:46:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id` int(11) NOT NULL,
  `torneo_id` int(11) DEFAULT NULL,
  `nombre` varchar(128) NOT NULL,
  `genero` varchar(255) NOT NULL,
  `disputa` longtext DEFAULT NULL,
  `estado` varchar(32) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `nombre_corto` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id`, `torneo_id`, `nombre`, `genero`, `disputa`, `estado`, `created_at`, `updated_at`, `nombre_corto`) VALUES
(1, 1, 'F+35', 'Femenino', NULL, 'Zonas_creadas', '2025-04-28 20:06:06', '2025-04-28 20:51:23', 'F+35'),
(2, 1, 'F+40', 'Femenino', NULL, 'Zonas_creadas', '2025-04-28 20:27:28', '2025-04-28 21:00:46', 'F+40'),
(3, 1, 'F+45', 'Femenino', NULL, 'Zonas_creadas', '2025-04-28 20:34:08', '2025-04-28 22:09:58', 'F+45'),
(4, 1, 'F+50', 'Femenino', NULL, 'Zonas_creadas', '2025-04-28 20:36:18', '2025-04-28 21:05:34', 'F+50'),
(5, 1, 'M+42', 'Masculino', NULL, 'Zonas_creadas', '2025-04-28 20:42:26', '2025-04-29 09:34:22', 'M+42'),
(6, 1, 'M+50', 'Masculino', NULL, 'Zonas_creadas', '2025-04-28 20:46:44', '2025-04-29 09:40:00', 'M+50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20250306221853', '2025-04-29 02:36:36', 1288);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipo`
--

CREATE TABLE `equipo` (
  `id` int(11) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `grupo_id` int(11) DEFAULT NULL,
  `nombre` varchar(128) NOT NULL,
  `nombre_corto` varchar(16) NOT NULL,
  `pais` varchar(128) DEFAULT NULL,
  `provincia` varchar(128) DEFAULT NULL,
  `localidad` varchar(128) DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `estado` varchar(32) NOT NULL,
  `numero` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `equipo`
--

INSERT INTO `equipo` (`id`, `categoria_id`, `grupo_id`, `nombre`, `nombre_corto`, `pais`, `provincia`, `localidad`, `created_at`, `updated_at`, `estado`, `numero`) VALUES
(1, 1, 1, 'Villa Dora', 'Villa Dora', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:07:07', '2025-04-28 23:49:22', 'Activo', 1),
(2, 1, 1, 'IMEI', 'IMEI', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:07:40', '2025-04-28 23:49:22', 'Activo', 2),
(3, 1, 1, 'Malucas', 'Malucas', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:08:12', '2025-04-28 23:49:22', 'Activo', 3),
(4, 1, 1, 'Romang Futbol Club', 'Romang FC', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:08:58', '2025-04-28 23:49:22', 'Activo', 4),
(5, 1, 1, 'Club Atletico Fisherton ', 'CA Fisherton ', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:14:23', '2025-04-28 23:49:22', 'Activo', 5),
(6, 1, 2, 'Regatas SF', 'Regatas SF', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:17:53', '2025-04-28 23:49:22', 'Activo', 6),
(7, 1, 2, 'Jerárquicas', 'Jerárquicas', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:18:31', '2025-04-28 23:49:22', 'Activo', 7),
(8, 1, 2, 'Regatas Rosario', 'Regatas Rosario', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:18:59', '2025-04-28 23:49:22', 'Activo', 8),
(9, 1, 2, 'ZED', 'ZED', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:19:33', '2025-04-28 23:49:22', 'Activo', 9),
(10, 1, 2, 'Nautico Zárate', 'Nautico Zárate', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:20:01', '2025-04-28 23:49:22', 'Activo', 10),
(11, 1, 3, 'El Quilla ', 'El Quilla ', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:20:46', '2025-04-28 23:49:22', 'Activo', 11),
(12, 1, 3, 'Santoto Voley', 'Santoto', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:21:47', '2025-04-28 23:49:22', 'Activo', 12),
(13, 1, 3, 'Club Barrio Norte Avellaneda', 'Barrio Norte', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:22:26', '2025-04-28 23:49:22', 'Activo', 13),
(14, 1, 3, 'Las Cuervas', 'Las Cuervas', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:23:19', '2025-04-28 23:49:22', 'Activo', 14),
(15, 1, 3, 'Club Junin', 'Club Junin', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:23:47', '2025-04-28 23:49:22', 'Activo', 15),
(16, 2, 4, 'Mala Mia', 'Mala Mia', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:28:45', '2025-04-29 09:12:35', 'Activo', 16),
(17, 2, 4, 'Cett', 'Cett', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:29:35', '2025-04-29 09:12:35', 'Activo', 17),
(18, 2, 4, 'Las Vascas', 'Las Vascas', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:29:55', '2025-04-29 09:12:35', 'Activo', 18),
(19, 2, 4, 'Las Patos', 'Las Patos', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:30:14', '2025-04-29 09:12:35', 'Activo', 19),
(20, 2, 4, 'Club Atletico Alumni Casilda', 'CA Alumni C', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:31:12', '2025-04-29 09:12:35', 'Activo', 20),
(21, 2, 4, 'Infinito', 'Infinito', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:31:32', '2025-04-29 09:12:35', 'Activo', 21),
(22, 2, 4, 'La 18', 'La 18', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:31:50', '2025-04-29 09:12:35', 'Activo', 22),
(23, 2, 5, 'Villa Dora', 'Villa Dora', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:32:10', '2025-04-29 09:12:36', 'Activo', 23),
(24, 2, 5, 'Costa Canelones', 'Costa Canelones', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:32:31', '2025-04-29 09:12:36', 'Activo', 24),
(25, 2, 5, 'Maxi Lanus', 'Maxi Lanus', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:32:54', '2025-04-29 09:12:36', 'Activo', 25),
(26, 2, 5, 'Vamos el apoyo', 'Vamos el apoyo', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:33:11', '2025-04-29 09:12:36', 'Activo', 26),
(27, 2, 5, 'Trede Birra', 'Trede Birra', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:33:31', '2025-04-29 09:12:36', 'Activo', 27),
(28, 2, 5, 'ATR', 'ATR', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:33:46', '2025-04-29 09:12:36', 'Activo', 28),
(29, 3, 6, 'NNV', 'NNV', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:34:52', '2025-04-29 09:22:30', 'Activo', 29),
(30, 3, 6, 'E.L.V.', 'E.L.V.', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:35:06', '2025-04-29 09:22:30', 'Activo', 30),
(31, 3, 6, 'Costa mix ', 'Costa mix ', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:35:36', '2025-04-29 09:22:30', 'Activo', 31),
(32, 3, 6, 'Las Inter', 'Las Inter', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:35:48', '2025-04-29 09:22:30', 'Activo', 32),
(33, 3, 6, 'UNI', 'UNI', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:36:01', '2025-04-29 09:22:30', 'Activo', 33),
(34, 3, 6, 'Banco SF', 'Banco SF', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 21:44:02', '2025-04-29 09:22:30', 'Activo', 34),
(35, 4, 7, 'Recalculando', 'Recalculando', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:38:51', '2025-04-29 09:27:38', 'Activo', 35),
(36, 4, 7, 'Infinito', 'Infinito', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:39:09', '2025-04-29 09:27:38', 'Activo', 36),
(37, 4, 7, 'Guemes Salta', 'Guemes Salta', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:39:30', '2025-04-29 09:27:38', 'Activo', 37),
(38, 4, 7, 'Somos 8 reinas', 'Somos 8 reinas', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:39:56', '2025-04-29 09:27:38', 'Activo', 38),
(39, 4, 7, 'Nautico Zárate', 'Nautico Zárate', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:40:10', '2025-04-29 09:27:38', 'Activo', 39),
(40, 4, 7, 'GyE Concepción del Uruguay', 'GyE C Uruguay', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:41:04', '2025-04-29 09:27:38', 'Activo', 40),
(41, 4, 7, 'Las Leonesas', 'Las Leonesas', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:41:20', '2025-04-29 09:27:38', 'Activo', 41),
(42, 5, 8, 'Ferrocarril de Vera', 'Ferrocarril Vera', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:42:57', '2025-04-29 09:35:26', 'Activo', 42),
(43, 5, 8, 'No Pasa Naranja (NPN)', 'NPN', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:43:28', '2025-04-29 09:35:26', 'Activo', 43),
(44, 5, 8, 'Amigos del Bosque', 'Amigos Bosque', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:43:52', '2025-04-29 09:35:26', 'Activo', 44),
(45, 5, 8, 'Romang FC', 'Romang FC', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:44:19', '2025-04-29 09:35:26', 'Activo', 45),
(46, 5, 8, 'Maxi SF', 'Maxi SF', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:44:38', '2025-04-29 09:35:26', 'Activo', 46),
(47, 6, 9, 'Maxi SF', 'Maxi SF', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:47:07', '2025-04-29 09:44:16', 'Activo', 47),
(48, 6, 9, 'La Tribu', 'La Tribu', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:47:28', '2025-04-29 09:44:16', 'Activo', 48),
(49, 6, 9, 'CTGUSJ', 'CTGUSJ', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:47:44', '2025-04-29 09:44:16', 'Activo', 49),
(50, 6, 9, 'Las Flores', 'Las Flores', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:48:03', '2025-04-29 09:44:16', 'Activo', 50),
(51, 6, 10, 'Los Perkin', 'Los Perkin', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:48:19', '2025-04-29 09:44:16', 'Activo', 51),
(52, 6, 10, 'Defensores de Moreno', 'Def Moreno', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:48:46', '2025-04-29 09:44:16', 'Activo', 52),
(53, 6, 10, 'Rosario Voley', 'Rosario Voley', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:49:01', '2025-04-29 09:44:16', 'Activo', 53),
(54, 6, 10, 'Vintage', 'Vintage', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:49:16', '2025-04-29 09:44:16', 'Activo', 54);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupo`
--

CREATE TABLE `grupo` (
  `id` int(11) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `nombre` varchar(25) NOT NULL,
  `clasifica_oro` int(11) NOT NULL,
  `clasifica_plata` int(11) DEFAULT NULL,
  `clasifica_bronce` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `estado` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `grupo`
--

INSERT INTO `grupo` (`id`, `categoria_id`, `nombre`, `clasifica_oro`, `clasifica_plata`, `clasifica_bronce`, `created_at`, `updated_at`, `estado`) VALUES
(1, 1, 'A', 2, 2, 1, '2025-04-28 23:49:07', '2025-04-28 23:49:07', 'Borrador'),
(2, 1, 'B', 2, 2, 1, '2025-04-28 23:49:07', '2025-04-28 23:49:07', 'Borrador'),
(3, 1, 'C', 2, 2, 1, '2025-04-28 23:49:07', '2025-04-28 23:49:07', 'Borrador'),
(4, 2, 'D', 1, 1, NULL, '2025-04-29 09:12:02', '2025-04-29 09:12:02', 'Borrador'),
(5, 2, 'E', 1, 1, NULL, '2025-04-29 09:12:02', '2025-04-29 09:12:02', 'Borrador'),
(6, 3, 'F', 4, 2, NULL, '2025-04-29 09:21:57', '2025-04-29 09:21:57', 'Borrador'),
(7, 4, 'G', 2, 2, NULL, '2025-04-29 09:26:15', '2025-04-29 09:26:15', 'Borrador'),
(8, 5, '1', 4, NULL, NULL, '2025-04-29 09:34:22', '2025-04-29 09:34:25', 'Finalizado'),
(9, 6, '2', 4, NULL, NULL, '2025-04-29 09:40:00', '2025-04-29 09:40:00', 'Borrador'),
(10, 6, '3', 4, NULL, NULL, '2025-04-29 09:40:00', '2025-04-29 09:40:00', 'Borrador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jugador`
--

CREATE TABLE `jugador` (
  `id` int(11) NOT NULL,
  `equipo_id` int(11) DEFAULT NULL,
  `nombre` varchar(128) NOT NULL,
  `apellido` varchar(128) NOT NULL,
  `tipo_documento` varchar(12) NOT NULL,
  `numero_documento` varchar(15) NOT NULL,
  `nacimiento` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `responsable` tinyint(1) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `celular` varchar(32) DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `tipo` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `jugador`
--

INSERT INTO `jugador` (`id`, `equipo_id`, `nombre`, `apellido`, `tipo_documento`, `numero_documento`, `nacimiento`, `responsable`, `email`, `celular`, `created_at`, `updated_at`, `tipo`) VALUES
(1, 1, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 20:07:07', '2025-04-28 20:07:07', 'Entrenador'),
(2, 2, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 20:07:40', '2025-04-28 20:07:40', 'Entrenador'),
(3, 3, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 20:08:12', '2025-04-28 20:08:12', 'Entrenador'),
(4, 4, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 20:08:58', '2025-04-28 20:08:58', 'Entrenador'),
(5, 5, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 20:14:23', '2025-04-28 20:14:23', 'Entrenador'),
(6, 6, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 20:17:53', '2025-04-28 20:17:53', 'Entrenador'),
(7, 7, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 20:18:31', '2025-04-28 20:18:31', 'Entrenador'),
(8, 8, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 20:18:59', '2025-04-28 20:18:59', 'Entrenador'),
(9, 9, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 20:19:33', '2025-04-28 20:19:33', 'Entrenador'),
(10, 10, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 20:20:01', '2025-04-28 20:20:01', 'Entrenador'),
(11, 11, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 20:20:46', '2025-04-28 20:20:46', 'Entrenador'),
(12, 12, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 20:21:47', '2025-04-28 20:21:47', 'Entrenador'),
(13, 13, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 20:22:26', '2025-04-28 20:22:26', 'Entrenador'),
(14, 14, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 20:23:19', '2025-04-28 20:23:19', 'Entrenador'),
(15, 15, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 20:23:47', '2025-04-28 20:23:47', 'Entrenador'),
(16, 16, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 20:28:45', '2025-04-28 20:28:45', 'Entrenador'),
(17, 17, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 20:29:35', '2025-04-28 20:29:35', 'Entrenador'),
(18, 18, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 20:29:55', '2025-04-28 20:29:55', 'Entrenador'),
(19, 19, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 20:30:14', '2025-04-28 20:30:14', 'Entrenador'),
(20, 20, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 20:31:12', '2025-04-28 20:31:12', 'Entrenador'),
(21, 21, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 20:31:32', '2025-04-28 20:31:32', 'Entrenador'),
(22, 22, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 20:31:50', '2025-04-28 20:31:50', 'Entrenador'),
(23, 23, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 20:32:10', '2025-04-28 20:32:10', 'Entrenador'),
(24, 24, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 20:32:31', '2025-04-28 20:32:31', 'Entrenador'),
(25, 25, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 20:32:54', '2025-04-28 20:32:54', 'Entrenador'),
(26, 26, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 20:33:11', '2025-04-28 20:33:11', 'Entrenador'),
(27, 27, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 20:33:31', '2025-04-28 20:33:31', 'Entrenador'),
(28, 28, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 20:33:46', '2025-04-28 20:33:46', 'Entrenador'),
(29, 29, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 20:34:52', '2025-04-28 20:34:52', 'Entrenador'),
(30, 30, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 20:35:06', '2025-04-28 20:35:06', 'Entrenador'),
(31, 31, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 20:35:36', '2025-04-28 20:35:36', 'Entrenador'),
(32, 32, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 20:35:48', '2025-04-28 20:35:48', 'Entrenador'),
(33, 33, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 20:36:01', '2025-04-28 20:36:01', 'Entrenador'),
(34, 34, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 20:38:51', '2025-04-28 20:38:51', 'Entrenador'),
(35, 35, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 20:39:09', '2025-04-28 20:39:09', 'Entrenador'),
(36, 36, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 20:39:30', '2025-04-28 20:39:30', 'Entrenador'),
(37, 37, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 20:39:56', '2025-04-28 20:39:56', 'Entrenador'),
(38, 38, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 20:40:10', '2025-04-28 20:40:10', 'Entrenador'),
(39, 39, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 20:41:04', '2025-04-28 20:41:04', 'Entrenador'),
(40, 40, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 20:41:20', '2025-04-28 20:41:20', 'Entrenador'),
(41, 41, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 20:42:57', '2025-04-28 20:42:57', 'Entrenador'),
(42, 42, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 20:43:28', '2025-04-28 20:43:28', 'Entrenador'),
(43, 43, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 20:43:52', '2025-04-28 20:43:52', 'Entrenador'),
(44, 44, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 20:44:19', '2025-04-28 20:44:19', 'Entrenador'),
(45, 45, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 20:44:38', '2025-04-28 20:44:38', 'Entrenador'),
(46, 46, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 20:47:07', '2025-04-28 20:47:07', 'Entrenador'),
(47, 47, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 20:47:28', '2025-04-28 20:47:28', 'Entrenador'),
(48, 48, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 20:47:44', '2025-04-28 20:47:44', 'Entrenador'),
(49, 49, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 20:48:03', '2025-04-28 20:48:03', 'Entrenador'),
(50, 50, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 20:48:19', '2025-04-28 20:48:19', 'Entrenador'),
(51, 51, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 20:48:46', '2025-04-28 20:48:46', 'Entrenador'),
(52, 52, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 20:49:01', '2025-04-28 20:49:01', 'Entrenador'),
(53, 53, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 20:49:16', '2025-04-28 20:49:16', 'Entrenador'),
(54, 54, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 21:44:02', '2025-04-28 21:44:02', 'Entrenador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `partido`
--

CREATE TABLE `partido` (
  `id` int(11) NOT NULL,
  `cancha_id` int(11) DEFAULT NULL,
  `categoria_id` int(11) NOT NULL,
  `grupo_id` int(11) DEFAULT NULL,
  `equipo_local_id` int(11) DEFAULT NULL,
  `equipo_visitante_id` int(11) DEFAULT NULL,
  `horario` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `local_set1` smallint(6) DEFAULT NULL,
  `local_set2` smallint(6) DEFAULT NULL,
  `local_set3` smallint(6) DEFAULT NULL,
  `local_set4` smallint(6) DEFAULT NULL,
  `local_set5` smallint(6) DEFAULT NULL,
  `visitante_set1` smallint(6) DEFAULT NULL,
  `visitante_set2` smallint(6) DEFAULT NULL,
  `visitante_set3` smallint(6) DEFAULT NULL,
  `visitante_set4` smallint(6) DEFAULT NULL,
  `visitante_set5` smallint(6) DEFAULT NULL,
  `estado` varchar(32) NOT NULL,
  `tipo` varchar(32) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `numero` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `partido`
--

INSERT INTO `partido` (`id`, `cancha_id`, `categoria_id`, `grupo_id`, `equipo_local_id`, `equipo_visitante_id`, `horario`, `local_set1`, `local_set2`, `local_set3`, `local_set4`, `local_set5`, `visitante_set1`, `visitante_set2`, `visitante_set3`, `visitante_set4`, `visitante_set5`, `estado`, `tipo`, `created_at`, `updated_at`, `numero`) VALUES
(1, 2, 1, 1, 1, 2, '2025-05-01 20:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-04-29 19:00:16', 1),
(2, 1, 1, 1, 1, 3, '2025-05-02 13:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-04-29 14:14:11', 2),
(3, 2, 1, 1, 1, 4, '2025-05-01 16:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-04-29 18:58:31', 3),
(4, 1, 1, 1, 1, 5, '2025-05-02 16:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-04-29 14:15:12', 4),
(5, 2, 1, 1, 2, 3, '2025-05-02 11:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-04-29 19:02:45', 5),
(6, 1, 1, 1, 2, 4, '2025-05-02 14:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-04-29 14:14:31', 6),
(7, 2, 1, 1, 2, 5, '2025-05-01 14:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-04-29 18:57:04', 7),
(8, 2, 1, 1, 3, 4, '2025-05-01 21:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-04-29 19:00:28', 8),
(9, 2, 1, 1, 3, 5, '2025-05-01 19:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-04-29 18:59:56', 9),
(10, 1, 1, 1, 4, 5, '2025-05-02 11:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-04-29 14:09:46', 10),
(11, 4, 1, 2, 6, 7, '2025-05-02 14:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-04-29 19:57:55', 11),
(12, 4, 1, 2, 6, 8, '2025-05-01 14:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-04-29 19:41:09', 12),
(13, 4, 1, 2, 6, 9, '2025-05-01 16:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-04-29 19:41:44', 13),
(14, 4, 1, 2, 6, 10, '2025-05-02 11:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-04-29 19:52:36', 14),
(15, 4, 1, 2, 7, 8, '2025-05-01 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-04-29 19:44:35', 15),
(16, 4, 1, 2, 7, 9, '2025-05-02 12:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-04-29 20:06:44', 16),
(17, 4, 1, 2, 7, 10, '2025-05-01 15:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-04-29 19:41:24', 17),
(18, 4, 1, 2, 8, 9, '2025-05-02 10:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-04-29 19:55:42', 18),
(19, 4, 1, 2, 8, 10, '2025-05-02 13:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-04-29 19:56:32', 19),
(20, 4, 1, 2, 9, 10, '2025-05-01 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-04-29 19:44:51', 20),
(21, 2, 1, 3, 11, 12, '2025-05-01 15:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-04-29 18:58:04', 21),
(22, 2, 1, 3, 11, 13, '2025-05-02 13:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-04-29 19:03:38', 22),
(23, 1, 1, 3, 11, 14, '2025-05-02 15:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-04-29 14:14:55', 23),
(24, 2, 1, 3, 11, 15, '2025-05-01 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-04-29 18:59:35', 24),
(25, 1, 1, 3, 12, 13, '2025-05-02 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-04-29 14:15:48', 25),
(26, 2, 1, 3, 12, 14, '2025-05-01 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-04-29 18:59:08', 26),
(27, 1, 1, 3, 12, 15, '2025-05-02 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-04-29 14:16:09', 27),
(28, 1, 1, 3, 13, 14, '2025-05-01 19:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-04-30 08:16:27', 28),
(29, 1, 1, 3, 13, 15, '2025-05-01 16:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-04-29 13:45:36', 29),
(30, 2, 1, 3, 14, 15, '2025-05-02 12:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-04-29 19:03:06', 30),
(31, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 23:56:25', '2025-04-28 23:56:25', 31),
(32, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 23:56:25', '2025-04-28 23:56:25', 32),
(33, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 23:56:25', '2025-04-28 23:56:25', 33),
(34, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 23:57:20', '2025-04-28 23:57:20', 34),
(35, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 23:57:20', '2025-04-28 23:57:20', 35),
(36, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 23:57:20', '2025-04-28 23:57:20', 36),
(37, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-29 00:01:43', '2025-04-29 00:01:43', 37),
(38, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-29 00:01:43', '2025-04-29 00:01:43', 38),
(39, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-29 00:01:43', '2025-04-29 00:01:43', 39),
(40, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-29 00:01:43', '2025-04-29 00:01:43', 40),
(41, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-29 00:01:43', '2025-04-29 00:01:43', 41),
(42, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-29 00:01:43', '2025-04-29 00:01:43', 42),
(43, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-29 00:04:32', '2025-04-29 00:04:32', 43),
(44, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-29 00:04:32', '2025-04-29 00:04:32', 44),
(45, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-29 00:04:32', '2025-04-29 00:04:32', 45),
(46, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Eliminatorio', '2025-04-29 00:13:00', '2025-04-29 00:13:00', 46),
(47, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Eliminatorio', '2025-04-29 00:13:00', '2025-04-29 00:13:00', 47),
(48, 6, 2, 4, 16, 17, '2025-05-02 10:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:12:35', '2025-04-29 20:31:53', 48),
(49, 6, 2, 4, 16, 18, '2025-05-02 14:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:12:35', '2025-04-29 20:33:07', 49),
(50, 8, 2, 4, 16, 19, '2025-05-01 16:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:12:35', '2025-04-29 20:43:23', 50),
(51, 8, 2, 4, 16, 20, '2025-05-01 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:12:35', '2025-04-29 20:43:54', 51),
(52, 6, 2, 4, 16, 21, '2025-05-02 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:12:35', '2025-04-29 20:34:08', 52),
(53, 6, 2, 4, 16, 22, '2025-05-01 14:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:12:35', '2025-04-29 20:28:36', 53),
(54, 6, 2, 4, 17, 18, '2025-05-01 16:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:12:35', '2025-04-29 20:29:21', 54),
(55, 7, 2, 4, 17, 19, '2025-05-01 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:12:35', '2025-04-29 20:37:22', 55),
(56, 6, 2, 4, 17, 20, '2025-05-02 15:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:12:35', '2025-04-29 20:33:26', 56),
(57, 6, 2, 4, 17, 21, '2025-05-02 20:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:12:35', '2025-04-29 20:35:00', 57),
(58, 6, 2, 4, 17, 22, '2025-05-02 12:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:12:36', '2025-04-29 20:32:31', 58),
(59, 6, 2, 4, 18, 19, '2025-05-02 11:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:12:36', '2025-04-29 20:32:11', 59),
(60, 7, 2, 4, 18, 20, '2025-05-01 14:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:12:36', '2025-04-29 20:35:56', 60),
(61, 6, 2, 4, 18, 21, '2025-05-01 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:12:36', '2025-04-29 20:30:05', 61),
(62, 6, 2, 4, 18, 22, '2025-05-02 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:12:36', '2025-04-29 20:34:23', 62),
(63, 6, 2, 4, 19, 20, '2025-05-02 19:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:12:36', '2025-04-29 20:34:46', 63),
(64, 8, 2, 4, 19, 21, '2025-05-01 14:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:12:36', '2025-04-29 20:42:46', 64),
(65, 6, 2, 4, 19, 22, '2025-05-02 16:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:12:36', '2025-04-29 20:33:51', 65),
(66, 6, 2, 4, 20, 21, '2025-05-02 13:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:12:36', '2025-04-29 20:32:51', 66),
(67, 7, 2, 4, 20, 22, '2025-05-01 16:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:12:36', '2025-04-29 20:36:44', 67),
(68, 6, 2, 4, 21, 22, '2025-05-01 20:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:12:36', '2025-04-29 20:30:59', 68),
(69, 2, 2, 5, 23, 24, '2025-05-02 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:12:36', '2025-04-29 19:06:56', 69),
(70, 1, 2, 5, 23, 25, '2025-05-01 14:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:12:36', '2025-04-29 13:41:57', 70),
(71, 2, 2, 5, 23, 26, '2025-05-02 15:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:12:36', '2025-04-29 19:05:05', 71),
(72, 1, 2, 5, 23, 27, '2025-05-02 10:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:12:36', '2025-04-29 14:09:14', 72),
(73, 1, 2, 5, 23, 28, '2025-05-01 20:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:12:36', '2025-04-30 08:13:26', 73),
(74, 2, 2, 5, 24, 25, '2025-05-02 14:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:12:36', '2025-04-29 19:04:24', 74),
(75, 1, 2, 5, 24, 26, '2025-05-01 21:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:12:36', '2025-04-30 08:16:51', 75),
(76, 2, 2, 5, 24, 27, '2025-05-02 16:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:12:36', '2025-04-29 19:06:03', 76),
(77, 1, 2, 5, 24, 28, '2025-05-01 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:12:36', '2025-04-29 13:46:56', 77),
(78, 2, 2, 5, 25, 26, '2025-05-02 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:12:36', '2025-04-29 19:06:23', 78),
(79, 1, 2, 5, 25, 27, '2025-05-01 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:12:36', '2025-04-29 13:46:09', 79),
(80, 2, 2, 5, 25, 28, '2025-05-02 10:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:12:36', '2025-04-29 19:02:24', 80),
(81, 1, 2, 5, 26, 27, '2025-05-01 15:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:12:36', '2025-04-29 13:43:50', 81),
(82, 2, 2, 5, 26, 28, '2025-05-02 19:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:12:36', '2025-04-29 19:07:13', 82),
(83, 1, 2, 5, 27, 28, '2025-05-02 12:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:12:36', '2025-04-29 14:13:30', 83),
(84, NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Eliminatorio', '2025-04-29 09:12:36', '2025-04-29 09:12:36', 84),
(85, NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Eliminatorio', '2025-04-29 09:12:36', '2025-04-29 09:12:36', 85),
(86, NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Eliminatorio', '2025-04-29 12:14:13', '2025-04-29 12:14:13', 86),
(87, 5, 3, 6, 29, 30, '2025-05-02 13:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:22:30', '2025-04-29 20:12:28', 87),
(88, 5, 3, 6, 29, 31, '2025-05-02 10:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:22:30', '2025-04-29 20:11:02', 88),
(89, 5, 3, 6, 29, 32, '2025-05-02 16:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:22:30', '2025-04-29 20:23:46', 89),
(90, 5, 3, 6, 29, 33, '2025-05-01 16:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:22:30', '2025-04-29 20:09:13', 90),
(91, 5, 3, 6, 29, 34, '2025-05-01 14:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:22:30', '2025-04-29 20:08:30', 91),
(92, 4, 3, 6, 30, 31, '2025-05-02 15:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:22:30', '2025-04-29 19:58:42', 92),
(93, 5, 3, 6, 30, 32, '2025-05-01 15:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:22:30', '2025-04-29 20:08:51', 93),
(94, 5, 3, 6, 30, 33, '2025-05-01 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:22:30', '2025-04-29 20:09:46', 94),
(95, 4, 3, 6, 30, 34, '2025-05-02 16:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:22:30', '2025-04-29 19:58:57', 95),
(96, 4, 3, 6, 31, 32, '2025-05-01 19:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:22:30', '2025-04-29 19:45:33', 96),
(97, 5, 3, 6, 31, 33, '2025-05-02 12:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:22:30', '2025-04-29 20:11:59', 97),
(98, 5, 3, 6, 31, 34, '2025-05-01 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:22:30', '2025-04-29 20:09:32', 98),
(99, 5, 3, 6, 32, 33, '2025-05-02 15:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:22:30', '2025-04-29 20:23:16', 99),
(100, 5, 3, 6, 32, 34, '2025-05-02 11:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:22:30', '2025-04-29 20:11:31', 100),
(101, 5, 3, 6, 33, 34, '2025-05-02 14:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:22:30', '2025-04-29 20:21:00', 101),
(102, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Eliminatorio', '2025-04-29 09:22:30', '2025-04-29 09:22:30', 102),
(103, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Eliminatorio', '2025-04-29 09:22:30', '2025-04-29 09:22:30', 103),
(104, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Eliminatorio', '2025-04-29 09:22:30', '2025-04-29 09:22:30', 104),
(105, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Eliminatorio', '2025-04-29 09:22:30', '2025-04-29 09:22:30', 105),
(106, 7, 4, 7, 35, 36, '2025-05-02 20:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:27:38', '2025-04-29 20:41:37', 106),
(107, 7, 4, 7, 35, 37, '2025-05-02 11:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:27:38', '2025-04-29 20:38:54', 107),
(108, 6, 4, 7, 35, 38, '2025-05-01 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:27:38', '2025-04-29 20:29:44', 108),
(109, 6, 4, 7, 35, 39, '2025-05-01 15:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:27:38', '2025-04-29 20:29:00', 109),
(110, 7, 4, 7, 35, 40, '2025-05-02 16:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:27:38', '2025-04-29 20:40:27', 110),
(111, 7, 4, 7, 35, 41, '2025-05-01 19:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:27:38', '2025-04-29 20:37:34', 111),
(112, 7, 4, 7, 36, 37, '2025-05-02 14:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:27:38', '2025-04-29 20:39:55', 112),
(113, 7, 4, 7, 36, 38, '2025-05-02 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:27:38', '2025-04-29 20:41:02', 113),
(114, 8, 4, 7, 36, 39, '2025-05-01 19:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:27:38', '2025-04-29 20:44:08', 114),
(115, 8, 4, 7, 36, 40, '2025-05-01 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:27:38', '2025-04-29 20:43:39', 115),
(116, 8, 4, 7, 36, 41, '2025-05-01 15:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:27:38', '2025-04-29 20:43:08', 116),
(117, 6, 4, 7, 37, 38, '2025-05-01 19:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:27:38', '2025-04-29 20:30:36', 117),
(118, 7, 4, 7, 37, 39, '2025-05-01 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:27:38', '2025-04-29 20:37:10', 118),
(119, 7, 4, 7, 37, 40, '2025-05-01 15:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:27:38', '2025-04-29 20:36:26', 119),
(120, 7, 4, 7, 37, 41, '2025-05-02 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:27:38', '2025-04-29 20:40:45', 120),
(121, 7, 4, 7, 38, 39, '2025-05-02 10:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:27:38', '2025-04-29 20:38:36', 121),
(122, 7, 4, 7, 38, 40, '2025-05-02 12:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:27:38', '2025-04-29 20:39:18', 122),
(123, 7, 4, 7, 38, 41, '2025-05-02 15:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:27:38', '2025-04-29 20:40:11', 123),
(124, 7, 4, 7, 39, 40, '2025-05-02 19:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:27:38', '2025-04-29 20:41:24', 124),
(125, 7, 4, 7, 39, 41, '2025-05-02 13:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:27:38', '2025-04-29 20:39:35', 125),
(126, 8, 4, 7, 40, 41, '2025-05-01 20:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:27:38', '2025-04-29 20:44:26', 126),
(127, NULL, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Eliminatorio', '2025-04-29 09:27:38', '2025-04-29 09:27:38', 127),
(128, NULL, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Eliminatorio', '2025-04-29 09:27:38', '2025-04-29 09:27:38', 128),
(129, NULL, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Eliminatorio', '2025-04-29 12:28:31', '2025-04-29 12:28:31', 129),
(130, 3, 5, 8, 42, 43, '2025-05-01 21:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:35:26', '2025-04-29 19:15:52', 130),
(131, 3, 5, 8, 42, 44, '2025-05-02 19:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:35:26', '2025-04-29 19:27:25', 131),
(132, 3, 5, 8, 42, 45, '2025-05-02 14:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:35:26', '2025-04-29 19:18:29', 132),
(133, 3, 5, 8, 42, 46, '2025-05-01 14:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:35:26', '2025-04-29 19:12:55', 133),
(134, 3, 5, 8, 43, 44, '2025-05-02 11:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:35:26', '2025-04-29 19:27:40', 134),
(135, 3, 5, 8, 43, 45, '2025-05-01 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:35:26', '2025-04-29 19:14:36', 135),
(136, 3, 5, 8, 43, 46, '2025-05-02 13:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:35:26', '2025-04-29 23:49:44', 136),
(137, 3, 5, 8, 44, 45, '2025-05-01 22:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:35:26', '2025-04-29 19:16:08', 137),
(138, 3, 5, 8, 44, 46, '2025-05-01 20:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:35:26', '2025-04-29 19:15:38', 138),
(139, 3, 5, 8, 45, 46, '2025-05-02 10:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:35:26', '2025-04-29 19:17:12', 139),
(140, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Eliminatorio', '2025-04-29 09:35:26', '2025-04-29 09:35:26', 140),
(141, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Eliminatorio', '2025-04-29 09:35:26', '2025-04-29 09:35:26', 141),
(142, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Eliminatorio', '2025-04-29 09:35:26', '2025-04-29 09:35:26', 142),
(143, 3, 6, 9, 47, 48, '2025-05-02 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:44:16', '2025-04-29 23:49:53', 143),
(144, 3, 6, 9, 47, 49, '2025-05-01 16:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:44:16', '2025-04-29 19:13:52', 144),
(145, 1, 6, 9, 47, 50, '2025-05-01 22:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:44:16', '2025-04-29 13:51:23', 145),
(146, 1, 6, 9, 48, 49, '2025-05-01 23:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:44:16', '2025-04-29 13:51:51', 146),
(147, 3, 6, 9, 48, 50, '2025-05-01 19:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:44:16', '2025-04-29 19:15:15', 147),
(148, 3, 6, 9, 49, 50, '2025-05-02 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:44:16', '2025-04-29 19:19:08', 148),
(149, 3, 6, 10, 51, 52, '2025-05-02 16:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:44:16', '2025-04-29 19:18:55', 149),
(150, 3, 6, 10, 51, 53, '2025-05-01 15:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:44:16', '2025-04-29 19:13:31', 150),
(151, 2, 6, 10, 51, 54, '2025-05-01 22:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:44:16', '2025-04-29 19:00:48', 151),
(152, 2, 6, 10, 52, 53, '2025-05-01 23:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:44:16', '2025-04-29 19:01:06', 152),
(153, 3, 6, 10, 52, 54, '2025-05-01 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:44:16', '2025-04-29 19:14:59', 153),
(154, 3, 6, 10, 53, 54, '2025-05-02 12:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 09:44:16', '2025-04-29 19:17:57', 154),
(155, 3, 6, NULL, NULL, NULL, '2025-05-02 20:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-04-29 09:44:16', '2025-04-29 19:35:31', 155),
(156, 3, 6, NULL, NULL, NULL, '2025-05-02 21:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-04-29 09:44:16', '2025-04-29 19:35:50', 156),
(157, 1, 6, NULL, NULL, NULL, '2025-05-02 19:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-04-29 09:44:16', '2025-04-29 14:17:09', 157),
(158, 1, 6, NULL, NULL, NULL, '2025-05-02 20:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-04-29 09:44:16', '2025-04-29 14:18:16', 158),
(159, NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Eliminatorio', '2025-04-29 09:44:16', '2025-04-29 09:44:16', 159),
(160, NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Eliminatorio', '2025-04-29 09:44:16', '2025-04-29 09:44:16', 160),
(161, NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Eliminatorio', '2025-04-29 09:44:16', '2025-04-29 09:44:16', 161),
(162, NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Eliminatorio', '2025-04-29 12:47:31', '2025-04-29 12:47:31', 162),
(163, NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Eliminatorio', '2025-04-29 12:47:31', '2025-04-29 12:47:31', 163),
(164, NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Eliminatorio', '2025-04-29 12:47:31', '2025-04-29 12:47:31', 164);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `partido_config`
--

CREATE TABLE `partido_config` (
  `id` int(11) NOT NULL,
  `partido_id` int(11) NOT NULL,
  `grupo_equipo1_id` int(11) DEFAULT NULL,
  `grupo_equipo2_id` int(11) DEFAULT NULL,
  `ganador_partido1_id` int(11) DEFAULT NULL,
  `ganador_partido2_id` int(11) DEFAULT NULL,
  `posicion_equipo1` smallint(6) DEFAULT NULL,
  `posicion_equipo2` smallint(6) DEFAULT NULL,
  `nombre` varchar(64) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `perdedor_partido1_id` int(11) DEFAULT NULL,
  `perdedor_partido2_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `partido_config`
--

INSERT INTO `partido_config` (`id`, `partido_id`, `grupo_equipo1_id`, `grupo_equipo2_id`, `ganador_partido1_id`, `ganador_partido2_id`, `posicion_equipo1`, `posicion_equipo2`, `nombre`, `created_at`, `updated_at`, `perdedor_partido1_id`, `perdedor_partido2_id`) VALUES
(1, 31, 1, 3, NULL, NULL, 1, 2, 'Triangular 1 Oro 1', '2025-04-28 23:57:20', '2025-04-28 23:57:20', NULL, NULL),
(2, 32, 2, 1, NULL, NULL, 2, 1, 'Triangular 1 Oro 2', '2025-04-28 23:57:20', '2025-04-28 23:57:20', NULL, NULL),
(3, 33, 3, 2, NULL, NULL, 2, 2, 'Triangular 1 Oro 3', '2025-04-28 23:57:20', '2025-04-28 23:57:20', NULL, NULL),
(4, 34, 2, 3, NULL, NULL, 1, 1, 'Triangular 2 Oro 1', '2025-04-28 23:57:20', '2025-04-28 23:57:20', NULL, NULL),
(5, 35, 1, 2, NULL, NULL, 2, 1, 'Triangular 2 Oro 2', '2025-04-28 23:57:20', '2025-04-28 23:57:20', NULL, NULL),
(6, 36, 3, 1, NULL, NULL, 1, 2, 'Triangular 2 Oro 3', '2025-04-28 23:57:20', '2025-04-28 23:57:20', NULL, NULL),
(7, 37, 1, 3, NULL, NULL, 3, 4, 'Triangular 1 Plata 1', '2025-04-29 00:01:43', '2025-04-29 00:01:43', NULL, NULL),
(8, 38, 2, 1, NULL, NULL, 4, 3, 'Triangular 1 Plata 2', '2025-04-29 00:01:43', '2025-04-29 00:01:43', NULL, NULL),
(9, 39, 3, 2, NULL, NULL, 4, 4, 'Triangular 1 Plata 3', '2025-04-29 00:01:43', '2025-04-29 00:01:43', NULL, NULL),
(10, 40, 2, 3, NULL, NULL, 3, 3, 'Triangular 2 Plata 1', '2025-04-29 00:01:43', '2025-04-29 00:01:43', NULL, NULL),
(11, 41, 1, 2, NULL, NULL, 4, 3, 'Triangular 2 Plata 2', '2025-04-29 00:01:43', '2025-04-29 00:01:43', NULL, NULL),
(12, 42, 3, 1, NULL, NULL, 3, 4, 'Triangular 2 Plata 3', '2025-04-29 00:01:43', '2025-04-29 00:01:43', NULL, NULL),
(13, 43, 1, 2, NULL, NULL, 5, 5, 'Triangular 1 Bronce 1', '2025-04-29 00:04:32', '2025-04-29 00:04:32', NULL, NULL),
(14, 44, 3, 1, NULL, NULL, 5, 5, 'Triangular 1 Bronce 2', '2025-04-29 00:04:32', '2025-04-29 00:04:32', NULL, NULL),
(15, 45, 2, 3, NULL, NULL, 5, 5, 'Triangular 1 Bronce 3', '2025-04-29 00:04:32', '2025-04-29 00:04:32', NULL, NULL),
(16, 46, NULL, NULL, 31, 34, NULL, NULL, 'Final Oro', '2025-04-29 11:45:16', '2025-04-29 11:45:16', NULL, NULL),
(17, 47, NULL, NULL, 37, 40, NULL, NULL, 'Final Plata', '2025-04-29 11:45:16', '2025-04-29 11:45:16', NULL, NULL),
(18, 84, 4, 5, NULL, NULL, 1, 1, 'Final Oro 1', '2025-04-29 09:12:36', '2025-04-29 09:12:36', NULL, NULL),
(19, 85, 4, 5, NULL, NULL, 3, 3, 'Final Plata 1', '2025-04-29 09:12:36', '2025-04-29 09:12:36', NULL, NULL),
(20, 86, 4, 5, NULL, NULL, 2, 2, '3y4to Final Oro', '2025-04-29 12:15:45', '2025-04-29 12:15:45', NULL, NULL),
(22, 102, 6, 6, NULL, NULL, 1, 4, 'Semi Final Oro 1', '2025-04-29 09:22:30', '2025-04-29 09:22:30', NULL, NULL),
(23, 103, 6, 6, NULL, NULL, 2, 3, 'Semi Final Oro 2', '2025-04-29 09:22:30', '2025-04-29 09:22:30', NULL, NULL),
(24, 104, NULL, NULL, 102, 103, NULL, NULL, 'Final Oro 1', '2025-04-29 09:22:30', '2025-04-29 09:22:30', NULL, NULL),
(25, 105, 6, 6, NULL, NULL, 5, 6, 'Final Plata 1', '2025-04-29 09:22:30', '2025-04-29 09:22:30', NULL, NULL),
(26, 127, 7, 7, NULL, NULL, 1, 2, 'Final Oro 1', '2025-04-29 09:27:38', '2025-04-29 09:27:38', NULL, NULL),
(27, 128, 7, 7, NULL, NULL, 5, 6, 'Final Plata 1', '2025-04-29 09:27:38', '2025-04-29 09:27:38', NULL, NULL),
(28, 129, 7, 7, NULL, NULL, 3, 4, '3y4to Final Oro', '2025-04-29 12:29:43', '2025-04-29 12:29:43', NULL, NULL),
(29, 140, 8, 8, NULL, NULL, 1, 4, 'Semi Final Oro 1', '2025-04-29 09:35:26', '2025-04-29 09:35:26', NULL, NULL),
(30, 141, 8, 8, NULL, NULL, 2, 3, 'Semi Final Oro 2', '2025-04-29 09:35:26', '2025-04-29 09:35:26', NULL, NULL),
(31, 142, NULL, NULL, 140, 141, NULL, NULL, 'Final Oro 1', '2025-04-29 09:35:26', '2025-04-29 09:35:26', NULL, NULL),
(32, 155, 9, 10, NULL, NULL, 1, 4, 'Cuartos de Final Oro 1', '2025-04-29 09:44:16', '2025-04-29 09:44:16', NULL, NULL),
(33, 156, 9, 10, NULL, NULL, 2, 3, 'Cuartos de Final Oro 2', '2025-04-29 09:44:16', '2025-04-29 09:44:16', NULL, NULL),
(34, 157, 10, 9, NULL, NULL, 1, 4, 'Cuartos de Final Oro 3', '2025-04-29 09:44:16', '2025-04-29 09:44:16', NULL, NULL),
(35, 158, 10, 9, NULL, NULL, 2, 3, 'Cuartos de Final Oro 4', '2025-04-29 09:44:16', '2025-04-29 09:44:16', NULL, NULL),
(36, 159, NULL, NULL, 155, 158, NULL, NULL, 'Semi Final Oro 1', '2025-04-29 09:44:16', '2025-04-29 09:44:16', NULL, NULL),
(37, 160, NULL, NULL, 156, 157, NULL, NULL, 'Semi Final Oro 2', '2025-04-29 09:44:16', '2025-04-29 09:44:16', NULL, NULL),
(38, 161, NULL, NULL, 159, 160, NULL, NULL, 'Final Oro 1', '2025-04-29 09:44:16', '2025-04-29 09:44:16', NULL, NULL),
(48, 162, NULL, NULL, NULL, NULL, NULL, NULL, 'Semi Final Plata 1', '2025-04-29 13:13:02', '2025-04-29 13:13:02', 155, 158),
(49, 163, NULL, NULL, NULL, NULL, NULL, NULL, 'Semi Final Plata 2', '2025-04-29 13:13:02', '2025-04-29 13:13:02', 156, 157),
(50, 164, NULL, NULL, 162, 163, NULL, NULL, 'Final Plata 1', '2025-04-29 13:13:02', '2025-04-29 13:13:02', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sede`
--

CREATE TABLE `sede` (
  `id` int(11) NOT NULL,
  `torneo_id` int(11) DEFAULT NULL,
  `nombre` varchar(255) NOT NULL,
  `domicilio` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sede`
--

INSERT INTO `sede` (`id`, `torneo_id`, `nombre`, `domicilio`, `created_at`, `updated_at`) VALUES
(1, 1, 'Club Villa Dora', 'Ruperto Godoy 1231', '2025-04-28 20:06:06', '2025-04-28 20:06:06'),
(2, 1, 'Regatas', 'Calle 1234', '2025-04-28 21:14:03', '2025-04-28 21:14:03'),
(3, 1, 'GyE', 'Calle 1234', '2025-04-28 21:14:42', '2025-04-28 21:14:42');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `torneo`
--

CREATE TABLE `torneo` (
  `id` int(11) NOT NULL,
  `creador_id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `ruta` varchar(32) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `fecha_inicio_inscripcion` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `fecha_fin_inscripcion` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `fecha_inicio_torneo` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `fecha_fin_torneo` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `reglamento` longtext DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `estado` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `torneo`
--

INSERT INTO `torneo` (`id`, `creador_id`, `nombre`, `ruta`, `descripcion`, `fecha_inicio_inscripcion`, `fecha_fin_inscripcion`, `fecha_inicio_torneo`, `fecha_fin_torneo`, `reglamento`, `created_at`, `updated_at`, `estado`) VALUES
(1, 1, 'XV Torneo Abierto Sudamericano de Mastervoley Sta Fe', 'xv_master_voley', '', '2025-04-28 20:05:00', '2025-04-28 20:06:00', '2025-05-01 11:00:00', '2025-05-03 23:59:00', NULL, '2025-04-28 20:06:06', '2025-04-28 20:06:06', 'Borrador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `torneo_usuario`
--

CREATE TABLE `torneo_usuario` (
  `torneo_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `username` varchar(180) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`roles`)),
  `password` varchar(255) NOT NULL,
  `email` varchar(128) DEFAULT NULL,
  `nombre` varchar(128) DEFAULT NULL,
  `apellido` varchar(128) DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `username`, `roles`, `password`, `email`, `nombre`, `apellido`, `created_at`, `updated_at`) VALUES
(1, 'admin', '[\"ROLE_ADMIN\"]', '$2y$13$W8NGUFnLJtDrWvM9KKIAnuKEgtY4QWKe76AVSjxwFz0ifT1IiRLsa', 'administrador@correo.com', 'Administrador', 'Administrador', '2025-04-28 15:30:39', '2025-04-28 20:04:38');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cancha`
--
ALTER TABLE `cancha`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_9D09C782E19F41BF` (`sede_id`);

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_4E10122DA0139802` (`torneo_id`);

--
-- Indices de la tabla `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Indices de la tabla `equipo`
--
ALTER TABLE `equipo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_C49C530B3397707A` (`categoria_id`),
  ADD KEY `IDX_C49C530B9C833003` (`grupo_id`);

--
-- Indices de la tabla `grupo`
--
ALTER TABLE `grupo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_8C0E9BD33397707A` (`categoria_id`);

--
-- Indices de la tabla `jugador`
--
ALTER TABLE `jugador`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_527D6F1823BFBED` (`equipo_id`);

--
-- Indices de la tabla `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Indices de la tabla `partido`
--
ALTER TABLE `partido`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_4E79750B7997F36E` (`cancha_id`),
  ADD KEY `IDX_4E79750B3397707A` (`categoria_id`),
  ADD KEY `IDX_4E79750B9C833003` (`grupo_id`),
  ADD KEY `IDX_4E79750B88774E73` (`equipo_local_id`),
  ADD KEY `IDX_4E79750B8C243011` (`equipo_visitante_id`);

--
-- Indices de la tabla `partido_config`
--
ALTER TABLE `partido_config`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_FB58ADC711856EB4` (`partido_id`),
  ADD UNIQUE KEY `UNIQ_FB58ADC783ECE76` (`grupo_equipo1_id`,`posicion_equipo1`),
  ADD UNIQUE KEY `UNIQ_FB58ADC71A8B6198` (`grupo_equipo2_id`,`posicion_equipo2`),
  ADD UNIQUE KEY `UNIQ_FB58ADC7A1716C4E` (`ganador_partido1_id`),
  ADD UNIQUE KEY `UNIQ_FB58ADC7B3C4C3A0` (`ganador_partido2_id`),
  ADD UNIQUE KEY `UNIQ_FB58ADC7EDE271C1` (`perdedor_partido1_id`),
  ADD UNIQUE KEY `UNIQ_FB58ADC7FF57DE2F` (`perdedor_partido2_id`);

--
-- Indices de la tabla `sede`
--
ALTER TABLE `sede`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_2A9BE2D1A0139802` (`torneo_id`);

--
-- Indices de la tabla `torneo`
--
ALTER TABLE `torneo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_7CEB63FE3A909126` (`nombre`),
  ADD UNIQUE KEY `UNIQ_7CEB63FEC3AEF08C` (`ruta`),
  ADD KEY `IDX_7CEB63FE62F40C3D` (`creador_id`);

--
-- Indices de la tabla `torneo_usuario`
--
ALTER TABLE `torneo_usuario`
  ADD PRIMARY KEY (`torneo_id`,`usuario_id`),
  ADD KEY `IDX_4633E7AFA0139802` (`torneo_id`),
  ADD KEY `IDX_4633E7AFDB38439E` (`usuario_id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_IDENTIFIER_USERNAME` (`username`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cancha`
--
ALTER TABLE `cancha`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `equipo`
--
ALTER TABLE `equipo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT de la tabla `grupo`
--
ALTER TABLE `grupo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `jugador`
--
ALTER TABLE `jugador`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT de la tabla `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `partido`
--
ALTER TABLE `partido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=165;

--
-- AUTO_INCREMENT de la tabla `partido_config`
--
ALTER TABLE `partido_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT de la tabla `sede`
--
ALTER TABLE `sede`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `torneo`
--
ALTER TABLE `torneo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cancha`
--
ALTER TABLE `cancha`
  ADD CONSTRAINT `FK_9D09C782E19F41BF` FOREIGN KEY (`sede_id`) REFERENCES `sede` (`id`);

--
-- Filtros para la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD CONSTRAINT `FK_4E10122DA0139802` FOREIGN KEY (`torneo_id`) REFERENCES `torneo` (`id`);

--
-- Filtros para la tabla `equipo`
--
ALTER TABLE `equipo`
  ADD CONSTRAINT `FK_C49C530B3397707A` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id`),
  ADD CONSTRAINT `FK_C49C530B9C833003` FOREIGN KEY (`grupo_id`) REFERENCES `grupo` (`id`);

--
-- Filtros para la tabla `grupo`
--
ALTER TABLE `grupo`
  ADD CONSTRAINT `FK_8C0E9BD33397707A` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id`);

--
-- Filtros para la tabla `jugador`
--
ALTER TABLE `jugador`
  ADD CONSTRAINT `FK_527D6F1823BFBED` FOREIGN KEY (`equipo_id`) REFERENCES `equipo` (`id`);

--
-- Filtros para la tabla `partido`
--
ALTER TABLE `partido`
  ADD CONSTRAINT `FK_4E79750B3397707A` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id`),
  ADD CONSTRAINT `FK_4E79750B7997F36E` FOREIGN KEY (`cancha_id`) REFERENCES `cancha` (`id`),
  ADD CONSTRAINT `FK_4E79750B88774E73` FOREIGN KEY (`equipo_local_id`) REFERENCES `equipo` (`id`),
  ADD CONSTRAINT `FK_4E79750B8C243011` FOREIGN KEY (`equipo_visitante_id`) REFERENCES `equipo` (`id`),
  ADD CONSTRAINT `FK_4E79750B9C833003` FOREIGN KEY (`grupo_id`) REFERENCES `grupo` (`id`);

--
-- Filtros para la tabla `partido_config`
--
ALTER TABLE `partido_config`
  ADD CONSTRAINT `FK_FB58ADC711856EB4` FOREIGN KEY (`partido_id`) REFERENCES `partido` (`id`),
  ADD CONSTRAINT `FK_FB58ADC71A8B6198` FOREIGN KEY (`grupo_equipo2_id`) REFERENCES `grupo` (`id`),
  ADD CONSTRAINT `FK_FB58ADC783ECE76` FOREIGN KEY (`grupo_equipo1_id`) REFERENCES `grupo` (`id`),
  ADD CONSTRAINT `FK_FB58ADC7A1716C4E` FOREIGN KEY (`ganador_partido1_id`) REFERENCES `partido` (`id`),
  ADD CONSTRAINT `FK_FB58ADC7B3C4C3A0` FOREIGN KEY (`ganador_partido2_id`) REFERENCES `partido` (`id`);

--
-- Filtros para la tabla `sede`
--
ALTER TABLE `sede`
  ADD CONSTRAINT `FK_2A9BE2D1A0139802` FOREIGN KEY (`torneo_id`) REFERENCES `torneo` (`id`);

--
-- Filtros para la tabla `torneo`
--
ALTER TABLE `torneo`
  ADD CONSTRAINT `FK_7CEB63FE62F40C3D` FOREIGN KEY (`creador_id`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `torneo_usuario`
--
ALTER TABLE `torneo_usuario`
  ADD CONSTRAINT `FK_4633E7AFA0139802` FOREIGN KEY (`torneo_id`) REFERENCES `torneo` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_4633E7AFDB38439E` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
