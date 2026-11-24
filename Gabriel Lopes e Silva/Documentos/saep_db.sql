-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 24, 2026 at 01:53 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `saep_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `agendamento`
--

CREATE TABLE `agendamento` (
  `id_agendamento` int(11) NOT NULL,
  `data` date NOT NULL,
  `id_tutor` int(11) NOT NULL,
  `id_pet` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `agendamento`
--

INSERT INTO `agendamento` (`id_agendamento`, `data`, `id_tutor`, `id_pet`) VALUES
(5, '2026-11-30', 1, 1),
(3, '2026-11-29', 2, 7),
(7, '2026-11-27', 3, 3),
(9, '2026-12-01', 3, 9);

-- --------------------------------------------------------

--
-- Table structure for table `pet`
--

CREATE TABLE `pet` (
  `id_pet` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `especie` varchar(50) NOT NULL,
  `id_tutor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pet`
--

INSERT INTO `pet` (`id_pet`, `nome`, `especie`, `id_tutor`) VALUES
(7, 'Bolinha', 'Gato', 2),
(3, 'Jerry', 'Jabuti', 3),
(1, 'Kyara', 'Cachorro', 1),
(5, 'Milly', 'Gato', 2),
(9, 'Sonic', 'Ouriço', 3);

-- --------------------------------------------------------

--
-- Table structure for table `tutor`
--

CREATE TABLE `tutor` (
  `id_tutor` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `cpf` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tutor`
--

INSERT INTO `tutor` (`id_tutor`, `nome`, `cpf`, `senha`) VALUES
(1, 'José', '$2y$10$lwvmiOzfPD/A3R.0lK5Qx.yvMK33Uu95CVeSqIeTeDCA04x9ySEta', '$2y$10$u6MJklfhn6VtMtcnp.WFB.bCTm35McpAV3aIaMUqJBnGyWMypo1DS'),
(2, 'Marcos', '$2y$10$g3w8L1fO4FATKnu5OkL1xeBIEgAlC.711i9A2/S34wmU506MqPJ3y', '$2y$10$KqFilqR5E9gCjXWaMCyIxuC92YAvV6bwCIdGnEuEAKR2F13mrCaEq'),
(3, 'Paulo', '$2y$10$xvRICSekxOji/EEtrgKWFeVVIaSJRW5rM3Amp6N3ACWZeaEjJm5bC', '$2y$10$oRo2D/P91c0qfNfn6QO3JOpboXUeWY3OxlidW3sUjHbksQ0nyaeJ.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `agendamento`
--
ALTER TABLE `agendamento`
  ADD PRIMARY KEY (`id_agendamento`),
  ADD UNIQUE KEY `uc_agendamento_data` (`data`),
  ADD UNIQUE KEY `uc_agendamento_completo` (`id_tutor`,`id_pet`,`data`),
  ADD KEY `fk_agendamento_pet_tutor` (`id_pet`,`id_tutor`);

--
-- Indexes for table `pet`
--
ALTER TABLE `pet`
  ADD PRIMARY KEY (`id_pet`),
  ADD UNIQUE KEY `uc_pet_tutor` (`nome`,`especie`,`id_tutor`),
  ADD UNIQUE KEY `idx_pet_tutor` (`id_pet`,`id_tutor`),
  ADD KEY `fk_pet_tutor` (`id_tutor`);

--
-- Indexes for table `tutor`
--
ALTER TABLE `tutor`
  ADD PRIMARY KEY (`id_tutor`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `agendamento`
--
ALTER TABLE `agendamento`
  MODIFY `id_agendamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `pet`
--
ALTER TABLE `pet`
  MODIFY `id_pet` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tutor`
--
ALTER TABLE `tutor`
  MODIFY `id_tutor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `agendamento`
--
ALTER TABLE `agendamento`
  ADD CONSTRAINT `fk_agendamento_pet` FOREIGN KEY (`id_pet`) REFERENCES `pet` (`id_pet`),
  ADD CONSTRAINT `fk_agendamento_pet_tutor` FOREIGN KEY (`id_pet`,`id_tutor`) REFERENCES `pet` (`id_pet`, `id_tutor`),
  ADD CONSTRAINT `fk_agendamento_tutor` FOREIGN KEY (`id_tutor`) REFERENCES `tutor` (`id_tutor`);

--
-- Constraints for table `pet`
--
ALTER TABLE `pet`
  ADD CONSTRAINT `fk_pet_tutor` FOREIGN KEY (`id_tutor`) REFERENCES `tutor` (`id_tutor`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
