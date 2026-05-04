-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 30-04-2026 a las 23:30:43
-- Versión del servidor: 11.8.6-MariaDB-log
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
(8, 3, 'Cancha 3', 'Cancha 3', '2025-04-28 23:46:50', '2025-04-28 23:46:50'),
(9, 4, 'Cancha 1', '', '2026-04-27 22:00:00', '2026-04-27 22:00:00'),
(10, 4, 'Cancha 2', '', '2026-04-27 22:00:07', '2026-04-27 22:00:07'),
(11, 4, 'Cancha 3', '', '2026-04-27 22:00:11', '2026-04-27 22:00:11'),
(12, 5, 'Cancha 1', '', '2026-04-27 22:00:28', '2026-04-27 22:00:28'),
(14, 6, 'Cancha 1', '', '2026-04-27 22:00:42', '2026-04-27 22:00:42'),
(15, 6, 'Cancha 2', '', '2026-04-27 22:00:45', '2026-04-27 22:00:45');

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
(1, 1, 'F+35', 'Femenino', NULL, 'Zonas_cerradas', '2025-04-28 20:06:06', '2025-05-02 20:31:05', 'F+35'),
(2, 1, 'F+40', 'Femenino', NULL, 'Zonas_cerradas', '2025-04-28 20:27:28', '2025-05-02 22:25:29', 'F+40'),
(3, 1, 'F+45', 'Femenino', NULL, 'Zonas_cerradas', '2025-04-28 20:34:08', '2025-05-02 22:28:36', 'F+45'),
(4, 1, 'F+50', 'Femenino', NULL, 'Zonas_cerradas', '2025-04-28 20:36:18', '2025-05-02 22:32:05', 'F+50'),
(5, 1, 'M+42', 'Masculino', NULL, 'Zonas_cerradas', '2025-04-28 20:42:26', '2025-05-02 22:33:31', 'M+42'),
(6, 1, 'M+50', 'Masculino', NULL, 'Zonas_cerradas', '2025-04-28 20:46:44', '2025-05-02 19:53:12', 'M+50'),
(7, 2, 'F+35', 'Femenino', NULL, 'Zonas_creadas', '2026-04-24 08:51:01', '2026-04-27 19:35:47', 'F+35'),
(8, 2, 'F+40', 'Femenino', NULL, 'Zonas_creadas', '2026-04-25 22:02:14', '2026-04-27 19:51:24', 'F+40'),
(9, 2, 'F+45', 'Femenino', NULL, 'Zonas_creadas', '2026-04-25 22:06:47', '2026-04-27 20:02:16', 'F+45'),
(10, 2, 'M+42', 'Masculino', NULL, 'Zonas_creadas', '2026-04-25 22:11:42', '2026-04-27 20:08:33', 'M+42'),
(11, 2, 'M+50', 'Masculino', NULL, 'Zonas_creadas', '2026-04-25 22:12:56', '2026-04-27 20:09:35', 'M+50');

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
('DoctrineMigrations\\Version20250306221853', '2025-04-29 02:36:36', 1288),
('DoctrineMigrations\\Version20260420190000', '2026-04-24 14:51:09', 30),
('DoctrineMigrations\\Version20260424190000', '2026-04-24 14:51:09', 5);

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
  `numero` smallint(6) NOT NULL,
  `logo_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `equipo`
--

INSERT INTO `equipo` (`id`, `categoria_id`, `grupo_id`, `nombre`, `nombre_corto`, `pais`, `provincia`, `localidad`, `created_at`, `updated_at`, `estado`, `numero`, `logo_path`) VALUES
(1, 1, 1, 'Villa Dora', 'Villa Dora', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:07:07', '2025-04-28 23:49:22', 'Activo', 1, NULL),
(2, 1, 1, 'IMEI', 'IMEI', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:07:40', '2025-04-28 23:49:22', 'Activo', 2, NULL),
(3, 1, 1, 'Malucas', 'Malucas', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:08:12', '2025-04-28 23:49:22', 'Activo', 3, NULL),
(4, 1, 1, 'Romang Futbol Club', 'Romang FC', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:08:58', '2025-04-28 23:49:22', 'Activo', 4, NULL),
(5, 1, 1, 'Club Atletico Fisherton ', 'CA Fisherton ', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:14:23', '2025-04-28 23:49:22', 'Activo', 5, NULL),
(6, 1, 2, 'Regatas SF', 'Regatas SF', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:17:53', '2025-04-28 23:49:22', 'Activo', 6, NULL),
(7, 1, 2, 'Jerárquicas', 'Jerárquicas', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:18:31', '2025-04-28 23:49:22', 'Activo', 7, NULL),
(8, 1, 2, 'Regatas Rosario', 'Regatas Rosario', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:18:59', '2025-04-28 23:49:22', 'Activo', 8, NULL),
(9, 1, 2, 'ZED', 'ZED', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:19:33', '2025-04-28 23:49:22', 'Activo', 9, NULL),
(10, 1, 2, 'Nautico Zárate', 'Nautico Zárate', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:20:01', '2025-04-28 23:49:22', 'Activo', 10, NULL),
(11, 1, 3, 'El Quilla ', 'El Quilla ', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:20:46', '2025-04-28 23:49:22', 'Activo', 11, NULL),
(12, 1, 3, 'Santoto Voley', 'Santoto', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:21:47', '2025-04-28 23:49:22', 'Activo', 12, NULL),
(13, 1, 3, 'Club Barrio Norte Avellaneda', 'Barrio Norte', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:22:26', '2025-04-28 23:49:22', 'Activo', 13, NULL),
(14, 1, 3, 'Las Cuervas', 'Las Cuervas', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:23:19', '2025-04-28 23:49:22', 'Activo', 14, NULL),
(15, 1, 3, 'Club Junin', 'Club Junin', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:23:47', '2025-04-28 23:49:22', 'Activo', 15, NULL),
(16, 2, 4, 'Mala Mia', 'Mala Mia', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:28:45', '2025-04-29 09:12:35', 'Activo', 16, NULL),
(17, 2, 4, 'Cett', 'Cett', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:29:35', '2025-04-29 09:12:35', 'Activo', 17, NULL),
(18, 2, 4, 'Las Vascas', 'Las Vascas', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:29:55', '2025-04-29 09:12:35', 'Activo', 18, NULL),
(19, 2, 4, 'Las Patos', 'Las Patos', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:30:14', '2025-04-29 09:12:35', 'Activo', 19, NULL),
(20, 2, 4, 'Club Atletico Alumni Casilda', 'CA Alumni C', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:31:12', '2025-04-29 09:12:35', 'Activo', 20, NULL),
(21, 2, 4, 'Infinito', 'Infinito', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:31:32', '2025-04-29 09:12:35', 'Activo', 21, NULL),
(22, 2, 4, 'La 18', 'La 18', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:31:50', '2025-04-29 09:12:35', 'Activo', 22, NULL),
(23, 2, 5, 'Villa Dora', 'Villa Dora', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:32:10', '2025-04-29 09:12:36', 'Activo', 23, NULL),
(24, 2, 5, 'Costa Canelones', 'Costa Canelones', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:32:31', '2025-04-29 09:12:36', 'Activo', 24, NULL),
(25, 2, 5, 'Maxi Lanus', 'Maxi Lanus', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:32:54', '2025-04-29 09:12:36', 'Activo', 25, NULL),
(26, 2, 5, 'Vamos el apoyo', 'Vamos el apoyo', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:33:11', '2025-04-29 09:12:36', 'Activo', 26, NULL),
(27, 2, 5, 'Trede Birra', 'Trede Birra', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:33:31', '2025-04-29 09:12:36', 'Activo', 27, NULL),
(28, 2, 5, 'ATR', 'ATR', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:33:46', '2025-04-29 09:12:36', 'Activo', 28, NULL),
(29, 3, 6, 'NNV', 'NNV', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:34:52', '2025-04-29 09:22:30', 'Activo', 29, NULL),
(30, 3, 6, 'E.L.V.', 'E.L.V.', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:35:06', '2025-04-29 09:22:30', 'Activo', 30, NULL),
(31, 3, 6, 'Costa mix ', 'Costa mix ', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:35:36', '2025-04-29 09:22:30', 'Activo', 31, NULL),
(32, 3, 6, 'Las Inter', 'Las Inter', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:35:48', '2025-04-29 09:22:30', 'Activo', 32, NULL),
(33, 3, 6, 'UNI', 'UNI', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:36:01', '2025-04-29 09:22:30', 'Activo', 33, NULL),
(34, 3, 6, 'Banco SF', 'Banco SF', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 21:44:02', '2025-04-29 09:22:30', 'Activo', 34, NULL),
(35, 4, 7, 'Recalculando', 'Recalculando', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:38:51', '2025-04-29 09:27:38', 'Activo', 35, NULL),
(36, 4, 7, 'Infinito', 'Infinito', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:39:09', '2025-04-29 09:27:38', 'Activo', 36, NULL),
(37, 4, 7, 'Guemes Salta', 'Guemes Salta', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:39:30', '2025-04-29 09:27:38', 'Activo', 37, NULL),
(38, 4, 7, 'Somos 8 reinas', 'Somos 8 reinas', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:39:56', '2025-04-29 09:27:38', 'Activo', 38, NULL),
(39, 4, 7, 'Nautico Zárate', 'Nautico Zárate', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:40:10', '2025-04-29 09:27:38', 'Activo', 39, NULL),
(40, 4, 7, 'GyE Concepción del Uruguay', 'GyE C Uruguay', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:41:04', '2025-04-29 09:27:38', 'Activo', 40, NULL),
(41, 4, 7, 'Las Leonesas', 'Las Leonesas', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:41:20', '2025-04-29 09:27:38', 'Activo', 41, NULL),
(42, 5, 8, 'Ferrocarril de Vera', 'Ferrocarril Vera', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:42:57', '2025-04-29 09:35:26', 'Activo', 42, NULL),
(43, 5, 8, 'No Pasa Naranja (NPN)', 'NPN', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:43:28', '2025-04-29 09:35:26', 'Activo', 43, NULL),
(44, 5, 8, 'Amigos del Bosque', 'Amigos Bosque', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:43:52', '2025-04-29 09:35:26', 'Activo', 44, NULL),
(45, 5, 8, 'Romang FC', 'Romang FC', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:44:19', '2025-04-29 09:35:26', 'Activo', 45, NULL),
(46, 5, 8, 'Rosario Voley', 'Rosario Voley', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:44:38', '2025-04-30 09:43:52', 'Activo', 46, NULL),
(47, 6, 9, 'Maxi SF', 'Maxi SF', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:47:07', '2025-04-29 09:44:16', 'Activo', 47, NULL),
(48, 6, 9, 'La Tribu', 'La Tribu', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:47:28', '2025-04-29 09:44:16', 'Activo', 48, NULL),
(49, 6, 9, 'CTGUSJ', 'CTGUSJ', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:47:44', '2025-04-29 09:44:16', 'Activo', 49, NULL),
(50, 6, 9, 'Las Flores', 'Las Flores', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:48:03', '2025-04-29 09:44:16', 'Activo', 50, NULL),
(51, 6, 10, 'Los Perkin', 'Los Perkin', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:48:19', '2025-04-29 09:44:16', 'Activo', 51, NULL),
(52, 6, 10, 'Defensores de Moreno', 'Def Moreno', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:48:46', '2025-04-29 09:44:16', 'Activo', 52, NULL),
(53, 6, 10, 'Rosario Voley', 'Rosario Voley', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:49:01', '2025-04-29 09:44:16', 'Activo', 53, NULL),
(54, 6, 10, 'Vintage', 'Vintage', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-04-28 20:49:16', '2025-04-29 09:44:16', 'Activo', 54, NULL),
(55, 7, 12, 'BDI', 'BDI', 'Argentina', 'Santa Fe', 'Santa Fe', '2026-04-25 21:51:26', '2026-04-29 10:49:03', 'Activo', 1, 'uploads/logos/2026/xvi_master_voley/55-1777470543392-fb5754.png'),
(56, 7, 12, 'Birra', 'Birra', 'Argentina', 'Santa Fe', 'Santa Fe', '2026-04-25 21:51:39', '2026-04-27 20:12:04', 'Activo', 2, 'uploads/logos/2026/xvi_master_voley/56.png'),
(57, 7, 12, 'Club Alma Juniors', 'CAJ', 'Argentina', 'Santa Fe', 'Santa Fe', '2026-04-25 21:52:01', '2026-04-27 20:12:04', 'Activo', 3, 'uploads/logos/2026/xvi_master_voley/57.png'),
(58, 7, 11, 'Club de Amigas', 'CdA', 'Argentina', 'Santa Fe', 'Santa Fe', '2026-04-25 21:52:19', '2026-04-29 13:57:12', 'Activo', 4, 'uploads/logos/2026/xvi_master_voley/58-1777481832718-e9464e.png'),
(59, 7, 11, 'Club Junín', 'CJ', 'Argentina', 'Santa Fe', 'Santa Fe', '2026-04-25 21:52:34', '2026-04-28 09:28:51', 'Activo', 5, 'uploads/logos/2026/xvi_master_voley/59-1777379331455-0b7482.png'),
(60, 7, 13, 'Club El Quilla', 'CEQ', 'Argentina', 'Santa Fe', 'Santa Fe', '2026-04-25 21:53:01', '2026-04-27 20:12:04', 'Activo', 6, 'uploads/logos/2026/xvi_master_voley/60.png'),
(61, 7, 11, 'IMEI', 'IMEI', 'Argentina', 'Santa Fe', 'Santa Fe', '2026-04-25 21:53:14', '2026-04-27 20:12:03', 'Activo', 7, 'uploads/logos/2026/xvi_master_voley/61.png'),
(62, 7, 13, 'Jerárquicas', 'Jerárquicas', 'Argentina', 'Santa Fe', 'Santa Fe', '2026-04-25 21:53:31', '2026-04-27 20:12:04', 'Activo', 8, 'uploads/logos/2026/xvi_master_voley/62.png'),
(63, 7, 12, 'La 18', 'La 18', 'Argentina', 'Santa Fe', 'Santa Fe', '2026-04-25 21:53:46', '2026-04-27 20:12:04', 'Activo', 9, 'uploads/logos/2026/xvi_master_voley/63.png'),
(64, 7, 13, 'Quilmes', 'Quilmes', 'Argentina', 'Santa Fe', 'Santa Fe', '2026-04-25 21:53:58', '2026-04-27 20:12:04', 'Activo', 10, NULL),
(65, 7, 12, 'Club Regatas Sta Fe', 'CRSF', 'Argentina', 'Santa Fe', 'Santa Fe', '2026-04-25 21:54:22', '2026-04-27 20:12:04', 'Activo', 11, 'uploads/logos/2026/xvi_master_voley/65.png'),
(66, 7, 13, 'Santoto Voley', 'SV', 'Argentina', 'Santa Fe', 'Santa Fe', '2026-04-25 21:54:47', '2026-04-27 20:12:04', 'Activo', 12, 'uploads/logos/2026/xvi_master_voley/66.png'),
(67, 7, 11, 'Club Villa Dora', 'CVD', 'Argentina', 'Santa Fe', 'Santa Fe', '2026-04-25 21:55:22', '2026-04-27 20:12:03', 'Activo', 13, 'uploads/logos/2026/xvi_master_voley/67.png'),
(68, 7, 13, 'Zaspi', 'Zaspi', 'Argentina', 'Santa Fe', 'Santa Fe', '2026-04-25 21:55:33', '2026-04-27 20:12:04', 'Activo', 14, 'uploads/logos/2026/xvi_master_voley/68.png'),
(69, 8, 16, 'Costa Canelones ', 'CCU', 'Uruguay', 'Uruguay', 'Uruguay', '2026-04-25 22:02:38', '2026-04-27 21:06:00', 'Activo', 15, NULL),
(70, 8, 16, 'Infinito Voley', 'IV', 'Argentina', 'Santa Fe', 'Santa Fe', '2026-04-25 22:02:52', '2026-04-27 21:06:00', 'Activo', 16, 'uploads/logos/2026/xvi_master_voley/70.png'),
(71, 8, 14, 'Club Villa Dora', 'CVD', 'Argentina', 'Santa Fe', 'Santa Fe', '2026-04-25 22:03:10', '2026-04-27 21:06:00', 'Activo', 17, 'uploads/logos/2026/xvi_master_voley/71.png'),
(72, 8, 14, 'UNSJ', 'UNSJ', 'Argentina', 'Santa Fe', 'Santa Fe', '2026-04-25 22:03:23', '2026-04-28 09:12:20', 'Activo', 18, 'uploads/logos/2026/xvi_master_voley/72-1777378340306-814fd0.png'),
(73, 8, 15, 'Las Vascas', 'Las Vascas', 'Argentina', 'Santa Fe', 'Santa Fe', '2026-04-25 22:03:34', '2026-04-29 10:49:27', 'Activo', 19, 'uploads/logos/2026/xvi_master_voley/73-1777470567855-c05f5a.png'),
(74, 8, 15, 'Las Paqui Girls', 'Las Paqui Girls', 'Argentina', 'Santa Fe', 'Santa Fe', '2026-04-25 22:03:45', '2026-04-27 21:06:00', 'Activo', 20, 'uploads/logos/2026/xvi_master_voley/74.png'),
(75, 8, 14, 'Scaloneta Voley', 'Scaloneta Voley', 'Argentina', 'Misiones', 'Posadas', '2026-04-25 22:04:28', '2026-04-27 21:06:00', 'Activo', 21, 'uploads/logos/2026/xvi_master_voley/75.png'),
(76, 8, 15, 'Al Toque', 'Al Toque', 'Argentina', 'Santa Fe', 'Santa Fe', '2026-04-25 22:05:05', '2026-04-27 21:06:00', 'Activo', 22, 'uploads/logos/2026/xvi_master_voley/76.png'),
(77, 8, 14, 'CAS de Junin', 'CAS de Junin', 'Argentina', 'Santa Fe', 'Santa Fe', '2026-04-25 22:05:23', '2026-04-27 21:06:00', 'Activo', 23, 'uploads/logos/2026/xvi_master_voley/77.png'),
(78, 8, 15, 'Mala Mia', 'Mala Mia', 'Argentina', 'Santa Fe', 'Santa Fe', '2026-04-25 22:05:34', '2026-04-27 21:06:00', 'Activo', 24, 'uploads/logos/2026/xvi_master_voley/78.png'),
(79, 8, 16, 'CETT Chaco', 'CETT', 'Argentina', 'Chaco', 'Resistencia', '2026-04-25 22:05:58', '2026-04-27 21:06:00', 'Activo', 25, 'uploads/logos/2026/xvi_master_voley/79.png'),
(80, 9, 17, 'Central', 'Central', 'Uruguay', 'Uruguay', 'Uruguay', '2026-04-25 22:07:14', '2026-04-29 10:48:43', 'Activo', 26, 'uploads/logos/2026/xvi_master_voley/80-1777470523389-5c3a92.png'),
(81, 9, 19, 'UNI', 'UNI', 'Argentina', 'Santa Fe', 'Santa Fe', '2026-04-25 22:07:24', '2026-04-27 21:48:45', 'Activo', 27, NULL),
(82, 9, 17, 'Monstars', 'Monstars', 'Argentina', 'Misiones', 'Posadas', '2026-04-25 22:07:54', '2026-04-27 21:48:45', 'Activo', 28, 'uploads/logos/2026/xvi_master_voley/82.png'),
(83, 9, 19, 'Infinito Voley', 'IV', 'Argentina', 'Santa Fe', 'Santa Fe', '2026-04-25 22:08:11', '2026-04-27 21:48:45', 'Activo', 29, 'uploads/logos/2026/xvi_master_voley/83.png'),
(84, 9, 19, 'Gurisas', 'Gurisas', 'Argentina', 'Entre Rios', 'Parana', '2026-04-25 22:08:29', '2026-04-27 21:48:45', 'Activo', 30, 'uploads/logos/2026/xvi_master_voley/84.png'),
(85, 9, 18, 'Maxi Lanus', 'Maxi Lanus', 'Argentina', 'Santa Fe', 'Santa Fe', '2026-04-25 22:08:42', '2026-04-28 09:12:46', 'Activo', 31, 'uploads/logos/2026/xvi_master_voley/85-1777378366288-118040.png'),
(86, 9, 18, 'La 18', 'La 18', 'Argentina', 'Santa Fe', 'Santa Fe', '2026-04-25 22:08:52', '2026-04-27 21:48:45', 'Activo', 32, 'uploads/logos/2026/xvi_master_voley/86.png'),
(87, 9, 18, 'E.L.V (Lobos)', 'E.L.V (Lobos)', 'Argentina', 'Santa Fe', 'Santa Fe', '2026-04-25 22:09:01', '2026-04-27 21:48:45', 'Activo', 33, 'uploads/logos/2026/xvi_master_voley/87.png'),
(88, 9, 20, 'Malucas', 'Malucas', 'Argentina', 'Misiones', 'Posadas', '2026-04-25 22:09:28', '2026-04-27 21:48:45', 'Activo', 34, 'uploads/logos/2026/xvi_master_voley/88.png'),
(89, 9, 20, 'Amigas del Voley', 'Amigas del Voley', 'Uruguay', 'Uruguay', 'Uruguay', '2026-04-25 22:09:47', '2026-04-27 21:48:45', 'Activo', 35, 'uploads/logos/2026/xvi_master_voley/89.png'),
(90, 9, 17, 'Paso del Rey', 'Paso del Rey', 'Argentina', 'Santa Fe', 'Santa Fe', '2026-04-25 22:09:59', '2026-04-29 10:51:42', 'Activo', 36, 'uploads/logos/2026/xvi_master_voley/90-1777470702712-5f212f.png'),
(91, 9, 18, 'Las Nutrias', 'Las Nutrias', 'Argentina', 'Entre Rios', 'Parana', '2026-04-25 22:10:19', '2026-04-27 22:51:34', 'Activo', 37, 'uploads/logos/2026/xvi_master_voley/91-1777341094105-9bc6a0.png'),
(92, 9, 19, 'El Clan', 'El Clan', 'Argentina', 'Santa Fe', 'Santa Fe', '2026-04-25 22:10:31', '2026-04-27 21:48:45', 'Activo', 38, 'uploads/logos/2026/xvi_master_voley/92.png'),
(93, 9, 20, '8 Reinas', '8 Reinas', 'Argentina', 'Entre Rios', 'Parana', '2026-04-25 22:10:47', '2026-04-27 21:48:45', 'Activo', 39, 'uploads/logos/2026/xvi_master_voley/93.png'),
(94, 9, 17, 'Club Banco Provincia', 'CBP', 'Argentina', 'Santa Fe', 'Santa Fe', '2026-04-25 22:10:59', '2026-04-27 21:48:45', 'Activo', 40, 'uploads/logos/2026/xvi_master_voley/94.png'),
(95, 10, 21, 'No Pasa Naranja', 'NPN', 'Argentina', 'Santa Fe', 'Santa Fe', '2026-04-25 22:12:23', '2026-04-27 21:52:09', 'Activo', 41, 'uploads/logos/2026/xvi_master_voley/95.png'),
(96, 10, 21, 'Divididos', 'Divididos', 'Argentina', 'Santa Fe', 'Santa Fe', '2026-04-25 22:12:34', '2026-04-27 21:52:09', 'Activo', 42, 'uploads/logos/2026/xvi_master_voley/96.png'),
(97, 11, 22, 'Los Perkins', 'Los Perkins', 'Argentina', 'Santa Fe', 'Santa Fe', '2026-04-25 22:13:10', '2026-04-29 16:09:11', 'Activo', 43, 'uploads/logos/2026/xvi_master_voley/97-1777489750960-e88073.png'),
(98, 11, 22, 'Divididos', 'Divididos', 'Argentina', 'Santa Fe', 'Santa Fe', '2026-04-25 22:13:46', '2026-04-27 21:53:19', 'Activo', 44, 'uploads/logos/2026/xvi_master_voley/98.png'),
(99, 11, 22, 'Velez', 'Velez', 'Argentina', 'Santa Fe', 'Santa Fe', '2026-04-25 22:13:58', '2026-04-27 21:53:19', 'Activo', 45, 'uploads/logos/2026/xvi_master_voley/99.png'),
(100, 11, 22, 'Rosario Voley', 'Rosario Voley', 'Argentina', 'Santa Fe', 'Rosario', '2026-04-25 22:14:11', '2026-04-27 21:53:19', 'Activo', 46, 'uploads/logos/2026/xvi_master_voley/100.png'),
(101, 11, 22, 'Centro 11 Rio Cuarto', 'C11RC', 'Argentina', 'Santa Fe', 'Santa Fe', '2026-04-25 22:21:59', '2026-04-27 21:53:19', 'Activo', 47, 'uploads/logos/2026/xvi_master_voley/101.png'),
(102, 10, 21, 'Alfa Voley', 'Alfa Voley', 'Argentina', 'Santa Fe', 'Rosario', '2026-04-26 16:18:22', '2026-04-27 21:52:09', 'Activo', 48, 'uploads/logos/2026/xvi_master_voley/102.png'),
(103, 10, 21, 'ABV', 'ABV', 'Argentina', 'Santa Fe', 'Santa Fe', '2026-04-26 16:19:00', '2026-04-29 16:08:48', 'Activo', 49, 'uploads/logos/2026/xvi_master_voley/103-1777489727991-5f3d99.png'),
(104, 11, 22, 'Los Dinos', 'Los Dinos', 'Argentina', 'Santa Fe', 'Santa Fe', '2026-04-26 16:20:35', '2026-04-27 22:44:54', 'Activo', 50, 'uploads/logos/2026/xvi_master_voley/104-1777340694735-f0fad2.png'),
(105, 7, 11, 'San Justino', 'San Justino', 'Argentina', 'Santa Fe', 'Santa Fe', '2026-04-27 19:21:54', '2026-04-28 08:02:03', 'Activo', 51, 'uploads/logos/2026/xvi_master_voley/105-1777374123599-248068.png'),
(106, 8, 16, 'Dios las crias', 'Dios las crias', 'Argentina', 'Santa Fe', 'Santa Fe', '2026-04-27 19:22:56', '2026-04-28 09:12:08', 'Activo', 52, 'uploads/logos/2026/xvi_master_voley/106-1777378327931-ccfb82.png'),
(107, 9, 20, 'Güemes Salta', 'Güemes Salta', 'Argentina', 'Santa Fe', 'Santa Fe', '2026-04-27 19:23:33', '2026-04-29 10:43:33', 'Activo', 53, 'uploads/logos/2026/xvi_master_voley/107-1777470213627-c141c9.png'),
(108, 10, 21, 'Vera', 'Vera', 'Argentina', 'Santa Fe', 'Santa Fe', '2026-04-27 19:24:37', '2026-04-27 22:44:03', 'Activo', 54, 'uploads/logos/2026/xvi_master_voley/108-1777340643090-abe95e.png');

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
(1, 1, 'A', 2, 2, 1, '2025-04-28 23:49:07', '2025-05-02 18:31:11', 'Finalizado'),
(2, 1, 'B', 2, 2, 1, '2025-04-28 23:49:07', '2025-05-02 17:06:45', 'Finalizado'),
(3, 1, 'C', 2, 2, 1, '2025-04-28 23:49:07', '2025-05-02 19:16:07', 'Finalizado'),
(4, 2, 'D', 1, 1, NULL, '2025-04-29 09:12:02', '2025-05-02 22:23:51', 'Finalizado'),
(5, 2, 'E', 1, 1, NULL, '2025-04-29 09:12:02', '2025-05-02 20:46:33', 'Finalizado'),
(6, 3, 'F', 4, 2, NULL, '2025-04-29 09:21:57', '2025-05-02 19:14:23', 'Finalizado'),
(7, 4, 'G', 2, 2, NULL, '2025-04-29 09:26:15', '2025-05-02 22:08:12', 'Finalizado'),
(8, 5, '1', 4, NULL, NULL, '2025-04-29 09:34:22', '2025-04-29 09:34:25', 'Finalizado'),
(9, 6, '2', 4, NULL, NULL, '2025-04-29 09:40:00', '2025-05-02 19:50:12', 'Finalizado'),
(10, 6, '3', 4, NULL, NULL, '2025-04-29 09:40:00', '2025-05-02 17:02:42', 'Finalizado'),
(11, 7, 'A', 2, 2, 1, '2026-04-27 19:35:47', '2026-04-27 19:35:47', 'Borrador'),
(12, 7, 'B', 2, 2, 1, '2026-04-27 19:35:47', '2026-04-27 19:35:47', 'Borrador'),
(13, 7, 'C', 2, 2, 1, '2026-04-27 19:35:47', '2026-04-27 19:35:47', 'Borrador'),
(14, 8, 'D', 2, 2, NULL, '2026-04-27 19:51:24', '2026-04-27 20:22:32', 'Finalizado'),
(15, 8, 'E', 2, 2, NULL, '2026-04-27 19:51:24', '2026-04-27 20:22:32', 'Finalizado'),
(16, 8, 'F', 2, 2, NULL, '2026-04-27 19:51:24', '2026-04-27 20:22:32', 'Finalizado'),
(17, 9, 'G', 2, 2, NULL, '2026-04-27 20:02:16', '2026-04-27 20:02:16', 'Borrador'),
(18, 9, 'H', 2, 2, NULL, '2026-04-27 20:02:16', '2026-04-27 20:02:16', 'Borrador'),
(19, 9, 'I', 2, 2, NULL, '2026-04-27 20:02:16', '2026-04-27 20:02:16', 'Borrador'),
(20, 9, 'J', 2, 2, NULL, '2026-04-27 20:02:16', '2026-04-27 20:02:16', 'Borrador'),
(21, 10, '1', 4, NULL, NULL, '2026-04-27 20:08:33', '2026-04-27 21:49:47', 'Finalizado'),
(22, 11, '2', 2, 2, 2, '2026-04-27 20:09:35', '2026-04-27 20:09:35', 'Borrador');

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
(54, 54, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2025-04-28 21:44:02', '2025-04-28 21:44:02', 'Entrenador'),
(55, 55, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2026-04-25 21:51:27', '2026-04-25 21:51:27', 'Entrenador'),
(56, 56, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2026-04-25 21:51:39', '2026-04-25 21:51:39', 'Entrenador'),
(57, 57, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2026-04-25 21:52:01', '2026-04-25 21:52:01', 'Entrenador'),
(58, 58, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2026-04-25 21:52:19', '2026-04-25 21:52:19', 'Entrenador'),
(59, 59, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2026-04-25 21:52:34', '2026-04-25 21:52:34', 'Entrenador'),
(60, 60, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2026-04-25 21:53:01', '2026-04-25 21:53:01', 'Entrenador'),
(61, 61, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2026-04-25 21:53:14', '2026-04-25 21:53:14', 'Entrenador'),
(62, 62, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2026-04-25 21:53:31', '2026-04-25 21:53:31', 'Entrenador'),
(63, 63, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2026-04-25 21:53:46', '2026-04-25 21:53:46', 'Entrenador'),
(64, 64, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2026-04-25 21:53:58', '2026-04-25 21:53:58', 'Entrenador'),
(65, 65, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2026-04-25 21:54:22', '2026-04-25 21:54:22', 'Entrenador'),
(66, 66, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2026-04-25 21:54:47', '2026-04-25 21:54:47', 'Entrenador'),
(67, 67, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2026-04-25 21:55:22', '2026-04-25 21:55:22', 'Entrenador'),
(68, 68, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2026-04-25 21:55:33', '2026-04-25 21:55:33', 'Entrenador'),
(69, 69, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2026-04-25 22:02:38', '2026-04-25 22:02:38', 'Entrenador'),
(70, 70, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2026-04-25 22:02:52', '2026-04-25 22:02:52', 'Entrenador'),
(71, 71, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2026-04-25 22:03:10', '2026-04-25 22:03:10', 'Entrenador'),
(72, 72, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2026-04-25 22:03:23', '2026-04-25 22:03:23', 'Entrenador'),
(73, 73, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2026-04-25 22:03:34', '2026-04-25 22:03:34', 'Entrenador'),
(74, 74, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2026-04-25 22:03:45', '2026-04-25 22:03:45', 'Entrenador'),
(75, 75, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2026-04-25 22:04:28', '2026-04-25 22:04:28', 'Entrenador'),
(76, 76, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2026-04-25 22:05:05', '2026-04-25 22:05:05', 'Entrenador'),
(77, 77, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2026-04-25 22:05:23', '2026-04-25 22:05:23', 'Entrenador'),
(78, 78, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2026-04-25 22:05:34', '2026-04-25 22:05:34', 'Entrenador'),
(79, 79, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2026-04-25 22:05:58', '2026-04-25 22:05:58', 'Entrenador'),
(80, 80, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2026-04-25 22:07:14', '2026-04-25 22:07:14', 'Entrenador'),
(81, 81, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2026-04-25 22:07:24', '2026-04-25 22:07:24', 'Entrenador'),
(82, 82, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2026-04-25 22:07:54', '2026-04-25 22:07:54', 'Entrenador'),
(83, 83, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2026-04-25 22:08:11', '2026-04-25 22:08:11', 'Entrenador'),
(84, 84, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2026-04-25 22:08:29', '2026-04-25 22:08:29', 'Entrenador'),
(85, 85, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2026-04-25 22:08:42', '2026-04-25 22:08:42', 'Entrenador'),
(86, 86, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2026-04-25 22:08:52', '2026-04-25 22:08:52', 'Entrenador'),
(87, 87, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2026-04-25 22:09:01', '2026-04-25 22:09:01', 'Entrenador'),
(88, 88, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2026-04-25 22:09:28', '2026-04-25 22:09:28', 'Entrenador'),
(89, 89, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2026-04-25 22:09:47', '2026-04-25 22:09:47', 'Entrenador'),
(90, 90, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2026-04-25 22:09:59', '2026-04-25 22:09:59', 'Entrenador'),
(91, 91, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2026-04-25 22:10:19', '2026-04-25 22:10:19', 'Entrenador'),
(92, 92, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2026-04-25 22:10:31', '2026-04-25 22:10:31', 'Entrenador'),
(93, 93, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2026-04-25 22:10:47', '2026-04-25 22:10:47', 'Entrenador'),
(94, 94, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2026-04-25 22:10:59', '2026-04-25 22:10:59', 'Entrenador'),
(95, 95, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2026-04-25 22:12:23', '2026-04-25 22:12:23', 'Entrenador'),
(96, 96, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2026-04-25 22:12:34', '2026-04-25 22:12:34', 'Entrenador'),
(97, 97, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2026-04-25 22:13:10', '2026-04-25 22:13:10', 'Entrenador'),
(98, 98, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2026-04-25 22:13:46', '2026-04-25 22:13:46', 'Entrenador'),
(99, 99, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2026-04-25 22:13:58', '2026-04-25 22:13:58', 'Entrenador'),
(100, 100, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2026-04-25 22:14:11', '2026-04-25 22:14:11', 'Entrenador'),
(101, 101, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2026-04-25 22:21:59', '2026-04-25 22:21:59', 'Entrenador'),
(102, 102, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2026-04-26 16:18:23', '2026-04-26 16:18:23', 'Entrenador'),
(103, 103, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2026-04-26 16:19:00', '2026-04-26 16:19:00', 'Entrenador'),
(104, 104, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2026-04-26 16:20:35', '2026-04-26 16:20:35', 'Entrenador'),
(105, 105, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456788', '2026-04-27 19:21:54', '2026-04-27 19:21:54', 'Entrenador'),
(106, 106, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2026-04-27 19:22:56', '2026-04-27 19:22:56', 'Entrenador'),
(107, 107, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2026-04-27 19:23:33', '2026-04-27 19:23:33', 'Entrenador'),
(108, 108, 'Nombre', 'Apellido', 'DNI', '12345678', NULL, 1, 'delegado@correo.com', '123456789', '2026-04-27 19:24:37', '2026-04-27 19:24:37', 'Entrenador');

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
(1, 2, 1, 1, 1, 2, '2025-05-01 20:00:00', 24, 19, NULL, NULL, NULL, 26, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-05-01 22:04:06', 1),
(2, 1, 1, 1, 1, 3, '2025-05-02 13:00:00', 25, 25, NULL, NULL, NULL, 20, 16, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-05-02 13:53:03', 2),
(3, 2, 1, 1, 1, 4, '2025-05-01 16:00:00', 20, 20, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-05-01 17:38:51', 3),
(4, 1, 1, 1, 1, 5, '2025-05-02 16:00:00', 25, 25, NULL, NULL, NULL, 21, 20, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-05-02 18:22:56', 4),
(5, 2, 1, 1, 2, 3, '2025-05-02 11:00:00', 25, 25, NULL, NULL, NULL, 12, 21, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-05-02 11:55:53', 5),
(6, 1, 1, 1, 2, 4, '2025-05-02 14:00:00', 25, 8, 6, NULL, NULL, 13, 25, 15, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-05-02 14:55:17', 6),
(7, 2, 1, 1, 2, 5, '2025-05-01 14:00:00', 25, 25, NULL, NULL, NULL, 21, 23, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-05-01 15:09:19', 7),
(8, 2, 1, 1, 3, 4, '2025-05-01 21:00:00', 10, 10, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-05-01 22:55:03', 8),
(9, 2, 1, 1, 3, 5, '2025-05-01 19:00:00', 25, 13, 10, NULL, NULL, 23, 25, 15, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-05-01 21:06:44', 9),
(10, 1, 1, 1, 4, 5, '2025-05-02 11:00:00', 25, 25, NULL, NULL, NULL, 9, 10, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-05-02 12:01:05', 10),
(11, 4, 1, 2, 6, 7, '2025-05-02 14:00:00', 8, 25, 15, NULL, NULL, 25, 20, 11, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-05-02 19:41:01', 11),
(12, 4, 1, 2, 6, 8, '2025-05-01 14:00:00', 15, 16, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-05-01 14:48:48', 12),
(13, 4, 1, 2, 6, 9, '2025-05-01 16:00:00', 25, 25, NULL, NULL, NULL, 18, 24, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-05-01 17:18:49', 13),
(14, 4, 1, 2, 6, 10, '2025-05-02 11:00:00', 18, 12, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-05-02 11:59:43', 14),
(15, 4, 1, 2, 7, 8, '2025-05-01 17:00:00', 25, 17, 10, NULL, NULL, 23, 25, 15, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-05-01 18:45:31', 15),
(16, 4, 1, 2, 7, 9, '2025-05-02 12:00:00', 25, 25, NULL, NULL, NULL, 8, 21, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-05-02 17:02:47', 16),
(17, 4, 1, 2, 7, 10, '2025-05-01 15:00:00', 15, 25, 9, NULL, NULL, 25, 18, 15, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-05-01 16:15:21', 17),
(18, 4, 1, 2, 8, 9, '2025-05-02 10:00:00', 25, 25, NULL, NULL, NULL, 10, 7, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-05-02 10:54:40', 18),
(19, 4, 1, 2, 8, 10, '2025-05-02 13:00:00', 23, 23, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-05-02 19:38:14', 19),
(20, 4, 1, 2, 9, 10, '2025-05-01 18:00:00', 8, 11, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-05-01 19:40:13', 20),
(21, 2, 1, 3, 11, 12, '2025-05-01 15:00:00', 25, 25, NULL, NULL, NULL, 22, 15, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-05-01 16:21:49', 21),
(22, 2, 1, 3, 11, 13, '2025-05-02 13:00:00', 25, 25, NULL, NULL, NULL, 11, 13, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-05-02 13:57:08', 22),
(23, 1, 1, 3, 11, 14, '2025-05-02 15:00:00', 23, 25, 15, NULL, NULL, 25, 23, 8, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-05-02 18:23:41', 23),
(24, 2, 1, 3, 11, 15, '2025-05-01 18:00:00', 25, 25, NULL, NULL, NULL, 13, 13, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-05-01 19:31:58', 24),
(25, 1, 1, 3, 12, 13, '2025-05-02 17:00:00', 25, 25, NULL, NULL, NULL, 9, 16, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-05-02 18:22:12', 25),
(26, 2, 1, 3, 12, 14, '2025-05-01 17:00:00', 20, 6, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-05-01 18:37:13', 26),
(27, 1, 1, 3, 12, 15, '2025-05-02 18:00:00', 25, 25, NULL, NULL, NULL, 10, 20, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-05-02 19:16:05', 27),
(28, 1, 1, 3, 13, 14, '2025-05-01 19:00:00', 15, 13, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-05-01 19:45:13', 28),
(29, 1, 1, 3, 13, 15, '2025-05-01 16:00:00', 21, 25, 5, NULL, NULL, 25, 20, 15, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-05-01 18:05:39', 29),
(30, 2, 1, 3, 14, 15, '2025-05-02 12:00:00', 18, 25, 15, NULL, NULL, 25, 17, 8, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-28 23:49:22', '2025-05-02 13:09:28', 30),
(31, 1, 1, NULL, 4, 14, '2025-05-03 15:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-28 23:56:25', '2025-05-02 20:30:58', 31),
(32, 1, 1, NULL, 8, 4, '2025-05-03 11:00:00', 18, 14, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-28 23:56:25', '2025-05-03 12:05:33', 32),
(33, 1, 1, NULL, 14, 8, '2025-05-03 13:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-28 23:56:25', '2025-05-02 20:30:59', 33),
(34, 2, 1, NULL, 10, 11, '2025-05-03 15:00:00', 17, 17, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-28 23:57:20', '2025-05-03 16:17:27', 34),
(35, 2, 1, NULL, 2, 10, '2025-05-03 11:00:00', 18, 18, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-28 23:57:20', '2025-05-03 11:58:13', 35),
(36, 2, 1, NULL, 11, 2, '2025-05-03 13:00:00', 25, 25, NULL, NULL, NULL, 15, 20, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-28 23:57:20', '2025-05-03 13:58:58', 36),
(37, 6, 1, NULL, 1, 15, '2025-05-03 15:00:00', 13, 14, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 00:01:43', '2025-05-03 16:02:39', 37),
(38, 6, 1, NULL, 7, 1, '2025-05-03 11:00:00', 18, 15, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 00:01:43', '2025-05-03 12:15:30', 38),
(39, 6, 1, NULL, 15, 7, '2025-05-03 13:00:00', 25, 25, NULL, NULL, NULL, 20, 16, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 00:01:43', '2025-05-03 13:15:37', 39),
(40, 7, 1, NULL, 6, 12, '2025-05-03 17:00:00', 17, 17, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 00:01:43', '2025-05-03 18:17:11', 40),
(41, 7, 1, NULL, 5, 6, '2025-05-03 13:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 00:01:43', '2025-05-02 20:31:03', 41),
(42, 7, 1, NULL, 12, 5, '2025-05-03 15:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 00:01:43', '2025-05-02 20:31:03', 42),
(43, 7, 1, NULL, 3, 9, '2025-05-03 16:00:00', NULL, NULL, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 00:04:32', '2025-05-03 17:06:34', 43),
(44, 6, 1, NULL, 13, 3, '2025-05-03 12:00:00', 25, 18, 15, NULL, NULL, 13, 25, 8, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 00:04:32', '2025-05-03 12:16:34', 44),
(45, 7, 1, NULL, 9, 13, '2025-05-03 18:00:00', 23, 11, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 00:04:32', '2025-05-03 17:07:23', 45),
(46, 1, 1, NULL, NULL, 11, '2025-05-03 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-04-29 00:13:00', '2025-05-03 16:17:27', 46),
(47, 7, 1, NULL, 15, 12, '2025-05-03 19:00:00', 16, 18, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Eliminatorio', '2025-04-29 00:13:00', '2025-05-03 19:26:01', 47),
(48, 6, 2, 4, 16, 17, '2025-05-02 10:00:00', 25, 23, 11, NULL, NULL, 23, 25, 15, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:12:35', '2025-05-02 12:49:35', 48),
(49, 6, 2, 4, 16, 18, '2025-05-02 14:00:00', 25, 25, NULL, NULL, NULL, 5, 11, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:12:35', '2025-05-02 15:55:19', 49),
(50, 8, 2, 4, 16, 19, '2025-05-01 16:00:00', 24, 25, 15, NULL, NULL, 25, 21, 11, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:12:35', '2025-05-02 12:02:36', 50),
(51, 8, 2, 4, 16, 20, '2025-05-01 18:00:00', 25, 25, NULL, NULL, NULL, 3, 13, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:12:35', '2025-05-02 12:04:13', 51),
(52, 6, 2, 4, 16, 21, '2025-05-02 17:00:00', 25, 25, NULL, NULL, NULL, 11, 19, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:12:35', '2025-05-02 19:04:06', 52),
(53, 6, 2, 4, 16, 22, '2025-05-01 14:00:00', 25, 25, NULL, NULL, NULL, 15, 22, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:12:35', '2025-05-01 14:53:26', 53),
(54, 6, 2, 4, 17, 18, '2025-05-01 16:00:00', 25, 25, NULL, NULL, NULL, 15, 12, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:12:35', '2025-05-01 17:31:10', 54),
(55, 7, 2, 4, 17, 19, '2025-05-01 18:00:00', 25, 25, NULL, NULL, NULL, 19, 11, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:12:35', '2025-05-01 20:09:34', 55),
(56, 6, 2, 4, 17, 20, '2025-05-02 15:00:00', 25, 25, NULL, NULL, NULL, 13, 11, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:12:35', '2025-05-02 16:49:43', 56),
(57, 6, 2, 4, 17, 21, '2025-05-02 20:00:00', 25, 25, NULL, NULL, NULL, 17, 20, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:12:35', '2025-05-02 22:19:22', 57),
(58, 6, 2, 4, 17, 22, '2025-05-02 12:00:00', 25, 25, NULL, NULL, NULL, 18, 23, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:12:36', '2025-05-02 13:48:37', 58),
(59, 6, 2, 4, 18, 19, '2025-05-02 11:00:00', 16, 17, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:12:36', '2025-05-02 12:50:04', 59),
(60, 7, 2, 4, 18, 20, '2025-05-01 14:00:00', 25, 25, NULL, NULL, NULL, 21, 17, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:12:36', '2025-05-01 14:59:01', 60),
(61, 6, 2, 4, 18, 21, '2025-05-01 18:00:00', 24, 23, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:12:36', '2025-05-01 19:40:17', 61),
(62, 6, 2, 4, 18, 22, '2025-05-02 18:00:00', 10, 9, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:12:36', '2025-05-02 19:53:16', 62),
(63, 6, 2, 4, 19, 20, '2025-05-02 19:00:00', 25, 25, NULL, NULL, NULL, 21, 10, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:12:36', '2025-05-02 20:54:59', 63),
(64, 8, 2, 4, 19, 21, '2025-05-01 14:00:00', 20, 25, 16, NULL, NULL, 25, 13, 14, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:12:36', '2025-05-01 15:19:35', 64),
(65, 6, 2, 4, 19, 22, '2025-05-02 16:00:00', 22, 18, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:12:36', '2025-05-02 18:04:57', 65),
(66, 6, 2, 4, 20, 21, '2025-05-02 13:00:00', 22, 19, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:12:36', '2025-05-02 15:01:56', 66),
(67, 7, 2, 4, 20, 22, '2025-05-01 16:00:00', 13, 23, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:12:36', '2025-05-01 17:26:21', 67),
(68, 6, 2, 4, 21, 22, '2025-05-01 20:00:00', 10, 12, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:12:36', '2025-05-01 21:35:01', 68),
(69, 2, 2, 5, 23, 24, '2025-05-02 18:00:00', 25, 25, NULL, NULL, NULL, 13, 15, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:12:36', '2025-05-02 19:30:50', 69),
(70, 1, 2, 5, 23, 25, '2025-05-01 14:00:00', 25, 25, NULL, NULL, NULL, 8, 14, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:12:36', '2025-05-01 18:04:06', 70),
(71, 2, 2, 5, 23, 26, '2025-05-02 15:00:00', 25, 25, NULL, NULL, NULL, 15, 17, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:12:36', '2025-05-02 16:18:48', 71),
(72, 1, 2, 5, 23, 27, '2025-05-02 10:00:00', 25, 21, 10, NULL, NULL, 22, 25, 15, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:12:36', '2025-05-02 12:02:44', 72),
(73, 1, 2, 5, 23, 28, '2025-05-01 20:00:00', 25, 25, NULL, NULL, NULL, 22, 21, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:12:36', '2025-05-01 20:39:32', 73),
(74, 2, 2, 5, 24, 25, '2025-05-02 14:00:00', 25, 17, 7, NULL, NULL, 18, 25, 15, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:12:36', '2025-05-02 15:19:26', 74),
(75, 1, 2, 5, 24, 26, '2025-05-01 21:00:00', 16, 20, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:12:36', '2025-05-01 22:45:32', 75),
(76, 2, 2, 5, 24, 27, '2025-05-02 16:00:00', 19, 9, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:12:36', '2025-05-02 17:15:20', 76),
(77, 1, 2, 5, 24, 28, '2025-05-01 18:00:00', 15, 15, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:12:36', '2025-05-01 19:21:28', 77),
(78, 2, 2, 5, 25, 26, '2025-05-02 17:00:00', 14, 25, 5, NULL, NULL, 25, 23, 15, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:12:36', '2025-05-02 18:41:14', 78),
(79, 1, 2, 5, 25, 27, '2025-05-01 17:00:00', 7, 11, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:12:36', '2025-05-01 18:04:45', 79),
(80, 2, 2, 5, 25, 28, '2025-05-02 10:00:00', 25, 25, NULL, NULL, NULL, 14, 13, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:12:36', '2025-05-02 10:54:50', 80),
(81, 1, 2, 5, 26, 27, '2025-05-01 15:00:00', 24, 13, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:12:36', '2025-05-01 18:09:00', 81),
(82, 2, 2, 5, 26, 28, '2025-05-02 19:00:00', 25, 25, NULL, NULL, NULL, 17, 24, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:12:36', '2025-05-02 20:45:55', 82),
(83, 1, 2, 5, 27, 28, '2025-05-02 12:00:00', 25, 25, NULL, NULL, NULL, 8, 19, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:12:36', '2025-05-02 12:54:27', 83),
(84, 2, 2, NULL, 17, 27, '2025-05-03 16:00:00', 25, 24, 11, NULL, NULL, 16, 26, 15, NULL, NULL, 'Finalizado', 'Eliminatorio', '2025-04-29 09:12:36', '2025-05-03 17:43:59', 84),
(85, 6, 2, NULL, 22, 26, '2025-05-03 16:00:00', 13, 22, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Eliminatorio', '2025-04-29 09:12:36', '2025-05-03 17:14:19', 85),
(86, 2, 2, NULL, 16, 23, '2025-05-03 14:00:00', 19, 25, 15, NULL, NULL, 25, 17, 7, NULL, NULL, 'Finalizado', 'Eliminatorio', '2025-04-29 12:14:13', '2025-05-03 15:16:20', 86),
(87, 5, 3, 6, 29, 30, '2025-05-02 13:00:00', 25, 25, NULL, NULL, NULL, 12, 5, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:22:30', '2025-05-02 16:32:42', 87),
(88, 5, 3, 6, 29, 31, '2025-05-02 10:00:00', 25, 25, NULL, NULL, NULL, 23, 24, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:22:30', '2025-05-02 11:23:50', 88),
(89, 5, 3, 6, 29, 32, '2025-05-02 16:00:00', 25, 25, NULL, NULL, NULL, 16, 9, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:22:30', '2025-05-02 18:30:26', 89),
(90, 5, 3, 6, 29, 33, '2025-05-01 16:00:00', 25, 25, NULL, NULL, NULL, 20, 16, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:22:30', '2025-05-01 17:42:37', 90),
(91, 5, 3, 6, 29, 34, '2025-05-01 14:00:00', 25, 25, NULL, NULL, NULL, 13, 14, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:22:30', '2025-05-01 14:43:41', 91),
(92, 4, 3, 6, 30, 31, '2025-05-02 15:00:00', 23, 12, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:22:30', '2025-05-02 16:44:29', 92),
(93, 5, 3, 6, 30, 32, '2025-05-01 15:00:00', 25, 14, 15, NULL, NULL, 23, 25, 7, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:22:30', '2025-05-01 16:30:12', 93),
(94, 5, 3, 6, 30, 33, '2025-05-01 18:00:00', 17, 25, 6, NULL, NULL, 25, 18, 15, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:22:30', '2025-05-01 20:21:50', 94),
(95, 4, 3, 6, 30, 34, '2025-05-02 16:00:00', 16, 19, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:22:30', '2025-05-02 17:38:49', 95),
(96, 4, 3, 6, 31, 32, '2025-05-01 19:00:00', 25, 25, NULL, NULL, NULL, 13, 18, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:22:30', '2025-05-01 20:36:26', 96),
(97, 5, 3, 6, 31, 33, '2025-05-02 12:00:00', 23, 21, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:22:30', '2025-05-02 13:46:09', 97),
(98, 5, 3, 6, 31, 34, '2025-05-01 17:00:00', 20, 22, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:22:30', '2025-05-01 18:52:58', 98),
(99, 5, 3, 6, 32, 33, '2025-05-02 15:00:00', 8, 15, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:22:30', '2025-05-02 17:26:34', 99),
(100, 5, 3, 6, 32, 34, '2025-05-02 11:00:00', 24, 8, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:22:30', '2025-05-02 12:28:40', 100),
(101, 5, 3, 6, 33, 34, '2025-05-02 14:00:00', 25, 12, 9, NULL, NULL, 24, 25, 15, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:22:30', '2025-05-02 16:31:50', 101),
(102, 1, 3, NULL, 29, 31, '2025-05-03 12:00:00', 25, 25, NULL, NULL, NULL, 10, 23, NULL, NULL, NULL, 'Finalizado', 'Eliminatorio', '2025-04-29 09:22:30', '2025-05-03 13:19:11', 102),
(103, 2, 3, NULL, 34, 33, '2025-05-03 12:00:00', 25, 25, NULL, NULL, NULL, 13, 15, NULL, NULL, NULL, 'Finalizado', 'Eliminatorio', '2025-04-29 09:22:30', '2025-05-03 12:58:19', 103),
(104, 2, 3, NULL, 29, 34, '2025-05-03 17:00:00', 25, 25, NULL, NULL, NULL, 13, 11, NULL, NULL, NULL, 'Finalizado', 'Eliminatorio', '2025-04-29 09:22:30', '2025-05-03 18:38:17', 104),
(105, 6, 3, NULL, 30, 32, '2025-05-03 14:00:00', 21, 25, 15, NULL, NULL, 25, 17, 17, NULL, NULL, 'Finalizado', 'Eliminatorio', '2025-04-29 09:22:30', '2025-05-03 16:03:47', 105),
(106, 7, 4, 7, 35, 36, '2025-05-02 20:00:00', 25, 25, NULL, NULL, NULL, 17, 19, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:27:38', '2025-05-02 21:55:30', 106),
(107, 7, 4, 7, 35, 37, '2025-05-02 11:00:00', 20, 25, 15, NULL, NULL, 25, 23, 9, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:27:38', '2025-05-02 12:35:32', 107),
(108, 6, 4, 7, 35, 38, '2025-05-01 17:00:00', 15, 10, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:27:38', '2025-05-01 18:28:51', 108),
(109, 6, 4, 7, 35, 39, '2025-05-01 15:00:00', 25, 19, 16, NULL, NULL, 20, 25, 18, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:27:38', '2025-05-01 17:47:42', 109),
(110, 7, 4, 7, 35, 40, '2025-05-02 16:00:00', 25, 25, NULL, NULL, NULL, 20, 19, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:27:38', '2025-05-02 18:21:06', 110),
(111, 7, 4, 7, 35, 41, '2025-05-01 19:00:00', 11, 16, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:27:38', '2025-05-01 21:12:24', 111),
(112, 7, 4, 7, 36, 37, '2025-05-02 14:00:00', 13, 15, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:27:38', '2025-05-02 15:28:59', 112),
(113, 7, 4, 7, 36, 38, '2025-05-02 18:00:00', 17, 7, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:27:38', '2025-05-02 19:42:46', 113),
(114, 8, 4, 7, 36, 39, '2025-05-01 19:00:00', 24, 17, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:27:38', '2025-05-02 12:06:46', 114),
(115, 8, 4, 7, 36, 40, '2025-05-01 17:00:00', 25, 17, 16, NULL, NULL, 19, 25, 14, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:27:38', '2025-05-01 19:53:19', 115),
(116, 8, 4, 7, 36, 41, '2025-05-01 15:00:00', 21, 14, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:27:38', '2025-05-01 16:43:43', 116),
(117, 6, 4, 7, 37, 38, '2025-05-01 19:00:00', 14, 18, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:27:38', '2025-05-01 20:38:03', 117),
(118, 7, 4, 7, 37, 39, '2025-05-01 17:00:00', 23, 25, 11, NULL, NULL, 25, 19, 15, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:27:38', '2025-05-01 19:12:27', 118),
(119, 7, 4, 7, 37, 40, '2025-05-01 15:00:00', 25, 15, 15, NULL, NULL, 20, 25, 11, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:27:38', '2025-05-01 16:30:55', 119),
(120, 7, 4, 7, 37, 41, '2025-05-02 17:00:00', 20, 11, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:27:38', '2025-05-02 18:49:39', 120),
(121, 7, 4, 7, 38, 39, '2025-05-02 10:00:00', 25, 25, NULL, NULL, NULL, 12, 16, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:27:38', '2025-05-02 12:37:58', 121),
(122, 7, 4, 7, 38, 40, '2025-05-02 12:00:00', 25, 25, NULL, NULL, NULL, 7, 10, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:27:38', '2025-05-02 13:22:37', 122),
(123, 7, 4, 7, 38, 41, '2025-05-02 15:00:00', 25, 25, NULL, NULL, NULL, 16, 20, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:27:38', '2025-05-02 16:35:14', 123),
(124, 7, 4, 7, 39, 40, '2025-05-02 19:00:00', 25, 25, NULL, NULL, NULL, 16, 17, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:27:38', '2025-05-02 20:47:29', 124),
(125, 7, 4, 7, 39, 41, '2025-05-02 13:00:00', 9, 20, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:27:38', '2025-05-02 14:25:18', 125),
(126, 8, 4, 7, 40, 41, '2025-05-01 20:00:00', 15, 11, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:27:38', '2025-05-01 23:01:45', 126),
(127, 1, 4, NULL, 38, 41, '2025-05-03 16:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-04-29 09:27:38', '2025-05-02 22:32:03', 127),
(128, 7, 4, NULL, 37, 36, '2025-05-03 14:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-04-29 09:27:38', '2025-05-02 22:32:04', 128),
(129, 1, 4, NULL, 39, 35, '2025-05-03 14:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-04-29 12:28:31', '2025-05-02 22:32:04', 129),
(130, 3, 5, 8, 42, 43, '2025-05-01 21:00:00', 21, 22, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:35:26', '2025-05-01 22:40:54', 130),
(131, 3, 5, 8, 42, 44, '2025-05-02 19:00:00', 25, 24, 15, NULL, NULL, 17, 25, 10, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:35:26', '2025-05-02 23:12:42', 131),
(132, 3, 5, 8, 42, 45, '2025-05-02 14:00:00', 25, 25, NULL, NULL, NULL, 14, 13, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:35:26', '2025-05-02 15:45:40', 132),
(133, 3, 5, 8, 42, 46, '2025-05-01 14:00:00', 25, 25, NULL, NULL, NULL, 21, 17, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:35:26', '2025-05-01 14:54:27', 133),
(134, 3, 5, 8, 43, 44, '2025-05-02 11:00:00', 25, 25, NULL, NULL, NULL, 17, 18, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:35:26', '2025-05-02 12:21:04', 134),
(135, 3, 5, 8, 43, 45, '2025-05-01 17:00:00', 25, 25, NULL, NULL, NULL, 11, 11, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:35:26', '2025-05-01 17:55:00', 135),
(136, 3, 5, 8, 43, 46, '2025-05-02 13:00:00', 25, 20, 15, NULL, NULL, 24, 25, 13, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:35:26', '2025-05-02 14:53:48', 136),
(137, 3, 5, 8, 44, 45, '2025-05-01 22:00:00', 25, 25, NULL, NULL, NULL, 18, 18, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:35:26', '2025-05-01 23:32:21', 137),
(138, 3, 5, 8, 44, 46, '2025-05-01 20:00:00', 18, 17, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:35:26', '2025-05-01 21:33:06', 138),
(139, 3, 5, 8, 45, 46, '2025-05-02 10:00:00', 19, 25, 6, NULL, NULL, 25, 21, 15, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:35:26', '2025-05-02 11:29:54', 139),
(140, 3, 5, NULL, 43, 44, '2025-05-03 14:00:00', 25, 25, NULL, NULL, NULL, 20, 23, NULL, NULL, NULL, 'Finalizado', 'Eliminatorio', '2025-04-29 09:35:26', '2025-05-04 15:35:55', 140),
(141, 3, 5, NULL, 46, 42, '2025-05-03 15:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-04-29 09:35:26', '2025-05-02 22:33:30', 141),
(142, 3, 5, NULL, 43, NULL, '2025-05-03 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-04-29 09:35:26', '2025-05-04 15:35:55', 142),
(143, 3, 6, 9, 47, 48, '2025-05-02 18:00:00', 25, 23, 19, NULL, NULL, 22, 25, 17, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:44:16', '2025-05-02 19:49:36', 143),
(144, 3, 6, 9, 47, 49, '2025-05-01 16:00:00', 25, 13, 6, NULL, NULL, 21, 25, 15, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:44:16', '2025-05-01 17:10:59', 144),
(145, 1, 6, 9, 47, 50, '2025-05-01 22:00:00', 18, 21, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:44:16', '2025-05-01 22:44:49', 145),
(146, 1, 6, 9, 48, 49, '2025-05-01 23:00:00', 25, 14, 9, NULL, NULL, 18, 25, 15, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:44:16', '2025-05-02 10:09:56', 146),
(147, 3, 6, 9, 48, 50, '2025-05-01 19:00:00', 22, 20, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:44:16', '2025-05-01 20:33:04', 147),
(148, 3, 6, 9, 49, 50, '2025-05-02 17:00:00', 25, 25, NULL, NULL, NULL, 17, 14, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:44:16', '2025-05-02 18:09:46', 148),
(149, 3, 6, 10, 51, 52, '2025-05-02 16:00:00', 25, 25, NULL, NULL, NULL, 19, 19, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:44:16', '2025-05-02 16:55:14', 149),
(150, 3, 6, 10, 51, 53, '2025-05-01 15:00:00', 17, 15, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:44:16', '2025-05-01 15:59:15', 150),
(151, 2, 6, 10, 51, 54, '2025-05-01 22:00:00', 26, 25, NULL, NULL, NULL, 24, 19, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:44:16', '2025-05-02 00:07:27', 151),
(152, 2, 6, 10, 52, 53, '2025-05-01 23:00:00', 19, 10, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:44:16', '2025-05-02 00:38:59', 152),
(153, 3, 6, 10, 52, 54, '2025-05-01 18:00:00', 24, 25, 15, NULL, NULL, 26, 23, 13, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:44:16', '2025-05-01 19:27:09', 153),
(154, 3, 6, 10, 53, 54, '2025-05-02 12:00:00', 25, 25, NULL, NULL, NULL, 21, 19, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-04-29 09:44:16', '2025-05-02 13:26:53', 154),
(155, 3, 6, NULL, 49, 54, '2025-05-02 20:00:00', 25, 25, NULL, NULL, NULL, 13, 23, NULL, NULL, NULL, 'Finalizado', 'Eliminatorio', '2025-04-29 09:44:16', '2025-05-02 22:56:16', 155),
(156, 3, 6, NULL, 50, 52, '2025-05-02 21:00:00', 25, 25, NULL, NULL, NULL, 16, 19, NULL, NULL, NULL, 'Finalizado', 'Eliminatorio', '2025-04-29 09:44:16', '2025-05-02 22:54:54', 156),
(157, 1, 6, NULL, 53, 48, '2025-05-02 19:00:00', 25, 22, 15, NULL, NULL, 16, 25, 7, NULL, NULL, 'Finalizado', 'Eliminatorio', '2025-04-29 09:44:16', '2025-05-02 21:03:24', 157),
(158, 1, 6, NULL, 51, 47, '2025-05-02 20:00:00', 24, 25, 15, NULL, NULL, 26, 21, 7, NULL, NULL, 'Finalizado', 'Eliminatorio', '2025-04-29 09:44:16', '2025-05-02 22:31:38', 158),
(159, 3, 6, NULL, 49, 51, '2025-05-03 12:00:00', 26, 25, NULL, NULL, NULL, 24, 15, NULL, NULL, NULL, 'Finalizado', 'Eliminatorio', '2025-04-29 09:44:16', '2025-05-03 13:05:00', 159),
(160, 3, 6, NULL, 50, 53, '2025-05-03 13:00:00', 18, 12, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Eliminatorio', '2025-04-29 09:44:16', '2025-05-03 13:55:51', 160),
(161, 3, 6, NULL, 49, 53, '2025-05-03 16:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-04-29 09:44:16', '2025-05-03 13:55:51', 161),
(162, 7, 6, NULL, 54, 47, '2025-05-03 11:00:00', 23, 20, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Eliminatorio', '2025-04-29 12:47:31', '2025-05-03 11:12:26', 162),
(163, 7, 6, NULL, 52, 48, '2025-05-03 12:00:00', 21, 17, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Eliminatorio', '2025-04-29 12:47:31', '2025-05-03 12:37:18', 163),
(164, 6, 6, NULL, 47, 48, '2025-05-03 17:00:00', 26, 13, NULL, NULL, NULL, 27, 25, NULL, NULL, NULL, 'Finalizado', 'Eliminatorio', '2025-04-29 12:47:31', '2025-05-03 18:22:04', 164),
(165, 3, 5, NULL, 44, 45, '2025-05-03 11:00:00', 25, 25, NULL, NULL, NULL, 23, 21, NULL, NULL, NULL, 'Finalizado', 'Eliminatorio', '2025-05-02 13:03:00', '2025-05-03 12:02:07', 165),
(166, 10, 7, 11, 58, 59, '2026-05-02 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 20:12:03', '2026-04-28 18:49:43', 1),
(167, 9, 7, 11, 58, 61, '2026-05-01 19:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 20:12:03', '2026-04-27 22:24:58', 2),
(168, 9, 7, 11, 58, 67, '2026-05-01 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 20:12:03', '2026-04-27 22:24:36', 3),
(169, 10, 7, 11, 58, 105, '2026-05-02 13:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 20:12:03', '2026-04-28 18:48:43', 4),
(170, 10, 7, 11, 59, 61, '2026-05-01 14:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 20:12:03', '2026-04-27 22:27:23', 5),
(171, 9, 7, 11, 59, 67, '2026-05-02 11:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 20:12:03', '2026-04-28 18:24:06', 6),
(172, 10, 7, 11, 59, 105, '2026-05-01 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 20:12:03', '2026-04-27 22:31:58', 7),
(173, 9, 7, 11, 61, 67, '2026-05-02 14:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 20:12:03', '2026-04-28 18:25:36', 8),
(174, 10, 7, 11, 61, 105, '2026-05-02 16:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 20:12:03', '2026-04-28 18:49:24', 9),
(175, 9, 7, 11, 67, 105, '2026-05-01 13:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 20:12:03', '2026-04-27 22:11:49', 10),
(176, 12, 7, 12, 55, 56, '2026-05-01 19:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 20:12:03', '2026-04-28 08:08:36', 11),
(177, 12, 7, 12, 55, 57, '2026-05-02 16:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 20:12:03', '2026-04-28 17:21:09', 12),
(178, 12, 7, 12, 55, 63, '2026-05-02 12:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 20:12:03', '2026-04-28 17:17:33', 13),
(179, 12, 7, 12, 55, 65, '2026-05-01 14:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 20:12:03', '2026-04-28 08:05:29', 14),
(180, 12, 7, 12, 56, 57, '2026-05-01 15:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 20:12:03', '2026-04-28 08:05:58', 15),
(181, 12, 7, 12, 56, 63, '2026-05-02 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 20:12:03', '2026-04-28 17:21:34', 16),
(182, 12, 7, 12, 56, 65, '2026-05-02 13:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 20:12:03', '2026-04-28 17:18:48', 17),
(183, 12, 7, 12, 57, 63, '2026-05-01 20:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 20:12:03', '2026-04-28 08:09:00', 18),
(184, 12, 7, 12, 57, 65, '2026-05-02 11:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 20:12:03', '2026-04-28 17:17:15', 19),
(185, 12, 7, 12, 63, 65, '2026-05-01 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 20:12:03', '2026-04-28 08:08:14', 20),
(186, 14, 7, 13, 60, 62, '2026-05-02 10:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 20:12:04', '2026-04-28 19:19:39', 21),
(187, 14, 7, 13, 60, 64, '2026-05-01 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 20:12:04', '2026-04-28 08:18:40', 22),
(188, 14, 7, 13, 60, 66, '2026-05-02 16:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 20:12:04', '2026-04-28 19:21:51', 23),
(189, 14, 7, 13, 60, 68, '2026-05-01 15:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 20:12:04', '2026-04-28 08:16:04', 24),
(190, 14, 7, 13, 62, 64, '2026-05-01 19:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 20:12:04', '2026-04-28 08:20:41', 25),
(191, 15, 7, 13, 62, 66, '2026-05-01 15:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 20:12:04', '2026-04-28 08:33:46', 26),
(192, 14, 7, 13, 62, 68, '2026-05-02 13:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 20:12:04', '2026-04-28 19:20:55', 27),
(193, 15, 7, 13, 64, 66, '2026-05-02 12:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 20:12:04', '2026-04-28 19:23:03', 28),
(194, 15, 7, 13, 64, 68, '2026-05-02 15:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 20:12:04', '2026-04-28 19:23:55', 29),
(195, 15, 7, 13, 66, 68, '2026-05-01 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 20:12:04', '2026-04-28 08:35:15', 30),
(196, 9, 7, NULL, NULL, NULL, '2026-05-02 19:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2026-04-27 20:17:26', '2026-04-28 21:52:33', 31),
(198, 9, 7, NULL, NULL, NULL, '2026-05-03 15:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2026-04-27 20:36:11', '2026-04-29 20:44:26', 32),
(199, 9, 7, NULL, NULL, NULL, '2026-05-03 12:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2026-04-27 20:36:46', '2026-04-28 21:58:43', 33),
(200, 10, 7, NULL, NULL, NULL, '2026-05-03 14:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2026-04-27 20:37:58', '2026-04-29 20:44:57', 34),
(201, 10, 7, NULL, NULL, NULL, '2026-05-02 20:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2026-04-27 20:38:58', '2026-04-28 21:53:24', 35),
(202, 10, 7, NULL, NULL, NULL, '2026-05-03 11:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2026-04-27 20:40:03', '2026-04-29 20:37:44', 36),
(203, 10, 7, NULL, NULL, NULL, '2026-05-03 16:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2026-04-27 20:47:55', '2026-04-29 22:00:41', 37),
(204, 12, 7, NULL, NULL, NULL, '2026-05-02 19:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2026-04-27 20:50:00', '2026-04-28 17:24:41', 38),
(205, 14, 7, NULL, NULL, NULL, '2026-05-03 10:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2026-04-27 20:50:58', '2026-04-29 21:01:27', 39),
(206, 14, 7, NULL, NULL, NULL, '2026-05-03 12:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2026-04-27 20:51:49', '2026-04-29 21:04:01', 40),
(207, 15, 7, NULL, NULL, NULL, '2026-05-03 12:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2026-04-27 20:52:35', '2026-04-29 21:04:23', 41),
(208, 15, 7, NULL, NULL, NULL, '2026-05-03 10:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2026-04-27 20:53:28', '2026-04-29 21:01:57', 42),
(209, 12, 7, NULL, NULL, NULL, '2026-05-02 20:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2026-04-27 20:54:05', '2026-04-28 17:25:52', 43),
(210, 12, 7, NULL, NULL, NULL, '2026-05-03 14:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2026-04-27 20:56:24', '2026-04-29 22:03:26', 44),
(211, 9, 7, NULL, NULL, NULL, '2026-05-03 09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2026-04-27 20:57:34', '2026-04-28 21:56:47', 45),
(212, 9, 7, NULL, NULL, NULL, '2026-05-03 11:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2026-04-27 20:58:42', '2026-04-29 20:20:30', 46),
(213, 9, 7, NULL, NULL, NULL, '2026-05-03 10:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2026-04-27 20:59:26', '2026-04-28 21:57:18', 47),
(214, 9, 8, 14, 71, 72, '2026-05-01 14:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:06:00', '2026-04-27 22:13:42', 48),
(215, 9, 8, 14, 71, 75, '2026-05-02 09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:06:00', '2026-04-28 18:22:54', 49),
(216, 9, 8, 14, 71, 77, '2026-05-02 15:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:06:00', '2026-04-28 18:26:37', 50),
(217, 9, 8, 14, 72, 75, '2026-05-02 16:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:06:00', '2026-04-28 18:32:33', 51),
(218, 10, 8, 14, 72, 77, '2026-05-02 09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:06:00', '2026-04-28 18:39:00', 52),
(219, 10, 8, 14, 75, 77, '2026-05-01 15:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:06:00', '2026-04-27 22:28:21', 53),
(220, 10, 8, 15, 73, 74, '2026-05-02 14:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:06:00', '2026-04-28 18:49:06', 54),
(221, 9, 8, 15, 73, 76, '2026-05-02 10:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:06:00', '2026-04-28 18:23:48', 55),
(222, 10, 8, 15, 73, 78, '2026-05-01 13:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:06:00', '2026-04-27 22:26:49', 56),
(223, 9, 8, 15, 74, 76, '2026-05-01 15:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:06:00', '2026-04-27 22:15:34', 57),
(224, 10, 8, 15, 74, 78, '2026-05-02 10:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:06:00', '2026-04-28 18:46:49', 58),
(225, 10, 8, 15, 76, 78, '2026-05-02 15:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:06:00', '2026-04-28 18:39:27', 59),
(226, 15, 8, 16, 69, 70, '2026-05-02 14:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:06:00', '2026-04-28 19:23:41', 60),
(227, 15, 8, 16, 69, 79, '2026-05-02 11:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:06:00', '2026-04-28 19:22:38', 61),
(228, 14, 8, 16, 69, 106, '2026-05-01 16:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:06:00', '2026-04-28 08:17:00', 62),
(229, 15, 8, 16, 70, 79, '2026-05-01 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:06:00', '2026-04-28 08:34:27', 63),
(230, 15, 8, 16, 70, 106, '2026-05-02 10:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:06:00', '2026-04-28 19:22:20', 64),
(231, 15, 8, 16, 79, 106, '2026-05-02 13:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:06:00', '2026-04-28 19:23:24', 65),
(232, 10, 8, NULL, NULL, NULL, '2026-05-03 12:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2026-04-27 21:07:20', '2026-04-29 20:54:28', 66),
(233, 9, 8, NULL, NULL, NULL, '2026-05-03 14:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2026-04-27 21:08:43', '2026-04-29 20:50:47', 67),
(234, 11, 8, NULL, NULL, NULL, '2026-05-02 20:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2026-04-27 21:09:26', '2026-04-28 21:56:07', 68),
(235, 10, 8, NULL, NULL, NULL, '2026-05-03 15:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2026-04-27 21:12:48', '2026-04-29 20:46:11', 69),
(236, 9, 8, NULL, NULL, NULL, '2026-05-02 20:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2026-04-27 21:13:42', '2026-04-28 21:53:02', 70),
(237, 9, 8, NULL, NULL, NULL, '2026-05-03 16:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2026-04-27 21:14:20', '2026-04-29 20:52:15', 71),
(238, 10, 8, NULL, NULL, NULL, '2026-05-03 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2026-04-27 21:15:16', '2026-04-29 22:01:07', 72),
(239, 12, 8, NULL, NULL, NULL, '2026-05-02 21:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2026-04-27 21:32:49', '2026-04-28 17:29:53', 73),
(240, 14, 8, NULL, NULL, NULL, '2026-05-03 11:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2026-04-27 21:33:32', '2026-04-29 21:06:03', 74),
(241, 14, 8, NULL, NULL, NULL, '2026-05-03 13:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2026-04-27 21:34:15', '2026-04-29 21:06:43', 75),
(242, 15, 8, NULL, NULL, NULL, '2026-05-03 11:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2026-04-27 21:35:59', '2026-04-29 21:07:07', 76),
(243, 12, 8, NULL, NULL, NULL, '2026-05-02 22:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2026-04-27 21:36:47', '2026-04-28 17:30:51', 77),
(246, 15, 8, NULL, NULL, NULL, '2026-05-03 13:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2026-04-27 21:41:34', '2026-04-29 21:07:38', 78),
(247, 12, 8, NULL, NULL, NULL, '2026-05-03 15:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2026-04-27 21:41:58', '2026-04-29 22:03:54', 79),
(248, 9, 9, 17, 80, 82, '2026-05-02 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:48:45', '2026-04-28 18:33:50', 80),
(249, 10, 9, 17, 80, 90, '2026-05-01 16:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:48:45', '2026-04-27 22:29:10', 81),
(250, 9, 9, 17, 80, 94, '2026-05-02 12:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:48:45', '2026-04-28 18:24:47', 82),
(251, 9, 9, 17, 82, 90, '2026-05-02 13:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:48:45', '2026-04-28 18:25:20', 83),
(252, 9, 9, 17, 82, 94, '2026-05-01 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:48:45', '2026-04-27 22:23:28', 84),
(253, 9, 9, 17, 90, 94, '2026-05-02 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:48:45', '2026-04-28 18:33:23', 85),
(254, 12, 9, 18, 85, 86, '2026-05-02 14:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:48:45', '2026-04-28 17:19:11', 86),
(255, 12, 9, 18, 85, 87, '2026-05-01 16:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:48:45', '2026-04-28 08:06:51', 87),
(256, 12, 9, 18, 85, 91, '2026-05-02 09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:48:45', '2026-04-28 17:15:54', 88),
(257, 12, 9, 18, 86, 87, '2026-05-02 10:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:48:45', '2026-04-28 17:16:24', 89),
(258, 12, 9, 18, 86, 91, '2026-05-01 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:48:45', '2026-04-28 08:07:50', 90),
(259, 12, 9, 18, 87, 91, '2026-05-02 15:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:48:45', '2026-04-28 17:20:20', 91),
(260, 14, 9, 19, 81, 83, '2026-05-02 11:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:48:45', '2026-04-28 19:20:11', 92),
(261, 14, 9, 19, 81, 84, '2026-05-02 15:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:48:45', '2026-04-28 19:21:34', 93),
(262, 15, 9, 19, 81, 92, '2026-05-01 16:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:48:45', '2026-04-28 08:34:50', 94),
(263, 14, 9, 19, 83, 84, '2026-05-01 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:48:45', '2026-04-28 08:20:19', 95),
(264, 14, 9, 19, 83, 92, '2026-05-02 14:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:48:45', '2026-04-28 19:21:14', 96),
(265, 14, 9, 19, 84, 92, '2026-05-02 12:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:48:45', '2026-04-28 19:20:35', 97),
(266, 10, 9, 20, 88, 89, '2026-05-02 19:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:48:45', '2026-04-28 18:51:08', 98),
(267, 10, 9, 20, 88, 93, '2026-05-01 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:48:45', '2026-04-27 22:30:29', 99),
(268, 10, 9, 20, 88, 107, '2026-05-02 11:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:48:45', '2026-04-28 18:47:54', 100),
(269, 10, 9, 20, 89, 93, '2026-05-02 12:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:48:45', '2026-04-28 18:48:24', 101),
(270, 9, 9, 20, 89, 107, '2026-05-01 16:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:48:45', '2026-04-27 22:16:40', 102),
(271, 10, 9, 20, 93, 107, '2026-05-02 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:48:45', '2026-04-28 18:50:47', 103),
(272, 10, 9, NULL, NULL, NULL, '2026-05-03 10:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2026-04-27 21:48:45', '2026-04-29 20:25:44', 104),
(273, 11, 9, NULL, NULL, NULL, '2026-05-03 09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2026-04-27 21:48:45', '2026-04-29 20:26:47', 105),
(274, 10, 9, NULL, NULL, NULL, '2026-05-03 09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2026-04-27 21:48:45', '2026-04-29 20:56:35', 106);
INSERT INTO `partido` (`id`, `cancha_id`, `categoria_id`, `grupo_id`, `equipo_local_id`, `equipo_visitante_id`, `horario`, `local_set1`, `local_set2`, `local_set3`, `local_set4`, `local_set5`, `visitante_set1`, `visitante_set2`, `visitante_set3`, `visitante_set4`, `visitante_set5`, `estado`, `tipo`, `created_at`, `updated_at`, `numero`) VALUES
(275, 11, 9, NULL, NULL, NULL, '2026-05-03 10:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2026-04-27 21:48:45', '2026-04-29 20:42:03', 107),
(276, 9, 9, NULL, NULL, NULL, '2026-05-03 13:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2026-04-27 21:48:45', '2026-04-29 21:58:44', 108),
(277, 10, 9, NULL, NULL, NULL, '2026-05-03 13:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2026-04-27 21:48:45', '2026-04-29 21:59:19', 109),
(278, 9, 9, NULL, NULL, NULL, '2026-05-03 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2026-04-27 21:48:45', '2026-04-29 22:00:06', 110),
(279, 12, 9, NULL, NULL, NULL, '2026-05-03 09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2026-04-27 21:48:45', '2026-04-28 17:35:23', 111),
(280, 14, 9, NULL, NULL, NULL, '2026-05-03 09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2026-04-27 21:48:45', '2026-04-29 21:00:40', 112),
(281, 12, 9, NULL, NULL, NULL, '2026-05-03 10:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2026-04-27 21:48:45', '2026-04-28 17:35:53', 113),
(282, 15, 9, NULL, NULL, NULL, '2026-05-03 09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2026-04-27 21:48:45', '2026-04-29 21:01:03', 114),
(283, 12, 9, NULL, NULL, NULL, '2026-05-03 11:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2026-04-27 21:48:45', '2026-04-29 22:02:09', 115),
(284, 12, 9, NULL, NULL, NULL, '2026-05-03 12:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2026-04-27 21:48:45', '2026-04-29 22:02:41', 116),
(285, 12, 9, NULL, NULL, NULL, '2026-05-03 13:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2026-04-27 21:48:45', '2026-04-29 22:03:05', 117),
(286, 11, 10, 21, 95, 96, '2026-05-01 15:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:52:09', '2026-04-27 22:36:01', 118),
(287, 11, 10, 21, 95, 102, '2026-05-02 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:52:09', '2026-04-28 19:28:45', 119),
(288, 10, 10, 21, 95, 103, '2026-05-01 19:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:52:09', '2026-04-27 22:32:35', 120),
(289, 11, 10, 21, 95, 108, '2026-05-02 19:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:52:09', '2026-04-28 19:29:15', 121),
(290, 11, 10, 21, 96, 102, '2026-05-02 13:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:52:09', '2026-04-28 19:27:07', 122),
(291, 11, 10, 21, 96, 103, '2026-05-02 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:52:09', '2026-04-28 19:29:00', 123),
(292, 11, 10, 21, 96, 108, '2026-05-01 20:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:52:09', '2026-04-27 22:40:55', 124),
(293, 11, 10, 21, 102, 103, '2026-05-01 16:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:52:09', '2026-04-27 22:37:15', 125),
(294, 11, 10, 21, 102, 108, '2026-05-01 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:52:09', '2026-04-27 22:38:58', 126),
(295, 11, 10, 21, 103, 108, '2026-05-02 12:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:52:09', '2026-04-28 19:26:47', 127),
(296, 11, 10, NULL, NULL, NULL, '2026-05-03 14:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2026-04-27 21:52:09', '2026-04-29 20:42:50', 128),
(297, 11, 10, NULL, NULL, NULL, '2026-05-03 15:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2026-04-27 21:52:09', '2026-04-29 20:43:09', 129),
(298, 11, 10, NULL, NULL, NULL, '2026-05-03 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2026-04-27 21:52:09', '2026-04-29 22:01:29', 130),
(299, 10, 11, 22, 97, 98, '2026-05-01 20:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:53:19', '2026-04-27 22:33:04', 131),
(300, 11, 11, 22, 97, 99, '2026-05-02 09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:53:19', '2026-04-28 19:25:27', 132),
(301, 11, 11, 22, 97, 100, '2026-05-01 14:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:53:19', '2026-04-27 22:35:26', 133),
(302, 11, 11, 22, 97, 101, '2026-05-03 11:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:53:19', '2026-04-28 19:29:58', 134),
(303, 11, 11, 22, 97, 104, '2026-05-02 14:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:53:19', '2026-04-28 19:27:30', 135),
(304, 11, 11, 22, 98, 99, '2026-05-01 13:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:53:19', '2026-04-27 22:34:34', 136),
(305, 11, 11, 22, 98, 100, '2026-05-03 13:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:53:19', '2026-04-28 19:30:29', 137),
(306, 11, 11, 22, 98, 101, '2026-05-02 16:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:53:19', '2026-04-28 19:28:28', 138),
(307, 11, 11, 22, 98, 104, '2026-05-02 10:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:53:19', '2026-04-28 19:25:50', 139),
(308, 11, 11, 22, 99, 100, '2026-05-02 15:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:53:19', '2026-04-28 19:27:58', 140),
(309, 11, 11, 22, 99, 101, '2026-05-01 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:53:19', '2026-04-27 22:37:47', 141),
(310, 11, 11, 22, 99, 104, '2026-05-03 12:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:53:19', '2026-04-28 19:30:15', 142),
(311, 11, 11, 22, 100, 101, '2026-05-02 11:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:53:19', '2026-04-28 19:26:23', 143),
(312, 11, 11, 22, 100, 104, '2026-05-01 21:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:53:19', '2026-04-27 22:41:30', 144),
(313, 11, 11, 22, 101, 104, '2026-05-01 19:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2026-04-27 21:53:19', '2026-04-27 22:40:08', 145),
(314, 11, 11, NULL, NULL, NULL, '2026-05-03 16:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2026-04-27 21:53:19', '2026-04-29 20:43:52', 146),
(315, 14, 11, NULL, NULL, NULL, '2026-05-03 14:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2026-04-27 21:53:19', '2026-04-30 09:36:20', 147),
(316, 15, 11, NULL, NULL, NULL, '2026-05-03 14:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2026-04-27 21:53:19', '2026-04-30 09:36:30', 148),
(318, 9, 10, NULL, NULL, NULL, '2026-05-02 21:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2026-04-30 09:34:03', '2026-04-30 09:34:51', 149);

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
(50, 164, NULL, NULL, 162, 163, NULL, NULL, 'Final Plata 1', '2025-04-29 13:13:02', '2025-04-29 13:13:02', NULL, NULL),
(51, 165, 8, 8, NULL, NULL, 4, 5, 'RECLASIFICACION M+42', '2025-05-02 13:03:00', '2025-05-02 13:03:00', NULL, NULL),
(52, 196, 11, 12, NULL, NULL, 1, 2, 'Triangular 1 Oro 1', '2026-04-27 20:32:13', '2026-04-27 20:32:13', NULL, NULL),
(54, 198, 13, 11, NULL, NULL, 2, 1, 'Triangula 1 Oro 2', '2026-04-27 20:36:11', '2026-04-27 20:36:11', NULL, NULL),
(55, 199, 12, 13, NULL, NULL, 2, 2, 'Triangula 1 Oro 3', '2026-04-27 20:36:47', '2026-04-27 20:36:47', NULL, NULL),
(56, 200, 12, 13, NULL, NULL, 1, 1, 'Triangula 2 Oro 1', '2026-04-27 20:37:58', '2026-04-27 20:37:58', NULL, NULL),
(57, 201, 11, 12, NULL, NULL, 2, 1, 'Triangula 2 Oro 2', '2026-04-27 20:38:58', '2026-04-27 20:38:58', NULL, NULL),
(58, 202, 13, 11, NULL, NULL, 1, 2, 'Triangula 2 Oro 3', '2026-04-27 20:40:03', '2026-04-27 20:40:03', NULL, NULL),
(60, 204, 11, 12, NULL, NULL, 3, 4, 'Triangular 1 Plata 1', '2026-04-27 20:50:00', '2026-04-27 20:50:00', NULL, NULL),
(61, 205, 12, 13, NULL, NULL, 4, 4, 'Triangular 1 Plata 2', '2026-04-27 20:50:58', '2026-04-27 20:50:58', NULL, NULL),
(62, 206, 13, 11, NULL, NULL, 4, 3, 'Triangular 1 Plata 3', '2026-04-27 20:51:49', '2026-04-27 20:51:49', NULL, NULL),
(63, 207, 12, 13, NULL, NULL, 3, 3, 'Triangular 2 Plata 1', '2026-04-27 20:52:35', '2026-04-27 20:52:35', NULL, NULL),
(64, 208, 13, 11, NULL, NULL, 3, 4, 'Triangular 2 Plata 2', '2026-04-27 20:53:28', '2026-04-27 20:53:28', NULL, NULL),
(65, 209, 11, 12, NULL, NULL, 4, 3, 'Triangular 2 Plata 3', '2026-04-27 20:54:05', '2026-04-27 20:54:05', NULL, NULL),
(66, 211, 11, 12, NULL, NULL, 5, 5, 'Triangular Bronce 1', '2026-04-27 20:57:34', '2026-04-27 20:57:34', NULL, NULL),
(67, 212, 13, 11, NULL, NULL, 5, 5, 'Triangular Bronce 2', '2026-04-27 20:58:42', '2026-04-27 20:58:42', NULL, NULL),
(68, 213, 12, 13, NULL, NULL, 5, 5, 'Triangular Bronce 3', '2026-04-27 20:59:26', '2026-04-27 20:59:26', NULL, NULL),
(69, 232, 14, 15, NULL, NULL, 1, 2, 'Triangular 1 Oro 1', '2026-04-27 21:07:20', '2026-04-27 21:07:20', NULL, NULL),
(70, 233, 15, 16, NULL, NULL, 2, 2, 'Triangular 1 Oro 2', '2026-04-27 21:08:43', '2026-04-27 21:08:43', NULL, NULL),
(71, 234, 16, 14, NULL, NULL, 2, 1, 'Triangular 1 Oro 3', '2026-04-27 21:11:57', '2026-04-27 21:11:57', NULL, NULL),
(72, 235, 15, 16, NULL, NULL, 1, 1, 'Triangular 2 Oro 1', '2026-04-27 21:12:48', '2026-04-27 21:12:48', NULL, NULL),
(73, 236, 16, 14, NULL, NULL, 1, 2, 'Triangular 2 Oro 2', '2026-04-27 21:13:42', '2026-04-27 21:13:42', NULL, NULL),
(74, 237, 14, 15, NULL, NULL, 2, 1, 'Triangular 2 Oro 3', '2026-04-27 21:14:20', '2026-04-27 21:14:20', NULL, NULL),
(75, 239, 14, 15, NULL, NULL, 3, 4, 'Triangular 1 Plata 1', '2026-04-27 21:32:49', '2026-04-27 21:32:49', NULL, NULL),
(76, 240, 15, 16, NULL, NULL, 4, 4, 'Triangular 1 Plata 2', '2026-04-27 21:33:32', '2026-04-27 21:33:32', NULL, NULL),
(77, 241, 16, 14, NULL, NULL, 4, 3, 'Triangular 1 Plata 3', '2026-04-27 21:34:15', '2026-04-27 21:34:15', NULL, NULL),
(78, 242, 15, 16, NULL, NULL, 3, 3, 'Triangular 2 Plata 1', '2026-04-27 21:35:59', '2026-04-27 21:35:59', NULL, NULL),
(79, 243, 16, 14, NULL, NULL, 3, 4, 'Triangular 2 Plata 2', '2026-04-27 21:36:47', '2026-04-27 21:36:47', NULL, NULL),
(82, 246, 14, 15, NULL, NULL, 4, 3, 'Triangular 2 Plata 3', '2026-04-27 21:41:34', '2026-04-27 21:41:34', NULL, NULL),
(83, 272, 17, 18, NULL, NULL, 1, 2, 'Cuartos de Final Oro 1', '2026-04-27 21:48:45', '2026-04-27 21:48:45', NULL, NULL),
(84, 273, 18, 17, NULL, NULL, 1, 2, 'Cuartos de Final Oro 2', '2026-04-27 21:48:45', '2026-04-27 21:48:45', NULL, NULL),
(85, 274, 19, 20, NULL, NULL, 1, 2, 'Cuartos de Final Oro 3', '2026-04-27 21:48:45', '2026-04-27 21:48:45', NULL, NULL),
(86, 275, 20, 19, NULL, NULL, 1, 2, 'Cuartos de Final Oro 4', '2026-04-27 21:48:45', '2026-04-27 21:48:45', NULL, NULL),
(87, 276, NULL, NULL, 272, 275, NULL, NULL, 'Semi Final Oro 1', '2026-04-27 21:48:45', '2026-04-29 21:47:25', NULL, NULL),
(88, 277, NULL, NULL, 273, 274, NULL, NULL, 'Semi Final Oro 2', '2026-04-27 21:48:45', '2026-04-29 21:48:13', NULL, NULL),
(89, 278, NULL, NULL, 276, 277, NULL, NULL, 'Final Oro 1', '2026-04-27 21:48:45', '2026-04-29 21:48:49', NULL, NULL),
(90, 279, 17, 18, NULL, NULL, 3, 4, 'Cuartos de Final Plata 1', '2026-04-27 21:48:45', '2026-04-27 21:48:45', NULL, NULL),
(91, 280, 18, 17, NULL, NULL, 3, 4, 'Cuartos de Final Plata 2', '2026-04-27 21:48:45', '2026-04-27 21:48:45', NULL, NULL),
(92, 281, 19, 20, NULL, NULL, 3, 4, 'Cuartos de Final Plata 3', '2026-04-27 21:48:45', '2026-04-27 21:48:45', NULL, NULL),
(93, 282, 20, 19, NULL, NULL, 3, 4, 'Cuartos de Final Plata 4', '2026-04-27 21:48:45', '2026-04-27 21:48:45', NULL, NULL),
(94, 283, NULL, NULL, 279, 282, NULL, NULL, 'Semi Final Plata 1', '2026-04-27 21:48:45', '2026-04-29 21:53:37', NULL, NULL),
(95, 284, NULL, NULL, 280, 281, NULL, NULL, 'Semi Final Plata 2', '2026-04-27 21:48:45', '2026-04-29 21:54:02', NULL, NULL),
(96, 285, NULL, NULL, 283, 284, NULL, NULL, 'Final Plata 1', '2026-04-27 21:48:45', '2026-04-29 21:54:33', NULL, NULL),
(97, 296, 21, 21, NULL, NULL, 1, 4, 'Semi Final Oro 1', '2026-04-27 21:52:09', '2026-04-27 21:52:09', NULL, NULL),
(98, 297, 21, 21, NULL, NULL, 2, 3, 'Semi Final Oro 2', '2026-04-27 21:52:09', '2026-04-27 21:52:09', NULL, NULL),
(99, 298, NULL, NULL, 296, 297, NULL, NULL, 'Final Oro 1', '2026-04-27 21:52:09', '2026-04-29 21:56:42', NULL, NULL),
(100, 314, 22, 22, NULL, NULL, 1, 2, 'Final Oro 1', '2026-04-27 21:53:19', '2026-04-27 21:53:19', NULL, NULL),
(101, 315, 22, 22, NULL, NULL, 3, 4, 'Final Plata 1', '2026-04-27 21:53:19', '2026-04-27 21:53:19', NULL, NULL),
(102, 316, 22, 22, NULL, NULL, 5, 6, 'Final Bronce 1', '2026-04-27 21:53:19', '2026-04-27 21:53:19', NULL, NULL),
(103, 203, NULL, NULL, 202, 196, NULL, NULL, 'Final Oro F+35', '2026-04-29 21:14:43', '2026-04-29 21:17:05', NULL, NULL),
(104, 210, NULL, NULL, 209, 206, NULL, NULL, 'Final Plata F+35', '2026-04-29 21:15:52', '2026-04-29 21:15:52', NULL, NULL),
(105, 238, NULL, NULL, 232, 237, NULL, NULL, 'Final Oro F+40', '2026-04-29 21:18:55', '2026-04-29 21:20:28', NULL, NULL),
(106, 247, NULL, NULL, 246, 239, NULL, NULL, 'Final Plata F+40', '2026-04-29 21:19:38', '2026-04-29 21:19:38', NULL, NULL),
(107, 318, 21, 21, NULL, NULL, 4, 5, 'Clasificación 4 y 5', '2026-04-30 09:34:03', '2026-04-30 09:34:03', NULL, NULL);

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
(3, 1, 'GyE', 'Calle 1234', '2025-04-28 21:14:42', '2025-04-28 21:14:42'),
(4, 2, 'Club Villa Dora', 'Ruperto Godoy 1231', '2026-04-24 08:51:01', '2026-04-24 08:51:01'),
(5, 2, 'Club Regatas Santa Fe', 'Av. Leandro N. Alem 3288', '2026-04-25 23:00:03', '2026-04-25 23:00:03'),
(6, 2, 'Club El Quilla', 'Av. de Circunvalación de Sta. Fe 4264', '2026-04-25 23:00:34', '2026-04-25 23:00:34');

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
(1, 1, 'XV Torneo Abierto Sudamericano de Mastervoley Sta Fe', 'xv_master_voley', '', '2025-04-28 20:05:00', '2025-04-28 20:06:00', '2025-05-01 11:00:00', '2025-05-03 23:59:00', NULL, '2025-04-28 20:06:06', '2025-04-28 20:06:06', 'Borrador'),
(2, 1, 'XVI Torneo Abierto Sudamericano de Mastervoley Sta Fe', 'xvi_master_voley', '', '2026-04-24 08:49:00', '2026-04-30 23:59:00', '2026-05-01 07:00:00', '2026-05-03 23:59:00', NULL, '2026-04-24 08:51:01', '2026-04-24 08:51:01', 'Borrador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `torneo_partido_secuencia`
--

CREATE TABLE `torneo_partido_secuencia` (
  `torneo_id` int(11) NOT NULL,
  `ultimo_numero` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `torneo_partido_secuencia`
--

INSERT INTO `torneo_partido_secuencia` (`torneo_id`, `ultimo_numero`) VALUES
(1, 165),
(2, 149);

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
(1, 'admin', '[\"ROLE_USER\",\"ROLE_ADMIN\"]', '$2y$13$Lj5VyBwyiKK5EG7rfFUZY.Yv62A.KnlqAzPvp7XoppGXGTwEJH6Tm', 'administrador@correo.com', 'Administrador', 'Administrador', '2025-04-28 15:30:39', '2026-04-21 09:40:12'),
(2, 'planillero', '[\"ROLE_USER\",\"ROLE_PLANILLERO\"]', '$2y$13$1uKMb7XyaU5bHcxooM.0NOqi0IviF1fCmBj//qYh8JhULAgO1TnZi', 'planillero@correo.com', 'Planillero', 'Planillero', '2025-04-30 09:19:04', '2026-04-21 09:40:19');

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
-- Indices de la tabla `torneo_partido_secuencia`
--
ALTER TABLE `torneo_partido_secuencia`
  ADD PRIMARY KEY (`torneo_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `equipo`
--
ALTER TABLE `equipo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT de la tabla `grupo`
--
ALTER TABLE `grupo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `jugador`
--
ALTER TABLE `jugador`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT de la tabla `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `partido`
--
ALTER TABLE `partido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=319;

--
-- AUTO_INCREMENT de la tabla `partido_config`
--
ALTER TABLE `partido_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT de la tabla `sede`
--
ALTER TABLE `sede`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `torneo`
--
ALTER TABLE `torneo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
-- Filtros para la tabla `torneo_partido_secuencia`
--
ALTER TABLE `torneo_partido_secuencia`
  ADD CONSTRAINT `FK_TORNEO_PARTIDO_SECUENCIA_TORNEO` FOREIGN KEY (`torneo_id`) REFERENCES `torneo` (`id`) ON DELETE CASCADE;

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
