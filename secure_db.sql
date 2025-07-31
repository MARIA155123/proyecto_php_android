-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 31, 2025 at 04:07 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `secure_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `archivos`
--

CREATE TABLE `archivos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `nombre_archivo` varchar(255) NOT NULL,
  `ruta_archivo` varchar(255) NOT NULL,
  `tipo_archivo` varchar(100) DEFAULT NULL,
  `tamano` int(11) DEFAULT NULL,
  `subido_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `archivos`
--

INSERT INTO `archivos` (`id`, `usuario_id`, `nombre_archivo`, `ruta_archivo`, `tipo_archivo`, `tamano`, `subido_en`) VALUES
(1, 0, 'Captura de pantalla (1).png', 'uploads/68558d2436d9f.png', 'image/png', 112586, '2025-06-20 16:32:36'),
(2, 0, 'Captura de pantalla 2025-06-22 131733.png', 'uploads/685991874406a.png', 'image/png', 1138, '2025-06-23 17:40:23'),
(3, 0, 'proyectoEmu.pdf', 'uploads/685991b956420.pdf', 'application/pdf', 494888, '2025-06-23 17:41:13'),
(4, 12, 'constancia.pdf', 'uploads/685b8f6ab76f4.pdf', 'application/pdf', 114930, '2025-06-25 05:55:54'),
(5, 12, 'Captura de pantalla (4).png', 'uploads/6861d836dd8ca.png', 'image/png', 249639, '2025-06-30 00:20:06'),
(6, 12, 'Captura de pantalla (4).png', 'uploads/687bf2e0c6f9f.png', 'image/png', 249639, '2025-07-19 19:32:48');

-- --------------------------------------------------------

--
-- Table structure for table `tokens`
--

CREATE TABLE `tokens` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expiracion` datetime NOT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tokens`
--

INSERT INTO `tokens` (`id`, `usuario_id`, `token`, `expiracion`, `creado_en`) VALUES
(8, 13, 'd9b081e0097e841ac6f62d0197fa1faf', '2025-06-25 08:04:54', '2025-06-25 05:59:54'),
(15, 12, '359ef3c956d7636d4f1760715b23ce9f', '2025-07-19 21:37:34', '2025-07-19 19:32:34');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `intentos_fallidos` int(11) DEFAULT 0,
  `bloqueado_hasta` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `email`, `password`, `creado_en`, `intentos_fallidos`, `bloqueado_hasta`) VALUES
(6, 'mari', 'marycolin254@gmail.com', '$2y$10$X1TG2tpUuzF.uQLz27WnkuvFwWm.BP7Vwgb0ed4JLx/I.LQLayWQW', '2025-06-02 08:42:38', 0, NULL),
(7, 'osiris', 'osiris@gmail.com', '$2y$10$K6M4fLwhilKjxy4MfFksq.I97CumQKdlorzt/6M3fynzQDI2t24xy', '2025-06-02 17:17:37', 0, NULL),
(8, 'valentina', 'valentina@gmail.com', '$2y$10$mkVvxTwNZUN2EbfZ6YInMOSDfg.DPqBL22Vtxietr9ISUcD7h0HGa', '2025-06-02 17:25:57', 0, NULL),
(9, 'gael', 'gaelcolin@gmail.com', '$2y$10$GLZNpkh9EFsyYjRWFkFUh.LlPZ1kkvI/20CvU0ioNo.TajOMuAi1O', '2025-06-19 19:40:01', 0, NULL),
(12, 'antonio', 'antonio@gmail.com', '$2y$10$pCYf3G9CrCDecENOLOyzTOLSSh8C9KP8Zr6E917zJSlPoJ8EYdfgG', '2025-06-25 04:44:57', 0, NULL),
(13, 'fuljen', 'fuljen@gmail.com', '$2y$10$SEaZyljv6gGXPmm5PtVUCuTYlXYIK4ftr4Y/FcC8jEg9Ex2SGuYgW', '2025-06-25 05:59:54', 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `archivos`
--
ALTER TABLE `archivos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indexes for table `tokens`
--
ALTER TABLE `tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `archivos`
--
ALTER TABLE `archivos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tokens`
--
ALTER TABLE `tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tokens`
--
ALTER TABLE `tokens`
  ADD CONSTRAINT `tokens_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
