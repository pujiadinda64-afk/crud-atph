-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 14, 2026 at 06:28 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `projek`
--

-- --------------------------------------------------------

--
-- Table structure for table `guru`
--

CREATE TABLE `guru` (
  `Nip` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Mapel_Utama` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mapel utama yg diampu',
  `Wali_Kelas` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Contoh: 10 ATPh 1, 11 ATPh 2, 12 ATPh 3',
  `Foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nama file foto guru'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `guru`
--

INSERT INTO `guru` (`Nip`, `Nama`, `Mapel_Utama`, `Wali_Kelas`, `Foto`) VALUES
('045628', 'Peter Parker', 'Dasar ATPH', 'XI ATPH 2', '1789358770_download (6).jpg'),
('086757', 'Lee Dong-Hyeok', 'Dasar ATPH', 'XI ATPH 1', '1789358819_Haechan.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `guru`
--
ALTER TABLE `guru`
  ADD PRIMARY KEY (`Nip`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
