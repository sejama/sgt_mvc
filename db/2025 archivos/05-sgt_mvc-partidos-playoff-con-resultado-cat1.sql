-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: server-mysql
-- Tiempo de generación: 21-04-2025 a las 17:48:57
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
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cancha`
--

INSERT INTO `cancha` (`id`, `sede_id`, `nombre`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, 1, 'Cancha 1 - Arriba', 'Cancha 1 - Arriba', '2025-03-06 19:24:01', '2025-03-06 19:24:01'),
(2, 1, 'Cancha 2 - Arriba', 'Cancha 2 - Arriba', '2025-03-06 19:24:01', '2025-03-06 19:24:01'),
(3, 1, 'Cancha 3 - Abajo', 'Cancha 3 - Abajo', '2025-03-06 19:24:01', '2025-03-06 19:24:01'),
(4, 1, 'Cancha 4 - Abajo', 'Cancha 4 - Abajo', '2025-03-06 19:24:01', '2025-03-06 19:24:01'),
(5, 2, 'Cancha 1', 'Cancha 1', '2025-03-06 19:24:01', '2025-03-06 19:24:01'),
(6, 2, 'Cancha 2', 'Cancha 2', '2025-03-06 19:24:01', '2025-03-06 19:24:01');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id` int NOT NULL,
  `torneo_id` int DEFAULT NULL,
  `nombre` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `genero` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `disputa` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `estado` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `nombre_corto` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id`, `torneo_id`, `nombre`, `genero`, `disputa`, `estado`, `created_at`, `updated_at`, `nombre_corto`) VALUES
(1, 1, 'Femenino +35', 'Femenino', NULL, 'Zonas_cerradas', '2025-03-06 19:24:01', '2025-04-01 11:50:02', 'F35'),
(2, 1, 'Femenino +40', 'Femenino', NULL, 'Borrador', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'F40'),
(3, 1, 'Femenino +45', 'Femenino', NULL, 'Zonas_cerradas', '2025-03-06 19:24:01', '2025-04-01 15:01:28', 'F45'),
(4, 1, 'Masculino +42', 'Masculino', NULL, 'Zonas_cerradas', '2025-03-06 19:24:01', '2025-04-04 09:58:39', 'M42'),
(5, 1, 'Masculino +50', 'Masculino', NULL, 'Zonas_cerradas', '2025-03-06 19:24:01', '2025-04-04 09:58:52', 'M50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20250306221853', '2025-03-06 22:18:59', 1715);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipo`
--

