-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: server-mysql
-- Tiempo de generación: 29-04-2025 a las 01:49:00
-- Versión del servidor: 8.0.40
-- Versión de PHP: 8.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sgt`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cancha`
--

CREATE TABLE `cancha` (
  `id` int NOT NULL,
  `sede_id` int DEFAULT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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
(7, 3, 'Cancha 2', 'Cancha 2', '2025-04-28 21:15:08', '2025-04-28 21:15:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id` int NOT NULL,
  `torneo_id` int DEFAULT NULL,
  `nombre` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `genero` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `disputa` longtext COLLATE utf8mb4_unicode_ci,
  `estado` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `nombre_corto` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id`, `torneo_id`, `nombre`, `genero`, `disputa`, `estado`, `created_at`, `updated_at`, `nombre_corto`) VALUES
(1, 1, '+35', 'Femenino', NULL, 'Zonas_creadas', '2025-04-28 20:06:06', '2025-04-28 20:51:23', 'F+35'),
(2, 1, '+40', 'Femenino', NULL, 'Zonas_creadas', '2025-04-28 20:27:28', '2025-04-28 21:00:46', 'F+40'),
(3, 1, '+45', 'Femenino', NULL, 'Zonas_creadas', '2025-04-28 20:34:08', '2025-04-28 22:09:58', 'F+45'),
(4, 1, '+50', 'Femenino', NULL, 'Zonas_creadas', '2025-04-28 20:36:18', '2025-04-28 21:05:34', 'F+50'),
(5, 1, '+42', 'Masculino', NULL, 'Borrador', '2025-04-28 20:42:26', '2025-04-28 20:42:26', 'm+42'),
(6, 1, '+50', 'Masculino', NULL, 'Borrador', '2025-04-28 20:46:44', '2025-04-28 20:46:44', 'M+50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20250306221853', '2025-04-28 18:29:53', 3600);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipo`
--

CREATE TABLE `equipo` (
  `id` int NOT NULL,
  `categoria_id` int NOT NULL,
  `grupo_id` int DEFAULT NULL,
  `nombre` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre_corto` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pais` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provincia` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `localidad` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `estado` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `numero` smallint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `equipo`
--

INSERT INTO `equipo` (`id`, `categoria_id`, `grupo_id`, `nombre`, `nombre_corto`, `pais`, `provincia`, `localidad`, `created_at`, `updated_at`, `estado`, `numero`) VALUES
(1, 1, 1, 'Villa Dora', 'Villa Dora', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:07:07', '2025-04-28 21:11:57', 'Activo', 1),
(2, 1, 1, 'IMEI', 'IMEI', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:07:40', '2025-04-28 21:11:57', 'Activo', 2),
(3, 1, 1, 'Malucas', 'Malucas', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:08:12', '2025-04-28 21:11:57', 'Activo', 3),
(4, 1, 1, 'Romang Futbol Club', 'Romang FC', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:08:58', '2025-04-28 21:11:57', 'Activo', 4),
(5, 1, 1, 'Club Atletico Fisherton ', 'CA Fisherton ', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:14:23', '2025-04-28 21:11:57', 'Activo', 5),
(6, 1, 2, 'Regatas SF', 'Regatas SF', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:17:53', '2025-04-28 21:11:57', 'Activo', 6),
(7, 1, 2, 'Jerárquicas', 'Jerárquicas', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:18:31', '2025-04-28 21:11:57', 'Activo', 7),
(8, 1, 2, 'Regatas Rosario', 'Regatas Rosario', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:18:59', '2025-04-28 21:11:57', 'Activo', 8),
(9, 1, 2, 'ZED', 'ZED', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:19:33', '2025-04-28 21:11:57', 'Activo', 9),
(10, 1, 2, 'Nautico Zárate', 'Nautico Zárate', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:20:01', '2025-04-28 21:11:57', 'Activo', 10),
(11, 1, 3, 'El Quilla ', 'El Quilla ', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:20:46', '2025-04-28 21:11:57', 'Activo', 11),
(12, 1, 3, 'Santoto Voley', 'Santoto', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:21:47', '2025-04-28 21:11:57', 'Activo', 12),
(13, 1, 3, 'Club Barrio Norte Avellaneda', 'Barrio Norte', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:22:26', '2025-04-28 21:11:57', 'Activo', 13),
(14, 1, 3, 'Las Cuervas', 'Las Cuervas', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:23:19', '2025-04-28 21:11:57', 'Activo', 14),
(15, 1, 3, 'Club Junin', 'Club Junin', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:23:47', '2025-04-28 21:11:57', 'Activo', 15),
(16, 2, 4, 'Mala Mia', 'Mala Mia', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:28:45', '2025-04-28 22:17:48', 'Activo', 16),
(17, 2, 4, 'Cett', 'Cett', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:29:35', '2025-04-28 22:17:48', 'Activo', 17),
(18, 2, 4, 'Las Vascas', 'Las Vascas', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:29:55', '2025-04-28 22:17:48', 'Activo', 18),
(19, 2, 4, 'Las Patos', 'Las Patos', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:30:14', '2025-04-28 22:17:48', 'Activo', 19),
(20, 2, 4, 'Club Atletico Alumni Casilda', 'CA Alumni C', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:31:12', '2025-04-28 22:17:48', 'Activo', 20),
(21, 2, 4, 'Infinito', 'Infinito', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:31:32', '2025-04-28 22:17:48', 'Activo', 21),
(22, 2, 4, 'La 18', 'La 18', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:31:50', '2025-04-28 22:17:48', 'Activo', 22),
(23, 2, 5, 'Villa Dora', 'Villa Dora', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:32:10', '2025-04-28 22:17:48', 'Activo', 23),
(24, 2, 5, 'Costa Canelones', 'Costa Canelones', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:32:31', '2025-04-28 22:17:48', 'Activo', 24),
(25, 2, 5, 'Maxi Lanus', 'Maxi Lanus', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:32:54', '2025-04-28 22:17:48', 'Activo', 25),
(26, 2, 5, 'Vamos el apoyo', 'Vamos el apoyo', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:33:11', '2025-04-28 22:17:48', 'Activo', 26),
(27, 2, 5, 'Trede Birra', 'Trede Birra', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:33:31', '2025-04-28 22:17:48', 'Activo', 27),
(28, 2, 5, 'ATR', 'ATR', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:33:46', '2025-04-28 22:17:48', 'Activo', 28),
(29, 3, 7, 'NNV', 'NNV', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:34:52', '2025-04-28 22:13:53', 'Activo', 29),
(30, 3, 7, 'E.L.V.', 'E.L.V.', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:35:06', '2025-04-28 22:13:53', 'Activo', 30),
(31, 3, 7, 'Costa mix ', 'Costa mix ', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:35:36', '2025-04-28 22:13:53', 'Activo', 31),
(32, 3, 7, 'Las Inter', 'Las Inter', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:35:48', '2025-04-28 22:13:53', 'Activo', 32),
(33, 3, 7, 'UNI', 'UNI', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:36:01', '2025-04-28 22:13:53', 'Activo', 33),
(34, 4, 6, 'Recalculando', 'Recalculando', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:38:51', '2025-04-28 21:05:34', 'Borrador', 34),
(35, 4, 6, 'Infinito', 'Infinito', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:39:09', '2025-04-28 21:05:34', 'Borrador', 35),
(36, 4, 6, 'Guemes Salta', 'Guemes Salta', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:39:30', '2025-04-28 21:05:34', 'Borrador', 36),
(37, 4, 6, 'Somos 8 reinas', 'Somos 8 reinas', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:39:56', '2025-04-28 21:05:34', 'Borrador', 37),
(38, 4, 6, 'Nautico Zárate', 'Nautico Zárate', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:40:10', '2025-04-28 21:05:34', 'Borrador', 38),
(39, 4, 6, 'GyE Concepción del Uruguay', 'GyE C Uruguay', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:41:04', '2025-04-28 21:05:34', 'Borrador', 39),
(40, 4, 6, 'Las Leonesas', 'Las Leonesas', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:41:20', '2025-04-28 21:05:34', 'Borrador', 40),
(41, 5, NULL, 'Ferrocarril de Vera', 'Ferrocarril Vera', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:42:57', '2025-04-28 20:42:57', 'Borrador', 41),
(42, 5, NULL, 'No Pasa Naranja (NPN)', 'NPN', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:43:28', '2025-04-28 20:43:28', 'Borrador', 42),
(43, 5, NULL, 'Amigos del Bosque', 'Amigos Bosque', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:43:52', '2025-04-28 20:43:52', 'Borrador', 43),
(44, 5, NULL, 'Romang FC', 'Romang FC', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:44:19', '2025-04-28 20:44:19', 'Borrador', 44),
(45, 5, NULL, 'Maxi SF', 'Maxi SF', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:44:38', '2025-04-28 20:44:38', 'Borrador', 45),
(46, 6, NULL, 'Maxi SF', 'Maxi SF', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:47:07', '2025-04-28 20:47:07', 'Borrador', 46),
(47, 6, NULL, 'La Tribu', 'La Tribu', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:47:28', '2025-04-28 20:47:28', 'Borrador', 47),
(48, 6, NULL, 'CTGUSJ', 'CTGUSJ', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:47:44', '2025-04-28 20:47:44', 'Borrador', 48),
(49, 6, NULL, 'Las Flores', 'Las Flores', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:48:03', '2025-04-28 20:48:03', 'Borrador', 49),
(50, 6, NULL, 'Los Perkin', 'Los Perkin', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:48:19', '2025-04-28 20:48:19', 'Borrador', 50),
(51, 6, NULL, 'Defensores de Moreno', 'Def Moreno', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:48:46', '2025-04-28 20:48:46', 'Borrador', 51),
(52, 6, NULL, 'Rosario Voley', 'Rosario Voley', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:49:01', '2025-04-28 20:49:01', 'Borrador', 52),
(53, 6, NULL, 'Vintage', 'Vintage', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:49:16', '2025-04-28 20:49:16', 'Borrador', 53),
(54, 3, 7, 'Banco SF', 'Banco SF', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 21:44:02', '2025-04-28 22:13:53', 'Activo', 54);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupo`
--

CREATE TABLE `grupo` (
  `id` int NOT NULL,
  `categoria_id` int NOT NULL,
  `nombre` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `clasifica_oro` int NOT NULL,
  `clasifica_plata` int DEFAULT NULL,
  `clasifica_bronce` int DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `estado` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `grupo`
--

INSERT INTO `grupo` (`id`, `categoria_id`, `nombre`, `clasifica_oro`, `clasifica_plata`, `clasifica_bronce`, `created_at`, `updated_at`, `estado`) VALUES
(1, 1, 'A', 2, 2, 2, '2025-04-28 20:51:23', '2025-04-28 21:11:03', 'Finalizado'),
(2, 1, 'B', 2, 2, 2, '2025-04-28 20:51:23', '2025-04-28 21:11:03', 'Finalizado'),
(3, 1, 'C', 2, 2, 2, '2025-04-28 20:51:23', '2025-04-28 21:11:03', 'Finalizado'),
(4, 2, 'D', 2, 1, NULL, '2025-04-28 21:00:46', '2025-04-28 22:14:08', 'Finalizado'),
(5, 2, 'E', 2, 1, NULL, '2025-04-28 21:00:46', '2025-04-28 22:14:08', 'Finalizado'),
(6, 4, 'G', 2, 2, NULL, '2025-04-28 21:05:34', '2025-04-28 21:05:34', 'Borrador'),
(7, 3, 'F', 4, 2, NULL, '2025-04-28 22:09:58', '2025-04-28 22:09:58', 'Borrador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jugador`
--

CREATE TABLE `jugador` (
  `id` int NOT NULL,
  `equipo_id` int DEFAULT NULL,
  `nombre` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_documento` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL,
  `numero_documento` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nacimiento` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `responsable` tinyint(1) NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `celular` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `tipo` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL
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
  `id` bigint NOT NULL,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `partido`
--

CREATE TABLE `partido` (
  `id` int NOT NULL,
  `cancha_id` int DEFAULT NULL,
  `categoria_id` int NOT NULL,
  `grupo_id` int DEFAULT NULL,
  `equipo_local_id` int DEFAULT NULL,
  `equipo_visitante_id` int DEFAULT NULL,
  `horario` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `local_set1` smallint DEFAULT NULL,
  `local_set2` smallint DEFAULT NULL,
  `local_set3` smallint DEFAULT NULL,
  `local_set4` smallint DEFAULT NULL,
  `local_set5` smallint DEFAULT NULL,
  `visitante_set1` smallint DEFAULT NULL,
  `visitante_set2` smallint DEFAULT NULL,
  `visitante_set3` smallint DEFAULT NULL,
  `visitante_set4` smallint DEFAULT NULL,
  `visitante_set5` smallint DEFAULT NULL,
  `estado` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `numero` smallint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `partido`
--

INSERT INTO `partido` (`id`, `cancha_id`, `categoria_id`, `grupo_id`, `equipo_local_id`, `equipo_visitante_id`, `horario`, `local_set1`, `local_set2`, `local_set3`, `local_set4`, `local_set5`, `visitante_set1`, `visitante_set2`, `visitante_set3`, `visitante_set4`, `visitante_set5`, `estado`, `tipo`, `created_at`, `updated_at`, `numero`) VALUES
(1, NULL, 1, 1, 1, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 21:11:57', '2025-04-28 21:11:57', 1),
(2, NULL, 1, 1, 1, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 21:11:57', '2025-04-28 21:11:57', 2),
(3, NULL, 1, 1, 1, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 21:11:57', '2025-04-28 21:11:57', 3),
(4, NULL, 1, 1, 1, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 21:11:57', '2025-04-28 21:11:57', 4),
(5, NULL, 1, 1, 2, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 21:11:57', '2025-04-28 21:11:57', 5),
(6, NULL, 1, 1, 2, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 21:11:57', '2025-04-28 21:11:57', 6),
(7, NULL, 1, 1, 2, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 21:11:57', '2025-04-28 21:11:57', 7),
(8, NULL, 1, 1, 3, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 21:11:57', '2025-04-28 21:11:57', 8),
(9, NULL, 1, 1, 3, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 21:11:57', '2025-04-28 21:11:57', 9),
(10, NULL, 1, 1, 4, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 21:11:57', '2025-04-28 21:11:57', 10),
(11, NULL, 1, 2, 6, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 21:11:57', '2025-04-28 21:11:57', 11),
(12, NULL, 1, 2, 6, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 21:11:57', '2025-04-28 21:11:57', 12),
(13, NULL, 1, 2, 6, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 21:11:57', '2025-04-28 21:11:57', 13),
(14, NULL, 1, 2, 6, 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 21:11:57', '2025-04-28 21:11:57', 14),
(15, NULL, 1, 2, 7, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 21:11:57', '2025-04-28 21:11:57', 15),
(16, NULL, 1, 2, 7, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 21:11:57', '2025-04-28 21:11:57', 16),
(17, NULL, 1, 2, 7, 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 21:11:57', '2025-04-28 21:11:57', 17),
(18, NULL, 1, 2, 8, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 21:11:57', '2025-04-28 21:11:57', 18),
(19, NULL, 1, 2, 8, 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 21:11:57', '2025-04-28 21:11:57', 19),
(20, NULL, 1, 2, 9, 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 21:11:57', '2025-04-28 21:11:57', 20),
(21, NULL, 1, 3, 11, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 21:11:57', '2025-04-28 21:11:57', 21),
(22, NULL, 1, 3, 11, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 21:11:57', '2025-04-28 21:11:57', 22),
(23, NULL, 1, 3, 11, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 21:11:57', '2025-04-28 21:11:57', 23),
(24, NULL, 1, 3, 11, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 21:11:57', '2025-04-28 21:11:57', 24),
(25, NULL, 1, 3, 12, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 21:11:57', '2025-04-28 21:11:57', 25),
(26, NULL, 1, 3, 12, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 21:11:57', '2025-04-28 21:11:57', 26),
(27, NULL, 1, 3, 12, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 21:11:57', '2025-04-28 21:11:57', 27),
(28, NULL, 1, 3, 13, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 21:11:57', '2025-04-28 21:11:57', 28),
(29, NULL, 1, 3, 13, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 21:11:57', '2025-04-28 21:11:57', 29),
(30, NULL, 1, 3, 14, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 21:11:57', '2025-04-28 21:11:57', 30),
(31, NULL, 3, 7, 29, 30, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 22:13:53', '2025-04-28 22:13:53', 31),
(32, NULL, 3, 7, 29, 31, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 22:13:53', '2025-04-28 22:13:53', 32),
(33, NULL, 3, 7, 29, 32, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 22:13:53', '2025-04-28 22:13:53', 33),
(34, NULL, 3, 7, 29, 33, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 22:13:53', '2025-04-28 22:13:53', 34),
(35, NULL, 3, 7, 29, 54, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 22:13:53', '2025-04-28 22:13:53', 35),
(36, NULL, 3, 7, 30, 31, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 22:13:53', '2025-04-28 22:13:53', 36),
(37, NULL, 3, 7, 30, 32, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 22:13:53', '2025-04-28 22:13:53', 37),
(38, NULL, 3, 7, 30, 33, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 22:13:53', '2025-04-28 22:13:53', 38),
(39, NULL, 3, 7, 30, 54, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 22:13:53', '2025-04-28 22:13:53', 39),
(40, NULL, 3, 7, 31, 32, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 22:13:53', '2025-04-28 22:13:53', 40),
(41, NULL, 3, 7, 31, 33, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 22:13:53', '2025-04-28 22:13:53', 41),
(42, NULL, 3, 7, 31, 54, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 22:13:53', '2025-04-28 22:13:53', 42),
(43, NULL, 3, 7, 32, 33, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 22:13:53', '2025-04-28 22:13:53', 43),
(44, NULL, 3, 7, 32, 54, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 22:13:53', '2025-04-28 22:13:53', 44),
(45, NULL, 3, 7, 33, 54, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 22:13:53', '2025-04-28 22:13:53', 45),
(46, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Eliminatorio', '2025-04-28 22:13:53', '2025-04-28 22:13:53', 46),
(47, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Eliminatorio', '2025-04-28 22:13:53', '2025-04-28 22:13:53', 47),
(48, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Eliminatorio', '2025-04-28 22:13:53', '2025-04-28 22:13:53', 48),
(49, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Eliminatorio', '2025-04-28 22:13:53', '2025-04-28 22:13:53', 49),
(50, NULL, 2, 4, 16, 17, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 22:17:48', '2025-04-28 22:17:48', 50),
(51, NULL, 2, 4, 16, 18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 22:17:48', '2025-04-28 22:17:48', 51),
(52, NULL, 2, 4, 16, 19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 22:17:48', '2025-04-28 22:17:48', 52),
(53, NULL, 2, 4, 16, 20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 22:17:48', '2025-04-28 22:17:48', 53),
(54, NULL, 2, 4, 16, 21, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 22:17:48', '2025-04-28 22:17:48', 54),
(55, NULL, 2, 4, 16, 22, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 22:17:48', '2025-04-28 22:17:48', 55),
(56, NULL, 2, 4, 17, 18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 22:17:48', '2025-04-28 22:17:48', 56),
(57, NULL, 2, 4, 17, 19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 22:17:48', '2025-04-28 22:17:48', 57),
(58, NULL, 2, 4, 17, 20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 22:17:48', '2025-04-28 22:17:48', 58),
(59, NULL, 2, 4, 17, 21, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 22:17:48', '2025-04-28 22:17:48', 59),
(60, NULL, 2, 4, 17, 22, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 22:17:48', '2025-04-28 22:17:48', 60),
(61, NULL, 2, 4, 18, 19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 22:17:48', '2025-04-28 22:17:48', 61),
(62, NULL, 2, 4, 18, 20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 22:17:48', '2025-04-28 22:17:48', 62),
(63, NULL, 2, 4, 18, 21, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 22:17:48', '2025-04-28 22:17:48', 63),
(64, NULL, 2, 4, 18, 22, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 22:17:48', '2025-04-28 22:17:48', 64),
(65, NULL, 2, 4, 19, 20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 22:17:48', '2025-04-28 22:17:48', 65),
(66, NULL, 2, 4, 19, 21, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 22:17:48', '2025-04-28 22:17:48', 66),
(67, NULL, 2, 4, 19, 22, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 22:17:48', '2025-04-28 22:17:48', 67),
(68, NULL, 2, 4, 20, 21, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 22:17:48', '2025-04-28 22:17:48', 68),
(69, NULL, 2, 4, 20, 22, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 22:17:48', '2025-04-28 22:17:48', 69),
(70, NULL, 2, 4, 21, 22, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 22:17:48', '2025-04-28 22:17:48', 70),
(71, NULL, 2, 5, 23, 24, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 22:17:48', '2025-04-28 22:17:48', 71),
(72, NULL, 2, 5, 23, 25, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 22:17:48', '2025-04-28 22:17:48', 72),
(73, NULL, 2, 5, 23, 26, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 22:17:48', '2025-04-28 22:17:48', 73),
(74, NULL, 2, 5, 23, 27, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 22:17:48', '2025-04-28 22:17:48', 74),
(75, NULL, 2, 5, 23, 28, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 22:17:48', '2025-04-28 22:17:48', 75),
(76, NULL, 2, 5, 24, 25, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 22:17:48', '2025-04-28 22:17:48', 76),
(77, NULL, 2, 5, 24, 26, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 22:17:48', '2025-04-28 22:17:48', 77),
(78, NULL, 2, 5, 24, 27, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 22:17:49', '2025-04-28 22:17:49', 78),
(79, NULL, 2, 5, 24, 28, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 22:17:49', '2025-04-28 22:17:49', 79),
(80, NULL, 2, 5, 25, 26, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 22:17:49', '2025-04-28 22:17:49', 80),
(81, NULL, 2, 5, 25, 27, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 22:17:49', '2025-04-28 22:17:49', 81),
(82, NULL, 2, 5, 25, 28, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 22:17:49', '2025-04-28 22:17:49', 82),
(83, NULL, 2, 5, 26, 27, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 22:17:49', '2025-04-28 22:17:49', 83),
(84, NULL, 2, 5, 26, 28, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 22:17:49', '2025-04-28 22:17:49', 84),
(85, NULL, 2, 5, 27, 28, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Clasificatorio', '2025-04-28 22:17:49', '2025-04-28 22:17:49', 85),
(86, NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Eliminatorio', '2025-04-28 22:17:49', '2025-04-28 22:17:49', 86),
(87, NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Eliminatorio', '2025-04-28 22:17:49', '2025-04-28 22:17:49', 87),
(88, NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Eliminatorio', '2025-04-28 22:17:49', '2025-04-28 22:17:49', 88),
(89, NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Borrador', 'Eliminatorio', '2025-04-28 22:17:49', '2025-04-28 22:17:49', 89);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `partido_config`
--

CREATE TABLE `partido_config` (
  `id` int NOT NULL,
  `partido_id` int NOT NULL,
  `grupo_equipo1_id` int DEFAULT NULL,
  `grupo_equipo2_id` int DEFAULT NULL,
  `ganador_partido1_id` int DEFAULT NULL,
  `ganador_partido2_id` int DEFAULT NULL,
  `posicion_equipo1` smallint DEFAULT NULL,
  `posicion_equipo2` smallint DEFAULT NULL,
  `nombre` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `partido_config`
--

INSERT INTO `partido_config` (`id`, `partido_id`, `grupo_equipo1_id`, `grupo_equipo2_id`, `ganador_partido1_id`, `ganador_partido2_id`, `posicion_equipo1`, `posicion_equipo2`, `nombre`, `created_at`, `updated_at`) VALUES
(1, 46, 7, 7, NULL, NULL, 1, 4, 'Semi Final Oro 1', '2025-04-28 22:13:53', '2025-04-28 22:13:53'),
(2, 47, 7, 7, NULL, NULL, 2, 3, 'Semi Final Oro 2', '2025-04-28 22:13:53', '2025-04-28 22:13:53'),
(3, 48, NULL, NULL, 46, 47, NULL, NULL, 'Final Oro 1', '2025-04-28 22:13:53', '2025-04-28 22:13:53'),
(4, 49, 7, 7, NULL, NULL, 5, 6, 'Final Plata 1', '2025-04-28 22:13:53', '2025-04-28 22:13:53'),
(5, 86, 4, 5, NULL, NULL, 1, 1, 'Final Oro', '2025-04-28 22:17:49', '2025-04-28 22:17:49'),
(6, 87, 4, 5, NULL, NULL, 2, 2, '3 y 4', '2025-04-28 22:17:49', '2025-04-28 22:17:49'),
(7, 88, NULL, NULL, 86, 87, NULL, NULL, 'No se  juega', '2025-04-28 22:17:49', '2025-04-28 22:17:49'),
(8, 89, 4, 5, NULL, NULL, 3, 3, 'Final Plata 1', '2025-04-28 22:17:49', '2025-04-28 22:17:49');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sede`
--

CREATE TABLE `sede` (
  `id` int NOT NULL,
  `torneo_id` int DEFAULT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `domicilio` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `id` int NOT NULL,
  `creador_id` int NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ruta` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_inicio_inscripcion` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `fecha_fin_inscripcion` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `fecha_inicio_torneo` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `fecha_fin_torneo` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `reglamento` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `estado` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `torneo`
--

INSERT INTO `torneo` (`id`, `creador_id`, `nombre`, `ruta`, `descripcion`, `fecha_inicio_inscripcion`, `fecha_fin_inscripcion`, `fecha_inicio_torneo`, `fecha_fin_torneo`, `reglamento`, `created_at`, `updated_at`, `estado`) VALUES
(1, 1, 'XV Torneo Abierto Sudamericano de Mastervoley en Santa Fe', 'xv_master_voley', '', '2025-04-28 20:05:00', '2025-04-28 20:06:00', '2025-05-01 11:00:00', '2025-05-03 23:59:00', NULL, '2025-04-28 20:06:06', '2025-04-28 20:06:06', 'Borrador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `torneo_usuario`
--

CREATE TABLE `torneo_usuario` (
  `torneo_id` int NOT NULL,
  `usuario_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int NOT NULL,
  `username` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nombre` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `apellido` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  ADD UNIQUE KEY `UNIQ_FB58ADC7B3C4C3A0` (`ganador_partido2_id`);

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `equipo`
--
ALTER TABLE `equipo`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT de la tabla `grupo`
--
ALTER TABLE `grupo`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `jugador`
--
ALTER TABLE `jugador`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT de la tabla `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `partido`
--
ALTER TABLE `partido`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT de la tabla `partido_config`
--
ALTER TABLE `partido_config`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `sede`
--
ALTER TABLE `sede`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `torneo`
--
ALTER TABLE `torneo`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
