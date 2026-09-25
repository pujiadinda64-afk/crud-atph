-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 20, 2026 at 06:47 AM
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
-- Table structure for table `alat`
--

CREATE TABLE `alat` (
  `id_alat` int NOT NULL,
  `nama_alat` varchar(100) NOT NULL,
  `jumlah_stok` int NOT NULL,
  `kondisi` varchar(50) NOT NULL,
  `foto` varchar(255) DEFAULT 'default.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `foto_kegiatan`
--

CREATE TABLE `foto_kegiatan` (
  `id_foto` int NOT NULL,
  `id_kegiatan` int NOT NULL,
  `nama_foto` varchar(255) NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `foto_kegiatan`
--

INSERT INTO `foto_kegiatan` (`id_foto`, `id_kegiatan`, `nama_foto`, `keterangan`) VALUES
(1, 1, '1788674014_suga1-removebg-preview.png', ''),
(11, 4, '1789881733_jibril5.jpeg', NULL),
(12, 4, '1789882496_nilong.jpg', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `guru`
--

CREATE TABLE `guru` (
  `Nip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Mapel_Utama` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mapel utama yg diampu',
  `Wali_Kelas` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Contoh: 10 ATPh 1, 11 ATPh 2, 12 ATPh 3',
  `Foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nama file foto guru'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `guru`
--

INSERT INTO `guru` (`Nip`, `Nama`, `Mapel_Utama`, `Wali_Kelas`, `Foto`) VALUES
('08956058', 'Suga Min', 'Dasar', 'XI ATPH 10', '1789531767_pa2.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal`
--

CREATE TABLE `jadwal` (
  `id_jadwal` int NOT NULL,
  `kegiatan` varchar(150) NOT NULL,
  `lokasi` varchar(100) NOT NULL,
  `tanggal` date NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `pembimbing` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kegiatan`
--

CREATE TABLE `kegiatan` (
  `id_kegiatan` int NOT NULL,
  `nama_kegiatan` varchar(255) NOT NULL,
  `deskripsi` text NOT NULL,
  `pembimbing` varchar(100) NOT NULL,
  `tanggal` date NOT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kegiatan`
--

INSERT INTO `kegiatan` (`id_kegiatan`, `nama_kegiatan`, `deskripsi`, `pembimbing`, `tanggal`, `foto`) VALUES
(1, 'Penanapan melon hidropolik', 'fkhfkfioayfkasjfufhsjfasfjk', 'Bu Dera', '2026-09-16', '1788674014_army.jpg'),
(2, 'Penyulaman Bibit', 'Melakuan Penyulaman Bibit pada saat program Magang kelas 10 ATPH 1 di Lembang, Bandung', 'Pak Sule', '2026-06-11', '1788925333_penyulaman bibit.jpeg'),
(3, 'Penyiraman Bibit', 'Melakukan penyiraman Bibit pada Saat Program Magang Kelas Industri 10 ATPH 1  ', 'Pak Sule', '2026-06-08', '1788923386_penanaman.jpeg'),
(4, 'hfjshfjkdhfsdjk', 'gfhsdgfhsdgsfgj', 'dfdwrerdgh', '2026-09-29', '1789521816_mugh.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id_pinjam` int NOT NULL,
  `id_user` int NOT NULL,
  `id_alat` int NOT NULL,
  `jumlah_pinjam` int NOT NULL,
  `tgl_pinjam` date NOT NULL,
  `tgl_kembali` date DEFAULT NULL,
  `status` enum('Menunggu','Dipinjam','Mengajukan Kembali','Selesai') DEFAULT 'Menunggu',
  `bukti_kembali` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','siswa') NOT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `kelas` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `username`, `password`, `role`, `nama_lengkap`, `email`, `kelas`) VALUES
(1234, 'siswa01', 's1sw4', 'siswa', 'Adinda Puji Lestari', 'pujiadinda64@gmail.com', 'XI ATPH 1'),
(4321, 'admin', '4dm1n', 'admin', 'Adinda Puji Lestari', 'pujiadinda64@gmail.com', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alat`
--
ALTER TABLE `alat`
  ADD PRIMARY KEY (`id_alat`);

--
-- Indexes for table `foto_kegiatan`
--
ALTER TABLE `foto_kegiatan`
  ADD PRIMARY KEY (`id_foto`),
  ADD KEY `id_kegiatan` (`id_kegiatan`);

--
-- Indexes for table `guru`
--
ALTER TABLE `guru`
  ADD PRIMARY KEY (`Nip`);

--
-- Indexes for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`id_jadwal`);

--
-- Indexes for table `kegiatan`
--
ALTER TABLE `kegiatan`
  ADD PRIMARY KEY (`id_kegiatan`);

--
-- Indexes for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id_pinjam`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_alat` (`id_alat`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alat`
--
ALTER TABLE `alat`
  MODIFY `id_alat` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `foto_kegiatan`
--
ALTER TABLE `foto_kegiatan`
  MODIFY `id_foto` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `jadwal`
--
ALTER TABLE `jadwal`
  MODIFY `id_jadwal` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kegiatan`
--
ALTER TABLE `kegiatan`
  MODIFY `id_kegiatan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id_pinjam` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4322;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `foto_kegiatan`
--
ALTER TABLE `foto_kegiatan`
  ADD CONSTRAINT `foto_kegiatan_ibfk_1` FOREIGN KEY (`id_kegiatan`) REFERENCES `kegiatan` (`id_kegiatan`) ON DELETE CASCADE;

--
-- Constraints for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `peminjaman_ibfk_2` FOREIGN KEY (`id_alat`) REFERENCES `alat` (`id_alat`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
