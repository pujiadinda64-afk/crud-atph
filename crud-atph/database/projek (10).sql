-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 15, 2026 at 01:29 PM
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

--
-- Dumping data for table `alat`
--

INSERT INTO `alat` (`id_alat`, `nama_alat`, `jumlah_stok`, `kondisi`, `foto`) VALUES
(5, 'Timbangan', 5, 'sangat baik', 'alat_1792063232_439.jpeg'),
(6, 'Slig Higrometer', 3, 'Baik', 'alat_1792063340_564.jpeg'),
(7, 'Termometer Tanah', 10, 'Baik', 'alat_1792063391_623.jpeg'),
(8, 'Roll Meteran', 4, 'Baik', 'alat_1792063437_750.jpeg'),
(9, 'Pelubang Mulsa', 3, 'Baik', 'alat_1792063486_177.jpeg'),
(10, 'Gembor', 10, 'Baik', 'alat_1792063528_742.jpeg'),
(11, 'Pemotong Rumput', 2, 'sangat baik', 'alat_1792063588_412.jpeg'),
(12, 'Sekop', 10, 'Baik', 'alat_1792063626_558.jpg'),
(13, 'Pemotong Rumput', 10, 'Baik', 'alat_1792063701_785.jpg'),
(14, 'Badeng', 20, 'Baik', 'alat_1792063738_693.jpg'),
(15, 'Selang', 4, 'Sangat Baik', 'alat_1792063768_985.jpg'),
(16, 'gerobak dorong', 5, 'Baik', 'alat_1792063850_812.jpg'),
(17, 'Mist Blower', 5, 'Baik', 'alat_1792063920_403.jpeg'),
(19, 'Power Spayer', 3, 'Baik', 'alat_1792063989_559.jpeg'),
(20, 'Hand Spayer', 10, 'Baik', 'alat_1792064053_887.jpeg'),
(21, 'Gari', 5, 'Baik', 'alat_1792064411_616.jpg'),
(22, 'Cangkul mini', 25, 'Baik', 'alat_1792064452_664.jpg'),
(23, 'cangkul', 20, 'sangat baik', 'alat_1792064519_511.jpg'),
(24, 'Celurit', 5, 'Baik', 'alat_1792064547_379.jpg'),
(25, 'Kalektor', 5, 'Sangat Baik', 'alat_1792064584_818.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `foto_kegiatan`
--

CREATE TABLE `foto_kegiatan` (
  `id_foto` int NOT NULL,
  `id_kegiatan` int NOT NULL,
  `nama_foto` varchar(255) NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `nama_file` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guru`
--

CREATE TABLE `guru` (
  `nip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Mapel_Utama` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mapel utama yg diampu',
  `Wali_Kelas` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Contoh: 10 ATPh 1, 11 ATPh 2, 12 ATPh 3',
  `Foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nama file foto guru'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `guru`
--

INSERT INTO `guru` (`nip`, `Nama`, `Mapel_Utama`, `Wali_Kelas`, `Foto`) VALUES
('19690613 200801 1 003', 'Dali Setiadi, S.P., M.P.', 'Dasar ATPH', '', '19690613 200801 1 003_1792066887.jpg'),
('19720814 202121 1 002', 'Agus Darmawan, S.P.', 'Dasar ATPH', '', '19720814 202121 1 002_1792066932.png'),
('19750518 201001 1 008', 'H. Hendi Sholahudin Amri, S.TP.', 'Dasar ATPH', '', '19750518 201001 1 008_1792067064.jpg'),
('19761003 202221 1 002', 'Ika Kusika, S.P.', 'Dasar ATPH', '', '19761003 202221 1 002_1792067088.jpg'),
('19780508 200901 1 003', 'Pendi Supendi, S.TP.', 'Dasar ATPH', '', '19780508 200901 1 003_1792067241.jpg'),
('19790418 200801 2 008', 'Dede Rasih, S.Pd.', 'Dasar ATPH', 'XI ATPH  1', '19790418 200801 2 008_1792067045.jpg'),
('19811013 200901 1 004', 'Dede Sukmana, S.Pd.', 'Dasar ATPH', '', '19811013 200901 1 004_1792067004.jpg'),
('19860414 202221 2 024', 'Sri Nurlianti, S.P.', 'Dasar ATPH', '', '19860414 202221 2 024_1792066983.jpg'),
('19910026 202521 1 151', 'Suryaman, S.P., M.P.', 'Dasar ATPH', '', '19910026 202521 1 151_1792066968.jpg'),
('19940319 202221 2 015', 'Titin Nurhotimah, S.P.', 'Dasar ATPH', '', '19940319 202221 2 015_1792066951.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_lapang`
--

CREATE TABLE `jadwal_lapang` (
  `id` int NOT NULL,
  `kelas` tinyint NOT NULL,
  `rombel` tinyint NOT NULL,
  `kegiatan` varchar(255) NOT NULL,
  `lokasi` varchar(255) NOT NULL,
  `tanggal` date NOT NULL,
  `jam` varchar(50) NOT NULL,
  `pembimbing` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `jadwal_lapang`
--

INSERT INTO `jadwal_lapang` (`id`, `kelas`, `rombel`, `kegiatan`, `lokasi`, `tanggal`, `jam`, `pembimbing`) VALUES
(2, 10, 3, 'Penanaman bibit melon', 'Lahan blok B', '2026-10-19', '07.30-09.10', 'Ibu Dede'),
(4, 10, 3, 'Penanaman kacang tanah', 'Lahan blok B', '2026-10-26', '08.10-10.30', 'Ibu Dede'),
(6, 10, 3, 'Panen sawi', 'Greenhouse', '2026-10-23', '09.00-11.35', 'Bapak Pendi'),
(7, 11, 2, 'Penanaman bibit melon', 'Greenhouse', '2026-10-28', '08.10-10.30', 'Pak Sule'),
(9, 12, 2, 'Praktik Hidroponik Sawi', 'Greenhouse', '2026-10-29', '07.30-09.10', 'Pak Sule'),
(10, 10, 2, 'Panen sawi', 'Greenhouse', '2026-10-28', '08.10-10.30', 'Ibu Dede Rasih'),
(12, 10, 2, 'Panen Ubi Jalar', 'Lahan Blok C', '2026-10-30', '07.30-09.10', 'Bpk. Pendi Supendi, S.TP'),
(13, 11, 5, 'Panen Cabai', 'Lahan Blok A', '2026-10-22', '08.30-10.50', 'Ibu Dede Rasih'),
(14, 10, 3, 'Panen sawi', 'Greenhouse', '2026-10-30', '09.00-11.35', 'Bpk. Pendi Supendi, S.TP');

-- --------------------------------------------------------

--
-- Table structure for table `jurnal_praktikum`
--

CREATE TABLE `jurnal_praktikum` (
  `id_jurnal` int NOT NULL,
  `id_user` int DEFAULT NULL,
  `nama_kegiatan` varchar(255) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `keterangan` text,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `jurnal_praktikum`
--

INSERT INTO `jurnal_praktikum` (`id_jurnal`, `id_user`, `nama_kegiatan`, `tanggal`, `keterangan`, `foto`, `created_at`) VALUES
(1, 1234, 'Penanapan melon hidropolik', '2026-10-02', 'memanen melon', '6ac59f9980f9a.png', '2026-10-07 01:25:45'),
(2, 1234, 'Penanapan melon hidropolik', '2026-10-02', 'memanen melon', '6ac5a05e9d158.png', '2026-10-07 01:29:02'),
(3, 1234, 'Program Magang Solo', '2026-10-01', 'gjhf', '6ac5a08359cd3.png', '2026-10-07 01:29:39'),
(4, 1234, 'Penyiraman Bibit', '2026-10-01', 'jskjskajksj', '6ac5efef40c62.jpg', '2026-10-07 07:08:31'),
(5, 34325, 'Penanapan melon hidropolik', '2026-10-01', 'fsfsfd', '6ac76b7ca5fed.jpg', '2026-10-08 10:07:56'),
(6, 34325, 'Penanapan melon hidropolik', '2026-10-01', 'fsfsfd', '6ac76b9269699.jpg', '2026-10-08 10:08:18'),
(7, 34326, 'Program Magang Solo', '2026-10-02', 'dfgdg', '6ac76e515fd00.jpg', '2026-10-08 10:20:01'),
(8, 34325, 'Penanapan melon hidropolik', '2026-10-15', 'jkjgjhjh', '6ad0aa2b3d0ea.jpeg', '2026-10-15 10:25:47');

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
(1, 'Pemeliharaan tanaman melon', 'Kegiatan pemeliharaan rutin tanaman di dalam greenhouse meliputi pengecekan pertumbuhan, perataan/pengikatan sulur tanaman, serta pemantauan kondisi media tanam dalam polybag. Praktik ini bertujuan untuk memastikan tanaman tumbuh optimal dan terhindar dari paparan hama luar secara terkontrol.', 'Bu Dera', '2026-08-27', '1791947076_at.jpeg'),
(2, 'Penyulaman Bibit', 'Melakuan Penyulaman Bibit pada saat program Magang kelas 10 ATPH 1 di Lembang, Bandung', 'Pak Sule', '2026-06-11', '1788925333_penyulaman bibit.jpeg'),
(3, 'Program Magang Lembang', 'Melakukan penyiraman Bibit pada Saat Program Magang Kelas Industri 10 ATPH 1  ', 'Pak Sule', '2026-06-08', '1788923386_penanaman.jpeg'),
(5, 'Program Magang Solo', 'Program Magang Solo merupakan program magang yang diselenggarakan oleh Framhill selama enam bulan, mulai dari Desember hingga Juni. Program ini ditujukan bagi siswa kelas X dan XI yang telah melalui proses seleksi. Melalui kegiatan ini, peserta diharapkan dapat memperoleh pengalaman kerja, meningkatkan keterampilan, serta mengembangkan kemampuan dan wawasan di bidang yang relevan.', 'Pak Hani', '2025-12-16', '1791941078_gng.jpeg'),
(6, 'Penyiraman Tanaman', 'Kegiatan praktik budidaya tanaman di greenhouse merupakan bagian dari pembelajaran pertanian yang berfokus pada perawatan dan pemeliharaan tanaman. Peserta melakukan perawatan tanaman, termasuk penyemprotan, pemantauan pertumbuhan, serta menjaga kondisi tanaman agar tumbuh dengan baik. Kegiatan ini bertujuan untuk meningkatkan keterampilan praktik dan pemahaman peserta mengenai teknik budidaya tanaman di lingkungan greenhouse.', 'Bu Dera', '2026-09-23', '1791946347_skll.jpeg'),
(7, 'Pemeliharaan Bibit Melon', 'Kegiatan pemeliharaan bibit melon (cucumis melo) pada tahap pembibitan di greenhouse. Praktik ini meliputi penyiraman bibit secara berkala menggunakan gayung/alat siram khusus untuk menjaga kelembapan media tanam polybag/tray, serta memantau kesehatan tanaman muda agar siap dipindah tanam.', 'Pak Hendi', '2026-09-10', '1791946658_ob.jpeg'),
(19, 'Pemipilan Jagung untuk dijadikan Pakan', 'Kegiatan praktik kerja lapangan pengolahan hasil pertanian (ATPH) dalam rangka persiapan dan pembuatan pakan ternak berbasis bahan baku jagung berkualitas. Melalui proses penyiapan dan pemilihan tongkol jagung ini, siswa dilatih untuk memahami tahapan formulasi pakan secara mandiri dan berkelanjutan untuk kebutuhan unit produksi agribisnis.', 'Pak Dede Sukmana', '2026-09-15', '1792069521_pmpln.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id_pinjam` int NOT NULL,
  `nama_peminjam` varchar(100) NOT NULL,
  `id_alat` int NOT NULL,
  `jumlah_pinjam` int NOT NULL,
  `tgl_pinjam` date NOT NULL,
  `tgl_kembali` date DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Pending',
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
  `kelas` varchar(50) DEFAULT NULL,
  `poin` int DEFAULT '0',
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `username`, `password`, `role`, `nama_lengkap`, `email`, `kelas`, `poin`, `foto`) VALUES
(2341, 'admin2', '4dm1n2', 'admin', 'Trisha silvia j', 'trsslvia@gmail.com', NULL, 0, NULL),
(4123, 'admin4', '4dm1n4', 'admin', 'Yenni', 'yeen@gmail.com', NULL, 0, NULL),
(4321, 'admin', '4dm1n', 'admin', 'Adinda Puji Lestari', 'pujiadinda64@gmail.com', NULL, 0, NULL),
(34321, 'admin3', '4dm1n3', 'admin', 'Rafitra ', 'rafitra@gmail.com', NULL, 0, NULL),
(34323, 'siswa1', '4gr0smk', 'siswa', 'virgi', NULL, 'XII ATPH 2', 0, NULL),
(34325, 'siswa', '4gr0smk', 'siswa', 'Adinda', 'pujiadinda64@gmail.com', 'XI ATPH 1', 150, NULL),
(34326, 'siswa2', '4gr0smk', 'siswa', 'Trisha silvia', NULL, 'XI ATPH 2', 50, NULL);

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
  ADD PRIMARY KEY (`nip`);

--
-- Indexes for table `jadwal_lapang`
--
ALTER TABLE `jadwal_lapang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_kelas_rombel` (`kelas`,`rombel`);

--
-- Indexes for table `jurnal_praktikum`
--
ALTER TABLE `jurnal_praktikum`
  ADD PRIMARY KEY (`id_jurnal`);

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
  ADD KEY `id_user` (`nama_peminjam`),
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
  MODIFY `id_alat` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `foto_kegiatan`
--
ALTER TABLE `foto_kegiatan`
  MODIFY `id_foto` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `jadwal_lapang`
--
ALTER TABLE `jadwal_lapang`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `jurnal_praktikum`
--
ALTER TABLE `jurnal_praktikum`
  MODIFY `id_jurnal` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `kegiatan`
--
ALTER TABLE `kegiatan`
  MODIFY `id_kegiatan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id_pinjam` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34327;

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
