-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 19, 2024 at 04:45 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

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
  `id_admin` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `id_alamat` int(11) NOT NULL,
  `alamat` text NOT NULL,
  `ongkir` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_alamat`
--

INSERT INTO `tbl_alamat` (`id_alamat`, `alamat`, `ongkir`) VALUES
(1, 'Simpang Len', 5000),
(2, 'Cunda', 10000),
(3, 'Medan', 50000);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_kategori`
--

CREATE TABLE `tbl_kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_kategori`
--

INSERT INTO `tbl_kategori` (`id_kategori`, `nama_kategori`) VALUES
(1, 'Originote'),
(2, 'Wardah'),
(3, 'Skintific');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_keranjang`
--

CREATE TABLE `tbl_keranjang` (
  `id_keranjang` int(11) NOT NULL,
  `id_pengguna` int(11) DEFAULT NULL,
  `id_produk` int(11) DEFAULT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_keranjang`
--

INSERT INTO `tbl_keranjang` (`id_keranjang`, `id_pengguna`, `id_produk`, `status`) VALUES
(43, 15, 14, 'Sudah Dibayar'),
(44, 9, 14, 'Sudah Dibayar'),
(45, 9, 15, 'Sudah Dibayar'),
(46, 15, 14, 'Sudah Dibayar'),
(47, 15, 15, 'Sudah Dibayar');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pembelian`
--

CREATE TABLE `tbl_pembelian` (
  `id_pembelian` int(11) NOT NULL,
  `kode_transaksi` varchar(10) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `id_pengguna` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `total_harga` int(10) NOT NULL,
  `status_pembelian` varchar(50) DEFAULT 'Diproses',
  `status_pembayaran` varchar(20) NOT NULL,
  `tanggal_pembelian` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pengguna`
--

