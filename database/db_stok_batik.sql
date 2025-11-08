-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 08, 2025 at 09:30 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_stok_batik`
--

-- --------------------------------------------------------

--
-- Table structure for table `detail_transaksi`
--

CREATE TABLE `detail_transaksi` (
  `id_detail` int(11) NOT NULL,
  `id_transaksi` int(11) DEFAULT NULL,
  `id_produk` int(11) DEFAULT NULL,
  `jumlah` int(11) NOT NULL,
  `harga_satuan` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) GENERATED ALWAYS AS (`jumlah` * `harga_satuan`) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_transaksi`
--

INSERT INTO `detail_transaksi` (`id_detail`, `id_transaksi`, `id_produk`, `jumlah`, `harga_satuan`) VALUES
(1, 1, 8, 5, 350000.00),
(2, 2, 8, 15, 350000.00),
(3, 3, 5, 2, 500000.00),
(4, 4, 3, 4, 200000.00),
(5, 5, 1, 15, 250000.00),
(6, 6, 2, 3, 200000.00),
(7, 7, 4, 4, 350000.00),
(8, 8, 3, 2, 200000.00),
(9, 9, 7, 4, 300000.00),
(10, 10, 6, 5, 250000.00),
(11, 11, 7, 20, 300000.00);

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`, `deskripsi`) VALUES
(1, 'Batik Lengan Panjang', 'Batik dengan lengan baju yang panjang'),
(2, 'Batik Lengan Pendek', 'Batik dengan lengan baju yang pendek'),
(3, 'Dress Batik', 'Dress batik wanita yang anggun'),
(4, 'Batik Couple', 'Batik untuk Pasangan');

-- --------------------------------------------------------

--
-- Stand-in structure for view `laporan_stok`
-- (See below for the actual view)
--
CREATE TABLE `laporan_stok` (
`id_produk` int(11)
,`nama_produk` varchar(100)
,`harga` decimal(12,2)
,`stok` int(11)
,`total_masuk` decimal(32,0)
,`total_keluar` decimal(32,0)
,`stok_saat_ini` int(11)
);

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id_produk` int(11) NOT NULL,
  `id_kategori` int(11) DEFAULT NULL,
  `kode_produk` varchar(50) NOT NULL,
  `nama_produk` varchar(100) NOT NULL,
  `jenis_batik` varchar(100) DEFAULT NULL,
  `harga` decimal(12,2) NOT NULL,
  `stok` int(11) DEFAULT 0,
  `lokasi_simpan` varchar(100) DEFAULT NULL,
  `foto_produk` varchar(255) DEFAULT NULL,
  `tanggal_ditambahkan` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id_produk`, `id_kategori`, `kode_produk`, `nama_produk`, `jenis_batik`, `harga`, `stok`, `lokasi_simpan`, `foto_produk`, `tanggal_ditambahkan`) VALUES
(1, 1, 'BLP220', 'Kemeja Dobby Sarimbit Hitam', 'Batik Lengan Panjang', 250000.00, 5, 'MDS Sawangan', 'produk_1762533501_1751030432_1747678075_Batik_Bali_Lestari_Kemeja_Dobby_Lengan_Panjang_Sarimbit_Hitam___220.png', '2025-11-07 23:38:21'),
(2, 2, 'BLPK033', 'Batik Pria Katun Foil Prada Hitam', 'Batik Lengan Pendek', 200000.00, 22, 'MDS Sawangan', 'produk_1762534729_1747682483_Batik_Bali_Lestari_Batik_Pria_Katun_Foil_Prada_Lengan_Pendek_Hitam___033.png', '2025-11-07 23:58:49'),
(3, 2, 'BLPK025', 'Batik Pria Lestari Hitam', 'Batik Lengan Pendek', 200000.00, 4, 'MDS Sawangan', 'produk_1762535228_1751045455_image_2025-06-28_002935913.png', '2025-11-08 00:07:08'),
(4, 3, 'DRS025', 'Dress Batik Wanita Hitam', 'Dress Batik', 350000.00, 30, 'MDS Sawangan', 'produk_1762535466_1751044138_image_2025-06-28_000831484.png', '2025-11-08 00:09:00'),
(5, 4, 'BCP045', 'Batik Couple Bambu Kuning', 'Batik Couple', 500000.00, 22, 'MDS Sawangan', 'produk_1762535720_1751093636_image_2025_06_28_135142210.png', '2025-11-08 00:15:20'),
(6, 2, 'BLPK048', 'Batik Slimfit Merah', 'Batik Lengan Pendek', 250000.00, 10, 'MDS Sawangan', 'produk_1762535955_1747687695_Batik_Bali_Lestari_batik_Slimfit_Lengan_Pendek_Merah.png', '2025-11-08 00:19:15'),
(7, 1, 'BLP225', 'Kemeja Sarimbit Biru', 'Batik Lengan Panjang', 300000.00, 22, 'MDS Sawangan', 'produk_1762536053_1751045241_image_2025-06-28_002514637.png', '2025-11-08 00:20:53'),
(8, 1, 'BLP035', 'Batik Lengan Panjang Hitam', 'Batik Lengan Panjang', 350000.00, 15, 'MDS Sawangan', 'produk_1762536218_1751044138_image_2025-06-28_000841721.png', '2025-11-08 00:23:38'),
(9, 4, 'BCP265', 'Batik Couple Hitam', 'Batik Couple', 550000.00, 36, 'MDS Sawangan', 'produk_1762536357_1747727056_Batik_Bali_Lestari_Kemeja_Lengan_Panjang_Sarimbit_Hitam___KS02.png', '2025-11-08 00:25:57'),
(10, 3, 'DRS201', 'Dress Batik Wanita Biru', 'Dress Batik', 200000.00, 3, 'MDS Sawangan', 'produk_1762536532_1751094593_image_2025_06_28_140830012.png', '2025-11-08 00:28:52');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `kode_transaksi` varchar(50) NOT NULL,
  `tanggal_transaksi` date NOT NULL,
  `jenis_transaksi` enum('masuk','keluar') NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `total_harga` decimal(12,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `kode_transaksi`, `tanggal_transaksi`, `jenis_transaksi`, `id_user`, `keterangan`, `total_harga`) VALUES
(1, 'OUT1762542217', '2025-11-07', 'keluar', 11, 'Terjual', 1750000.00),
(2, 'IN1762542358', '2025-11-07', 'masuk', 11, 'Stok masuk', 5250000.00),
(3, 'OUT1762588426', '2025-11-08', 'keluar', 11, 'Terjual', 1000000.00),
(4, 'OUT1762588918', '2025-11-08', 'keluar', 13, 'Terjual', 800000.00),
(5, 'OUT1762588945', '2025-11-08', 'keluar', 13, 'Terjual', 3750000.00),
(6, 'OUT1762588977', '2025-11-08', 'keluar', 13, 'Terjual', 600000.00),
(7, 'OUT1762589000', '2025-11-08', 'keluar', 13, 'Terjual', 1400000.00),
(8, 'OUT1762589057', '2025-11-08', 'keluar', 13, 'Terjual', 400000.00),
(9, 'OUT1762589103', '2025-11-08', 'keluar', 13, 'Terjual', 1200000.00),
(10, 'OUT1762589217', '2025-11-08', 'keluar', 13, 'Terjual', 1250000.00),
(11, 'IN1762589273', '2025-11-08', 'masuk', 13, 'Stok Masuk', 6000000.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','staf_gudang','kasir','owner') DEFAULT 'admin',
  `email` varchar(100) DEFAULT NULL,
  `tanggal_dibuat` datetime DEFAULT current_timestamp(),
  `status_aktif` enum('aktif','nonaktif') DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `nama_lengkap`, `username`, `password`, `role`, `email`, `tanggal_dibuat`, `status_aktif`) VALUES
(11, 'Firda', 'admin', '0192023a7bbd73250516f069df18b500', 'admin', 'admin@batik.com', '2025-11-05 19:24:39', 'aktif'),
(13, 'Mei', 'kasir', 'de28f8f7998f23ab4194b51a6029416f', 'kasir', 'kasir@gmail.com', '2025-11-08 14:40:56', 'aktif'),
(14, 'Egi', 'Staf_Gudang', 'a74a8cebf50954c41519e02d423496c1', 'staf_gudang', 'staf@batik.com', '2025-11-08 14:42:13', 'aktif'),
(15, 'Dina', 'Owner', 'a02e9733abe1b14cc8bad68cbdbca058', 'owner', 'owner@gmail.com', '2025-11-08 14:43:05', 'aktif');

-- --------------------------------------------------------

--
-- Structure for view `laporan_stok`
--
DROP TABLE IF EXISTS `laporan_stok`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `laporan_stok`  AS SELECT `p`.`id_produk` AS `id_produk`, `p`.`nama_produk` AS `nama_produk`, `p`.`harga` AS `harga`, `p`.`stok` AS `stok`, coalesce(sum(case when `t`.`jenis_transaksi` = 'masuk' then `d`.`jumlah` end),0) AS `total_masuk`, coalesce(sum(case when `t`.`jenis_transaksi` = 'keluar' then `d`.`jumlah` end),0) AS `total_keluar`, `p`.`stok` AS `stok_saat_ini` FROM ((`produk` `p` left join `detail_transaksi` `d` on(`p`.`id_produk` = `d`.`id_produk`)) left join `transaksi` `t` on(`d`.`id_transaksi` = `t`.`id_transaksi`)) GROUP BY `p`.`id_produk` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_transaksi` (`id_transaksi`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`),
  ADD UNIQUE KEY `kode_produk` (`kode_produk`),
  ADD KEY `id_kategori` (`id_kategori`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD UNIQUE KEY `kode_transaksi` (`kode_transaksi`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD CONSTRAINT `detail_transaksi_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_transaksi_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE;

--
-- Constraints for table `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `produk_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`) ON DELETE SET NULL;

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