CREATE TABLE `equipo` (
  `id` int NOT NULL,
  `categoria_id` int NOT NULL,
  `grupo_id` int DEFAULT NULL,
  `nombre` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre_corto` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pais` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provincia` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `localidad` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `estado` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `numero` smallint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `equipo`
--

INSERT INTO `equipo` (`id`, `categoria_id`, `grupo_id`, `nombre`, `nombre_corto`, `pais`, `provincia`, `localidad`, `created_at`, `updated_at`, `estado`, `numero`) VALUES
(1, 1, 1, 'VILLA DORA', 'VD', 'Argentina', 'Santa Fe', 'Rosario', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Activo', 1),
(2, 1, 1, 'CORRIENTES VOLEY', 'CV', 'Argentina', 'Corrientes', 'Corrientes', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Activo', 2),
(3, 1, 1, 'TREDE BIRRA', 'TB', 'Argentina', 'Santa Fe', 'Rosario', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Activo', 3),
(4, 1, 1, 'ALUMNI CASILDA', 'AC', 'Argentina', 'Santa Fe', 'Casilda', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Activo', 4),
(5, 1, 2, 'EL QUILLA', 'EQ', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Activo', 5),
(6, 1, 2, 'MONSTARS', 'MON', 'Argentina', 'Santa Fe', 'Rosario', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Activo', 6),
(7, 1, 2, 'CLUB JUNIN', 'CJ', 'Argentina', 'Buenos Aires', 'Junin', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Activo', 7),
(8, 1, 2, 'REGATAS ROSARIO', 'RR', 'Argentina', 'Santa Fe', 'Rosario', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Activo', 8),
(9, 1, 3, 'ALIANZA SANTO TOME', 'AST', 'Argentina', 'Santa Fe', 'Santo Tome', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Activo', 9),
(10, 1, 3, 'NAUTICO AVELLANEDA', 'NA', 'Argentina', 'Santa Fe', 'Avellaneda', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Activo', 10),
(11, 1, 3, 'MALUCA', 'MAL', 'Argentina', 'Santa Fe', 'Rosario', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Activo', 11),
(12, 1, 3, 'LA GRULLAS', 'LG', 'Argentina', 'Santa Fe', 'Rosario', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Activo', 12),
(13, 1, 4, 'INFINITO', 'INF', 'Argentina', 'Santa Fe', 'Rosario', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Activo', 13),
(14, 1, 4, 'LA EMILIA', 'LE', 'Argentina', 'Santa Fe', 'Rosario', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Activo', 14),
(15, 1, 4, 'SANTO TOME', 'ST', 'Argentina', 'Santa Fe', 'Santo Tome', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Activo', 15),
(16, 1, 4, 'LAS CUERVAS', 'LC', 'Argentina', 'Santa Fe', 'Rosario', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Activo', 16),
(17, 2, 5, 'TREDE BIRRA', 'TB', 'Argentina', 'Santa Fe', 'Rosario', '2025-03-06 19:24:01', '2025-03-14 10:13:41', 'No_participa', 17),
(18, 2, 5, 'VAMOS EL APOYO', 'VEA', 'Argentina', 'Santa Fe', 'Rosario', '2025-03-06 19:24:01', '2025-03-14 08:45:00', 'Activo', 18),
(19, 2, 5, 'COSTA CANELONES', 'CC', 'Uruguay', 'Canelones', 'Canelones', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Activo', 19),
(20, 2, 5, 'INTRUSAS', 'INT', 'Argentina', 'Santa Fe', 'Rosario', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Activo', 20),
(21, 2, 6, 'DOS HACHES', 'DH', 'Argentina', 'Santa Fe', 'Rosario', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Activo', 21),
(22, 2, 6, 'SOMOS LA 18', 'S18', 'Argentina', 'Santa Fe', 'Rosario', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Activo', 22),
(23, 2, 6, 'CLUB FISHERTON', 'CF', 'Argentina', 'Santa Fe', 'Rosario', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Activo', 23),
(24, 2, 6, 'COSTA MIX', 'CM', 'Argentina', 'Santa Fe', 'Rosario', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Activo', 24),
(25, 2, 7, 'PASO REY', 'PR', 'Argentina', 'Santa Fe', 'Rosario', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Activo', 25),
(26, 2, 7, 'DESTINO VOLEY', 'DV', 'Argentina', 'Santa Fe', 'Rosario', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Activo', 26),
(27, 2, 7, 'CITADAS', 'CIT', 'Argentina', 'Santa Fe', 'Rosario', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Activo', 27),
(28, 2, 7, 'MALUCA', 'MAL', 'Argentina', 'Santa Fe', 'Rosario', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Activo', 28),
(29, 3, 8, 'EL REJUNTE', 'S18', 'Argentina', 'Santa Fe', 'Rosario', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Activo', 29),
(30, 3, 8, 'CLUB ROSARIO', 'CR', 'Argentina', 'Santa Fe', 'Rosario', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Activo', 30),
(31, 3, 8, 'GYE CONCEP URUGUAY', 'GCU', 'Argentina', 'Entre Rios', 'Concepcion del Uruguay', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Activo', 31),
(32, 3, 8, 'MONSTARS', 'MON', 'Argentina', 'Santa Fe', 'Rosario', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Activo', 32),
(33, 3, 8, 'VOLEY MONTE', 'VM', 'Argentina', 'Santa Fe', 'San Lorenzo', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Activo', 33),
(34, 3, 8, 'BANCO SANTA FE', 'BSF', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Activo', 34),
(35, 3, 8, 'UNI', 'UNI', 'Argentina', 'Santa Fe', 'Rosario', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Activo', 35),
(36, 4, 9, 'MAXI SANTA FE A', 'MSFA', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Activo', 36),
(37, 4, 9, 'PERO', 'PERO', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Activo', 37),
(38, 4, 9, 'RECREATIVO VERA', 'REVE', 'Argentina', 'Santa Fe', 'Vera', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Activo', 38),
(39, 4, 9, 'BOSQUE URUGUAY', 'BOSQUE', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Activo', 39),
(40, 4, 10, 'ROSARIO VOLEY', 'ROVA', 'Argentina', 'Santa Fe', 'Rosario', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Activo', 40),
(41, 4, 10, 'LA TRIBU', 'LATR', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Activo', 41),
(42, 4, 10, 'MAXI SANTA FE B', 'MSFB', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Activo', 42),
(43, 4, 10, 'BANCO PROVINCIA', 'BAPR', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Activo', 43),
(44, 5, 11, 'MAXI SANTA FE', 'MSF', 'Argentina', 'Santa Fe', 'Santa Fe', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Activo', 44),
(45, 5, 11, 'CORCHA VOLEY', 'CORCHA', 'Argentina', 'Santa Fe', 'Rosario', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Activo', 45),
(46, 5, 11, 'LOS PERKINS', 'LP', 'Argentina', 'Santa Fe', 'Rosario', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Activo', 46),
(47, 5, 11, 'ABANDONADOS', 'ABA', 'Argentina', 'Santa Fe', 'Rosario', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Activo', 47),
(48, 5, 12, 'ROSARIO VOLEY', 'ROVA', 'Argentina', 'Santa Fe', 'Rosario', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Activo', 48),
(49, 5, 12, 'DEFENSORES MORENO', 'DEFF', 'Argentina', 'Buenos Aires', 'Moreno', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Activo', 49),
(50, 5, 12, 'TUCUMAN DE GIMNASIA', 'TUGI', 'Argentina', 'Tucuman', 'San Miguel de Tucuman', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Activo', 50),
(51, 5, 12, 'DEPORTE RIO  IV', 'DRI4', 'Argentina', 'Cordoba', 'Rio Cuarto', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Activo', 51);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupo`
--

CREATE TABLE `grupo` (
  `id` int NOT NULL,
  `categoria_id` int NOT NULL,
  `nombre` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `clasifica_oro` int NOT NULL,
  `clasifica_plata` int DEFAULT NULL,
  `clasifica_bronce` int DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `estado` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `grupo`
--

INSERT INTO `grupo` (`id`, `categoria_id`, `nombre`, `clasifica_oro`, `clasifica_plata`, `clasifica_bronce`, `created_at`, `updated_at`, `estado`) VALUES
(1, 1, 'A', 2, 2, NULL, '2025-03-06 19:24:01', '2025-04-01 11:45:25', 'Finalizado'),
(2, 1, 'B', 2, 2, NULL, '2025-03-06 19:24:01', '2025-04-01 11:45:25', 'Finalizado'),
(3, 1, 'C', 2, 2, NULL, '2025-03-06 19:24:01', '2025-04-01 11:45:25', 'Finalizado'),
(4, 1, 'D', 2, 2, NULL, '2025-03-06 19:24:01', '2025-04-01 11:45:25', 'Finalizado'),
(5, 2, 'E', 2, 2, NULL, '2025-03-06 19:24:01', '2025-04-01 14:59:59', 'Finalizado'),
(6, 2, 'F', 2, 2, NULL, '2025-03-06 19:24:01', '2025-04-01 14:59:59', 'Finalizado'),
(7, 2, 'G', 2, 2, NULL, '2025-03-06 19:24:01', '2025-04-01 14:59:59', 'Finalizado'),
(8, 3, 'Unica', 4, 2, NULL, '2025-03-06 19:24:01', '2025-04-01 15:01:25', 'Finalizado'),
(9, 4, '1', 2, 2, NULL, '2025-03-06 19:24:01', '2025-04-01 15:01:34', 'Finalizado'),
(10, 4, '2', 2, 2, NULL, '2025-03-06 19:24:01', '2025-04-01 15:01:34', 'Finalizado'),
(11, 5, '3', 2, 2, NULL, '2025-03-06 19:24:01', '2025-04-04 09:58:49', 'Finalizado'),
(12, 5, '4', 2, 2, NULL, '2025-03-06 19:24:01', '2025-04-04 09:58:49', 'Finalizado'),
(13, 2, 'Triangular 1 Oro', 2, NULL, NULL, '2025-03-09 16:10:12', '2025-03-09 16:10:12', 'Borrador'),
(14, 2, 'Triangular 2 Oro', 2, NULL, NULL, '2025-03-09 16:10:12', '2025-03-09 16:10:12', 'Borrador'),
(15, 2, 'Triangular 1 Plata', 0, 2, NULL, '2025-03-09 16:10:12', '2025-03-09 16:10:12', 'Borrador'),
(16, 2, 'Triangular 2 Plata', 0, 2, NULL, '2025-03-09 16:10:12', '2025-03-09 16:10:12', 'Borrador'),
(17, 2, 'Triangular 1 Oro', 2, NULL, NULL, '2025-03-10 16:14:32', '2025-04-01 14:59:59', 'Finalizado'),
(18, 2, 'Triangular 2 Oro', 2, NULL, NULL, '2025-03-10 16:14:32', '2025-04-01 14:59:59', 'Finalizado'),
(19, 2, 'Triangular 1 Plata', 0, 2, NULL, '2025-03-10 16:14:32', '2025-04-01 14:59:59', 'Finalizado'),
(20, 2, 'Triangular 2 Plata', 0, 2, NULL, '2025-03-10 16:14:32', '2025-04-01 14:59:59', 'Finalizado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jugador`
--

CREATE TABLE `jugador` (
  `id` int NOT NULL,
  `equipo_id` int DEFAULT NULL,
  `nombre` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_documento` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `numero_documento` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nacimiento` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `responsable` tinyint(1) NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `celular` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `tipo` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `jugador`
--

INSERT INTO `jugador` (`id`, `equipo_id`, `nombre`, `apellido`, `tipo_documento`, `numero_documento`, `nacimiento`, `responsable`, `email`, `celular`, `created_at`, `updated_at`, `tipo`) VALUES
(1, 1, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Entrenador'),
(2, 2, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Entrenador'),
(3, 3, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Entrenador'),
(4, 4, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Entrenador'),
(5, 5, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Entrenador'),
(6, 6, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Entrenador'),
(7, 7, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Entrenador'),
(8, 8, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Entrenador'),
(9, 9, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Entrenador'),
(10, 10, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Entrenador'),
(11, 11, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Entrenador'),
(12, 12, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Entrenador'),
(13, 13, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Entrenador'),
(14, 14, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Entrenador'),
(15, 15, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Entrenador'),
(16, 16, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Entrenador'),
(17, 17, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Entrenador'),
(18, 18, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Entrenador'),
(19, 19, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Entrenador'),
(20, 20, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Entrenador'),
(21, 21, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Entrenador'),
(22, 22, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Entrenador'),
(23, 23, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Entrenador'),
(24, 24, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Entrenador'),
(25, 25, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Entrenador'),
(26, 26, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Entrenador'),
(27, 27, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Entrenador'),
(28, 28, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Entrenador'),
(29, 29, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Entrenador'),
(30, 30, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Entrenador'),
(31, 31, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Entrenador'),
(32, 32, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Entrenador'),
(33, 33, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Entrenador'),
(34, 34, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Entrenador'),
(35, 35, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Entrenador'),
(36, 36, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Entrenador'),
(37, 37, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Entrenador'),
(38, 38, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Entrenador'),
(39, 39, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Entrenador'),
(40, 40, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Entrenador'),
(41, 41, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Entrenador'),
(42, 42, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Entrenador'),
(43, 43, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Entrenador'),
(44, 44, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Entrenador'),
(45, 45, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Entrenador'),
(46, 46, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Entrenador'),
(47, 47, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Entrenador'),
(48, 48, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Entrenador'),
(49, 49, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Entrenador'),
(50, 50, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Entrenador'),
(51, 51, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2025-03-06 19:24:01', '2025-03-06 19:24:01', 'Entrenador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint NOT NULL,
  `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `estado` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `numero` smallint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `partido`
--

INSERT INTO `partido` (`id`, `cancha_id`, `categoria_id`, `grupo_id`, `equipo_local_id`, `equipo_visitante_id`, `horario`, `local_set1`, `local_set2`, `local_set3`, `local_set4`, `local_set5`, `visitante_set1`, `visitante_set2`, `visitante_set3`, `visitante_set4`, `visitante_set5`, `estado`, `tipo`, `created_at`, `updated_at`, `numero`) VALUES
(1, 2, 1, 1, 1, 2, '2024-05-04 15:00:00', 27, 25, NULL, NULL, NULL, 25, 19, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:14:01', '2025-03-16 23:28:59', 1),
(2, 2, 1, 1, 1, 3, '2024-05-04 12:00:00', 18, 28, 9, NULL, NULL, 25, 26, 15, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:14:01', '2025-03-16 23:28:19', 2),
(3, 1, 1, 1, 1, 4, '2024-05-03 18:00:00', 25, 25, NULL, NULL, NULL, 9, 13, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:14:01', '2025-03-14 22:30:26', 3),
(4, 2, 1, 1, 2, 3, '2024-05-03 23:00:00', 17, 25, 15, NULL, NULL, 25, 20, 11, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:14:01', '2025-03-15 17:24:54', 4),
(5, 1, 1, 1, 2, 4, '2024-05-04 12:00:00', 25, 25, NULL, NULL, NULL, 12, 13, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:14:01', '2025-03-16 23:28:37', 5),
(6, 1, 1, 1, 3, 4, '2024-05-04 16:00:00', 25, 25, NULL, NULL, NULL, 14, 6, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:14:01', '2025-03-16 23:29:17', 6),
(7, 2, 1, 2, 5, 6, '2024-05-04 10:00:00', 25, 25, NULL, NULL, NULL, 12, 15, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:14:01', '2025-03-16 23:30:33', 7),
(8, 2, 1, 2, 5, 7, '2024-05-04 16:00:00', 25, 25, NULL, NULL, NULL, 21, 13, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:14:01', '2025-03-16 23:32:06', 8),
(9, 2, 1, 2, 5, 8, '2024-05-03 18:00:00', 25, 25, NULL, NULL, NULL, 17, 13, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:14:01', '2025-03-16 23:30:11', 9),
(10, 1, 1, 2, 6, 7, '2024-05-03 19:00:00', 14, 8, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:14:01', '2025-03-16 23:29:54', 10),
(11, 2, 1, 2, 6, 8, '2024-05-04 11:00:00', 17, 14, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:14:01', '2025-03-16 23:31:08', 11),
(12, 1, 1, 2, 7, 8, '2024-05-04 17:00:00', 25, 22, 15, NULL, NULL, 20, 25, 8, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:14:01', '2025-03-16 23:32:40', 12),
(13, 6, 1, 3, 9, 10, '2024-05-04 14:00:00', 20, 19, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:14:01', '2025-03-16 23:35:08', 13),
(14, 6, 1, 3, 9, 11, '2024-05-04 10:00:00', 25, 25, NULL, NULL, NULL, 8, 10, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:14:01', '2025-03-16 23:34:19', 14),
(15, 2, 1, 3, 9, 12, '2024-05-03 21:00:00', 25, 21, 15, NULL, NULL, 23, 25, 8, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:14:01', '2025-03-16 23:33:15', 15),
(16, 2, 1, 3, 10, 11, '2024-05-03 22:00:00', 25, 25, NULL, NULL, NULL, 4, 16, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:14:01', '2025-03-16 23:33:43', 16),
(17, 6, 1, 3, 10, 12, '2024-05-04 11:00:00', 24, 22, NULL, NULL, NULL, 26, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:14:01', '2025-03-16 23:34:46', 17),
(18, 6, 1, 3, 11, 12, '2024-05-04 15:00:00', 8, 16, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:14:01', '2025-03-16 23:35:01', 18),
(19, 2, 1, 4, 13, 14, '2024-05-04 17:00:00', 14, 10, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:14:01', '2025-03-16 23:37:21', 19),
(20, 1, 1, 4, 13, 15, '2024-05-04 11:00:00', 6, 7, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:14:01', '2025-03-16 23:36:40', 20),
(21, 1, 1, 4, 13, 16, '2024-05-03 22:00:00', 15, 13, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:14:01', '2025-03-16 23:35:55', 21),
(22, 1, 1, 4, 14, 15, '2024-05-04 10:00:00', 17, 23, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:14:01', '2025-03-16 23:36:14', 22),
(23, 1, 1, 4, 14, 16, '2024-05-04 13:00:00', 25, 25, NULL, NULL, NULL, 19, 22, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:14:01', '2025-03-16 23:37:02', 23),
(24, 1, 1, 4, 15, 16, '2024-05-04 18:00:00', 22, 20, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:14:01', '2025-03-16 23:37:58', 24),
(25, 1, 1, NULL, 1, 10, '2024-05-05 10:00:00', 25, 22, 5, NULL, NULL, 19, 25, 15, NULL, NULL, 'Finalizado', 'Eliminatorio', '2025-03-10 16:14:01', '2025-04-14 11:34:23', 25),
(26, 1, 1, NULL, 5, 14, '2024-05-05 11:00:00', 25, 25, NULL, NULL, NULL, 20, 22, NULL, NULL, NULL, 'Finalizado', 'Eliminatorio', '2025-03-10 16:14:01', '2025-04-14 11:36:49', 26),
(27, 2, 1, NULL, 12, 3, '2024-05-05 10:00:00', 17, 21, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Eliminatorio', '2025-03-10 16:14:01', '2025-04-14 11:37:37', 27),
(28, 2, 1, NULL, 15, 7, '2024-05-05 11:00:00', 25, 25, NULL, NULL, NULL, 15, 19, NULL, NULL, NULL, 'Finalizado', 'Eliminatorio', '2025-03-10 16:14:01', '2025-04-14 11:38:16', 28),
(29, 1, 1, NULL, NULL, NULL, '2024-05-05 13:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-03-10 16:14:01', '2025-03-11 21:13:24', 29),
(30, 2, 1, NULL, NULL, NULL, '2024-05-05 13:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-03-10 16:14:01', '2025-03-11 21:13:43', 30),
(31, 1, 1, NULL, NULL, NULL, '2024-05-05 16:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-03-10 16:14:01', '2025-03-11 21:20:49', 31),
(32, 5, 1, NULL, 2, 11, '2024-05-05 09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-03-10 16:14:01', '2025-04-01 11:50:01', 32),
(33, 5, 1, NULL, 8, 13, '2024-05-05 10:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-03-10 16:14:01', '2025-04-01 11:50:01', 33),
(34, 6, 1, NULL, 9, 4, '2024-05-05 09:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-03-10 16:14:01', '2025-04-01 11:50:01', 34),
(35, 6, 1, NULL, 16, 6, '2024-05-05 10:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-03-10 16:14:01', '2025-04-01 11:50:02', 35),
(36, 5, 1, NULL, NULL, NULL, '2024-05-05 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-03-10 16:14:01', '2025-03-13 17:12:04', 36),
(37, 6, 1, NULL, NULL, NULL, '2024-05-05 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-03-10 16:14:01', '2025-03-13 17:12:12', 37),
(38, 6, 1, NULL, NULL, NULL, '2024-05-05 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-03-10 16:14:01', '2025-03-13 17:12:34', 38),
(39, NULL, 2, 5, 17, 18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Cancelado', 'Clasificatorio', '2025-03-10 16:14:09', '2025-03-14 10:13:41', 39),
(40, NULL, 2, 5, 17, 19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Cancelado', 'Clasificatorio', '2025-03-10 16:14:09', '2025-03-14 10:13:41', 40),
(41, NULL, 2, 5, 17, 20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Cancelado', 'Clasificatorio', '2025-03-10 16:14:09', '2025-03-14 10:13:41', 41),
(42, 4, 2, 5, 18, 19, '2024-05-04 10:00:00', 25, 25, NULL, NULL, NULL, 14, 20, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:14:09', '2025-03-16 23:40:22', 42),
(43, 4, 2, 5, 18, 20, '2024-05-04 16:00:00', 18, 16, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:14:09', '2025-03-16 23:41:22', 43),
(44, 3, 2, 5, 19, 20, '2024-05-04 14:00:00', 17, 14, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:14:09', '2025-03-16 23:40:50', 44),
(45, 4, 2, 6, 21, 22, '2024-05-04 13:00:00', 25, 26, NULL, NULL, NULL, 21, 24, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:14:09', '2025-03-16 23:44:40', 45),
(46, 3, 2, 6, 21, 23, '2024-05-04 10:00:00', 19, 23, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:14:09', '2025-03-16 23:43:44', 46),
(47, 4, 2, 6, 21, 24, '2024-05-03 18:00:00', 25, 19, 15, NULL, NULL, 11, 25, 5, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:14:09', '2025-03-16 23:42:41', 47),
(48, 4, 2, 6, 22, 23, '2024-05-03 19:00:00', 16, 25, 15, NULL, NULL, 25, 13, 11, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:14:09', '2025-03-16 23:43:14', 48),
(49, 3, 2, 6, 22, 24, '2024-05-04 11:00:00', 25, 24, 15, NULL, NULL, 16, 26, 13, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:14:09', '2025-03-16 23:44:10', 49),
(50, 3, 2, 6, 23, 24, '2024-05-04 15:00:00', 25, 25, NULL, NULL, NULL, 10, 18, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:14:09', '2025-03-16 23:45:04', 50),
(51, 6, 2, 7, 25, 26, '2024-05-04 16:00:00', 13, 26, NULL, NULL, NULL, 25, 28, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:14:09', '2025-03-16 23:48:27', 51),
(52, 6, 2, 7, 25, 27, '2024-05-04 12:00:00', 13, 25, 9, NULL, NULL, 25, 21, 15, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:14:09', '2025-03-16 23:47:25', 52),
(53, 3, 2, 7, 25, 28, '2024-05-03 21:00:00', 25, 25, NULL, NULL, NULL, 16, 17, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:14:09', '2025-03-16 23:46:11', 53),
(54, 3, 2, 7, 26, 27, '2024-05-03 22:00:00', 25, 18, NULL, NULL, NULL, 27, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:14:09', '2025-03-16 23:46:35', 54),
(55, 6, 2, 7, 26, 28, '2024-05-04 13:00:00', 25, 25, NULL, NULL, NULL, 21, 19, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:14:09', '2025-03-16 23:47:53', 55),
(56, 6, 2, 7, 27, 28, '2024-05-04 17:00:00', 25, 25, NULL, NULL, NULL, 9, 18, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:14:09', '2025-03-16 23:48:54', 56),
(57, 1, 2, 13, 20, 21, '2024-05-05 12:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-03-10 16:14:32', '2025-03-11 21:12:37', 57),
(58, 2, 2, 13, 20, 26, '2024-05-05 12:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-03-10 16:14:32', '2025-03-11 21:12:57', 58),
(59, 1, 2, 13, 21, 26, '2024-05-05 14:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-03-10 16:14:32', '2025-03-11 21:14:17', 59),
(60, 2, 2, 14, 23, 27, '2024-05-05 14:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-03-10 16:14:32', '2025-03-11 21:14:31', 60),
(61, 3, 2, 14, 23, 18, '2024-05-05 10:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-03-10 16:14:32', '2025-03-11 21:16:26', 61),
(62, 3, 2, 14, 18, 27, '2024-05-05 11:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-03-10 16:14:32', '2025-03-11 21:16:48', 62),
(63, 2, 2, NULL, NULL, NULL, '2024-05-05 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-03-10 16:14:32', '2025-03-11 21:17:34', 63),
(64, 1, 2, NULL, NULL, NULL, '2024-05-05 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-03-10 16:14:32', '2025-03-11 21:18:21', 64),
(65, 5, 2, 15, 19, 24, '2024-05-05 11:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-03-10 16:14:32', '2025-03-11 21:26:44', 65),
(66, 5, 2, 15, 19, 28, '2024-05-05 12:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-03-10 16:14:32', '2025-03-11 21:26:53', 66),
(67, 5, 2, 15, 24, 28, '2024-05-05 13:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-03-10 16:14:32', '2025-03-11 22:06:38', 67),
(68, 6, 2, 16, 22, 25, '2024-05-05 11:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-03-10 16:14:32', '2025-03-11 21:27:18', 68),
(69, 6, 2, 16, 22, 17, '2024-05-05 12:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-03-10 16:14:32', '2025-03-11 22:06:53', 69),
(70, 6, 2, 16, 25, 17, '2024-05-05 13:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-03-10 16:14:32', '2025-03-11 22:07:11', 70),
(71, 5, 2, NULL, NULL, NULL, '2024-05-05 19:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-03-10 16:14:32', '2025-03-11 22:07:35', 71),
(72, 6, 2, NULL, NULL, NULL, '2024-05-05 19:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-03-10 16:14:32', '2025-03-11 22:07:48', 72),
(73, 5, 3, 8, 29, 30, '2024-05-03 20:00:00', 20, 22, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:15:36', '2025-03-16 23:50:23', 73),
(74, 5, 3, 8, 29, 31, '2024-05-04 19:00:00', 25, 19, 10, NULL, NULL, 16, 25, 15, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:15:36', '2025-03-16 23:55:30', 74),
(75, 5, 3, 8, 29, 32, '2024-05-04 16:00:00', 25, 8, 7, NULL, NULL, 21, 25, 15, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:15:36', '2025-03-16 23:54:24', 75),
(76, 5, 3, 8, 29, 33, '2024-05-04 12:00:00', 21, 25, 15, NULL, NULL, 25, 23, 8, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:15:36', '2025-03-16 23:53:02', 76),
(77, 5, 3, 8, 29, 34, '2024-05-03 19:00:00', 20, 26, 5, NULL, NULL, 25, 24, 15, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:15:36', '2025-03-16 23:50:02', 77),
(78, 5, 3, 8, 29, 35, '2024-05-04 21:00:00', 22, 21, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:15:36', '2025-03-16 23:57:32', 78),
(79, 5, 3, 8, 30, 31, '2024-05-04 17:00:00', 25, 25, NULL, NULL, NULL, 19, 17, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:15:36', '2025-03-16 23:54:44', 79),
(80, 6, 3, 8, 30, 32, '2024-05-04 19:00:00', 18, 24, NULL, NULL, NULL, 25, 26, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:15:36', '2025-03-16 23:56:17', 80),
(81, 5, 3, 8, 30, 33, '2024-05-04 20:00:00', 25, 25, NULL, NULL, NULL, 18, 18, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:15:36', '2025-03-16 23:56:56', 81),
(82, 5, 3, 8, 30, 34, '2024-05-04 15:00:00', 22, 25, 12, NULL, NULL, 25, 20, 15, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:15:36', '2025-03-16 23:54:01', 82),
(83, 5, 3, 8, 30, 35, '2024-05-03 18:00:00', 23, 25, 15, NULL, NULL, 25, 23, 13, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:15:36', '2025-03-16 23:49:29', 83),
(84, 5, 3, 8, 31, 32, '2024-05-03 23:00:00', 25, 21, 13, NULL, NULL, 15, 25, 15, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:15:36', '2025-03-16 23:51:48', 84),
(85, 5, 3, 8, 31, 33, '2024-05-04 14:00:00', 25, 10, 13, NULL, NULL, 21, 25, 15, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:15:36', '2025-03-16 23:53:42', 85),
(86, 6, 3, 8, 31, 34, '2024-05-04 21:00:00', 18, 23, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:15:36', '2025-03-16 23:57:15', 86),
(87, 5, 3, 8, 31, 35, '2024-05-03 22:00:00', 25, 20, 11, NULL, NULL, 16, 25, 15, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:15:36', '2025-03-16 23:51:16', 87),
(88, 5, 3, 8, 32, 33, '2024-05-04 18:00:00', 25, 25, NULL, NULL, NULL, 20, 18, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:15:36', '2025-03-16 23:55:03', 88),
(89, 6, 3, 8, 32, 34, '2024-05-04 20:00:00', 25, 20, 9, NULL, NULL, 12, 25, 15, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:15:36', '2025-03-16 23:56:41', 89),
(90, 5, 3, 8, 32, 35, '2024-05-04 13:00:00', 25, 22, 15, NULL, NULL, 17, 25, 8, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:15:36', '2025-03-16 23:53:24', 90),
(91, 5, 3, 8, 33, 34, '2024-05-03 21:00:00', 25, 25, 15, NULL, NULL, 22, 27, 7, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:15:36', '2025-03-16 23:50:47', 91),
(92, 5, 3, 8, 33, 35, '2024-05-04 10:00:00', 21, 25, 9, NULL, NULL, 25, 13, 15, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:15:36', '2025-03-16 23:52:19', 92),
(93, 5, 3, 8, 34, 35, '2024-05-04 11:00:00', 25, 25, NULL, NULL, NULL, 17, 22, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:15:36', '2025-03-16 23:52:37', 93),
(94, 2, 3, NULL, 32, 30, '2024-05-05 15:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-03-10 16:15:36', '2025-04-01 15:01:27', 94),
(95, 1, 3, NULL, 34, 35, '2024-05-05 15:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-03-10 16:15:36', '2025-04-01 15:01:28', 95),
(96, 1, 3, NULL, NULL, NULL, '2024-05-05 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-03-10 16:15:36', '2025-03-11 21:20:51', 96),
(97, 5, 3, NULL, 33, 31, '2024-05-05 16:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-03-10 16:15:36', '2025-04-01 15:01:28', 97),
(98, 2, 4, 9, 36, 37, '2024-05-04 18:00:00', 25, 22, NULL, NULL, NULL, 27, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:16:15', '2025-03-17 00:00:11', 98),
(99, 1, 4, 9, 36, 38, '2024-05-04 14:00:00', 23, 20, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:16:15', '2025-03-16 23:59:49', 99),
(100, 1, 4, 9, 36, 39, '2024-05-03 21:00:00', 25, 25, NULL, NULL, NULL, 8, 15, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:16:15', '2025-03-16 23:58:46', 100),
(101, 2, 4, 9, 37, 38, '2024-05-03 20:00:00', 25, 25, NULL, NULL, NULL, 21, 19, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:16:15', '2025-03-16 23:58:25', 101),
(102, 2, 4, 9, 37, 39, '2024-05-04 13:00:00', 25, 25, NULL, NULL, NULL, 15, 15, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:16:15', '2025-03-16 23:59:16', 102),
(103, 1, 4, 9, 38, 39, '2024-05-04 19:00:00', 25, 25, NULL, NULL, NULL, 11, 21, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:16:15', '2025-03-17 00:00:42', 103),
(104, 2, 4, 10, 40, 41, '2024-05-04 19:00:00', 25, 25, NULL, NULL, NULL, 21, 20, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:16:15', '2025-03-17 00:02:15', 104),
(105, 1, 4, 10, 40, 42, '2024-05-04 15:00:00', 25, 25, NULL, NULL, NULL, 14, 17, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:16:15', '2025-03-17 00:01:55', 105),
(106, 1, 4, 10, 40, 43, '2024-05-03 20:00:00', 17, 25, 11, NULL, NULL, 25, 21, 15, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:16:15', '2025-03-17 00:01:21', 106),
(107, 2, 4, 10, 41, 42, '2024-05-03 19:00:00', 11, 18, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:16:15', '2025-03-17 00:01:03', 107),
(108, 2, 4, 10, 41, 43, '2024-05-04 14:00:00', 25, 19, 11, NULL, NULL, 23, 25, 15, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:16:15', '2025-03-17 00:01:39', 108),
(109, 1, 4, 10, 42, 43, '2024-05-04 20:00:00', 27, 22, 15, NULL, NULL, 25, 25, 8, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:16:15', '2025-03-17 00:02:39', 109),
(110, 3, 4, NULL, 37, 43, '2024-05-05 12:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-03-10 16:16:15', '2025-04-01 15:01:35', 110),
(111, 3, 4, NULL, 40, 38, '2024-05-05 13:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-03-10 16:16:15', '2025-04-01 15:01:36', 111),
(112, 3, 4, NULL, NULL, NULL, '2024-05-05 16:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-03-10 16:16:15', '2025-03-11 21:23:28', 112),
(113, 5, 4, NULL, 36, 41, '2024-05-05 14:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-03-10 16:16:15', '2025-04-04 09:58:39', 113),
(114, 6, 4, NULL, 42, 39, '2024-05-05 14:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-03-10 16:16:15', '2025-04-04 09:58:39', 114),
(115, 5, 4, NULL, NULL, NULL, '2024-05-05 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-03-10 16:16:15', '2025-03-13 17:12:50', 115),
(116, 4, 5, 11, 44, 45, '2024-05-04 14:00:00', 25, 25, NULL, NULL, NULL, 14, 19, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:16:47', '2025-03-17 00:04:49', 116),
(117, 3, 5, 11, 44, 46, '2024-05-04 12:00:00', 23, 19, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:16:47', '2025-03-17 00:04:30', 117),
(118, 3, 5, 11, 44, 47, '2024-05-03 19:00:00', 19, 23, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:16:47', '2025-03-17 00:03:04', 118),
(119, 3, 5, 11, 45, 46, '2024-05-03 20:00:00', 18, 23, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:16:47', '2025-03-17 00:03:19', 119),
(120, 4, 5, 11, 45, 47, '2024-05-04 11:00:00', 17, 17, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:16:47', '2025-03-17 00:03:44', 120),
(121, 3, 5, 11, 46, 47, '2024-05-04 16:00:00', 16, 21, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:16:47', '2025-03-17 00:05:24', 121),
(122, 4, 5, 12, 48, 49, '2024-05-04 15:00:00', 25, 25, NULL, NULL, NULL, 15, 16, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:16:47', '2025-03-17 00:07:09', 122),
(123, 3, 5, 12, 48, 50, '2024-05-04 13:00:00', 25, 22, 12, NULL, NULL, 14, 25, 15, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:16:47', '2025-03-17 00:06:53', 123),
(124, 4, 5, 12, 48, 51, '2024-05-03 20:00:00', 25, 25, NULL, NULL, NULL, 13, 17, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:16:47', '2025-03-17 00:05:48', 124),
(125, 4, 5, 12, 49, 50, '2024-05-03 23:00:00', 18, 23, NULL, NULL, NULL, 25, 25, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:16:47', '2025-03-17 00:06:08', 125),
(126, 4, 5, 12, 49, 51, '2024-05-04 12:00:00', 23, 25, 10, NULL, NULL, 25, 22, 15, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:16:47', '2025-03-17 00:06:31', 126),
(127, 3, 5, 12, 50, 51, '2024-05-04 17:00:00', 25, 25, NULL, NULL, NULL, 15, 13, NULL, NULL, NULL, 'Finalizado', 'Clasificatorio', '2025-03-10 16:16:47', '2025-03-17 00:07:32', 127),
(128, 3, 5, NULL, 47, 48, '2024-05-05 14:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-03-10 16:16:47', '2025-04-04 09:58:51', 128),
(129, 3, 5, NULL, 50, 46, '2024-05-05 15:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-03-10 16:16:47', '2025-04-04 09:58:51', 129),
(130, 3, 5, NULL, NULL, NULL, '2024-05-05 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-03-10 16:16:47', '2025-03-11 21:23:46', 130),
(131, 5, 5, NULL, 44, 49, '2024-05-05 15:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-03-10 16:16:47', '2025-04-04 09:58:51', 131),
(132, 6, 5, NULL, 51, 45, '2024-05-05 15:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-03-10 16:16:47', '2025-04-04 09:58:52', 132),
(133, 5, 5, NULL, NULL, NULL, '2024-05-05 20:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-03-10 16:16:47', '2025-03-13 17:14:15', 133);

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
  `nombre` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `partido_config`
--

INSERT INTO `partido_config` (`id`, `partido_id`, `grupo_equipo1_id`, `grupo_equipo2_id`, `ganador_partido1_id`, `ganador_partido2_id`, `posicion_equipo1`, `posicion_equipo2`, `nombre`, `created_at`, `updated_at`) VALUES
(1, 25, 1, 3, NULL, NULL, 1, 2, 'Cuartos de Final Oro 1', '2025-03-10 16:14:01', '2025-03-10 16:14:01'),
(2, 26, 2, 4, NULL, NULL, 1, 2, 'Cuartos de Final Oro 2', '2025-03-10 16:14:01', '2025-03-10 16:14:01'),
(3, 27, 3, 1, NULL, NULL, 1, 2, 'Cuartos de Final Oro 3', '2025-03-10 16:14:01', '2025-03-10 16:14:01'),
(4, 28, 4, 2, NULL, NULL, 1, 2, 'Cuartos de Final Oro 4', '2025-03-10 16:14:01', '2025-03-10 16:14:01'),
(5, 29, NULL, NULL, 25, 27, NULL, NULL, 'Semi Final Oro 1', '2025-03-10 16:14:01', '2025-03-10 16:14:01'),
(6, 30, NULL, NULL, 27, 28, NULL, NULL, 'Semi Final Oro 2', '2025-03-10 16:14:01', '2025-03-10 16:14:01'),
(7, 31, NULL, NULL, 29, 30, NULL, NULL, 'Final Oro 1', '2025-03-10 16:14:01', '2025-03-10 16:14:01'),
(8, 32, 1, 3, NULL, NULL, 3, 4, 'Cuartos de Final Plata 1', '2025-03-10 16:14:01', '2025-03-10 16:14:01'),
(9, 33, 2, 4, NULL, NULL, 3, 4, 'Cuartos de Final Plata 2', '2025-03-10 16:14:01', '2025-03-10 16:14:01'),
(10, 34, 3, 1, NULL, NULL, 3, 4, 'Cuartos de Final Plata 3', '2025-03-10 16:14:01', '2025-03-10 16:14:01'),
(11, 35, 4, 2, NULL, NULL, 3, 4, 'Cuartos de Final Plata 4', '2025-03-10 16:14:01', '2025-03-10 16:14:01'),
(12, 36, NULL, NULL, 32, 34, NULL, NULL, 'Semi Final Plata 1', '2025-03-10 16:14:01', '2025-03-10 16:14:01'),
(13, 37, NULL, NULL, 33, 35, NULL, NULL, 'Semi Final Plata 2', '2025-03-10 16:14:01', '2025-03-10 16:14:01'),
(14, 38, NULL, NULL, 36, 37, NULL, NULL, 'Final Plata 1', '2025-03-10 16:14:01', '2025-03-10 16:14:01'),
(15, 57, 5, 6, NULL, NULL, 1, 2, 'Partido 1 Triangular 1 Oro', '2025-03-10 16:14:32', '2025-03-10 16:14:32'),
(16, 58, 7, 5, NULL, NULL, 2, 1, 'Partido 2 Triangular 1 Oro', '2025-03-10 16:14:32', '2025-03-10 16:14:32'),
(17, 59, 6, 7, NULL, NULL, 2, 2, 'Partido 3 Triangular 1 Oro', '2025-03-10 16:14:32', '2025-03-10 16:14:32'),
(18, 60, 6, 7, NULL, NULL, 1, 1, 'Partido 1 Triangular 2 Oro', '2025-03-10 16:14:32', '2025-03-10 16:14:32'),
(19, 61, 5, 6, NULL, NULL, 2, 1, 'Partido 2 Triangular 2 Oro', '2025-03-10 16:14:32', '2025-03-10 16:14:32'),
(20, 62, 7, 5, NULL, NULL, 1, 2, 'Partido 3 Triangular 2 Oro', '2025-03-10 16:14:32', '2025-03-10 16:14:32'),
(21, 63, 13, 14, NULL, NULL, 2, 2, 'Partido 3y4 Oro', '2025-03-10 16:14:32', '2025-03-10 16:14:32'),
(22, 64, 13, 14, NULL, NULL, 1, 1, 'Partido Final Oro', '2025-03-10 16:14:32', '2025-03-10 16:14:32'),
(23, 65, 5, 6, NULL, NULL, 3, 4, 'Partido 1 Triangular 1 Plata', '2025-03-10 16:14:32', '2025-03-10 16:14:32'),
(24, 66, 7, 5, NULL, NULL, 4, 3, 'Partido 2 Triangular 1 Plata', '2025-03-10 16:14:32', '2025-03-10 16:14:32'),
(25, 67, 6, 7, NULL, NULL, 4, 4, 'Partido 3 Triangular 1 Plata', '2025-03-10 16:14:32', '2025-03-10 16:14:32'),
(26, 68, 7, 6, NULL, NULL, 3, 3, 'Partido 1 Triangular 2 Plata', '2025-03-10 16:14:32', '2025-03-10 16:14:32'),
(27, 69, 6, 5, NULL, NULL, 3, 4, 'Partido 2 Triangular 2 Plata', '2025-03-10 16:14:32', '2025-03-10 16:14:32'),
(28, 70, 5, 7, NULL, NULL, 4, 3, 'Partido 3 Triangular 2 Plata', '2025-03-10 16:14:32', '2025-03-10 16:14:32'),
(29, 71, 15, 16, NULL, NULL, 2, 2, 'Partido 3y4 Plata', '2025-03-10 16:14:32', '2025-03-10 16:14:32'),
(30, 72, 15, 16, NULL, NULL, 1, 1, 'Partido Final Plata', '2025-03-10 16:14:32', '2025-03-10 16:14:32'),
(31, 94, 8, 8, NULL, NULL, 1, 3, 'Semi Final Oro 1', '2025-03-10 16:15:36', '2025-03-10 16:15:36'),
(32, 95, 8, 8, NULL, NULL, 2, 4, 'Semi Final Oro 2', '2025-03-10 16:15:36', '2025-03-10 16:15:36'),
(33, 96, NULL, NULL, 94, 95, NULL, NULL, 'Final Oro 1', '2025-03-10 16:15:36', '2025-03-10 16:15:36'),
(34, 97, 8, 8, NULL, NULL, 5, 6, 'Final Plata 1', '2025-03-10 16:15:36', '2025-03-10 16:15:36'),
(35, 110, 9, 10, NULL, NULL, 1, 2, 'Semi Final Oro 1', '2025-03-10 16:16:15', '2025-03-10 16:16:15'),
(36, 111, 10, 9, NULL, NULL, 1, 2, 'Semi Final Oro 2', '2025-03-10 16:16:15', '2025-03-10 16:16:15'),
(37, 112, NULL, NULL, 110, 111, NULL, NULL, 'Final Oro 1', '2025-03-10 16:16:15', '2025-03-10 16:16:15'),
(38, 113, 9, 10, NULL, NULL, 3, 4, 'Semi Final Plata 1', '2025-03-10 16:16:15', '2025-03-10 16:16:15'),
(39, 114, 10, 9, NULL, NULL, 3, 4, 'Semi Final Plata 2', '2025-03-10 16:16:15', '2025-03-10 16:16:15'),
(40, 115, NULL, NULL, 113, 114, NULL, NULL, 'Final Plata 1', '2025-03-10 16:16:15', '2025-03-10 16:16:15'),
(41, 128, 11, 12, NULL, NULL, 1, 2, 'Semi Final Oro 1', '2025-03-10 16:16:47', '2025-03-10 16:16:47'),
(42, 129, 12, 11, NULL, NULL, 1, 2, 'Semi Final Oro 2', '2025-03-10 16:16:47', '2025-03-10 16:16:47'),
(43, 130, NULL, NULL, 128, 129, NULL, NULL, 'Final Oro 1', '2025-03-10 16:16:47', '2025-03-10 16:16:47'),
(44, 131, 11, 12, NULL, NULL, 3, 4, 'Semi Final Plata 1', '2025-03-10 16:16:47', '2025-03-10 16:16:47'),
(45, 132, 12, 11, NULL, NULL, 3, 4, 'Semi Final Plata 2', '2025-03-10 16:16:47', '2025-03-10 16:16:47'),
(46, 133, NULL, NULL, 131, 132, NULL, NULL, 'Final Plata 1', '2025-03-10 16:16:47', '2025-03-10 16:16:47');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sede`
--

CREATE TABLE `sede` (
  `id` int NOT NULL,
  `torneo_id` int DEFAULT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `domicilio` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sede`
--

INSERT INTO `sede` (`id`, `torneo_id`, `nombre`, `domicilio`, `created_at`, `updated_at`) VALUES
(1, 1, 'Club Villa Dora', 'Ruperto Godoy 1231', '2025-03-06 19:24:01', '2025-03-06 19:24:01'),
(2, 1, 'Club Regatas Santa Fe', 'Av. Leandro N. Alem 3288', '2025-03-06 19:24:01', '2025-03-06 19:24:01');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `torneo`
--

CREATE TABLE `torneo` (
  `id` int NOT NULL,
  `creador_id` int NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ruta` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_inicio_inscripcion` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `fecha_fin_inscripcion` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `fecha_inicio_torneo` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `fecha_fin_torneo` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `reglamento` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `estado` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `torneo`
--

INSERT INTO `torneo` (`id`, `creador_id`, `nombre`, `ruta`, `descripcion`, `fecha_inicio_inscripcion`, `fecha_fin_inscripcion`, `fecha_inicio_torneo`, `fecha_fin_torneo`, `reglamento`, `created_at`, `updated_at`, `estado`) VALUES
(1, 1, 'XIV Torneo Sudamericano de Master Voley Santa Fe', 'xiv-sudamericano-master-voley-sf', 'XIV Torneo Sudamericano de Master Voley Santa Fe', '2024-01-02 00:00:00', '2024-04-30 23:59:00', '2024-05-03 18:00:00', '2024-05-05 21:00:00', NULL, '2025-03-06 19:24:01', '2025-03-10 20:44:34', 'Borrador');

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
  `username` varchar(180) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nombre` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `apellido` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `username`, `roles`, `password`, `email`, `nombre`, `apellido`, `created_at`, `updated_at`) VALUES
(1, 'admin1', '[\"ROLE_ADMIN\"]', '$2y$13$AeQJ8kK8kp158tGc6R9/3.C8teHszJ4FsaxgsNhpOTVJ6oCipsd.e', 'admin1@correo.com', 'Administrador1', 'Administrador1', '2025-03-06 19:24:00', '2025-03-07 23:15:22');

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
  ADD UNIQUE KEY `UNIQ_FB58ADC7A1716C4E` (`ganador_partido1_id`),
  ADD UNIQUE KEY `UNIQ_FB58ADC7B3C4C3A0` (`ganador_partido2_id`),
  ADD UNIQUE KEY `UNIQ_FB58ADC783ECE76` (`grupo_equipo1_id`,`posicion_equipo1`) USING BTREE,
  ADD UNIQUE KEY `UNIQ_FB58ADC71A8B6198` (`grupo_equipo2_id`,`posicion_equipo2`) USING BTREE;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `equipo`
--
ALTER TABLE `equipo`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT de la tabla `grupo`
--
ALTER TABLE `grupo`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `jugador`
--
ALTER TABLE `jugador`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT de la tabla `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `partido`
--
ALTER TABLE `partido`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=134;

--
-- AUTO_INCREMENT de la tabla `partido_config`
--
ALTER TABLE `partido_config`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT de la tabla `sede`
--
ALTER TABLE `sede`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
