-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: mysql-server
-- Tiempo de generación: 03-03-2025 a las 18:17:57
-- Versión del servidor: 8.0.40
-- Versión de PHP: 8.2.27

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
(1, 1, 'Cancha 1 - Arriba', 'Cancha 1 - Arriba', '2024-12-19 19:34:49', '2024-12-19 19:34:49'),
(2, 1, 'Cancha 2 - Arriba', 'Cancha 2 - Arriba', '2024-12-19 19:34:49', '2024-12-19 19:34:49'),
(3, 1, 'Cancha 3 - Abajo', 'Cancha 3 - Abajo', '2024-12-19 19:34:49', '2024-12-19 19:34:49'),
(4, 1, 'Cancha 4 - Abajo', 'Cancha 4 - Abajo', '2024-12-19 19:34:49', '2024-12-19 19:34:49'),
(5, 2, 'Cancha 1', 'Cancha 1', '2024-12-19 19:34:49', '2024-12-19 19:34:49'),
(6, 2, 'Cancha 2', 'Cancha 2', '2024-12-19 19:34:49', '2024-12-19 19:34:49');

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
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `nombre_corto` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id`, `torneo_id`, `nombre`, `genero`, `disputa`, `created_at`, `updated_at`, `nombre_corto`, `estado`) VALUES
(1, 1, 'Fem +35', 'Femenino', NULL, '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'F35', ''),
(2, 1, 'Fem +40', 'Femenino', NULL, '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'F40', ''),
(3, 1, 'Fem +45', 'Femenino', NULL, '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'F45', ''),
(4, 1, 'Mas +42', 'Masculino', NULL, '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'M42', ''),
(5, 1, 'Mas +50', 'Masculino', NULL, '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'M50', ''),
(6, 2, 'Mas 35', 'Femenino', NULL, '2024-12-24 09:53:08', '2024-12-24 09:53:08', 'F35', '');

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
('DoctrineMigrations\\Version20241219222644', '2024-12-19 23:34:43', 394),
('DoctrineMigrations\\Version20241221003916', '2024-12-21 01:39:27', 234),
('DoctrineMigrations\\Version20241227203937', '2024-12-27 21:39:50', 127),
('DoctrineMigrations\\Version20241227204037', '2024-12-27 21:40:41', 43),
('DoctrineMigrations\\Version20250108005224', '2025-01-08 01:52:34', 89),
('DoctrineMigrations\\Version20250109211201', '2025-01-09 22:12:52', 9),
('DoctrineMigrations\\Version20250109211923', '2025-01-09 22:19:32', 22),
('DoctrineMigrations\\Version20250120194641', '2025-02-10 22:28:23', 112),
('DoctrineMigrations\\Version20250120201918', '2025-02-10 22:28:24', 3),
('DoctrineMigrations\\Version20250206215100', '2025-02-10 22:28:24', 47),
('DoctrineMigrations\\Version20250225142822', '2025-02-25 14:28:28', 402);

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
(1, 1, 1, 'VILLA DORA', 'VD', 'Argentina', 'Santa Fe', 'Rosario', '2024-12-19 19:34:49', '2024-12-19 19:34:49', '', 0),
(2, 1, 1, 'CORRIENTES VOLEY', 'CV', 'Argentina', 'Corrientes', 'Corrientes', '2024-12-19 19:34:49', '2024-12-19 19:34:49', '', 0),
(3, 1, 1, 'TREDE BIRRA', 'TB', 'Argentina', 'Santa Fe', 'Rosario', '2024-12-19 19:34:49', '2024-12-19 19:34:49', '', 0),
(4, 1, 1, 'ALUMNI CASILDA', 'AC', 'Argentina', 'Santa Fe', 'Casilda', '2024-12-19 19:34:49', '2024-12-19 19:34:49', '', 0),
(5, 1, 2, 'EL QUILLA', 'EQ', 'Argentina', 'Santa Fe', 'Santa Fe', '2024-12-19 19:34:49', '2024-12-19 19:34:49', '', 0),
(6, 1, 2, 'MONSTARS', 'MON', 'Argentina', 'Santa Fe', 'Rosario', '2024-12-19 19:34:49', '2024-12-19 19:34:49', '', 0),
(7, 1, 2, 'CLUB JUNIN', 'CJ', 'Argentina', 'Buenos Aires', 'Junin', '2024-12-19 19:34:49', '2024-12-19 19:34:49', '', 0),
(8, 1, 2, 'REGATAS ROSARIO', 'RR', 'Argentina', 'Santa Fe', 'Rosario', '2024-12-19 19:34:49', '2024-12-19 19:34:49', '', 0),
(9, 1, 3, 'ALIANZA SANTO TOME', 'AST', 'Argentina', 'Santa Fe', 'Santo Tome', '2024-12-19 19:34:49', '2024-12-19 19:34:49', '', 0),
(10, 1, 3, 'NAUTICO AVELLANEDA', 'NA', 'Argentina', 'Santa Fe', 'Avellaneda', '2024-12-19 19:34:49', '2024-12-19 19:34:49', '', 0),
(11, 1, 3, 'MALUCA', 'MAL', 'Argentina', 'Santa Fe', 'Rosario', '2024-12-19 19:34:49', '2024-12-19 19:34:49', '', 0),
(12, 1, 3, 'LA GRULLAS', 'LG', 'Argentina', 'Santa Fe', 'Rosario', '2024-12-19 19:34:49', '2024-12-19 19:34:49', '', 0),
(13, 1, 4, 'INFINITO', 'INF', 'Argentina', 'Santa Fe', 'Rosario', '2024-12-19 19:34:49', '2024-12-19 19:34:49', '', 0),
(14, 1, 4, 'LA EMILIA', 'LE', 'Argentina', 'Santa Fe', 'Rosario', '2024-12-19 19:34:49', '2024-12-19 19:34:49', '', 0),
(15, 1, 4, 'SANTO TOME', 'ST', 'Argentina', 'Santa Fe', 'Santo Tome', '2024-12-19 19:34:49', '2024-12-19 19:34:49', '', 0),
(16, 1, 4, 'LAS CUERVAS', 'LC', 'Argentina', 'Santa Fe', 'Rosario', '2024-12-19 19:34:49', '2024-12-19 19:34:49', '', 0),
(17, 2, 5, 'TREDE BIRRA', 'TB', 'Argentina', 'Santa Fe', 'Rosario', '2024-12-19 19:34:49', '2024-12-19 19:34:49', '', 0),
(18, 2, 5, 'VAMOS EL APOYO', 'VEA', 'Argentina', 'Santa Fe', 'Rosario', '2024-12-19 19:34:49', '2024-12-19 19:34:49', '', 0),
(19, 2, 5, 'COSTA CANELONES', 'CC', 'Uruguay', 'Canelones', 'Canelones', '2024-12-19 19:34:49', '2024-12-19 19:34:49', '', 0),
(20, 2, 5, 'INTRUSAS', 'INT', 'Argentina', 'Santa Fe', 'Rosario', '2024-12-19 19:34:49', '2024-12-19 19:34:49', '', 0),
(21, 2, 6, 'DOS HACHES', 'DH', 'Argentina', 'Santa Fe', 'Rosario', '2024-12-19 19:34:49', '2024-12-19 19:34:49', '', 0),
(22, 2, 6, 'SOMOS LA 18', 'S18', 'Argentina', 'Santa Fe', 'Rosario', '2024-12-19 19:34:49', '2024-12-19 19:34:49', '', 0),
(23, 2, 6, 'CLUB FISHERTON', 'CF', 'Argentina', 'Santa Fe', 'Rosario', '2024-12-19 19:34:49', '2024-12-19 19:34:49', '', 0),
(24, 2, 6, 'COSTA MIX', 'CM', 'Argentina', 'Santa Fe', 'Rosario', '2024-12-19 19:34:49', '2024-12-19 19:34:49', '', 0),
(25, 2, 7, 'PASO REY', 'PR', 'Argentina', 'Santa Fe', 'Rosario', '2024-12-19 19:34:49', '2024-12-19 19:34:49', '', 0),
(26, 2, 7, 'DESTINO VOLEY', 'DV', 'Argentina', 'Santa Fe', 'Rosario', '2024-12-19 19:34:49', '2024-12-19 19:34:49', '', 0),
(27, 2, 7, 'CITADAS', 'CIT', 'Argentina', 'Santa Fe', 'Rosario', '2024-12-19 19:34:49', '2024-12-19 19:34:49', '', 0),
(28, 2, 7, 'MALUCA', 'MAL', 'Argentina', 'Santa Fe', 'Rosario', '2024-12-19 19:34:49', '2024-12-19 19:34:49', '', 0),
(29, 3, 8, 'EL REJUNTE', 'S18', 'Argentina', 'Santa Fe', 'Rosario', '2024-12-19 19:34:49', '2024-12-19 19:34:49', '', 0),
(30, 3, 8, 'CLUB ROSARIO', 'CR', 'Argentina', 'Santa Fe', 'Rosario', '2024-12-19 19:34:49', '2024-12-19 19:34:49', '', 0),
(31, 3, 8, 'GYE CONCEP URUGUAY', 'GCU', 'Argentina', 'Entre Rios', 'Concepcion del Uruguay', '2024-12-19 19:34:49', '2024-12-19 19:34:49', '', 0),
(32, 3, 8, 'MONSTARS', 'MON', 'Argentina', 'Santa Fe', 'Rosario', '2024-12-19 19:34:49', '2024-12-19 19:34:49', '', 0),
(33, 3, 8, 'VOLEY MONTE', 'VM', 'Argentina', 'Santa Fe', 'San Lorenzo', '2024-12-19 19:34:49', '2024-12-19 19:34:49', '', 0),
(34, 3, 8, 'BANCO SANTA FE', 'BSF', 'Argentina', 'Santa Fe', 'Santa Fe', '2024-12-19 19:34:49', '2024-12-19 19:34:49', '', 0),
(35, 3, 8, 'UNI', 'UNI', 'Argentina', 'Santa Fe', 'Rosario', '2024-12-19 19:34:49', '2024-12-19 19:34:49', '', 0),
(36, 4, 9, 'MAXI SANTA FE A', 'MSFA', 'Argentina', 'Santa Fe', 'Santa Fe', '2024-12-19 19:34:49', '2024-12-19 19:34:49', '', 0),
(37, 4, 9, 'PERO', 'PERO', 'Argentina', 'Santa Fe', 'Santa Fe', '2024-12-19 19:34:49', '2024-12-19 19:34:49', '', 0),
(38, 4, 9, 'RECREATIVO VERA', 'REVE', 'Argentina', 'Santa Fe', 'Vera', '2024-12-19 19:34:49', '2024-12-19 19:34:49', '', 0),
(39, 4, 9, 'BOSQUE URUGUAY', 'BOSQUE', 'Argentina', 'Santa Fe', 'Santa Fe', '2024-12-19 19:34:49', '2024-12-19 19:34:49', '', 0),
(40, 4, 10, 'ROSARIO VOLEY', 'ROVA', 'Argentina', 'Santa Fe', 'Rosario', '2024-12-19 19:34:49', '2024-12-19 19:34:49', '', 0),
(41, 4, 10, 'LA TRIBU', 'LATR', 'Argentina', 'Santa Fe', 'Santa Fe', '2024-12-19 19:34:49', '2024-12-19 19:34:49', '', 0),
(42, 4, 10, 'MAXI SANTA FE B', 'MSFB', 'Argentina', 'Santa Fe', 'Santa Fe', '2024-12-19 19:34:49', '2024-12-19 19:34:49', '', 0),
(43, 4, 10, 'BANCO PROVINCIA', 'BAPR', 'Argentina', 'Santa Fe', 'Santa Fe', '2024-12-19 19:34:49', '2024-12-19 19:34:49', '', 0),
(44, 5, 11, 'MAXI SANTA FE', 'MSF', 'Argentina', 'Santa Fe', 'Santa Fe', '2024-12-19 19:34:49', '2024-12-19 19:34:49', '', 0),
(45, 5, 11, 'CORCHA VOLEY', 'CORCHA', 'Argentina', 'Santa Fe', 'Rosario', '2024-12-19 19:34:49', '2024-12-19 19:34:49', '', 0),
(46, 5, 11, 'LOS PERKINS', 'LP', 'Argentina', 'Santa Fe', 'Rosario', '2024-12-19 19:34:49', '2024-12-19 19:34:49', '', 0),
(47, 5, 11, 'ABANDONADOS', 'ABA', 'Argentina', 'Santa Fe', 'Rosario', '2024-12-19 19:34:49', '2024-12-19 19:34:49', '', 0),
(48, 5, 12, 'ROSARIO VOLEY', 'ROVA', 'Argentina', 'Santa Fe', 'Rosario', '2024-12-19 19:34:49', '2024-12-19 19:34:49', '', 0),
(49, 5, 12, 'DEFENSORES MORENO', 'DEFF', 'Argentina', 'Buenos Aires', 'Moreno', '2024-12-19 19:34:49', '2024-12-19 19:34:49', '', 0),
(50, 5, 12, 'TUCUMAN DE GIMNASIA', 'TUGI', 'Argentina', 'Tucuman', 'San Miguel de Tucuman', '2024-12-19 19:34:49', '2024-12-19 19:34:49', '', 0),
(51, 5, 12, 'DEPORTE RIO  IV', 'DRI4', 'Argentina', 'Cordoba', 'Rio Cuarto', '2024-12-19 19:34:49', '2024-12-19 19:34:49', '', 0);

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
  `estado` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `grupo`
--

INSERT INTO `grupo` (`id`, `categoria_id`, `nombre`, `clasifica_oro`, `clasifica_plata`, `clasifica_bronce`, `created_at`, `updated_at`, `estado`) VALUES
(1, 1, 'A', 2, 2, NULL, '2024-12-19 19:34:49', '2024-12-19 19:34:49', ''),
(2, 1, 'B', 2, 2, NULL, '2024-12-19 19:34:49', '2024-12-19 19:34:49', ''),
(3, 1, 'C', 2, 2, NULL, '2024-12-19 19:34:49', '2024-12-19 19:34:49', ''),
(4, 1, 'D', 2, 2, NULL, '2024-12-19 19:34:49', '2024-12-19 19:34:49', ''),
(5, 2, 'E', 2, 2, NULL, '2024-12-19 19:34:49', '2024-12-19 19:34:49', ''),
(6, 2, 'F', 2, 2, NULL, '2024-12-19 19:34:49', '2024-12-19 19:34:49', ''),
(7, 2, 'G', 2, 2, NULL, '2024-12-19 19:34:49', '2024-12-19 19:34:49', ''),
(8, 3, 'Unica', 4, 2, NULL, '2024-12-19 19:34:49', '2024-12-19 19:34:49', ''),
(9, 4, '1', 2, 2, NULL, '2024-12-19 19:34:49', '2024-12-19 19:34:49', ''),
(10, 4, '2', 2, 2, NULL, '2024-12-19 19:34:49', '2024-12-19 19:34:49', ''),
(11, 5, '3', 2, 2, NULL, '2024-12-19 19:34:49', '2024-12-19 19:34:49', ''),
(12, 5, '4', 2, 2, NULL, '2024-12-19 19:34:49', '2024-12-19 19:34:49', ''),
(17, 2, 'Triangular 1 Oro', 2, NULL, NULL, '2025-03-03 17:11:52', '2025-03-03 17:11:52', ''),
(18, 2, 'Triangular 2 Oro', 2, NULL, NULL, '2025-03-03 17:11:52', '2025-03-03 17:11:52', ''),
(19, 2, 'Triangular 1 Plata', 2, 2, NULL, '2025-03-03 17:11:52', '2025-03-03 17:11:52', ''),
(20, 2, 'Triangular 2 Plata', 2, 2, NULL, '2025-03-03 17:11:52', '2025-03-03 17:11:52', '');

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
(1, 1, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'Entrenador'),
(2, 2, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'Entrenador'),
(3, 3, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'Entrenador'),
(4, 4, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'Entrenador'),
(5, 5, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'Entrenador'),
(6, 6, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'Entrenador'),
(7, 7, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'Entrenador'),
(8, 8, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'Entrenador'),
(9, 9, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'Entrenador'),
(10, 10, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'Entrenador'),
(11, 11, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'Entrenador'),
(12, 12, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'Entrenador'),
(13, 13, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'Entrenador'),
(14, 14, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'Entrenador'),
(15, 15, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'Entrenador'),
(16, 16, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'Entrenador'),
(17, 17, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'Entrenador'),
(18, 18, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'Entrenador'),
(19, 19, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'Entrenador'),
(20, 20, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'Entrenador'),
(21, 21, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'Entrenador'),
(22, 22, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'Entrenador'),
(23, 23, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'Entrenador'),
(24, 24, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'Entrenador'),
(25, 25, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'Entrenador'),
(26, 26, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'Entrenador'),
(27, 27, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'Entrenador'),
(28, 28, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'Entrenador'),
(29, 29, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'Entrenador'),
(30, 30, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'Entrenador'),
(31, 31, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'Entrenador'),
(32, 32, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'Entrenador'),
(33, 33, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'Entrenador'),
(34, 34, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'Entrenador'),
(35, 35, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'Entrenador'),
(36, 36, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'Entrenador'),
(37, 37, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'Entrenador'),
(38, 38, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'Entrenador'),
(39, 39, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'Entrenador'),
(40, 40, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'Entrenador'),
(41, 41, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'Entrenador'),
(42, 42, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'Entrenador'),
(43, 43, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'Entrenador'),
(44, 44, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'Entrenador'),
(45, 45, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'Entrenador'),
(46, 46, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'Entrenador'),
(47, 47, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'Entrenador'),
(48, 48, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'Entrenador'),
(49, 49, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'Entrenador'),
(50, 50, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'Entrenador'),
(51, 51, 'Delegado', 'Delegado', 'DNI', '12345678', '1980-01-01 00:00:00', 1, 'delegado@delegado.com', '3411234567', '2024-12-19 19:34:49', '2024-12-19 19:34:49', 'Entrenador');

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
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `estado` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `categoria_id` int NOT NULL,
  `tipo` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `numero` smallint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `partido`
--

INSERT INTO `partido` (`id`, `cancha_id`, `grupo_id`, `equipo_local_id`, `equipo_visitante_id`, `horario`, `local_set1`, `local_set2`, `local_set3`, `local_set4`, `local_set5`, `visitante_set1`, `visitante_set2`, `visitante_set3`, `visitante_set4`, `visitante_set5`, `created_at`, `updated_at`, `estado`, `categoria_id`, `tipo`, `numero`) VALUES
(1, NULL, 1, 1, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:44', '2025-03-03 12:44:44', 'Borrador', 1, 'Clasificatorio', 1),
(2, NULL, 1, 1, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:44', '2025-03-03 12:44:44', 'Borrador', 1, 'Clasificatorio', 2),
(3, NULL, 1, 1, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:44', '2025-03-03 12:44:44', 'Borrador', 1, 'Clasificatorio', 3),
(4, NULL, 1, 2, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:44', '2025-03-03 12:44:44', 'Borrador', 1, 'Clasificatorio', 4),
(5, NULL, 1, 2, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:44', '2025-03-03 12:44:44', 'Borrador', 1, 'Clasificatorio', 5),
(6, NULL, 1, 3, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:44', '2025-03-03 12:44:44', 'Borrador', 1, 'Clasificatorio', 6),
(7, NULL, 2, 5, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:44', '2025-03-03 12:44:44', 'Borrador', 1, 'Clasificatorio', 7),
(8, NULL, 2, 5, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:44', '2025-03-03 12:44:44', 'Borrador', 1, 'Clasificatorio', 8),
(9, NULL, 2, 5, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:44', '2025-03-03 12:44:44', 'Borrador', 1, 'Clasificatorio', 9),
(10, NULL, 2, 6, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:44', '2025-03-03 12:44:44', 'Borrador', 1, 'Clasificatorio', 10),
(11, NULL, 2, 6, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:44', '2025-03-03 12:44:44', 'Borrador', 1, 'Clasificatorio', 11),
(12, NULL, 2, 7, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:44', '2025-03-03 12:44:44', 'Borrador', 1, 'Clasificatorio', 12),
(13, NULL, 3, 9, 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:44', '2025-03-03 12:44:44', 'Borrador', 1, 'Clasificatorio', 13),
(14, NULL, 3, 9, 11, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:44', '2025-03-03 12:44:44', 'Borrador', 1, 'Clasificatorio', 14),
(15, NULL, 3, 9, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:44', '2025-03-03 12:44:44', 'Borrador', 1, 'Clasificatorio', 15),
(16, NULL, 3, 10, 11, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:44', '2025-03-03 12:44:44', 'Borrador', 1, 'Clasificatorio', 16),
(17, NULL, 3, 10, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:44', '2025-03-03 12:44:44', 'Borrador', 1, 'Clasificatorio', 17),
(18, NULL, 3, 11, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:44', '2025-03-03 12:44:44', 'Borrador', 1, 'Clasificatorio', 18),
(19, NULL, 4, 13, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:44', '2025-03-03 12:44:44', 'Borrador', 1, 'Clasificatorio', 19),
(20, NULL, 4, 13, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:44', '2025-03-03 12:44:44', 'Borrador', 1, 'Clasificatorio', 20),
(21, NULL, 4, 13, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:44', '2025-03-03 12:44:44', 'Borrador', 1, 'Clasificatorio', 21),
(22, NULL, 4, 14, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:44', '2025-03-03 12:44:44', 'Borrador', 1, 'Clasificatorio', 22),
(23, NULL, 4, 14, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:44', '2025-03-03 12:44:44', 'Borrador', 1, 'Clasificatorio', 23),
(24, NULL, 4, 15, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:44', '2025-03-03 12:44:44', 'Borrador', 1, 'Clasificatorio', 24),
(25, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:44', '2025-03-03 12:44:44', 'Borrador', 1, 'Eliminatorio', 25),
(26, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:44', '2025-03-03 12:44:44', 'Borrador', 1, 'Eliminatorio', 26),
(27, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:44', '2025-03-03 12:44:44', 'Borrador', 1, 'Eliminatorio', 27),
(28, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:44', '2025-03-03 12:44:44', 'Borrador', 1, 'Eliminatorio', 28),
(29, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:44', '2025-03-03 12:44:44', 'Borrador', 1, 'Eliminatorio', 29),
(30, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:44', '2025-03-03 12:44:44', 'Borrador', 1, 'Eliminatorio', 30),
(31, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:44', '2025-03-03 12:44:44', 'Borrador', 1, 'Eliminatorio', 31),
(32, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:44', '2025-03-03 12:44:44', 'Borrador', 1, 'Eliminatorio', 32),
(33, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:44', '2025-03-03 12:44:44', 'Borrador', 1, 'Eliminatorio', 33),
(34, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:44', '2025-03-03 12:44:44', 'Borrador', 1, 'Eliminatorio', 34),
(35, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:44', '2025-03-03 12:44:44', 'Borrador', 1, 'Eliminatorio', 35),
(36, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:44', '2025-03-03 12:44:44', 'Borrador', 1, 'Eliminatorio', 36),
(37, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:44', '2025-03-03 12:44:44', 'Borrador', 1, 'Eliminatorio', 37),
(38, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:44', '2025-03-03 12:44:44', 'Borrador', 1, 'Eliminatorio', 38),
(39, NULL, 5, 17, 18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:58', '2025-03-03 12:44:58', 'Borrador', 2, 'Clasificatorio', 39),
(40, NULL, 5, 17, 19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:58', '2025-03-03 12:44:58', 'Borrador', 2, 'Clasificatorio', 40),
(41, NULL, 5, 17, 20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:58', '2025-03-03 12:44:58', 'Borrador', 2, 'Clasificatorio', 41),
(42, NULL, 5, 18, 19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:58', '2025-03-03 12:44:58', 'Borrador', 2, 'Clasificatorio', 42),
(43, NULL, 5, 18, 20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:58', '2025-03-03 12:44:58', 'Borrador', 2, 'Clasificatorio', 43),
(44, NULL, 5, 19, 20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:58', '2025-03-03 12:44:58', 'Borrador', 2, 'Clasificatorio', 44),
(45, NULL, 6, 21, 22, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:58', '2025-03-03 12:44:58', 'Borrador', 2, 'Clasificatorio', 45),
(46, NULL, 6, 21, 23, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:58', '2025-03-03 12:44:58', 'Borrador', 2, 'Clasificatorio', 46),
(47, NULL, 6, 21, 24, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:58', '2025-03-03 12:44:58', 'Borrador', 2, 'Clasificatorio', 47),
(48, NULL, 6, 22, 23, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:58', '2025-03-03 12:44:58', 'Borrador', 2, 'Clasificatorio', 48),
(49, NULL, 6, 22, 24, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:58', '2025-03-03 12:44:58', 'Borrador', 2, 'Clasificatorio', 49),
(50, NULL, 6, 23, 24, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:58', '2025-03-03 12:44:58', 'Borrador', 2, 'Clasificatorio', 50),
(51, NULL, 7, 25, 26, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:58', '2025-03-03 12:44:58', 'Borrador', 2, 'Clasificatorio', 51),
(52, NULL, 7, 25, 27, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:58', '2025-03-03 12:44:58', 'Borrador', 2, 'Clasificatorio', 52),
(53, NULL, 7, 25, 28, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:58', '2025-03-03 12:44:58', 'Borrador', 2, 'Clasificatorio', 53),
(54, NULL, 7, 26, 27, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:58', '2025-03-03 12:44:58', 'Borrador', 2, 'Clasificatorio', 54),
(55, NULL, 7, 26, 28, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:58', '2025-03-03 12:44:58', 'Borrador', 2, 'Clasificatorio', 55),
(56, NULL, 7, 27, 28, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 12:44:58', '2025-03-03 12:44:58', 'Borrador', 2, 'Clasificatorio', 56),
(57, NULL, 17, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 15:56:28', '2025-03-03 15:56:28', 'Borrador', 2, 'Eliminatorio', 57),
(58, NULL, 17, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 15:56:28', '2025-03-03 15:56:28', 'Borrador', 2, 'Eliminatorio', 58),
(59, NULL, 17, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 15:56:28', '2025-03-03 15:56:28', 'Borrador', 2, 'Eliminatorio', 59),
(60, NULL, 18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 15:56:28', '2025-03-03 15:56:28', 'Borrador', 2, 'Eliminatorio', 60),
(61, NULL, 18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 15:56:28', '2025-03-03 15:56:28', 'Borrador', 2, 'Eliminatorio', 61),
(62, NULL, 18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 15:56:28', '2025-03-03 15:56:28', 'Borrador', 2, 'Eliminatorio', 62),
(63, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 15:56:28', '2025-03-03 15:56:28', 'Borrador', 2, 'Eliminatorio', 63),
(64, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 15:56:28', '2025-03-03 15:56:28', 'Borrador', 2, 'Eliminatorio', 64),
(65, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 15:56:28', '2025-03-03 15:56:28', 'Borrador', 2, 'Eliminatorio', 65),
(66, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 15:56:28', '2025-03-03 15:56:28', 'Borrador', 2, 'Eliminatorio', 66),
(67, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 15:56:28', '2025-03-03 15:56:28', 'Borrador', 2, 'Eliminatorio', 67),
(68, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 15:56:28', '2025-03-03 15:56:28', 'Borrador', 2, 'Eliminatorio', 68),
(69, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 15:56:28', '2025-03-03 15:56:28', 'Borrador', 2, 'Eliminatorio', 69),
(70, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 15:56:28', '2025-03-03 15:56:28', 'Borrador', 2, 'Eliminatorio', 70),
(71, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 15:56:28', '2025-03-03 15:56:28', 'Borrador', 2, 'Eliminatorio', 71),
(72, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 15:56:28', '2025-03-03 15:56:28', 'Borrador', 2, 'Eliminatorio', 72),
(73, NULL, 8, 29, 30, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:37:47', '2025-03-03 14:37:47', 'Borrador', 3, 'Clasificatorio', 73),
(74, NULL, 8, 29, 31, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:37:47', '2025-03-03 14:37:47', 'Borrador', 3, 'Clasificatorio', 74),
(75, NULL, 8, 29, 32, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:37:47', '2025-03-03 14:37:47', 'Borrador', 3, 'Clasificatorio', 75),
(76, NULL, 8, 29, 33, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:37:47', '2025-03-03 14:37:47', 'Borrador', 3, 'Clasificatorio', 76),
(77, NULL, 8, 29, 34, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:37:47', '2025-03-03 14:37:47', 'Borrador', 3, 'Clasificatorio', 77),
(78, NULL, 8, 29, 35, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:37:47', '2025-03-03 14:37:47', 'Borrador', 3, 'Clasificatorio', 78),
(79, NULL, 8, 30, 31, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:37:47', '2025-03-03 14:37:47', 'Borrador', 3, 'Clasificatorio', 79),
(80, NULL, 8, 30, 32, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:37:47', '2025-03-03 14:37:47', 'Borrador', 3, 'Clasificatorio', 80),
(81, NULL, 8, 30, 33, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:37:47', '2025-03-03 14:37:47', 'Borrador', 3, 'Clasificatorio', 81),
(82, NULL, 8, 30, 34, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:37:47', '2025-03-03 14:37:47', 'Borrador', 3, 'Clasificatorio', 82),
(83, NULL, 8, 30, 35, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:37:47', '2025-03-03 14:37:47', 'Borrador', 3, 'Clasificatorio', 83),
(84, NULL, 8, 31, 32, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:37:47', '2025-03-03 14:37:47', 'Borrador', 3, 'Clasificatorio', 84),
(85, NULL, 8, 31, 33, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:37:47', '2025-03-03 14:37:47', 'Borrador', 3, 'Clasificatorio', 85),
(86, NULL, 8, 31, 34, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:37:47', '2025-03-03 14:37:47', 'Borrador', 3, 'Clasificatorio', 86),
(87, NULL, 8, 31, 35, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:37:47', '2025-03-03 14:37:47', 'Borrador', 3, 'Clasificatorio', 87),
(88, NULL, 8, 32, 33, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:37:47', '2025-03-03 14:37:47', 'Borrador', 3, 'Clasificatorio', 88),
(89, NULL, 8, 32, 34, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:37:47', '2025-03-03 14:37:47', 'Borrador', 3, 'Clasificatorio', 89),
(90, NULL, 8, 32, 35, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:37:47', '2025-03-03 14:37:47', 'Borrador', 3, 'Clasificatorio', 90),
(91, NULL, 8, 33, 34, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:37:47', '2025-03-03 14:37:47', 'Borrador', 3, 'Clasificatorio', 91),
(92, NULL, 8, 33, 35, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:37:47', '2025-03-03 14:37:47', 'Borrador', 3, 'Clasificatorio', 92),
(93, NULL, 8, 34, 35, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:37:47', '2025-03-03 14:37:47', 'Borrador', 3, 'Clasificatorio', 93),
(94, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:37:47', '2025-03-03 14:37:47', 'Borrador', 3, 'Eliminatorio', 94),
(95, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:37:47', '2025-03-03 14:37:47', 'Borrador', 3, 'Eliminatorio', 95),
(96, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:37:47', '2025-03-03 14:37:47', 'Borrador', 3, 'Eliminatorio', 96),
(97, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:37:47', '2025-03-03 14:37:47', 'Borrador', 3, 'Eliminatorio', 97),
(98, NULL, 9, 36, 37, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:39:11', '2025-03-03 14:39:11', 'Borrador', 4, 'Clasificatorio', 98),
(99, NULL, 9, 36, 38, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:39:11', '2025-03-03 14:39:11', 'Borrador', 4, 'Clasificatorio', 99),
(100, NULL, 9, 36, 39, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:39:11', '2025-03-03 14:39:11', 'Borrador', 4, 'Clasificatorio', 100),
(101, NULL, 9, 37, 38, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:39:11', '2025-03-03 14:39:11', 'Borrador', 4, 'Clasificatorio', 101),
(102, NULL, 9, 37, 39, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:39:11', '2025-03-03 14:39:11', 'Borrador', 4, 'Clasificatorio', 102),
(103, NULL, 9, 38, 39, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:39:11', '2025-03-03 14:39:11', 'Borrador', 4, 'Clasificatorio', 103),
(104, NULL, 10, 40, 41, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:39:11', '2025-03-03 14:39:11', 'Borrador', 4, 'Clasificatorio', 104),
(105, NULL, 10, 40, 42, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:39:11', '2025-03-03 14:39:11', 'Borrador', 4, 'Clasificatorio', 105),
(106, NULL, 10, 40, 43, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:39:12', '2025-03-03 14:39:12', 'Borrador', 4, 'Clasificatorio', 106),
(107, NULL, 10, 41, 42, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:39:12', '2025-03-03 14:39:12', 'Borrador', 4, 'Clasificatorio', 107),
(108, NULL, 10, 41, 43, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:39:12', '2025-03-03 14:39:12', 'Borrador', 4, 'Clasificatorio', 108),
(109, NULL, 10, 42, 43, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:39:12', '2025-03-03 14:39:12', 'Borrador', 4, 'Clasificatorio', 109),
(110, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:39:12', '2025-03-03 14:39:12', 'Borrador', 4, 'Eliminatorio', 110),
(111, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:39:12', '2025-03-03 14:39:12', 'Borrador', 4, 'Eliminatorio', 111),
(112, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:39:12', '2025-03-03 14:39:12', 'Borrador', 4, 'Eliminatorio', 112),
(113, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:39:12', '2025-03-03 14:39:12', 'Borrador', 4, 'Eliminatorio', 113),
(114, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:39:12', '2025-03-03 14:39:12', 'Borrador', 4, 'Eliminatorio', 114),
(115, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:39:12', '2025-03-03 14:39:12', 'Borrador', 4, 'Eliminatorio', 115),
(116, NULL, 11, 44, 45, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:58:11', '2025-03-03 14:58:11', 'Borrador', 5, 'Clasificatorio', 116),
(117, NULL, 11, 44, 46, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:58:11', '2025-03-03 14:58:11', 'Borrador', 5, 'Clasificatorio', 117),
(118, NULL, 11, 44, 47, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:58:11', '2025-03-03 14:58:11', 'Borrador', 5, 'Clasificatorio', 118),
(119, NULL, 11, 45, 46, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:58:11', '2025-03-03 14:58:11', 'Borrador', 5, 'Clasificatorio', 119),
(120, NULL, 11, 45, 47, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:58:11', '2025-03-03 14:58:11', 'Borrador', 5, 'Clasificatorio', 120),
(121, NULL, 11, 46, 47, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:58:11', '2025-03-03 14:58:11', 'Borrador', 5, 'Clasificatorio', 121),
(122, NULL, 12, 48, 49, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:58:11', '2025-03-03 14:58:11', 'Borrador', 5, 'Clasificatorio', 122),
(123, NULL, 12, 48, 50, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:58:11', '2025-03-03 14:58:11', 'Borrador', 5, 'Clasificatorio', 123),
(124, NULL, 12, 48, 51, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:58:11', '2025-03-03 14:58:11', 'Borrador', 5, 'Clasificatorio', 124),
(125, NULL, 12, 49, 50, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:58:11', '2025-03-03 14:58:11', 'Borrador', 5, 'Clasificatorio', 125),
(126, NULL, 12, 49, 51, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:58:11', '2025-03-03 14:58:11', 'Borrador', 5, 'Clasificatorio', 126),
(127, NULL, 12, 50, 51, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:58:11', '2025-03-03 14:58:11', 'Borrador', 5, 'Clasificatorio', 127),
(128, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:58:11', '2025-03-03 14:58:11', 'Borrador', 5, 'Eliminatorio', 128),
(129, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:58:11', '2025-03-03 14:58:11', 'Borrador', 5, 'Eliminatorio', 129),
(130, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:58:11', '2025-03-03 14:58:11', 'Borrador', 5, 'Eliminatorio', 130),
(131, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:58:11', '2025-03-03 14:58:11', 'Borrador', 5, 'Eliminatorio', 131),
(132, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:58:11', '2025-03-03 14:58:11', 'Borrador', 5, 'Eliminatorio', 132),
(133, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 14:58:11', '2025-03-03 14:58:11', 'Borrador', 5, 'Eliminatorio', 133);

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
(1, 25, 1, 3, NULL, NULL, 1, 2, 'Cuartos de Final Oro 1', '2025-03-03 12:44:44', '2025-03-03 12:44:44'),
(2, 26, 2, 4, NULL, NULL, 1, 2, 'Cuartos de Final Oro 2', '2025-03-03 12:44:44', '2025-03-03 12:44:44'),
(3, 27, 3, 1, NULL, NULL, 1, 2, 'Cuartos de Final Oro 3', '2025-03-03 12:44:44', '2025-03-03 12:44:44'),
(4, 28, 4, 2, NULL, NULL, 2, 2, 'Cuartos de Final Oro 4', '2025-03-03 12:44:44', '2025-03-03 12:44:44'),
(5, 29, NULL, NULL, 25, 27, NULL, NULL, 'Semi Final Oro 1', '2025-03-03 12:44:44', '2025-03-03 12:44:44'),
(6, 30, NULL, NULL, 26, 28, NULL, NULL, 'Semi Final Oro 2', '2025-03-03 12:44:44', '2025-03-03 12:44:44'),
(7, 31, NULL, NULL, 29, 30, NULL, NULL, 'Final Oro 1', '2025-03-03 12:44:44', '2025-03-03 12:44:44'),
(8, 32, 1, 3, NULL, NULL, 9, 10, 'Cuartos de Final Plata 1', '2025-03-03 12:44:44', '2025-03-03 12:44:44'),
(9, 33, 2, 4, NULL, NULL, 9, 10, 'Cuartos de Final Plata 2', '2025-03-03 12:44:44', '2025-03-03 12:44:44'),
(10, 34, 3, 1, NULL, NULL, 9, 10, 'Cuartos de Final Plata 3', '2025-03-03 12:44:44', '2025-03-03 12:44:44'),
(11, 35, 4, 2, NULL, NULL, 9, 10, 'Cuartos de Final Plata 4', '2025-03-03 12:44:44', '2025-03-03 12:44:44'),
(12, 36, NULL, NULL, 32, 34, NULL, NULL, 'Semi Final Plata 1', '2025-03-03 12:44:44', '2025-03-03 12:44:44'),
(13, 37, NULL, NULL, 33, 35, NULL, NULL, 'Semi Final Plata 2', '2025-03-03 12:44:44', '2025-03-03 12:44:44'),
(14, 38, NULL, NULL, 36, 37, NULL, NULL, 'Final Plata 1', '2025-03-03 12:44:44', '2025-03-03 12:44:44'),
(15, 57, 5, 6, NULL, NULL, 1, 2, 'Triangular1 Partido1 Oro', '2025-03-03 16:44:32', '2025-03-03 16:44:32'),
(16, 58, 7, 5, NULL, NULL, 2, 1, 'Triangular1 Partido2 Oro', '2025-03-03 16:44:32', '2025-03-03 16:44:32'),
(17, 59, 6, 7, NULL, NULL, 2, 2, 'Triangular1 Partido3 Oro', '2025-03-03 16:44:32', '2025-03-03 16:44:32'),
(18, 60, 6, 7, NULL, NULL, 1, 1, 'Triangular2 Partido1 Oro', '2025-03-03 16:44:32', '2025-03-03 16:44:32'),
(19, 61, 5, 6, NULL, NULL, 2, 1, 'Triangular2 Partido2 Oro', '2025-03-03 16:44:32', '2025-03-03 16:44:32'),
(20, 62, 7, 5, NULL, NULL, 1, 2, 'Triangular2 Partido3 Oro', '2025-03-03 16:44:32', '2025-03-03 16:44:32'),
(21, 63, 17, 18, NULL, NULL, 2, 2, '3y4 2do T1 vs 2do T2 Oro', '2025-03-03 16:44:32', '2025-03-03 16:44:32'),
(22, 64, 17, 18, NULL, NULL, 1, 1, 'Final 1ro T1 vs 1ro T2 Oro', '2025-03-03 16:44:32', '2025-03-03 16:44:32'),
(23, 65, 5, 6, NULL, NULL, 3, 4, 'Triangular1 Partido1 Plata', '2025-03-03 17:31:08', '2025-03-03 17:31:08'),
(24, 66, 7, 5, NULL, NULL, 4, 3, 'Triangular1 Partido2 Plata', '2025-03-03 17:31:08', '2025-03-03 17:31:08'),
(25, 67, 6, 7, NULL, NULL, 4, 4, 'Triangular1 Partido3 Plata', '2025-03-03 17:31:08', '2025-03-03 17:31:08'),
(26, 68, 6, 7, NULL, NULL, 3, 3, 'Triangular2 Partido1 Plata', '2025-03-03 17:31:08', '2025-03-03 17:31:08'),
(27, 69, 5, 6, NULL, NULL, 4, 3, 'Triangular2 Partido2 Plata', '2025-03-03 17:31:08', '2025-03-03 17:31:08'),
(28, 70, 7, 5, NULL, NULL, 3, 4, 'Triangular2 Partido3 Plata', '2025-03-03 17:31:08', '2025-03-03 17:31:08'),
(29, 71, 19, 20, NULL, NULL, 2, 2, '3y4 2do T1 vs 2do T2 Plata ', '2025-03-03 17:31:08', '2025-03-03 17:31:08'),
(30, 72, 19, 20, NULL, NULL, 1, 1, 'Final 1ro T1 vs 1ro T2 Plata', '2025-03-03 17:31:08', '2025-03-03 17:31:08'),
(31, 94, 8, 8, NULL, NULL, 1, 3, 'Semi Final Oro 1', '2025-03-03 14:37:47', '2025-03-03 14:37:47'),
(32, 95, 8, 8, NULL, NULL, 2, 4, 'Semi Final Oro 2', '2025-03-03 14:37:47', '2025-03-03 14:37:47'),
(33, 96, NULL, NULL, 94, 95, NULL, NULL, 'Final Oro 1', '2025-03-03 14:37:47', '2025-03-03 14:37:47'),
(34, 97, 8, 8, NULL, NULL, 5, 6, 'Final Plata 1', '2025-03-03 14:37:47', '2025-03-03 14:37:47'),
(35, 110, 9, 10, NULL, NULL, 1, 2, 'Semi Final Oro 1', '2025-03-03 14:39:12', '2025-03-03 14:39:12'),
(36, 111, 10, 9, NULL, NULL, 1, 1, 'Semi Final Oro 2', '2025-03-03 14:39:12', '2025-03-03 14:39:12'),
(37, 112, NULL, NULL, 110, 111, NULL, NULL, 'Final Oro 1', '2025-03-03 14:39:12', '2025-03-03 14:39:12'),
(38, 113, 9, 10, NULL, NULL, 5, 6, 'Semi Final Plata 1', '2025-03-03 14:39:12', '2025-03-03 14:39:12'),
(39, 114, 10, 9, NULL, NULL, 5, 6, 'Semi Final Plata 2', '2025-03-03 14:39:12', '2025-03-03 14:39:12'),
(40, 115, NULL, NULL, 113, 114, NULL, NULL, 'Final Plata 1', '2025-03-03 14:39:12', '2025-03-03 14:39:12'),
(41, 128, 11, 12, NULL, NULL, 1, 2, 'Semi Final Oro 1', '2025-03-03 14:58:11', '2025-03-03 14:58:11'),
(42, 129, 12, 11, NULL, NULL, 1, 2, 'Semi Final Oro 2', '2025-03-03 14:58:11', '2025-03-03 14:58:11'),
(43, 130, NULL, NULL, 128, 129, NULL, NULL, 'Final Oro 1', '2025-03-03 14:58:11', '2025-03-03 14:58:11'),
(44, 131, 11, 12, NULL, NULL, 5, 6, 'Semi Final Plata 1', '2025-03-03 14:58:11', '2025-03-03 14:58:11'),
(45, 132, 12, 11, NULL, NULL, 5, 6, 'Semi Final Plata 2', '2025-03-03 14:58:11', '2025-03-03 14:58:11'),
(46, 133, NULL, NULL, 131, 132, NULL, NULL, 'Final Plata 1', '2025-03-03 14:58:11', '2025-03-03 14:58:11');

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
(1, 1, 'Club Villa Dora', 'Ruperto Godoy 1231', '2024-12-19 19:34:49', '2024-12-19 19:34:49'),
(2, 1, 'Club Regatas Santa Fe', 'Av. Leandro N. Alem 3288', '2024-12-19 19:34:49', '2024-12-19 19:34:49'),
(3, 2, 'Sede 1 ', 'Calle 1234', '2024-12-24 09:53:08', '2024-12-24 09:53:08');

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
(1, 1, 'XIV Torneo Sudamericano de Master Voley Santa Fe', 'xiv-sudamericano-master-voley-sf', 'XIV Torneo Sudamericano de Master Voley Santa Fe', '2024-01-02 00:00:00', '2024-05-01 19:00:00', '2024-05-03 18:00:00', '2024-05-05 23:00:00', NULL, '2024-12-19 19:34:49', '2025-01-13 20:09:33', 'Borrador'),
(2, 2, 'Torneo 2 Administrador 2', 'torneo2_admin2', 'Torneo organizado por administrador 2', '2024-12-24 09:52:00', '2024-12-24 09:53:00', '2024-12-24 09:54:00', '2024-12-24 09:55:00', NULL, '2024-12-24 09:53:08', '2024-12-24 09:53:08', 'Borrador');

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
) ;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `username`, `roles`, `password`, `email`, `nombre`, `apellido`, `created_at`, `updated_at`) VALUES
(1, 'admin1', '[\"ROLE_ADMIN\"]', '$2y$13$DAUmTu9sjOdfLXinKH2oGuTj5QorVd4X3OKh4uGKY76VgRQ7JyFIK', 'admin1@correo.com', 'Administrador1', 'Administrador1', '2024-12-19 19:34:49', '2024-12-19 19:35:02'),
(2, 'admin2', '[\"ROLE_ORGANIZADOR\"]', '$2y$13$elOzPz7eK5a4AskWUFetSeUsAAkrHwSIdeKO02xE6h98FMv0jR2LW', 'admin2@admin.com', 'Administrador2', 'Administrador2', '2024-12-24 09:48:08', '2025-01-08 16:24:01');

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
  ADD KEY `IDX_4E79750B9C833003` (`grupo_id`),
  ADD KEY `IDX_4E79750B88774E73` (`equipo_local_id`),
  ADD KEY `IDX_4E79750B8C243011` (`equipo_visitante_id`),
  ADD KEY `IDX_4E79750B3397707A` (`categoria_id`);

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `torneo`
--
ALTER TABLE `torneo`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

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