CREATE TABLE `tbl_pengguna` (
  `id_pengguna` int(11) NOT NULL,
  `nama_pengguna` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `no_telepon` varchar(15) DEFAULT NULL,
  `id_alamat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_pengguna`
--

INSERT INTO `tbl_pengguna` (`id_pengguna`, `nama_pengguna`, `email`, `password`, `no_telepon`, `id_alamat`) VALUES
(9, 'budi', 'budiman1@gmail.com', '$2y$10$VQBcHfrkHZZwJUXUQNoqf.elOv2t7A86C6GLy7iI.6H7tABVVe0li', '082165443677', 1),
(10, 'budi', 'budi@gmail.com', '$2y$10$YvLCIuD4jilOJgc6JCz.q.MAw.eIagP2efLKTsZJ5QP0Fom3jDsxm', '082165446778', 1),
(12, 'budi', 'budi1@gmail.com', '$2y$10$zYycTsvCAfrfDpI1REIfy.hW9JjyBoIlGh2Wr5mHAE018fQ4.kNsq', '12121212', 1),
(14, 'tia', '', '$2y$10$x5wxZgvQwN4Tg.12B.DOcuMm80fgmlQnzREft4CYbWgDVaYIsrQLO', '12345678', 1),
(15, 'sari', 'sari@gmail.com', '$2y$10$VQBcHfrkHZZwJUXUQNoqf.elOv2t7A86C6GLy7iI.6H7tABVVe0li', '12345678', 1),
(16, 'nuri', 'nuri@gmail.com', '$2y$10$.dK1.cIWjui4vmM/4bI4hO7dUaak4/gi7WTPkS9gof5bbyU6mDugW', '082165443677', 3);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_penjualan`
--

CREATE TABLE `tbl_penjualan` (
  `id_pembayaran` int(11) NOT NULL,
  `kode_transaksi` varchar(10) DEFAULT NULL,
  `id_pengguna` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `total` int(5) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `metode_pembayaran` varchar(50) NOT NULL,
  `status_pembelian` varchar(50) NOT NULL,
  `estimasi_pengiriman` varchar(50) DEFAULT NULL,
  `id_keranjang` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_penjualan`
--

INSERT INTO `tbl_penjualan` (`id_pembayaran`, `kode_transaksi`, `id_pengguna`, `id_produk`, `quantity`, `total`, `created_at`, `metode_pembayaran`, `status_pembelian`, `estimasi_pengiriman`, `id_keranjang`) VALUES
(218, 'AKM001', 15, 14, 1, 185395, '2024-06-19 03:32:55', 'Transfer Bank', 'Diterima', '22 Juni 2024 - 23 Juni 2024', 43),
(219, 'AKM002', 9, 14, 1, 130559, '2024-06-19 04:43:28', 'Transfer Bank', 'Sudah Dibayar', '22 Juni 2024 - 23 Juni 2024', 44),
(220, 'AKM003', 9, 15, 1, 130559, '2024-06-19 05:00:36', 'Transfer Bank', 'Sudah Dibayar', '22 Juni 2024 - 23 Juni 2024', 45),
(221, 'AKM004', 15, 14, 1, 185395, '2024-06-19 08:14:54', 'Transfer Bank', 'Pembayaran ditolak', '22 Juni 2024 - 23 Juni 2024', 46),
(222, 'AKM004', 15, 15, 1, 185395, '2024-06-19 08:14:54', 'Transfer Bank', 'Sudah Dibayar', '22 Juni 2024 - 23 Juni 2024', 47);

--
-- Triggers `tbl_penjualan`
--
DELIMITER $$
CREATE TRIGGER `before_insert_pembayaran` BEFORE INSERT ON `tbl_penjualan` FOR EACH ROW BEGIN
    SET NEW.estimasi_pengiriman = CONCAT(
        DATE_FORMAT(DATE_ADD(NEW.created_at, INTERVAL 3 DAY), '%d %M %Y'),
        ' - ',
        DATE_FORMAT(DATE_ADD(NEW.created_at, INTERVAL 4 DAY), '%d %M %Y')
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_insert_penjualan` BEFORE INSERT ON `tbl_penjualan` FOR EACH ROW BEGIN
    DECLARE estimasi_awal DATE;
    DECLARE estimasi_akhir DATE;
    DECLARE bulan_awal VARCHAR(20);
    DECLARE bulan_akhir VARCHAR(20);

    SET estimasi_awal = DATE_ADD(NEW.created_at, INTERVAL 3 DAY);
    SET estimasi_akhir = DATE_ADD(NEW.created_at, INTERVAL 4 DAY);

    SET bulan_awal = CASE MONTH(estimasi_awal)
        WHEN 1 THEN 'Januari'
        WHEN 2 THEN 'Februari'
        WHEN 3 THEN 'Maret'
        WHEN 4 THEN 'April'
        WHEN 5 THEN 'Mei'
        WHEN 6 THEN 'Juni'
        WHEN 7 THEN 'Juli'
        WHEN 8 THEN 'Agustus'
        WHEN 9 THEN 'September'
        WHEN 10 THEN 'Oktober'
        WHEN 11 THEN 'November'
        WHEN 12 THEN 'Desember'
        ELSE 'Tidak Diketahui'
    END;

    SET bulan_akhir = CASE MONTH(estimasi_akhir)
        WHEN 1 THEN 'Januari'
        WHEN 2 THEN 'Februari'
        WHEN 3 THEN 'Maret'
        WHEN 4 THEN 'April'
        WHEN 5 THEN 'Mei'
        WHEN 6 THEN 'Juni'
        WHEN 7 THEN 'Juli'
        WHEN 8 THEN 'Agustus'
        WHEN 9 THEN 'September'
        WHEN 10 THEN 'Oktober'
        WHEN 11 THEN 'November'
        WHEN 12 THEN 'Desember'
        ELSE 'Tidak Diketahui'
    END;

    SET NEW.estimasi_pengiriman = CONCAT(
        DAY(estimasi_awal), ' ', bulan_awal, ' ', YEAR(estimasi_awal), 
        ' - ', 
        DAY(estimasi_akhir), ' ', bulan_akhir, ' ', YEAR(estimasi_akhir)
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_produk`
--

CREATE TABLE `tbl_produk` (
  `id_produk` int(11) NOT NULL,
  `nama_produk` varchar(100) NOT NULL,
  `merk_produk` varchar(100) NOT NULL,
  `gambar_produk` varchar(255) DEFAULT NULL,
  `harga_produk` int(5) NOT NULL,
  `deskripsi_produk` text DEFAULT NULL,
  `stok_produk` int(11) NOT NULL,
  `id_kategori` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_produk`
--

INSERT INTO `tbl_produk` (`id_produk`, `nama_produk`, `merk_produk`, `gambar_produk`, `harga_produk`, `deskripsi_produk`, `stok_produk`, `id_kategori`) VALUES
(14, 'Micellar Water', 'Originote', 'uploads/OriginoteMicellar55000.png', 54000, 'Micellar Water Originote', 10, 1),
(15, 'Face Wash', 'Skintific', 'uploads/SkintificFacewash125000.png', 125000, 'Face Wash dari Skintific', 10, 3);

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
-- Indexes for table `tbl_kategori`
--
ALTER TABLE `tbl_kategori`
  ADD PRIMARY KEY (`id_kategori`);

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
  ADD KEY `fk_produk` (`id_produk`),
  ADD KEY `fk_id_keranjang` (`id_keranjang`);

--
-- Indexes for table `tbl_produk`
--
ALTER TABLE `tbl_produk`
  ADD PRIMARY KEY (`id_produk`),
  ADD KEY `fk_kategori` (`id_kategori`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_alamat`
--
ALTER TABLE `tbl_alamat`
  MODIFY `id_alamat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_kategori`
--
ALTER TABLE `tbl_kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_keranjang`
--
ALTER TABLE `tbl_keranjang`
  MODIFY `id_keranjang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `tbl_pembelian`
--
ALTER TABLE `tbl_pembelian`
  MODIFY `id_pembelian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `tbl_pengguna`
--
ALTER TABLE `tbl_pengguna`
  MODIFY `id_pengguna` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tbl_penjualan`
--
ALTER TABLE `tbl_penjualan`
  MODIFY `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=223;

--
-- AUTO_INCREMENT for table `tbl_produk`
--
ALTER TABLE `tbl_produk`
  MODIFY `id_produk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

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
  ADD CONSTRAINT `fk_id_keranjang` FOREIGN KEY (`id_keranjang`) REFERENCES `tbl_keranjang` (`id_keranjang`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_pengguna` FOREIGN KEY (`id_pengguna`) REFERENCES `tbl_pengguna` (`id_pengguna`),
  ADD CONSTRAINT `fk_produk` FOREIGN KEY (`id_produk`) REFERENCES `tbl_produk` (`id_produk`);

--
-- Constraints for table `tbl_produk`
--
ALTER TABLE `tbl_produk`
  ADD CONSTRAINT `fk_kategori` FOREIGN KEY (`id_kategori`) REFERENCES `tbl_kategori` (`id_kategori`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
