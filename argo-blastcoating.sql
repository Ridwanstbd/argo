-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 04, 2025 at 04:35 PM
-- Server version: 8.0.30
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `argo-blastcoating`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_barang`
--

CREATE TABLE `tb_barang` (
  `id_barang` int NOT NULL,
  `nama_barang` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `layanan` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `harga` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_barang`
--

INSERT INTO `tb_barang` (`id_barang`, `nama_barang`, `layanan`, `harga`, `keterangan`) VALUES
(1, 'bureng', 'vapour blasting', '75000', 'pengerjaan 5 hari'),
(2, 'kop', 'vapour blasting', '76000', 'pengerjaan 5 hari'),
(3, 'velg', 'powder coating', '75000', 'pengerjaan 1 hari');

-- --------------------------------------------------------

--
-- Table structure for table `tb_detailpesanan`
--

CREATE TABLE `tb_detailpesanan` (
  `id_detail` int NOT NULL,
  `id_pesanan` int DEFAULT NULL,
  `id_barang` int DEFAULT NULL,
  `jumlah` int DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  `catatan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_detailpesanan`
--

INSERT INTO `tb_detailpesanan` (`id_detail`, `id_pesanan`, `id_barang`, `jumlah`, `subtotal`, `catatan`) VALUES
(1, 5, 1, 2, 150000.00, NULL),
(2, 5, 2, 1, 76000.00, NULL),
(3, 6, 1, 1, 75000.00, NULL),
(4, 6, 2, 2, 152000.00, NULL),
(5, 7, 1, 1, 75000.00, NULL),
(6, 7, 3, 2, 150000.00, NULL),
(7, 8, 1, 1, 75000.00, NULL),
(8, 8, 3, 2, 150000.00, NULL),
(9, 9, 3, 2, 150000.00, 'warna merah maron'),
(10, 9, 2, 1, 76000.00, 'warna silver');

-- --------------------------------------------------------

--
-- Table structure for table `tb_klien`
--

CREATE TABLE `tb_klien` (
  `id_pemesan` int NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `no_hp` varchar(16) COLLATE utf8mb4_general_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_klien`
--

INSERT INTO `tb_klien` (`id_pemesan`, `nama`, `no_hp`, `alamat`) VALUES
(1, 'ridwan', '085700340056', 'kampung inggris'),
(2, 'hasan', '0867746989', 'kampung coklat'),
(3, 'Windu', '085704412510', 'jalan jambu'),
(4, 'Rusdi', '0812345678', 'jalan Desa Sukamundur, kec. Yali yalili kab. Hello World'),
(5, 'Rusdi', '0812345678', 'jalan Desa Sukamundur, kec. Yali yalili kab. Hello World'),
(6, 'Ridwan Setio Budi', '08123456789', 'Duwet Street, RT 06 RW 01 Duwet Village Bendo Regency'),
(7, 'Kodrad', '0812345678', 'jalan Desa Sukamundur, kec. Yali yalili kab. Hello World');

-- --------------------------------------------------------

--
-- Table structure for table `tb_pesanan`
--

CREATE TABLE `tb_pesanan` (
  `id_pesanan` int NOT NULL,
  `id_pemesan` int NOT NULL,
  `waktu_pesan` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `kstatus` varchar(50) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_pesanan`
--

INSERT INTO `tb_pesanan` (`id_pesanan`, `id_pemesan`, `waktu_pesan`, `kstatus`) VALUES
(2, 1, '2023-02-14 21:53:00', 'Dalam Antrian'),
(3, 2, '2023-02-15 18:44:00', 'dalam antrian'),
(4, 1, '2023-02-24 23:44:00', 'Dalam Antrian'),
(5, 3, '2025-01-03 14:35:00', 'Dalam Antrian'),
(6, 4, '2024-12-28 07:09:44', 'Baru'),
(7, 5, '2025-01-01 15:45:24', 'Baru'),
(8, 6, '2025-01-01 15:46:43', 'Baru'),
(9, 7, '2025-01-04 16:07:47', 'Baru');

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE `tb_user` (
  `id_user` int NOT NULL,
  `username` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(500) COLLATE utf8mb4_general_ci NOT NULL,
  `u_password` varchar(500) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`id_user`, `username`, `email`, `u_password`) VALUES
(10, 'test', 'test@example.com', '$2y$10$8qDkrJ8aFDWFzTsvstoNgO01xzddBcGzRkGcK7VexZiewrX1rdsOS');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_barang`
--
ALTER TABLE `tb_barang`
  ADD PRIMARY KEY (`id_barang`),
  ADD KEY `id_barang` (`id_barang`),
  ADD KEY `id_barang_2` (`id_barang`);

--
-- Indexes for table `tb_detailpesanan`
--
ALTER TABLE `tb_detailpesanan`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_pesanan` (`id_pesanan`),
  ADD KEY `id_barang` (`id_barang`);

--
-- Indexes for table `tb_klien`
--
ALTER TABLE `tb_klien`
  ADD PRIMARY KEY (`id_pemesan`);

--
-- Indexes for table `tb_pesanan`
--
ALTER TABLE `tb_pesanan`
  ADD PRIMARY KEY (`id_pesanan`),
  ADD KEY `id_pemesan` (`id_pemesan`);

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id_user`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_barang`
--
ALTER TABLE `tb_barang`
  MODIFY `id_barang` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tb_detailpesanan`
--
ALTER TABLE `tb_detailpesanan`
  MODIFY `id_detail` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tb_klien`
--
ALTER TABLE `tb_klien`
  MODIFY `id_pemesan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tb_pesanan`
--
ALTER TABLE `tb_pesanan`
  MODIFY `id_pesanan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_detailpesanan`
--
ALTER TABLE `tb_detailpesanan`
  ADD CONSTRAINT `tb_detailpesanan_ibfk_1` FOREIGN KEY (`id_pesanan`) REFERENCES `tb_pesanan` (`id_pesanan`),
  ADD CONSTRAINT `tb_detailpesanan_ibfk_2` FOREIGN KEY (`id_barang`) REFERENCES `tb_barang` (`id_barang`);

--
-- Constraints for table `tb_pesanan`
--
ALTER TABLE `tb_pesanan`
  ADD CONSTRAINT `tb_pesanan_ibfk_2` FOREIGN KEY (`id_pemesan`) REFERENCES `tb_klien` (`id_pemesan`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
