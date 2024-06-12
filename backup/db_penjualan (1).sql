-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 10, 2024 at 12:25 PM
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
-- Database: `db_penjualan`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin`
--

CREATE TABLE `tbl_admin` (
  `id_admin` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_admin`
--

INSERT INTO `tbl_admin` (`id_admin`, `username`, `email`, `password`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$10$mxz1arLcnB1QOYL7oiYyweZ/P.XM4CZSCravBRpVWe1bK5qtoTl0i');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_alamat`
--

CREATE TABLE `tbl_alamat` (
  `id_alamat` int NOT NULL,
  `alamat` text NOT NULL,
  `ongkir` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_alamat`
--

INSERT INTO `tbl_alamat` (`id_alamat`, `alamat`, `ongkir`) VALUES
(1, 'Simpang Len', 5000);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_keranjang`
--

CREATE TABLE `tbl_keranjang` (
  `id_keranjang` int NOT NULL,
  `id_pengguna` int DEFAULT NULL,
  `id_produk` int DEFAULT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_keranjang`
--

INSERT INTO `tbl_keranjang` (`id_keranjang`, `id_pengguna`, `id_produk`, `status`) VALUES
(16, 9, 13, 'pending'),
(17, 9, 13, 'pending'),
(18, 9, 13, 'pending'),
(19, 9, 13, 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pembelian`
--

CREATE TABLE `tbl_pembelian` (
  `id_pembelian` int NOT NULL,
  `kode_transaksi` varchar(10) NOT NULL,
  `id_produk` int NOT NULL,
  `id_pengguna` int NOT NULL,
  `jumlah` int NOT NULL,
  `total_harga` int NOT NULL,
  `status_pembelian` varchar(50) DEFAULT 'Diproses',
  `status_pembayaran` varchar(20) NOT NULL,
  `tanggal_pembelian` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pengguna`
--

CREATE TABLE `tbl_pengguna` (
  `id_pengguna` int NOT NULL,
  `nama_pengguna` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `no_telepon` varchar(15) DEFAULT NULL,
  `id_alamat` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_pengguna`
--

INSERT INTO `tbl_pengguna` (`id_pengguna`, `nama_pengguna`, `email`, `password`, `no_telepon`, `id_alamat`) VALUES
(9, 'budi', 'budiman1@gmail.com', '$2y$10$VQBcHfrkHZZwJUXUQNoqf.elOv2t7A86C6GLy7iI.6H7tABVVe0li', '082165443677', 1),
(10, 'budi', 'budi@gmail.com', '$2y$10$YvLCIuD4jilOJgc6JCz.q.MAw.eIagP2efLKTsZJ5QP0Fom3jDsxm', '082165446778', 1),
(12, 'budi', 'budi1@gmail.com', '$2y$10$zYycTsvCAfrfDpI1REIfy.hW9JjyBoIlGh2Wr5mHAE018fQ4.kNsq', '12121212', 1),
(14, 'tia', '', '$2y$10$x5wxZgvQwN4Tg.12B.DOcuMm80fgmlQnzREft4CYbWgDVaYIsrQLO', '12345678', 1),
(15, 'sari', 'sari@gmail.com', '$2y$10$VQBcHfrkHZZwJUXUQNoqf.elOv2t7A86C6GLy7iI.6H7tABVVe0li', '12345678', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_penjualan`
--

CREATE TABLE `tbl_penjualan` (
  `id_pembayaran` int NOT NULL,
  `kode_transaksi` varchar(10) DEFAULT NULL,
  `id_pengguna` int NOT NULL,
  `id_produk` int NOT NULL,
  `quantity` int DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_penjualan`
--

INSERT INTO `tbl_penjualan` (`id_pembayaran`, `kode_transaksi`, `id_pengguna`, `id_produk`, `quantity`, `total`, `created_at`) VALUES
(98, 'AKM001', 9, 13, 1, '1000.00', '2024-06-08 15:52:13'),
(99, 'AKM001', 9, 13, 1, '1000.00', '2024-06-08 15:52:13'),
(100, 'AKM001', 9, 13, 1, '1000.00', '2024-06-08 15:52:13'),
(101, 'AKM001', 9, 13, 1, '1000.00', '2024-06-08 15:52:13'),
(102, 'AKM005', 9, 13, 1, '1000.00', '2024-06-08 15:55:32'),
(103, 'AKM005', 9, 13, 1, '1000.00', '2024-06-08 15:55:32'),
(104, 'AKM005', 9, 13, 1, '1000.00', '2024-06-08 15:55:32'),
(105, 'AKM005', 9, 13, 1, '1000.00', '2024-06-08 15:55:32'),
(106, 'AKM009', 9, 13, 1, '1000.00', '2024-06-08 15:57:56'),
(107, 'AKM009', 9, 13, 1, '1000.00', '2024-06-08 15:57:56'),
(108, 'AKM009', 9, 13, 1, '1000.00', '2024-06-08 15:57:56'),
(109, 'AKM009', 9, 13, 1, '1000.00', '2024-06-08 15:57:56'),
(110, 'AKM013', 9, 13, 1, '1000.00', '2024-06-08 16:00:52'),
(111, 'AKM013', 9, 13, 1, '1000.00', '2024-06-08 16:00:52'),
(112, 'AKM013', 9, 13, 1, '1000.00', '2024-06-08 16:00:52'),
(113, 'AKM013', 9, 13, 1, '1000.00', '2024-06-08 16:00:52'),
(114, 'AKM017', 9, 13, 1, '1000.00', '2024-06-08 16:01:25'),
(115, 'AKM017', 9, 13, 1, '1000.00', '2024-06-08 16:01:25'),
(116, 'AKM017', 9, 13, 1, '1000.00', '2024-06-08 16:01:25'),
(117, 'AKM017', 9, 13, 1, '1000.00', '2024-06-08 16:01:25'),
(118, 'AKM021', 9, 13, 1, '1000.00', '2024-06-08 16:03:50'),
(119, 'AKM021', 9, 13, 1, '1000.00', '2024-06-08 16:03:50'),
(120, 'AKM021', 9, 13, 1, '1000.00', '2024-06-08 16:03:50'),
(121, 'AKM021', 9, 13, 1, '1000.00', '2024-06-08 16:03:50');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_produk`
--

CREATE TABLE `tbl_produk` (
  `id_produk` int NOT NULL,
  `nama_produk` varchar(100) NOT NULL,
  `merk_produk` varchar(100) NOT NULL,
  `gambar_produk` varchar(255) DEFAULT NULL,
  `harga_produk` int NOT NULL,
  `deskripsi_produk` text,
  `stok_produk` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_produk`
--

INSERT INTO `tbl_produk` (`id_produk`, `nama_produk`, `merk_produk`, `gambar_produk`, `harga_produk`, `deskripsi_produk`, `stok_produk`) VALUES
(13, '1', '1', 'uploads/GAMBAR_TAMPAK_DEPAN-removebg-preview.png', 1000, '1', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `tbl_alamat`
--
ALTER TABLE `tbl_alamat`
  ADD PRIMARY KEY (`id_alamat`);

--
-- Indexes for table `tbl_keranjang`
--
ALTER TABLE `tbl_keranjang`
  ADD PRIMARY KEY (`id_keranjang`),
  ADD KEY `id_pengguna` (`id_pengguna`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indexes for table `tbl_pembelian`
--
ALTER TABLE `tbl_pembelian`
  ADD PRIMARY KEY (`id_pembelian`),
  ADD KEY `id_produk` (`id_produk`),
  ADD KEY `id_pengguna` (`id_pengguna`);

--
-- Indexes for table `tbl_pengguna`
--
ALTER TABLE `tbl_pengguna`
  ADD PRIMARY KEY (`id_pengguna`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_pengguna_alamat` (`id_alamat`);

--
-- Indexes for table `tbl_penjualan`
--
ALTER TABLE `tbl_penjualan`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `fk_pengguna` (`id_pengguna`),
  ADD KEY `fk_produk` (`id_produk`);

--
-- Indexes for table `tbl_produk`
--
ALTER TABLE `tbl_produk`
  ADD PRIMARY KEY (`id_produk`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `id_admin` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_alamat`
--
ALTER TABLE `tbl_alamat`
  MODIFY `id_alamat` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_keranjang`
--
ALTER TABLE `tbl_keranjang`
  MODIFY `id_keranjang` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tbl_pembelian`
--
ALTER TABLE `tbl_pembelian`
  MODIFY `id_pembelian` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `tbl_pengguna`
--
ALTER TABLE `tbl_pengguna`
  MODIFY `id_pengguna` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tbl_penjualan`
--
ALTER TABLE `tbl_penjualan`
  MODIFY `id_pembayaran` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;

--
-- AUTO_INCREMENT for table `tbl_produk`
--
ALTER TABLE `tbl_produk`
  MODIFY `id_produk` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_keranjang`
--
ALTER TABLE `tbl_keranjang`
  ADD CONSTRAINT `tbl_keranjang_ibfk_1` FOREIGN KEY (`id_pengguna`) REFERENCES `tbl_pengguna` (`id_pengguna`),
  ADD CONSTRAINT `tbl_keranjang_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `tbl_produk` (`id_produk`);

--
-- Constraints for table `tbl_pembelian`
--
ALTER TABLE `tbl_pembelian`
  ADD CONSTRAINT `tbl_pembelian_ibfk_1` FOREIGN KEY (`id_produk`) REFERENCES `tbl_produk` (`id_produk`),
  ADD CONSTRAINT `tbl_pembelian_ibfk_2` FOREIGN KEY (`id_pengguna`) REFERENCES `tbl_pengguna` (`id_pengguna`);

--
-- Constraints for table `tbl_pengguna`
--
ALTER TABLE `tbl_pengguna`
  ADD CONSTRAINT `fk_pengguna_alamat` FOREIGN KEY (`id_alamat`) REFERENCES `tbl_alamat` (`id_alamat`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_penjualan`
--
ALTER TABLE `tbl_penjualan`
  ADD CONSTRAINT `fk_pengguna` FOREIGN KEY (`id_pengguna`) REFERENCES `tbl_pengguna` (`id_pengguna`),
  ADD CONSTRAINT `fk_produk` FOREIGN KEY (`id_produk`) REFERENCES `tbl_produk` (`id_produk`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
