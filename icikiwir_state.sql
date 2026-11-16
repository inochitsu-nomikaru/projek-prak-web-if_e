-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 14, 2026 at 06:54 AM
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
-- Database: `icikiwir_state`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `username` varchar(20) NOT NULL,
  `password` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`username`, `password`) VALUES
('admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `detail_pesanan`
--

CREATE TABLE `detail_pesanan` (
  `id_detail` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `req` varchar(100) NOT NULL,
  `id_menu` int(11) NOT NULL,
  `id_custom` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_pesanan`
--

INSERT INTO `detail_pesanan` (`id_detail`, `jumlah`, `req`, `id_menu`, `id_custom`) VALUES
(1, 2, 'Pedas pol', 6, 1),
(2, 2, 'Es banyak', 1, 1),
(3, 1, 'keren mas', 1, 2),
(4, 1, 'keren mas', 8, 2),
(5, 1, 'icikiwir', 1, 3),
(6, 1, 'yang ganteng', 2, 4),
(7, 1, 'yang ganteng', 6, 4),
(8, 1, 'yang seger mas', 3, 5),
(9, 1, 'yang seger mas', 4, 5),
(10, 1, 'yang seger mas', 5, 5);

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id_menu` int(11) NOT NULL,
  `nama` varchar(20) NOT NULL,
  `desk` varchar(200) NOT NULL,
  `harga` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id_menu`, `nama`, `desk`, `harga`) VALUES
(1, 'Es Teh', 'Teh manis yang menyegarkan ketika haus.', 5000),
(2, 'Es Mambo', 'Es lilin dengan rasa random yang mengejutkan.', 5000),
(3, 'Soda Gembira', 'Perpaduan antara susu kental manis, sirup dan soda.', 8000),
(4, 'Es Buah', 'Es dengan isi buah buahan yang lengkap dan beragam.', 10000),
(5, 'Es Durian', 'Minuman dengan rasa durian yang bikin nagih.', 12000),
(6, 'Mie Slebew (1)', 'Mie dengan kepedasan level 1.', 10000),
(7, 'Mie Jebret (2)', 'Mie dengan kepedasan level 2.', 10500),
(8, 'Mie Huhah (3)', 'Mie dengan kepedasan level 3.', 11000),
(9, 'Mie Setan (4)', 'Mie dengan kepedasan level 4.', 11500),
(10, 'Mie Iblis (5)', 'Mie dengan kepedasan level 5.', 12000),
(11, 'Siomay', 'Adonan dari daging dan tepung yang gurih.', 8000),
(12, 'Mendoan', 'Tempe yang di goreng setengah matang.', 8000),
(13, 'Cireng', 'Aci yang di goreng ditemani dengan sambal asam.', 8000);

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id_custom` int(11) NOT NULL,
  `nama_custom` varchar(20) NOT NULL,
  `nomor_meja` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `tanggal` datetime NOT NULL,
  `selesai` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id_custom`, `nama_custom`, `nomor_meja`, `email`, `tanggal`, `selesai`) VALUES
(1, 'Agus Icikiwir', 7, 'agus@gejesgejes.com', '2026-11-13 16:41:10', 1),
(2, 'rell', 1, 'inochitsurell@gmail.com', '2026-11-14 02:57:18', 1),
(3, 'ino', 2, 'brianthio1503@gmail.com', '2026-11-14 04:30:05', 1),
(4, 'inos', 1, 'rell@gmail.com', '2026-11-14 04:30:40', 1),
(5, 'rog', 9, 'asus@gmail.com', '2026-11-14 04:34:57', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_custom` (`id_custom`),
  ADD KEY `id_menu` (`id_menu`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id_menu`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id_custom`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id_custom` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD CONSTRAINT `detail_pesanan_ibfk_1` FOREIGN KEY (`id_custom`) REFERENCES `pelanggan` (`id_custom`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_pesanan_ibfk_2` FOREIGN KEY (`id_menu`) REFERENCES `menu` (`id_menu`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
