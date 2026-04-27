-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 26-04-2025 a las 15:39:29
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
-- Base de datos: `u283477853_torneo`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cancha`
--

CREATE TABLE `cancha` (
  `id` int(11) NOT NULL,
  `club_id` int(11) DEFAULT NULL,
  `nombre` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cancha`
--

INSERT INTO `cancha` (`id`, `club_id`, `nombre`, `created_at`, `updated_at`) VALUES
(1, 1, 'Cancha 1 (Arriba)', '2024-04-26 20:44:37', '2024-04-30 17:06:03'),
(2, 1, 'Cancha 2 (Arriba)', '2024-04-26 20:44:49', '2024-04-30 17:06:07'),
(3, 1, 'Cancha 3 (Abajo)', '2024-04-26 20:45:07', '2024-04-30 17:06:40'),
(4, 1, 'Cancha 4 (Abajo)', '2024-04-30 17:06:35', '2024-04-30 17:06:35'),
(5, 2, 'Cancha 1', '2024-04-30 17:56:01', '2024-04-30 17:56:01'),
(6, 2, 'Cancha 2', '2024-04-30 17:56:08', '2024-04-30 17:56:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id` int(11) NOT NULL,
  `nombre` varchar(64) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id`, `nombre`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, '+30', 'Categoría mayores de 30 años', '2024-03-20 08:30:32', '2024-03-20 08:30:32'),
(2, '+35', 'Categoría mayores de 35 años', '2024-03-20 08:30:32', '2024-03-20 08:30:32'),
(3, '+42', 'Categoría mayores de 42 años', '2024-03-20 08:30:32', '2024-03-20 08:30:32'),
(4, '+50', 'Categoría mayores de 50 años', '2024-03-20 08:30:32', '2024-03-20 08:30:32'),
(5, '+45', 'Categoría mayores de 45 años', '2024-04-26 15:57:46', '2024-04-26 15:57:46'),
(6, '+40', 'Categoría mayores de 40 años', '2024-04-26 20:57:55', '2024-04-26 20:57:55');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `club`
--

CREATE TABLE `club` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `club`
--

INSERT INTO `club` (`id`, `nombre`, `direccion`, `created_at`, `updated_at`) VALUES
(1, 'Club Villa Dora', 'Ruperto Godoy 1231', '2024-04-26 20:44:20', '2024-04-30 17:54:38'),
(2, 'Club Regatas Santa Fe', 'Avenida Leandro N. Alem 3288', '2024-04-26 20:45:36', '2024-04-30 17:53:09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuartos`
--

CREATE TABLE `cuartos` (
  `id` int(11) NOT NULL,
  `partido1_id` int(11) NOT NULL,
  `partido2_id` int(11) NOT NULL,
  `partido3_id` int(11) NOT NULL,
  `partido4_id` int(11) NOT NULL,
  `play_off_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cuartos`
--

INSERT INTO `cuartos` (`id`, `partido1_id`, `partido2_id`, `partido3_id`, `partido4_id`, `play_off_id`) VALUES
(1, 88, 89, 90, 91, 1),
(2, 92, 93, 94, 95, 2);

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
('DoctrineMigrations\\Version20240326215205', '2024-03-26 18:53:35', 176),
('DoctrineMigrations\\Version20240329195031', '2024-04-04 20:46:23', 20),
('DoctrineMigrations\\Version20240430180724', '2024-05-01 18:13:25', 16),
('DoctrineMigrations\\Version20240502173509', '2024-05-03 00:18:49', 55);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipo`
--

CREATE TABLE `equipo` (
  `id` int(11) NOT NULL,
  `torneo_genero_categoria_id` int(11) DEFAULT NULL,
  `nombre` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `equipo`
--

INSERT INTO `equipo` (`id`, `torneo_genero_categoria_id`, `nombre`, `created_at`, `updated_at`) VALUES
(1, 1, 'VILLA DORA', '2024-04-29 21:26:21', '2024-04-29 21:26:21'),
(2, 1, 'CORRIENTES VOLEY', '2024-04-29 21:26:38', '2024-04-29 21:26:38'),
(3, 1, 'TREDE BIRRA', '2024-04-29 21:26:50', '2024-04-29 21:26:50'),
(4, 1, 'ALUMNI CASILDA', '2024-04-29 21:27:01', '2024-04-29 21:27:01'),
(5, 1, 'EL QUILLA', '2024-04-29 21:27:15', '2024-04-29 21:27:15'),
(6, 1, 'MONSTARS', '2024-04-29 21:27:24', '2024-04-29 21:27:24'),
(7, 1, 'CLUB JUNIN', '2024-04-29 21:27:38', '2024-04-29 21:27:38'),
(8, 1, 'REGATAS ROSARIO', '2024-04-29 21:27:49', '2024-04-29 21:27:49'),
(9, 1, 'ALIANZA SANTO TOME', '2024-04-29 21:28:01', '2024-04-29 21:28:01'),
(10, 1, 'NAUTICO AVELLANEDA', '2024-04-29 21:28:10', '2024-04-29 21:28:10'),
(11, 1, 'MALUCAS', '2024-04-29 21:28:21', '2024-04-29 21:28:21'),
(12, 1, 'LAS GRULLAS', '2024-04-29 21:28:33', '2024-04-29 21:28:33'),
(13, 1, 'INFINITO', '2024-04-29 21:28:43', '2024-04-29 21:28:43'),
(14, 1, 'LA EMILIA', '2024-04-29 21:28:51', '2024-04-29 21:28:51'),
(15, 1, 'SANTOTO VOLEY', '2024-04-29 21:29:34', '2024-04-29 21:29:34'),
(16, 1, 'LAS CUERVAS', '2024-04-29 21:29:45', '2024-04-29 21:29:45'),
(17, 2, 'TREDE BIRRA', '2024-04-29 22:29:09', '2024-04-29 22:29:09'),
(18, 2, 'VAMOS EL APOYO', '2024-04-29 22:29:18', '2024-04-29 22:29:18'),
(19, 2, 'COSTA CANELONES', '2024-04-29 22:29:27', '2024-04-29 22:29:27'),
(20, 2, 'INTRUSAS', '2024-04-29 22:29:44', '2024-04-29 22:29:44'),
(21, 2, 'DOS HACHES', '2024-04-29 22:30:02', '2024-04-29 22:30:02'),
(22, 2, 'SOMOS LA 18', '2024-04-29 22:30:11', '2024-04-29 22:30:11'),
(23, 2, 'CLUB A. FISHERTON', '2024-04-29 22:30:23', '2024-04-29 22:30:23'),
(24, 2, 'COSTA MIX', '2024-04-29 22:30:34', '2024-04-29 22:30:34'),
(25, 2, 'PASO DEL REY', '2024-04-29 22:30:51', '2024-04-29 22:30:51'),
(26, 2, 'DESTINO VOLEY', '2024-04-29 22:31:01', '2024-04-29 22:31:01'),
(27, 2, 'CITADAS', '2024-04-29 22:31:10', '2024-04-29 22:31:10'),
(28, 2, 'MALUCAS', '2024-04-29 22:31:18', '2024-04-29 22:31:18'),
(29, 3, 'EL REJUNTE', '2024-04-29 22:39:02', '2024-04-29 22:39:02'),
(30, 3, 'CLUB ROSARIO', '2024-04-29 22:39:18', '2024-04-29 22:39:18'),
(31, 3, 'GIMNASIA CONCEP. URUG.', '2024-04-29 22:39:38', '2024-04-29 22:39:38'),
(32, 3, 'MONSTARS', '2024-04-29 22:39:49', '2024-04-29 22:39:49'),
(33, 3, 'VOLEY MONTE', '2024-04-29 22:40:06', '2024-04-29 22:40:06'),
(34, 3, 'BANCO SF', '2024-04-29 22:40:26', '2024-04-29 22:40:26'),
(35, 3, 'UNI', '2024-04-29 22:40:42', '2024-04-29 22:40:42'),
(36, 4, 'MAXI STA FE A', '2024-04-30 14:54:57', '2024-04-30 14:56:43'),
(37, 4, 'PERO', '2024-04-30 14:55:05', '2024-04-30 14:55:05'),
(38, 4, 'RECREATIVO VERA', '2024-04-30 14:55:26', '2024-04-30 14:55:26'),
(39, 4, 'BOSQUE URUGUAY', '2024-04-30 14:55:44', '2024-04-30 14:55:44'),
(40, 4, 'ROSARIO VOLEY', '2024-04-30 14:55:57', '2024-04-30 14:55:57'),
(41, 4, 'LA TRIBU', '2024-04-30 14:56:05', '2024-04-30 14:56:05'),
(42, 4, 'MAXI STA FE B', '2024-04-30 14:56:19', '2024-04-30 14:56:19'),
(43, 4, 'BANCO PROVINCIA', '2024-04-30 14:56:32', '2024-04-30 14:56:32'),
(44, 5, 'MAXI STA FE', '2024-04-30 14:57:03', '2024-04-30 14:57:03'),
(45, 5, 'CORCHA VOLEY', '2024-04-30 14:57:10', '2024-04-30 14:57:10'),
(46, 5, 'LOS PERKINS', '2024-04-30 14:57:18', '2024-04-30 14:57:18'),
(47, 5, 'ABANDONADOS', '2024-04-30 14:57:34', '2024-04-30 14:57:34'),
(48, 5, 'ROSARIO VOLEY', '2024-04-30 14:57:43', '2024-04-30 14:57:43'),
(49, 5, 'DEF. MORENO', '2024-04-30 14:58:02', '2024-04-30 14:58:02'),
(50, 5, 'TUCUMAN DE GIMNASIA', '2024-04-30 14:58:10', '2024-04-30 14:58:10'),
(51, 5, 'DEPORTIVO RIO CUARTO', '2024-04-30 14:58:18', '2024-04-30 14:58:18');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fin`
--

CREATE TABLE `fin` (
  `id` int(11) NOT NULL,
  `partido1_id` int(11) NOT NULL,
  `play_off_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `fin`
--

INSERT INTO `fin` (`id`, `partido1_id`, `play_off_id`) VALUES
(1, 117, 9),
(2, 118, 10),
(3, 123, 1),
(4, 124, 2),
(5, 125, 5),
(6, 126, 6),
(7, 127, 8),
(8, 128, 7),
(9, 130, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `genero`
--

CREATE TABLE `genero` (
  `id` int(11) NOT NULL,
  `nombre` varchar(32) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `genero`
--

INSERT INTO `genero` (`id`, `nombre`, `created_at`, `updated_at`) VALUES
(1, 'Femenino', '2024-03-20 08:30:32', '2024-03-20 08:30:32'),
(2, 'Masculino', '2024-03-20 08:30:32', '2024-03-20 08:30:32');

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
  `zona_id` int(11) DEFAULT NULL,
  `equipo_local_id` int(11) DEFAULT NULL,
  `equipo_visitante_id` int(11) DEFAULT NULL,
  `local_set1` int(11) DEFAULT NULL,
  `local_set2` int(11) DEFAULT NULL,
  `local_set3` int(11) DEFAULT NULL,
  `local_set4` int(11) DEFAULT NULL,
  `local_set5` int(11) DEFAULT NULL,
  `visitante_set1` int(11) DEFAULT NULL,
  `visitante_set2` int(11) DEFAULT NULL,
  `visitante_set3` int(11) DEFAULT NULL,
  `visitante_set4` int(11) DEFAULT NULL,
  `visitante_set5` int(11) DEFAULT NULL,
  `horario` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `partido`
--

INSERT INTO `partido` (`id`, `cancha_id`, `zona_id`, `equipo_local_id`, `equipo_visitante_id`, `local_set1`, `local_set2`, `local_set3`, `local_set4`, `local_set5`, `visitante_set1`, `visitante_set2`, `visitante_set3`, `visitante_set4`, `visitante_set5`, `horario`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 1, 2, 27, 25, NULL, NULL, NULL, 25, 19, NULL, NULL, NULL, '2024-05-04 15:00:00', '2024-04-29 21:52:16', '2024-05-04 17:37:21'),
(2, 2, 1, 1, 3, 18, 28, 9, NULL, NULL, 25, 26, 15, NULL, NULL, '2024-05-04 12:00:00', '2024-04-29 21:52:16', '2024-05-04 13:39:44'),
(3, 1, 1, 1, 4, 25, 25, NULL, NULL, NULL, 9, 13, NULL, NULL, NULL, '2024-05-03 18:00:00', '2024-04-29 21:52:16', '2024-05-03 23:29:54'),
(4, 2, 1, 2, 3, 17, 25, 15, NULL, NULL, 25, 20, 11, NULL, NULL, '2024-05-03 23:00:00', '2024-04-29 21:52:16', '2024-05-04 00:26:21'),
(5, 1, 1, 2, 4, 25, 25, NULL, NULL, NULL, 12, 13, NULL, NULL, NULL, '2024-05-04 12:00:00', '2024-04-29 21:52:16', '2024-05-04 12:46:45'),
(6, 1, 1, 3, 4, 25, 25, NULL, NULL, NULL, 14, 6, NULL, NULL, NULL, '2024-05-04 16:00:00', '2024-04-29 21:52:16', '2024-05-04 16:58:08'),
(7, 2, 2, 5, 6, 25, 25, NULL, NULL, NULL, 12, 15, NULL, NULL, NULL, '2024-05-04 10:00:00', '2024-04-29 21:52:16', '2024-05-04 10:49:04'),
(8, 2, 2, 5, 7, 25, 25, NULL, NULL, NULL, 21, 13, NULL, NULL, NULL, '2024-05-04 16:00:00', '2024-04-29 21:52:16', '2024-05-04 18:06:33'),
(9, 2, 2, 5, 8, 25, 25, NULL, NULL, NULL, 17, 13, NULL, NULL, NULL, '2024-05-03 18:00:00', '2024-04-29 21:52:16', '2024-05-03 18:55:36'),
(10, 1, 2, 6, 7, 14, 8, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, '2024-05-03 19:00:00', '2024-04-29 21:52:16', '2024-05-03 20:01:54'),
(11, 2, 2, 6, 8, 17, 14, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, '2024-05-04 11:00:00', '2024-04-29 21:52:16', '2024-05-04 12:01:42'),
(12, 1, 2, 7, 8, 25, 22, 15, NULL, NULL, 20, 25, 8, NULL, NULL, '2024-05-04 17:00:00', '2024-04-29 21:52:16', '2024-05-04 19:52:12'),
(13, 6, 3, 9, 10, 20, 19, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, '2024-05-04 14:00:00', '2024-04-29 21:52:16', '2024-05-04 15:52:14'),
(14, 6, 3, 9, 11, 25, 25, NULL, NULL, NULL, 8, 10, NULL, NULL, NULL, '2024-05-04 10:00:00', '2024-04-29 21:52:16', '2024-05-04 12:02:01'),
(15, 2, 3, 9, 12, 25, 21, 15, NULL, NULL, 23, 25, 8, NULL, NULL, '2024-05-03 21:00:00', '2024-04-29 21:52:16', '2024-05-03 22:35:47'),
(16, 2, 3, 10, 11, 25, 25, NULL, NULL, NULL, 4, 16, NULL, NULL, NULL, '2024-05-03 22:00:00', '2024-04-29 21:52:16', '2024-05-04 00:06:15'),
(17, 6, 3, 10, 12, 24, 22, NULL, NULL, NULL, 26, 25, NULL, NULL, NULL, '2024-05-04 11:00:00', '2024-04-29 21:52:16', '2024-05-04 12:02:59'),
(18, 6, 3, 11, 12, 8, 16, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, '2024-05-04 15:00:00', '2024-04-29 21:52:16', '2024-05-04 16:41:42'),
(19, 2, 4, 13, 14, 14, 10, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, '2024-05-04 17:00:00', '2024-04-29 21:52:16', '2024-05-04 19:27:57'),
(20, 1, 4, 13, 15, 6, 7, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, '2024-05-04 11:00:00', '2024-04-29 21:52:16', '2024-05-04 11:59:16'),
(21, 1, 4, 13, 16, 15, 13, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, '2024-05-03 22:00:00', '2024-04-29 21:52:16', '2024-05-03 23:04:02'),
(22, 1, 4, 14, 15, 17, 23, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, '2024-05-04 10:00:00', '2024-04-29 21:52:16', '2024-05-04 11:12:04'),
(23, 1, 4, 14, 16, 25, 25, NULL, NULL, NULL, 19, 22, NULL, NULL, NULL, '2024-05-04 13:00:00', '2024-04-29 21:52:16', '2024-05-04 14:11:12'),
(24, 1, 4, 15, 16, 22, 20, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, '2024-05-04 18:00:00', '2024-04-29 21:52:16', '2024-05-04 18:35:21'),
(25, NULL, 5, 17, 18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-05-04 23:00:00', '2024-04-29 22:34:09', '2024-04-29 22:34:09'),
(26, NULL, 5, 17, 19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-05-04 23:00:00', '2024-04-29 22:34:09', '2024-04-29 22:34:09'),
(27, NULL, 5, 17, 20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-05-04 23:00:00', '2024-04-29 22:34:09', '2024-04-29 22:34:09'),
(28, 4, 5, 18, 19, 25, 25, NULL, NULL, NULL, 14, 20, NULL, NULL, NULL, '2024-05-04 10:00:00', '2024-04-29 22:34:09', '2024-05-04 10:58:42'),
(29, 4, 5, 18, 20, 18, 16, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, '2024-05-04 16:00:00', '2024-04-29 22:34:09', '2024-05-04 20:06:32'),
(30, 3, 5, 19, 20, 17, 14, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, '2024-05-04 14:00:00', '2024-04-29 22:34:09', '2024-05-04 20:07:02'),
(31, 4, 6, 21, 22, 25, 26, NULL, NULL, NULL, 21, 24, NULL, NULL, NULL, '2024-05-04 13:00:00', '2024-04-29 22:34:09', '2024-05-04 15:34:06'),
(32, 3, 6, 21, 23, 19, 23, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, '2024-05-04 10:00:00', '2024-04-29 22:34:09', '2024-05-04 11:08:44'),
(33, 4, 6, 21, 24, 25, 19, 15, NULL, NULL, 11, 25, 5, NULL, NULL, '2024-05-03 18:00:00', '2024-04-29 22:34:09', '2024-05-03 19:32:49'),
(34, 4, 6, 22, 23, 16, 25, 15, NULL, NULL, 25, 13, 11, NULL, NULL, '2024-05-03 19:00:00', '2024-04-29 22:34:09', '2024-05-03 21:05:53'),
(35, 3, 6, 22, 24, 25, 24, 15, NULL, NULL, 16, 26, 13, NULL, NULL, '2024-05-04 11:00:00', '2024-04-29 22:34:09', '2024-05-04 13:01:51'),
(36, 3, 6, 23, 24, 25, 25, NULL, NULL, NULL, 10, 18, NULL, NULL, NULL, '2024-05-04 15:00:00', '2024-04-29 22:34:09', '2024-05-04 17:27:19'),
(37, 6, 7, 25, 26, 13, 26, NULL, NULL, NULL, 25, 28, NULL, NULL, NULL, '2024-05-04 16:00:00', '2024-04-29 22:34:09', '2024-05-04 18:01:42'),
(38, 6, 7, 25, 27, 13, 25, 9, NULL, NULL, 25, 21, 15, NULL, NULL, '2024-05-04 12:00:00', '2024-04-29 22:34:09', '2024-05-04 13:29:31'),
(39, 3, 7, 25, 28, 25, 25, NULL, NULL, NULL, 16, 17, NULL, NULL, NULL, '2024-05-03 21:00:00', '2024-04-29 22:34:09', '2024-05-03 23:04:17'),
(40, 3, 7, 26, 27, 25, 18, NULL, NULL, NULL, 27, 25, NULL, NULL, NULL, '2024-05-03 22:00:00', '2024-04-29 22:34:09', '2024-05-04 00:18:54'),
(41, 6, 7, 26, 28, 25, 25, NULL, NULL, NULL, 21, 19, NULL, NULL, NULL, '2024-05-04 13:00:00', '2024-04-29 22:34:09', '2024-05-04 14:40:36'),
(42, 6, 7, 27, 28, 25, 25, NULL, NULL, NULL, 9, 18, NULL, NULL, NULL, '2024-05-04 17:00:00', '2024-04-29 22:34:09', '2024-05-04 18:54:40'),
(43, 5, 8, 29, 30, 20, 22, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, '2024-05-03 20:00:00', '2024-04-29 22:41:23', '2024-05-03 22:14:24'),
(44, 5, 8, 29, 31, 25, 19, 10, NULL, NULL, 16, 25, 15, NULL, NULL, '2024-05-04 19:00:00', '2024-04-29 22:41:23', '2024-05-04 23:34:59'),
(45, 5, 8, 29, 32, 25, 8, 7, NULL, NULL, 21, 25, 15, NULL, NULL, '2024-05-04 16:00:00', '2024-04-29 22:41:23', '2024-05-04 20:29:38'),
(46, 5, 8, 29, 33, 21, 25, 15, NULL, NULL, 25, 23, 8, NULL, NULL, '2024-05-04 12:00:00', '2024-04-29 22:41:23', '2024-05-04 14:34:46'),
(47, 5, 8, 29, 34, 20, 26, 5, NULL, NULL, 25, 24, 15, NULL, NULL, '2024-05-03 19:00:00', '2024-04-29 22:41:23', '2024-05-03 21:02:59'),
(48, 5, 8, 29, 35, 22, 21, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, '2024-05-04 21:00:00', '2024-04-29 22:41:23', '2024-05-05 00:42:44'),
(49, 5, 8, 30, 31, 25, 25, NULL, NULL, NULL, 19, 17, NULL, NULL, NULL, '2024-05-04 17:00:00', '2024-04-29 22:41:23', '2024-05-04 21:43:16'),
(50, 6, 8, 30, 32, 18, 24, NULL, NULL, NULL, 25, 26, NULL, NULL, NULL, '2024-05-04 19:00:00', '2024-04-29 22:41:23', '2024-05-05 01:44:37'),
(51, 5, 8, 30, 33, 25, 25, NULL, NULL, NULL, 18, 18, NULL, NULL, NULL, '2024-05-04 20:00:00', '2024-04-29 22:41:23', '2024-05-04 23:26:26'),
(52, 5, 8, 30, 34, 22, 25, 12, NULL, NULL, 25, 20, 15, NULL, NULL, '2024-05-04 15:00:00', '2024-04-29 22:41:23', '2024-05-04 19:08:49'),
(53, 5, 8, 30, 35, 23, 25, 15, NULL, NULL, 25, 23, 13, NULL, NULL, '2024-05-03 18:00:00', '2024-04-29 22:41:23', '2024-05-03 19:29:26'),
(54, 5, 8, 31, 32, 25, 21, 13, NULL, NULL, 15, 25, 15, NULL, NULL, '2024-05-03 23:00:00', '2024-04-29 22:41:23', '2024-05-04 02:34:37'),
(55, 5, 8, 31, 33, 25, 10, 13, NULL, NULL, 21, 25, 15, NULL, NULL, '2024-05-04 14:00:00', '2024-04-29 22:41:23', '2024-05-04 17:38:35'),
(56, 6, 8, 31, 34, 18, 23, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, '2024-05-04 21:00:00', '2024-04-29 22:41:23', '2024-05-04 20:29:21'),
(57, 5, 8, 31, 35, 25, 20, 11, NULL, NULL, 16, 25, 15, NULL, NULL, '2024-05-03 22:00:00', '2024-04-29 22:41:23', '2024-05-04 01:09:10'),
(58, 5, 8, 32, 33, 25, 25, NULL, NULL, NULL, 20, 18, NULL, NULL, NULL, '2024-05-04 18:00:00', '2024-04-29 22:41:23', '2024-05-05 00:32:38'),
(59, 6, 8, 32, 34, 25, 20, 9, NULL, NULL, 12, 25, 15, NULL, NULL, '2024-05-04 20:00:00', '2024-04-29 22:41:23', '2024-05-04 22:10:59'),
(60, 5, 8, 32, 35, 25, 22, 15, NULL, NULL, 17, 25, 8, NULL, NULL, '2024-05-04 13:00:00', '2024-04-29 22:41:23', '2024-05-04 16:09:07'),
(61, 5, 8, 33, 34, 25, 25, 15, NULL, NULL, 22, 27, 7, NULL, NULL, '2024-05-03 21:00:00', '2024-04-29 22:41:23', '2024-05-04 01:07:45'),
(62, 5, 8, 33, 35, 21, 25, 9, NULL, NULL, 25, 13, 15, NULL, NULL, '2024-05-04 10:00:00', '2024-04-29 22:41:23', '2024-05-04 11:40:52'),
(63, 5, 8, 34, 35, 25, 25, NULL, NULL, NULL, 17, 22, NULL, NULL, NULL, '2024-05-04 11:00:00', '2024-04-29 22:41:23', '2024-05-04 12:54:55'),
(64, 2, 9, 36, 37, 25, 22, NULL, NULL, NULL, 27, 25, NULL, NULL, NULL, '2024-05-04 18:00:00', '2024-04-30 14:58:48', '2024-05-04 20:39:56'),
(65, 1, 9, 36, 38, 23, 20, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, '2024-05-04 14:00:00', '2024-04-30 14:58:48', '2024-05-04 15:20:15'),
(66, 1, 9, 36, 39, 25, 25, NULL, NULL, NULL, 8, 15, NULL, NULL, NULL, '2024-05-03 21:00:00', '2024-04-30 14:58:48', '2024-05-03 22:20:48'),
(67, 2, 9, 37, 38, 25, 25, NULL, NULL, NULL, 21, 19, NULL, NULL, NULL, '2024-05-03 20:00:00', '2024-04-30 14:58:48', '2024-05-03 20:59:43'),
(68, 2, 9, 37, 39, 25, 25, NULL, NULL, NULL, 15, 15, NULL, NULL, NULL, '2024-05-04 13:00:00', '2024-04-30 14:58:48', '2024-05-04 14:52:16'),
(69, 1, 9, 38, 39, 25, 25, NULL, NULL, NULL, 11, 21, NULL, NULL, NULL, '2024-05-04 19:00:00', '2024-04-30 14:58:48', '2024-05-04 20:48:37'),
(70, 2, 10, 40, 41, 25, 25, NULL, NULL, NULL, 21, 20, NULL, NULL, NULL, '2024-05-04 19:00:00', '2024-04-30 14:58:48', '2024-05-04 21:51:39'),
(71, 1, 10, 40, 42, 25, 25, NULL, NULL, NULL, 14, 17, NULL, NULL, NULL, '2024-05-04 15:00:00', '2024-04-30 14:58:48', '2024-05-04 16:06:27'),
(72, 1, 10, 40, 43, 17, 25, 11, NULL, NULL, 25, 21, 15, NULL, NULL, '2024-05-03 20:00:00', '2024-04-30 14:58:48', '2024-05-03 21:21:09'),
(73, 2, 10, 41, 42, 11, 18, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, '2024-05-03 19:00:00', '2024-04-30 14:58:48', '2024-05-03 19:49:11'),
(74, 2, 10, 41, 43, 25, 19, 11, NULL, NULL, 23, 25, 15, NULL, NULL, '2024-05-04 14:00:00', '2024-04-30 14:58:48', '2024-05-04 16:12:12'),
(75, 1, 10, 42, 43, 27, 22, 15, NULL, NULL, 25, 25, 8, NULL, NULL, '2024-05-04 20:00:00', '2024-04-30 14:58:48', '2024-05-04 22:11:47'),
(76, 4, 11, 44, 45, 25, 25, NULL, NULL, NULL, 14, 19, NULL, NULL, NULL, '2024-05-04 14:00:00', '2024-04-30 14:59:02', '2024-05-04 17:49:53'),
(77, 3, 11, 44, 46, 23, 19, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, '2024-05-04 12:00:00', '2024-04-30 14:59:02', '2024-05-04 14:31:33'),
(78, 3, 11, 44, 47, 19, 23, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, '2024-05-03 19:00:00', '2024-04-30 14:59:02', '2024-05-03 20:46:01'),
(79, 3, 11, 45, 46, 18, 23, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, '2024-05-03 20:00:00', '2024-04-30 14:59:02', '2024-05-03 21:53:39'),
(80, 4, 11, 45, 47, 17, 17, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, '2024-05-04 11:00:00', '2024-04-30 14:59:02', '2024-05-04 12:27:16'),
(81, 3, 11, 46, 47, 16, 21, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, '2024-05-04 16:00:00', '2024-04-30 14:59:02', '2024-05-04 18:33:02'),
(82, 4, 12, 48, 49, 25, 25, NULL, NULL, NULL, 15, 16, NULL, NULL, NULL, '2024-05-04 15:00:00', '2024-04-30 14:59:02', '2024-05-04 18:59:20'),
(83, 3, 12, 48, 50, 25, 22, 12, NULL, NULL, 14, 25, 15, NULL, NULL, '2024-05-04 13:00:00', '2024-04-30 14:59:02', '2024-05-04 15:56:25'),
(84, 4, 12, 48, 51, 25, 25, NULL, NULL, NULL, 13, 17, NULL, NULL, NULL, '2024-05-03 20:00:00', '2024-04-30 14:59:02', '2024-05-03 21:59:13'),
(85, 4, 12, 49, 50, 18, 23, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, '2024-05-03 23:00:12', '2024-04-30 14:59:02', '2024-05-03 23:00:12'),
(86, 4, 12, 49, 51, 23, 25, 10, NULL, NULL, 25, 22, 15, NULL, NULL, '2024-05-04 12:00:00', '2024-04-30 14:59:02', '2024-05-04 13:57:44'),
(87, 3, 12, 50, 51, 25, 25, NULL, NULL, NULL, 15, 13, NULL, NULL, NULL, '2024-05-04 17:00:00', '2024-04-30 14:59:02', '2024-05-04 20:08:07'),
(88, NULL, NULL, 1, 10, 25, 22, 5, NULL, NULL, 19, 25, 15, NULL, NULL, NULL, '2024-05-04 21:52:41', '2024-05-06 18:02:01'),
(89, NULL, NULL, 5, 14, 25, 25, NULL, NULL, NULL, 20, 22, NULL, NULL, NULL, NULL, '2024-05-04 21:52:41', '2024-05-06 18:02:42'),
(90, NULL, NULL, 12, 3, 17, 21, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, NULL, '2024-05-04 21:52:41', '2024-05-06 18:03:21'),
(91, NULL, NULL, 15, 7, 25, 25, NULL, NULL, NULL, 15, 19, NULL, NULL, NULL, NULL, '2024-05-04 21:52:41', '2024-05-06 18:04:56'),
(92, NULL, NULL, 2, 11, 25, 25, NULL, NULL, NULL, 22, 14, NULL, NULL, NULL, NULL, '2024-05-04 21:59:45', '2024-05-06 18:36:32'),
(93, NULL, NULL, 8, 13, NULL, NULL, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, NULL, '2024-05-04 21:59:45', '2024-05-06 18:31:40'),
(94, NULL, NULL, 9, 4, 25, 25, NULL, NULL, NULL, 15, 7, NULL, NULL, NULL, NULL, '2024-05-04 21:59:45', '2024-05-06 18:35:52'),
(95, NULL, NULL, 16, 6, 25, 25, NULL, NULL, NULL, 20, 16, NULL, NULL, NULL, NULL, '2024-05-04 21:59:45', '2024-05-06 18:35:06'),
(96, NULL, NULL, 20, 21, 25, 22, 15, NULL, NULL, 20, 25, 10, NULL, NULL, NULL, '2024-05-04 22:29:35', '2024-05-06 18:08:07'),
(97, NULL, NULL, 20, 26, 25, 25, NULL, NULL, NULL, 14, 17, NULL, NULL, NULL, NULL, '2024-05-04 22:29:35', '2024-05-06 18:08:37'),
(98, NULL, NULL, 21, 26, 25, 25, NULL, NULL, NULL, 17, 16, NULL, NULL, NULL, NULL, '2024-05-04 22:29:35', '2024-05-06 18:09:21'),
(99, NULL, NULL, 23, 27, 25, 18, 15, NULL, NULL, 17, 25, 13, NULL, NULL, NULL, '2024-05-04 22:31:06', '2024-05-06 18:09:47'),
(100, NULL, NULL, 23, 18, 25, 25, NULL, NULL, NULL, 18, 23, NULL, NULL, NULL, NULL, '2024-05-04 22:31:06', '2024-05-06 18:10:18'),
(101, NULL, NULL, 27, 18, 25, 19, 15, NULL, NULL, 15, 25, 10, NULL, NULL, NULL, '2024-05-04 22:31:06', '2024-05-06 18:10:47'),
(102, NULL, NULL, 19, 24, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-05-04 22:38:26', '2024-05-04 22:38:26'),
(103, NULL, NULL, 19, 28, 25, 21, 17, NULL, NULL, 7, 25, 19, NULL, NULL, NULL, '2024-05-04 22:38:26', '2024-05-06 18:30:40'),
(104, NULL, NULL, 24, 28, 25, 27, NULL, NULL, NULL, 18, 25, NULL, NULL, NULL, NULL, '2024-05-04 22:38:26', '2024-05-06 18:28:55'),
(105, NULL, NULL, 22, 25, 25, 25, NULL, NULL, NULL, 22, 23, NULL, NULL, NULL, NULL, '2024-05-04 22:38:52', '2024-05-06 18:48:39'),
(106, NULL, NULL, 22, 17, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-05-04 22:38:52', '2024-05-04 22:38:52'),
(107, NULL, NULL, 25, 17, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-05-04 22:38:52', '2024-05-04 22:38:52'),
(108, NULL, NULL, 37, 43, 25, 25, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-05-04 22:52:26', '2024-05-06 18:13:23'),
(109, NULL, NULL, 40, 38, 25, 22, 11, NULL, NULL, 16, 25, 15, NULL, NULL, NULL, '2024-05-04 22:52:26', '2024-05-06 18:12:42'),
(110, NULL, NULL, 36, 41, NULL, NULL, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, NULL, '2024-05-04 22:52:55', '2024-05-06 18:33:27'),
(111, NULL, NULL, 42, 39, NULL, NULL, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, NULL, '2024-05-04 22:52:55', '2024-05-06 18:50:45'),
(112, NULL, NULL, 47, 48, 27, 19, 11, NULL, NULL, 25, 25, 15, NULL, NULL, NULL, '2024-05-04 22:56:10', '2024-05-06 18:12:00'),
(113, NULL, NULL, 50, 46, 21, 17, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, NULL, '2024-05-04 22:56:10', '2024-05-06 18:54:26'),
(114, NULL, NULL, 44, 49, NULL, NULL, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, NULL, '2024-05-04 22:56:36', '2024-05-06 18:32:27'),
(115, NULL, NULL, 45, 51, 25, 25, NULL, NULL, NULL, 17, 19, NULL, NULL, NULL, NULL, '2024-05-04 22:56:36', '2024-05-06 18:24:57'),
(116, NULL, NULL, 30, 35, 24, 21, NULL, NULL, NULL, 26, 25, NULL, NULL, NULL, NULL, '2024-05-05 01:46:33', '2024-05-06 18:16:22'),
(117, NULL, NULL, 32, 34, 21, 12, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, NULL, '2024-05-05 01:46:47', '2024-05-06 18:17:42'),
(118, NULL, NULL, 33, 31, 25, 22, 11, NULL, NULL, 13, 25, 15, NULL, NULL, NULL, '2024-05-05 01:47:02', '2024-05-06 18:23:25'),
(119, NULL, NULL, 10, 3, 25, 23, 14, NULL, NULL, 21, 25, 16, NULL, NULL, NULL, '2024-05-06 20:25:44', '2024-05-06 20:29:48'),
(120, NULL, NULL, 5, 15, 25, 16, 15, NULL, NULL, 12, 25, 8, NULL, NULL, NULL, '2024-05-06 20:25:44', '2024-05-06 20:29:17'),
(121, NULL, NULL, 13, 16, 12, 8, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, NULL, '2024-05-06 20:27:03', '2024-05-06 20:27:46'),
(122, NULL, NULL, 9, 2, 25, 25, NULL, NULL, NULL, 14, 22, NULL, NULL, NULL, NULL, '2024-05-06 20:27:03', '2024-05-06 20:28:41'),
(123, NULL, NULL, 5, 3, 25, 25, NULL, NULL, NULL, 18, 20, NULL, NULL, NULL, NULL, '2024-05-06 20:31:04', '2024-05-06 20:32:09'),
(124, NULL, NULL, 16, 9, 25, 25, NULL, NULL, NULL, 22, 15, NULL, NULL, NULL, NULL, '2024-05-06 20:31:22', '2024-05-06 20:31:48'),
(125, NULL, NULL, 37, 38, 23, 16, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, NULL, '2024-05-06 20:36:50', '2024-05-06 20:48:21'),
(126, NULL, NULL, 41, 39, 35, 33, 17, NULL, NULL, 33, 35, 15, NULL, NULL, NULL, '2024-05-06 20:37:11', '2024-05-06 20:47:48'),
(127, NULL, NULL, 49, 45, 25, 25, NULL, NULL, NULL, 17, 23, NULL, NULL, NULL, NULL, '2024-05-06 20:37:57', '2024-05-06 20:46:38'),
(128, NULL, NULL, 48, 46, 25, 23, 15, NULL, NULL, 18, 25, 12, NULL, NULL, NULL, '2024-05-06 20:38:19', '2024-05-06 20:46:05'),
(129, NULL, NULL, 21, 27, 26, 25, NULL, NULL, NULL, 24, 20, NULL, NULL, NULL, NULL, '2024-05-06 20:42:05', '2024-05-06 20:43:40'),
(130, NULL, NULL, 20, 23, 15, 25, 12, NULL, NULL, 25, 23, 15, NULL, NULL, NULL, '2024-05-06 20:42:24', '2024-05-06 20:44:15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `play_off`
--

CREATE TABLE `play_off` (
  `id` int(11) NOT NULL,
  `torneo_genero_categoria_id` int(11) NOT NULL,
  `oro` tinyint(1) NOT NULL,
  `plata` tinyint(1) NOT NULL,
  `bronce` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `play_off`
--

INSERT INTO `play_off` (`id`, `torneo_genero_categoria_id`, `oro`, `plata`, `bronce`) VALUES
(1, 1, 1, 0, 0),
(2, 1, 0, 1, 0),
(3, 2, 1, 0, 0),
(4, 2, 0, 1, 0),
(5, 4, 1, 0, 0),
(6, 4, 0, 1, 0),
(7, 5, 1, 0, 0),
(8, 5, 0, 1, 0),
(9, 3, 1, 0, 0),
(10, 3, 0, 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `semis`
--

CREATE TABLE `semis` (
  `id` int(11) NOT NULL,
  `partido1_id` int(11) NOT NULL,
  `partido2_id` int(11) NOT NULL,
  `play_off_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `semis`
--

INSERT INTO `semis` (`id`, `partido1_id`, `partido2_id`, `play_off_id`) VALUES
(1, 108, 109, 5),
(2, 110, 111, 6),
(3, 112, 113, 7),
(4, 114, 115, 8),
(5, 119, 120, 1),
(6, 121, 122, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tercero_cuarto`
--

CREATE TABLE `tercero_cuarto` (
  `id` int(11) NOT NULL,
  `partido1_id` int(11) NOT NULL,
  `play_off_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tercero_cuarto`
--

INSERT INTO `tercero_cuarto` (`id`, `partido1_id`, `play_off_id`) VALUES
(1, 116, 9),
(2, 129, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `torneo`
--

CREATE TABLE `torneo` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `fecha_inicio` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `fecha_fin` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `fecha_inicio_inscripcion` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `fecha_fin_inscripcion` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `torneo`
--

INSERT INTO `torneo` (`id`, `nombre`, `descripcion`, `fecha_inicio`, `fecha_fin`, `fecha_inicio_inscripcion`, `fecha_fin_inscripcion`, `created_at`, `updated_at`) VALUES
(1, 'XIV Torneo Sudamericano de Master Voley', '', '2024-05-03 00:00:00', '2024-05-05 22:00:00', '2024-04-01 00:00:00', '2024-04-30 23:59:00', '2024-04-26 11:24:05', '2024-04-26 11:24:05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `torneo_genero_categoria`
--

CREATE TABLE `torneo_genero_categoria` (
  `id` int(11) NOT NULL,
  `torneo_id` int(11) DEFAULT NULL,
  `genero_id` int(11) DEFAULT NULL,
  `categoria_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `cerrado` tinyint(1) NOT NULL,
  `creado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `torneo_genero_categoria`
--

INSERT INTO `torneo_genero_categoria` (`id`, `torneo_id`, `genero_id`, `categoria_id`, `created_at`, `updated_at`, `cerrado`, `creado`) VALUES
(1, 1, 1, 2, '2024-04-26 15:58:25', '2024-04-29 21:52:16', 1, 1),
(2, 1, 1, 6, '2024-04-26 20:58:15', '2024-04-29 22:34:09', 1, 1),
(3, 1, 1, 5, '2024-04-26 21:02:20', '2024-04-29 22:41:23', 1, 1),
(4, 1, 2, 3, '2024-04-26 21:09:53', '2024-04-30 14:58:48', 1, 1),
(5, 1, 2, 4, '2024-04-26 21:10:02', '2024-04-30 14:59:02', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `triangular`
--

CREATE TABLE `triangular` (
  `id` int(11) NOT NULL,
  `partido1_id` int(11) NOT NULL,
  `partido2_id` int(11) NOT NULL,
  `partido3_id` int(11) NOT NULL,
  `play_off_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `triangular`
--

INSERT INTO `triangular` (`id`, `partido1_id`, `partido2_id`, `partido3_id`, `play_off_id`) VALUES
(1, 96, 97, 98, 3),
(2, 99, 100, 101, 3),
(3, 102, 103, 104, 4),
(4, 105, 106, 107, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(180) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '(DC2Type:json)' CHECK (json_valid(`roles`)),
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`id`, `username`, `roles`, `password`) VALUES
(1, 'admin', '[\"ROLE_ADMIN\"]', '$2y$13$AeQJ8kK8kp158tGc6R9/3.C8teHszJ4FsaxgsNhpOTVJ6oCipsd.e'),
(2, 'planillero', '[]', '$2y$13$AeQJ8kK8kp158tGc6R9/3.C8teHszJ4FsaxgsNhpOTVJ6oCipsd.e');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `zona`
--

CREATE TABLE `zona` (
  `id` int(11) NOT NULL,
  `torneo_genero_categoria_id` int(11) DEFAULT NULL,
  `clasifican_oro` smallint(6) DEFAULT NULL,
  `clasifican_plata` smallint(6) DEFAULT NULL,
  `clasifican_bronce` smallint(6) DEFAULT NULL,
  `nombre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `zona`
--

INSERT INTO `zona` (`id`, `torneo_genero_categoria_id`, `clasifican_oro`, `clasifican_plata`, `clasifican_bronce`, `nombre`) VALUES
(1, 1, 0, 0, 0, 'A'),
(2, 1, 0, 0, 0, 'B'),
(3, 1, 0, 0, 0, 'C'),
(4, 1, 0, 0, 0, 'D'),
(5, 2, 0, 0, 0, 'E'),
(6, 2, 0, 0, 0, 'F'),
(7, 2, 0, 0, 0, 'G'),
(8, 3, 0, 0, 0, 'UNICA'),
(9, 4, 0, 0, 0, '1'),
(10, 4, 0, 0, 0, '2'),
(11, 5, 0, 0, 0, '3'),
(12, 5, 0, 0, 0, '4');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `zona_equipo`
--

CREATE TABLE `zona_equipo` (
  `id` int(11) NOT NULL,
  `zona_id` int(11) DEFAULT NULL,
  `equipo_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `zona_equipo`
--

INSERT INTO `zona_equipo` (`id`, `zona_id`, `equipo_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 2, 5),
(6, 2, 6),
(7, 2, 7),
(8, 2, 8),
(9, 3, 9),
(10, 3, 10),
(11, 3, 11),
(12, 3, 12),
(13, 4, 13),
(14, 4, 14),
(15, 4, 15),
(16, 4, 16),
(17, 5, 17),
(18, 5, 18),
(19, 5, 19),
(20, 5, 20),
(21, 6, 21),
(22, 6, 22),
(23, 6, 23),
(24, 6, 24),
(25, 7, 25),
(26, 7, 26),
(27, 7, 27),
(28, 7, 28),
(29, 8, 29),
(30, 8, 30),
(31, 8, 31),
(32, 8, 32),
(33, 8, 33),
(34, 8, 34),
(35, 8, 35),
(36, 9, 36),
(37, 9, 37),
(38, 9, 38),
(39, 9, 39),
(40, 10, 40),
(41, 10, 41),
(42, 10, 42),
(43, 10, 43),
(44, 11, 44),
(45, 11, 45),
(46, 11, 46),
(47, 11, 47),
(48, 12, 48),
(49, 12, 49),
(50, 12, 50),
(51, 12, 51);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cancha`
--
ALTER TABLE `cancha`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_9D09C78261190A32` (`club_id`);

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_4E10122D3A909126` (`nombre`);

--
-- Indices de la tabla `club`
--
ALTER TABLE `club`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cuartos`
--
ALTER TABLE `cuartos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_6128B4FC1A71DF68` (`partido1_id`),
  ADD UNIQUE KEY `UNIQ_6128B4FC8C47086` (`partido2_id`),
  ADD UNIQUE KEY `UNIQ_6128B4FCB07817E3` (`partido3_id`),
  ADD UNIQUE KEY `UNIQ_6128B4FC2DAF2F5A` (`partido4_id`),
  ADD UNIQUE KEY `UNIQ_6128B4FC1B790A7C` (`play_off_id`);

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
  ADD KEY `IDX_C49C530BD7CF149E` (`torneo_genero_categoria_id`);

--
-- Indices de la tabla `fin`
--
ALTER TABLE `fin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_AD2EF2311A71DF68` (`partido1_id`),
  ADD UNIQUE KEY `UNIQ_AD2EF2311B790A7C` (`play_off_id`);

--
-- Indices de la tabla `genero`
--
ALTER TABLE `genero`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_A000883A3A909126` (`nombre`);

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
  ADD KEY `IDX_4E79750B104EA8FC` (`zona_id`),
  ADD KEY `IDX_4E79750B88774E73` (`equipo_local_id`),
  ADD KEY `IDX_4E79750B8C243011` (`equipo_visitante_id`);

--
-- Indices de la tabla `play_off`
--
ALTER TABLE `play_off`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_F59DAD8CD7CF149E` (`torneo_genero_categoria_id`);

--
-- Indices de la tabla `semis`
--
ALTER TABLE `semis`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_4994C2281A71DF68` (`partido1_id`),
  ADD UNIQUE KEY `UNIQ_4994C2288C47086` (`partido2_id`),
  ADD UNIQUE KEY `UNIQ_4994C2281B790A7C` (`play_off_id`);

--
-- Indices de la tabla `tercero_cuarto`
--
ALTER TABLE `tercero_cuarto`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_F8CD32801A71DF68` (`partido1_id`),
  ADD UNIQUE KEY `UNIQ_F8CD32801B790A7C` (`play_off_id`);

--
-- Indices de la tabla `torneo`
--
ALTER TABLE `torneo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `torneo_genero_categoria`
--
ALTER TABLE `torneo_genero_categoria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D2F850FAA0139802` (`torneo_id`),
  ADD KEY `IDX_D2F850FABCE7B795` (`genero_id`),
  ADD KEY `IDX_D2F850FA3397707A` (`categoria_id`);

--
-- Indices de la tabla `triangular`
--
ALTER TABLE `triangular`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_E635B76D1A71DF68` (`partido1_id`),
  ADD UNIQUE KEY `UNIQ_E635B76D8C47086` (`partido2_id`),
  ADD UNIQUE KEY `UNIQ_E635B76DB07817E3` (`partido3_id`),
  ADD KEY `IDX_E635B76D1B790A7C` (`play_off_id`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649F85E0677` (`username`);

--
-- Indices de la tabla `zona`
--
ALTER TABLE `zona`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_A786041ED7CF149E` (`torneo_genero_categoria_id`);

--
-- Indices de la tabla `zona_equipo`
--
ALTER TABLE `zona_equipo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_7FC5758323BFBED` (`equipo_id`),
  ADD KEY `IDX_7FC57583104EA8FC` (`zona_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cancha`
--
ALTER TABLE `cancha`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `club`
--
ALTER TABLE `club`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `cuartos`
--
ALTER TABLE `cuartos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `equipo`
--
ALTER TABLE `equipo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT de la tabla `fin`
--
ALTER TABLE `fin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `genero`
--
ALTER TABLE `genero`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `partido`
--
ALTER TABLE `partido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=131;

--
-- AUTO_INCREMENT de la tabla `play_off`
--
ALTER TABLE `play_off`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `semis`
--
ALTER TABLE `semis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `tercero_cuarto`
--
ALTER TABLE `tercero_cuarto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `torneo`
--
ALTER TABLE `torneo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `torneo_genero_categoria`
--
ALTER TABLE `torneo_genero_categoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `triangular`
--
ALTER TABLE `triangular`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `zona`
--
ALTER TABLE `zona`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `zona_equipo`
--
ALTER TABLE `zona_equipo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cancha`
--
ALTER TABLE `cancha`
  ADD CONSTRAINT `FK_9D09C78261190A32` FOREIGN KEY (`club_id`) REFERENCES `club` (`id`);

--
-- Filtros para la tabla `cuartos`
--
ALTER TABLE `cuartos`
  ADD CONSTRAINT `FK_6128B4FC1A71DF68` FOREIGN KEY (`partido1_id`) REFERENCES `partido` (`id`),
  ADD CONSTRAINT `FK_6128B4FC1B790A7C` FOREIGN KEY (`play_off_id`) REFERENCES `play_off` (`id`),
  ADD CONSTRAINT `FK_6128B4FC2DAF2F5A` FOREIGN KEY (`partido4_id`) REFERENCES `partido` (`id`),
  ADD CONSTRAINT `FK_6128B4FC8C47086` FOREIGN KEY (`partido2_id`) REFERENCES `partido` (`id`),
  ADD CONSTRAINT `FK_6128B4FCB07817E3` FOREIGN KEY (`partido3_id`) REFERENCES `partido` (`id`);

--
-- Filtros para la tabla `equipo`
--
ALTER TABLE `equipo`
  ADD CONSTRAINT `FK_C49C530BD7CF149E` FOREIGN KEY (`torneo_genero_categoria_id`) REFERENCES `torneo_genero_categoria` (`id`);

--
-- Filtros para la tabla `fin`
--
ALTER TABLE `fin`
  ADD CONSTRAINT `FK_AD2EF2311A71DF68` FOREIGN KEY (`partido1_id`) REFERENCES `partido` (`id`),
  ADD CONSTRAINT `FK_AD2EF2311B790A7C` FOREIGN KEY (`play_off_id`) REFERENCES `play_off` (`id`);

--
-- Filtros para la tabla `partido`
--
ALTER TABLE `partido`
  ADD CONSTRAINT `FK_4E79750B104EA8FC` FOREIGN KEY (`zona_id`) REFERENCES `zona` (`id`),
  ADD CONSTRAINT `FK_4E79750B7997F36E` FOREIGN KEY (`cancha_id`) REFERENCES `cancha` (`id`),
  ADD CONSTRAINT `FK_4E79750B88774E73` FOREIGN KEY (`equipo_local_id`) REFERENCES `equipo` (`id`),
  ADD CONSTRAINT `FK_4E79750B8C243011` FOREIGN KEY (`equipo_visitante_id`) REFERENCES `equipo` (`id`);

--
-- Filtros para la tabla `play_off`
--
ALTER TABLE `play_off`
  ADD CONSTRAINT `FK_F59DAD8CD7CF149E` FOREIGN KEY (`torneo_genero_categoria_id`) REFERENCES `torneo_genero_categoria` (`id`);

--
-- Filtros para la tabla `semis`
--
ALTER TABLE `semis`
  ADD CONSTRAINT `FK_4994C2281A71DF68` FOREIGN KEY (`partido1_id`) REFERENCES `partido` (`id`),
  ADD CONSTRAINT `FK_4994C2281B790A7C` FOREIGN KEY (`play_off_id`) REFERENCES `play_off` (`id`),
  ADD CONSTRAINT `FK_4994C2288C47086` FOREIGN KEY (`partido2_id`) REFERENCES `partido` (`id`);

--
-- Filtros para la tabla `tercero_cuarto`
--
ALTER TABLE `tercero_cuarto`
  ADD CONSTRAINT `FK_F8CD32801A71DF68` FOREIGN KEY (`partido1_id`) REFERENCES `partido` (`id`),
  ADD CONSTRAINT `FK_F8CD32801B790A7C` FOREIGN KEY (`play_off_id`) REFERENCES `play_off` (`id`);

--
-- Filtros para la tabla `torneo_genero_categoria`
--
ALTER TABLE `torneo_genero_categoria`
  ADD CONSTRAINT `FK_D2F850FA3397707A` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id`),
  ADD CONSTRAINT `FK_D2F850FAA0139802` FOREIGN KEY (`torneo_id`) REFERENCES `torneo` (`id`),
  ADD CONSTRAINT `FK_D2F850FABCE7B795` FOREIGN KEY (`genero_id`) REFERENCES `genero` (`id`);

--
-- Filtros para la tabla `triangular`
--
ALTER TABLE `triangular`
  ADD CONSTRAINT `FK_E635B76D1A71DF68` FOREIGN KEY (`partido1_id`) REFERENCES `partido` (`id`),
  ADD CONSTRAINT `FK_E635B76D1B790A7C` FOREIGN KEY (`play_off_id`) REFERENCES `play_off` (`id`),
  ADD CONSTRAINT `FK_E635B76D8C47086` FOREIGN KEY (`partido2_id`) REFERENCES `partido` (`id`),
  ADD CONSTRAINT `FK_E635B76DB07817E3` FOREIGN KEY (`partido3_id`) REFERENCES `partido` (`id`);

--
-- Filtros para la tabla `zona`
--
ALTER TABLE `zona`
  ADD CONSTRAINT `FK_A786041ED7CF149E` FOREIGN KEY (`torneo_genero_categoria_id`) REFERENCES `torneo_genero_categoria` (`id`);

--
-- Filtros para la tabla `zona_equipo`
--
ALTER TABLE `zona_equipo`
  ADD CONSTRAINT `FK_7FC57583104EA8FC` FOREIGN KEY (`zona_id`) REFERENCES `zona` (`id`),
  ADD CONSTRAINT `FK_7FC5758323BFBED` FOREIGN KEY (`equipo_id`) REFERENCES `equipo` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
