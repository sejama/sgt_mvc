-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 03-05-2025 a las 02:16:14
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
(32, 1, 1, NULL, 8, 4, '2025-05-03 11:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-28 23:56:25', '2025-05-02 20:30:58', 32),
(33, 1, 1, NULL, 14, 8, '2025-05-03 13:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-28 23:56:25', '2025-05-02 20:30:59', 33),
(34, 2, 1, NULL, 10, 11, '2025-05-03 15:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-28 23:57:20', '2025-05-02 20:30:59', 34),
(35, 2, 1, NULL, 2, 10, '2025-05-03 11:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-28 23:57:20', '2025-05-02 20:31:00', 35),
(36, 2, 1, NULL, 11, 2, '2025-05-03 13:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-28 23:57:20', '2025-05-02 20:31:00', 36),
(37, 6, 1, NULL, 1, 15, '2025-05-03 15:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 00:01:43', '2025-05-02 20:31:01', 37),
(38, 6, 1, NULL, 7, 1, '2025-05-03 11:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 00:01:43', '2025-05-02 20:31:01', 38),
(39, 6, 1, NULL, 15, 7, '2025-05-03 13:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 00:01:43', '2025-05-02 20:31:02', 39),
(40, 7, 1, NULL, 6, 12, '2025-05-03 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 00:01:43', '2025-05-02 20:31:02', 40),
(41, 7, 1, NULL, 5, 6, '2025-05-03 13:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 00:01:43', '2025-05-02 20:31:03', 41),
(42, 7, 1, NULL, 12, 5, '2025-05-03 15:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 00:01:43', '2025-05-02 20:31:03', 42),
(43, 7, 1, NULL, 3, 9, '2025-05-03 16:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 00:04:32', '2025-05-02 20:31:04', 43),
(44, 6, 1, NULL, 13, 3, '2025-05-03 12:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 00:04:32', '2025-05-02 20:31:04', 44),
(45, 7, 1, NULL, 9, 13, '2025-05-03 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Clasificatorio', '2025-04-29 00:04:32', '2025-05-02 20:31:05', 45),
(46, 1, 1, NULL, NULL, NULL, '2025-05-03 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-04-29 00:13:00', '2025-05-02 10:18:36', 46),
(47, 7, 1, NULL, NULL, NULL, '2025-05-03 19:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-04-29 00:13:00', '2025-05-02 13:15:03', 47),
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
(84, 2, 2, NULL, 17, 27, '2025-05-03 16:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-04-29 09:12:36', '2025-05-02 22:25:28', 84),
(85, 6, 2, NULL, 22, 26, '2025-05-03 16:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-04-29 09:12:36', '2025-05-02 22:25:28', 85),
(86, 2, 2, NULL, 16, 23, '2025-05-03 14:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-04-29 12:14:13', '2025-05-02 22:25:29', 86),
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
(102, 1, 3, NULL, 29, 31, '2025-05-03 12:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-04-29 09:22:30', '2025-05-02 22:28:35', 102),
(103, 2, 3, NULL, 34, 33, '2025-05-03 12:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-04-29 09:22:30', '2025-05-02 22:28:35', 103),
(104, 2, 3, NULL, NULL, NULL, '2025-05-03 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-04-29 09:22:30', '2025-05-02 10:26:12', 104),
(105, 6, 3, NULL, 30, 32, '2025-05-03 14:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-04-29 09:22:30', '2025-05-02 22:28:36', 105),
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
(140, 3, 5, NULL, 43, 44, '2025-05-03 14:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-04-29 09:35:26', '2025-05-02 22:33:30', 140),
(141, 3, 5, NULL, 46, 42, '2025-05-03 15:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-04-29 09:35:26', '2025-05-02 22:33:30', 141),
(142, 3, 5, NULL, NULL, NULL, '2025-05-03 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-04-29 09:35:26', '2025-05-02 10:42:23', 142),
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
(159, 3, 6, NULL, 49, 51, '2025-05-03 12:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-04-29 09:44:16', '2025-05-02 22:56:16', 159),
(160, 3, 6, NULL, 50, 53, '2025-05-03 13:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-04-29 09:44:16', '2025-05-02 22:54:54', 160),
(161, 3, 6, NULL, NULL, NULL, '2025-05-03 16:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-04-29 09:44:16', '2025-05-02 10:41:53', 161),
(162, 7, 6, NULL, NULL, NULL, '2025-05-03 11:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-04-29 12:47:31', '2025-05-02 12:55:55', 162),
(163, 7, 6, NULL, NULL, NULL, '2025-05-03 12:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-04-29 12:47:31', '2025-05-02 12:56:11', 163),
(164, 6, 6, NULL, NULL, NULL, '2025-05-03 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-04-29 12:47:31', '2025-05-02 12:56:23', 164),
(165, 3, 5, NULL, 44, 45, '2025-05-03 11:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Programado', 'Eliminatorio', '2025-05-02 13:03:00', '2025-05-02 22:33:31', 165);

--
-- Índices para tablas volcadas
--

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
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `partido`
--
ALTER TABLE `partido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=166;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `partido`
--
ALTER TABLE `partido`
  ADD CONSTRAINT `FK_4E79750B3397707A` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id`),
  ADD CONSTRAINT `FK_4E79750B7997F36E` FOREIGN KEY (`cancha_id`) REFERENCES `cancha` (`id`),
  ADD CONSTRAINT `FK_4E79750B88774E73` FOREIGN KEY (`equipo_local_id`) REFERENCES `equipo` (`id`),
  ADD CONSTRAINT `FK_4E79750B8C243011` FOREIGN KEY (`equipo_visitante_id`) REFERENCES `equipo` (`id`),
  ADD CONSTRAINT `FK_4E79750B9C833003` FOREIGN KEY (`grupo_id`) REFERENCES `grupo` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
