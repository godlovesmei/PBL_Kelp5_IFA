-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 30, 2024 at 09:45 PM
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
-- Database: `pure_beauty_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `keranjang`
--

CREATE TABLE `keranjang` (
  `id_keranjang` int NOT NULL,
  `id_pembeli` int NOT NULL,
  `id_produk` int NOT NULL,
  `jumlah_produk` int NOT NULL DEFAULT '1',
  `harga_produk` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `keranjang`
--

INSERT INTO `keranjang` (`id_keranjang`, `id_pembeli`, `id_produk`, `jumlah_produk`, `harga_produk`) VALUES
(54, 2, 22, 1, 149500),
(55, 2, 21, 1, 78000),
(78, 1, 14, 1, 105616),
(79, 1, 17, 1, 65000),
(86, 1, 18, 1, 112000),
(87, 4, 18, 1, 112000);

-- --------------------------------------------------------

--
-- Table structure for table `pembeli`
--

CREATE TABLE `pembeli` (
  `id_pembeli` int NOT NULL,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `username` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(255) NOT NULL,
  `nomor_telepon` varchar(15) DEFAULT NULL,
  `alamat` text,
  `foto_profil` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pembeli`
--

INSERT INTO `pembeli` (`id_pembeli`, `email`, `name`, `username`, `password`, `nomor_telepon`, `alamat`, `foto_profil`, `created_at`, `updated_at`) VALUES
(1, 'aulia.anggraini009@gmail.com', 'Putri Aulia Anggraini', 'putri009___', '$2y$10$ZQD9ELhGQN2AlN0he6tpOOAOwZYegr8QK/tdljBvWLvxNv2lSx7zO', '082169500629', 'Perumahan Citra Pendawa Asri ', '/PBL_OKE/admin/uploads/profile_pictures/profile-picture6756a258f20d2_Screenshot.png', '2024-11-25 19:11:12', '2024-12-09 18:09:00'),
(2, '4putri.anggraini@gmail.com', 'aulia anggraini', 'putri_anggraini048', '$2y$10$K4kYio.ihpFyY0wQUoYs3uU4UfprK0SNDTgSMnB2DuqCVkiJqX8Za', '081277592155', 'Perumahan Rindang Garden \r\nKelurahan Buliang, Kecamatan Batu Aji \r\nKota Batam', '/PBL_OKE/admin/uploads/profile_pictures/profile-picture6758267298ec9_438362156_364264702614913_5882712294725696354_n.jpg', '2024-12-10 18:18:43', '2024-12-10 11:30:58'),
(3, 'choccolatter@gmail.com', 'ChoccoLatte', 'chocco299_', '$2y$10$x/IieX/6vjwmdVEY/Ei0meTXROFpNlGM1f/r1i1ek41PgAERCyATm', '081234578443', 'Perumahan Grand Niaga Mas \r\nBlok B1/3A Kota Batam', '/PBL_OKE/admin/uploads/profile_pictures/profile-picture676a53b25f598_DSCF2016.JPG', '2024-12-24 13:10:37', '2024-12-24 06:24:50'),
(4, 'meiskesahertian7@gmail.com', 'Meiske Priskilla Sahertian', 'gigiundip', '$2y$10$Q2vqkGBtkzuLXKk5DzmbIusC//Pd3LIKqzP4dE5/dR340E1JA.ePy', '081524697967', 'Bukit Tiban Permai Blok D.16', '/PBL_OKE/admin/uploads/profile_pictures/profile-picture676ebf6f4d7bf_IMG_0335.jpeg', '2024-12-27 21:43:26', '2024-12-27 16:05:02'),
(5, 'valcey07@gmail.com', 'Meiske Sahertian', 'godlovesmei', '$2y$10$wiX5EQRtp99UNGwxb15iL.iQxGkMLqNEgMsK7/HVhEsZtHAOHQobG', '081524697967', 'Batam Center', '/PBL_OKE/admin/uploads/profile_pictures/profile-picture6772481d7c6a6_WhatsApp Image 2024-12-13 at 00.39.17_4fbc2314.jpg', '2024-12-30 14:10:46', '2024-12-30 07:13:33'),
(6, 'christine61@gmail.com', 'Christine', 'littlebunny', '$2y$10$fv7.frgs2Qw5OUWYW3/tt.fXnUV9XoaIDow7p.sjJwJqA/La7GhM.', NULL, NULL, NULL, '2024-12-30 16:07:40', '2024-12-30 09:07:40'),
(7, 'richardo@gmail.com', 'richardo', 'richardo22', '$2y$10$8HQbjSUJFTb7xbTBWW21k.DDxzU2mGDGP3Qrjxs7bgybVy/BStD7i', '081276380832', 'Perumahan Griya Surya Kharisma Blok J no5', '/PBL_OKE/admin/uploads/profile_pictures/profile-picture6772645f2fd0e_1.1.png', '2024-12-30 16:12:23', '2024-12-30 09:14:40'),
(8, 'marshaolivia2006@gmail.com', 'caca', 'caca', '$2y$10$7716xTlYk3P4NigpVGqEzO5keWfs.GhjrH8tMOzlGtTFUtsPunBYG', '0978798400428', 'Taman raya thp 2 ', '/PBL_OKE/admin/uploads/profile_pictures/profile-picture6772ed8928e69_logo poltek.png', '2024-12-31 00:42:11', '2024-12-30 19:12:53');

-- --------------------------------------------------------

--
-- Table structure for table `penjual`
--

CREATE TABLE `penjual` (
  `id_penjual` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `penjual`
--

INSERT INTO `penjual` (`id_penjual`, `username`, `email`, `password`, `role`) VALUES
(3, 'Officially_admin', 'purebeautyofficial@gmail.com', '$2y$10$mizHsF8dFq/dzdsWWcgVSOKUCz1SXoDGGXQrBAaT5zDJTGNhHCQwe', 'penjual');

-- --------------------------------------------------------

--
-- Table structure for table `penjualan`
--

CREATE TABLE `penjualan` (
  `id_penjualan` int NOT NULL,
  `id_produk` int NOT NULL,
  `jumlah_terjual` int NOT NULL,
  `tanggal` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `penjualan`
--

INSERT INTO `penjualan` (`id_penjualan`, `id_produk`, `jumlah_terjual`, `tanggal`) VALUES
(1, 2, 5, '2024-11-20'),
(2, 20, 1, '2024-12-24'),
(3, 14, 1, '2024-12-24'),
(4, 15, 1, '2024-12-24'),
(5, 18, 1, '2024-12-24'),
(6, 18, 1, '2024-12-24'),
(7, 22, 1, '2024-12-24'),
(8, 21, 3, '2024-12-24'),
(9, 18, 1, '2024-12-25'),
(10, 20, 1, '2024-12-25'),
(11, 20, 5, '2024-12-27'),
(12, 16, 5, '2024-12-27'),
(13, 23, 4, '2024-12-27'),
(14, 14, 3, '2024-12-30'),
(15, 15, 4, '2024-12-30'),
(16, 14, 1, '2024-12-30'),
(17, 15, 1, '2024-12-30'),
(18, 17, 2, '2024-12-30');

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id_pesanan` int NOT NULL,
  `id_transaksi` varchar(50) NOT NULL,
  `id_pembeli` int NOT NULL,
  `produk_dipesan` json NOT NULL,
  `total_harga` int NOT NULL,
  `metode_pembayaran` enum('COD') DEFAULT 'COD',
  `status_pesanan` enum('Menunggu Konfirmasi','Dikemas','Dikirim','Selesai','Dibatalkan') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `link_invoice` varchar(255) DEFAULT NULL,
  `alamat_pengiriman` text NOT NULL,
  `tanggal_pesanan` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pesanan`
--

INSERT INTO `pesanan` (`id_pesanan`, `id_transaksi`, `id_pembeli`, `produk_dipesan`, `total_harga`, `metode_pembayaran`, `status_pesanan`, `link_invoice`, `alamat_pengiriman`, `tanggal_pesanan`) VALUES
(6, 'TRX_676634a48655e', 1, '[{\"subtotal\": 105616, \"nama_produk\": \"SKINTIFIC - 5X Ceramide Calendula Soothing Toner 80ml |\", \"harga_produk\": 105616, \"gambar_produk\": \"/PBL_OKE/admin/uploads/SKINTIFIC 5X Ceramide Soothing Toner 80ml.jpg\", \"jumlah_produk\": 1}]', 120616, 'COD', 'Dikemas', 'invoices/TRX_676634a48655e.pdf', 'Perumahan Citra Pendawa Asri ', '2024-12-21 03:23:16'),
(7, 'TRX_67663537f27c3', 1, '[{\"subtotal\": 78000, \"nama_produk\": \"SKINTIFIC - 5% AHA BHA PHA Exfoliating Toner 80ml\", \"harga_produk\": 78000, \"gambar_produk\": \"/PBL_OKE/admin/uploads/674c8c9ab7a8d-AHA BHA PHA TONER.jpeg\", \"jumlah_produk\": 1}]', 93000, 'COD', 'Dikemas', 'invoices/TRX_67663537f27c3.pdf', 'Perumahan Citra Pendawa Asri ', '2024-12-21 03:25:43'),
(8, 'TRX_6768a1cca8cac', 1, '[{\"subtotal\": 189050, \"nama_produk\": \"SKINTIFIC - Niacinamide Bright Boost Clay Stick 40g\", \"harga_produk\": 189050, \"gambar_produk\": \"/PBL_OKE/admin/uploads/facemask2.jpg\", \"jumlah_produk\": 1}, {\"subtotal\": 105616, \"nama_produk\": \"SKINTIFIC - 5X Ceramide Calendula Soothing Toner 80ml |\", \"harga_produk\": 105616, \"gambar_produk\": \"/PBL_OKE/admin/uploads/SKINTIFIC 5X Ceramide Soothing Toner 80ml.jpg\", \"jumlah_produk\": 1}, {\"subtotal\": 75000, \"nama_produk\": \"SKINTIFIC - 5X Ceramide Barrier Moisturizer Gel 30g | Cream Pemutih Wajah Calming Whitening Brightening Dark Spot \", \"harga_produk\": 75000, \"gambar_produk\": \"/PBL_OKE/admin/uploads/SKINTIFIC-5X-Ceramide-Barrier-Repair-Moisturize-Gel-Moisturizer-30G.jpg\", \"jumlah_produk\": 1}, {\"subtotal\": 112000, \"nama_produk\": \"SKINTIFIC - Salicylic Acid Anti Acne Serum 20ml | Serum Mencerahkan Peeling Dark Spot Tumpas Komedo anti aging\", \"harga_produk\": 112000, \"gambar_produk\": \"/PBL_OKE/admin/uploads/serum2.jpg\", \"jumlah_produk\": 1}]', 496666, 'COD', 'Dikemas', 'invoices/TRX_6768a1cca8cac.pdf', 'Perumahan Citra Pendawa Asri ', '2024-12-22 23:33:32'),
(9, 'TRX_6768a5bd2aa21', 2, '[{\"subtotal\": 76000, \"nama_produk\": \"SKINTIFIC - Aqua Light Daily Sunscreen SPF 35 PA+++ 30ml | Matte Fit Serum Sunscreen 30g SPF50+ PA++++\", \"harga_produk\": 76000, \"gambar_produk\": \"/PBL_OKE/admin/uploads/sunscreen1.jpg\", \"jumlah_produk\": 1}, {\"subtotal\": 189050, \"nama_produk\": \"SKINTIFIC - Niacinamide Bright Boost Clay Stick 40g\", \"harga_produk\": 189050, \"gambar_produk\": \"/PBL_OKE/admin/uploads/facemask2.jpg\", \"jumlah_produk\": 1}, {\"subtotal\": 78000, \"nama_produk\": \"SKINTIFIC - 5% AHA BHA PHA Exfoliating Toner 80ml\", \"harga_produk\": 78000, \"gambar_produk\": \"/PBL_OKE/admin/uploads/674c8c9ab7a8d-AHA BHA PHA TONER.jpeg\", \"jumlah_produk\": 1}]', 358050, 'COD', 'Dibatalkan', 'invoices/TRX_6768a5bd2aa21.pdf', 'Perumahan Rindang Garden \r\nKelurahan Buliang, Kecamatan Batu Aji \r\nKota Batam', '2024-12-23 06:50:21'),
(10, 'TRX_6768a788cbeaa', 2, '[{\"subtotal\": 112000, \"nama_produk\": \"SKINTIFIC - Salicylic Acid Anti Acne Serum 20ml | Serum Mencerahkan Peeling Dark Spot Tumpas Komedo anti aging\", \"harga_produk\": 112000, \"gambar_produk\": \"/PBL_OKE/admin/uploads/serum2.jpg\", \"jumlah_produk\": 1}, {\"subtotal\": 127900, \"nama_produk\": \"SKINTIFIC - 12 % AHA BHA PHA LHA Peeling Solution\", \"harga_produk\": 127900, \"gambar_produk\": \"/PBL_OKE/admin/uploads/id-11134207-7rasc-m32yr5sx4k8i30.jpg\", \"jumlah_produk\": 1}]', 254900, 'COD', 'Dibatalkan', 'invoices/TRX_6768a788cbeaa.pdf', 'Perumahan Rindang Garden \r\nKelurahan Buliang, Kecamatan Batu Aji \r\nKota Batam', '2024-12-23 06:58:00'),
(11, 'TRX_676a568a5b55d', 3, '[{\"subtotal\": 112000, \"nama_produk\": \"SKINTIFIC - Salicylic Acid Anti Acne Serum 20ml | Serum Mencerahkan Peeling Dark Spot Tumpas Komedo anti aging\", \"harga_produk\": 112000, \"gambar_produk\": \"/PBL_OKE/admin/uploads/serum2.jpg\", \"jumlah_produk\": 1}, {\"subtotal\": 149500, \"nama_produk\": \"KINTIFIC - Outdoor Sunscreen Spray SPF 50+ PA++++ 250ml | UV Mist Tone Up Sun Shield Sunscreen Whitening Glowing\", \"harga_produk\": 149500, \"gambar_produk\": \"/PBL_OKE/admin/uploads/products6757ea43cf4a4-id-11134207-7rash-m1dom0dpn4gj49.jpg\", \"jumlah_produk\": 1}]', 276500, 'COD', 'Dikemas', 'invoices/TRX_676a568a5b55d.pdf', 'Perumahan Grand Niaga Mas \r\nBlok B1/3A Kota Batam', '2024-12-24 13:36:58'),
(12, 'TRX_676ac8d54b4f3', 1, '[{\"subtotal\": 234000, \"nama_produk\": \"SKINTIFIC - 5% AHA BHA PHA Exfoliating Toner 80ml\", \"harga_produk\": 78000, \"gambar_produk\": \"/PBL_OKE/admin/uploads/674c8c9ab7a8d-AHA BHA PHA TONER.jpeg\", \"jumlah_produk\": 3}]', 249000, 'COD', 'Dikemas', NULL, 'Perumahan Citra Pendawa Asri ', '2024-12-24 21:44:37'),
(13, 'TRX_676c06759114c', 3, '[{\"subtotal\": 112000, \"nama_produk\": \"SKINTIFIC - Salicylic Acid Anti Acne Serum 20ml | Serum Mencerahkan Peeling Dark Spot Tumpas Komedo anti aging\", \"harga_produk\": 112000, \"gambar_produk\": \"/PBL_OKE/admin/uploads/serum2.jpg\", \"jumlah_produk\": 1}, {\"subtotal\": 189050, \"nama_produk\": \"SKINTIFIC - Niacinamide Bright Boost Clay Stick 40g\", \"harga_produk\": 189050, \"gambar_produk\": \"/PBL_OKE/admin/uploads/facemask2.jpg\", \"jumlah_produk\": 1}]', 316050, 'COD', 'Dikemas', 'invoices/TRX_676c06759114c.pdf', 'Perumahan Grand Niaga Mas \r\nBlok B1/3A Kota Batam', '2024-12-25 20:19:49'),
(14, 'TRX_676c07ffa859c', 3, '[{\"subtotal\": 154200, \"nama_produk\": \"Skintific 3X Acid Anti-Acne Gel Moisturizer 30g\", \"harga_produk\": 154200, \"gambar_produk\": \"/PBL_OKE/admin/uploads/products676c07418b0e3-acid_anti-acne_gel_moisturizer-min.png\", \"jumlah_produk\": 1}]', 169200, 'COD', 'Dibatalkan', 'invoices/TRX_676c07ffa859c.pdf', 'Perumahan Grand Niaga Mas \r\nBlok B1/3A Kota Batam', '2024-12-25 20:26:23'),
(15, 'TRX_676ebefdecd83', 4, '[{\"subtotal\": 945250, \"nama_produk\": \"SKINTIFIC - Niacinamide Bright Boost Clay Stick 40g\", \"harga_produk\": 189050, \"gambar_produk\": \"/PBL_OKE/admin/uploads/facemask2.jpg\", \"jumlah_produk\": 5}, {\"subtotal\": 639500, \"nama_produk\": \"SKINTIFIC - 12 % AHA BHA PHA LHA Peeling Solution\", \"harga_produk\": 127900, \"gambar_produk\": \"/PBL_OKE/admin/uploads/id-11134207-7rasc-m32yr5sx4k8i30.jpg\", \"jumlah_produk\": 5}, {\"subtotal\": 516000, \"nama_produk\": \"Skintific Retinol Skin Renewal Serum 20ml\", \"harga_produk\": 129000, \"gambar_produk\": \"/PBL_OKE/admin/uploads/products676a5872520af-retinol_skin_renewal_serum-min.png\", \"jumlah_produk\": 4}]', 2115750, 'COD', 'Dikemas', 'invoices/TRX_676ebefdecd83.pdf', 'Bukit Tiban Permai Blok D.16', '2024-12-27 21:51:41'),
(16, 'TRX_67725c303164f', 5, '[{\"subtotal\": 207000, \"nama_produk\": \"SKINTIFIC - All Day Light Sunscreen Mist SPF 50 PA++++ 70ml \", \"harga_produk\": 69000, \"gambar_produk\": \"/PBL_OKE/admin/uploads/sunscreen3.jpg\", \"jumlah_produk\": 3}, {\"subtotal\": 300000, \"nama_produk\": \"SKINTIFIC - 5X Ceramide Barrier Moisturizer Gel 30g | Cream Pemutih Wajah Calming Whitening Brightening Dark Spot \", \"harga_produk\": 75000, \"gambar_produk\": \"/PBL_OKE/admin/uploads/moisturizer1.jpg\", \"jumlah_produk\": 4}]', 522000, 'COD', 'Dikemas', 'invoices/TRX_67725c303164f.pdf', 'Batam Center', '2024-12-30 08:39:12'),
(17, 'TRX_677264aa0e965', 7, '[{\"subtotal\": 69000, \"nama_produk\": \"SKINTIFIC - All Day Light Sunscreen Mist SPF 50 PA++++ 70ml \", \"harga_produk\": 69000, \"gambar_produk\": \"/PBL_OKE/admin/uploads/sunscreen3.jpg\", \"jumlah_produk\": 1}, {\"subtotal\": 75000, \"nama_produk\": \"SKINTIFIC - 5X Ceramide Barrier Moisturizer Gel 30g | Cream Pemutih Wajah Calming Whitening Brightening Dark Spot \", \"harga_produk\": 75000, \"gambar_produk\": \"/PBL_OKE/admin/uploads/moisturizer1.jpg\", \"jumlah_produk\": 1}, {\"subtotal\": 178000, \"nama_produk\": \"SKINTIFIC - Alaska Volcano Pore Clay Stick 40g | Masker Wajah Komedo Skincare BPOM Sleeping Mask Organik\", \"harga_produk\": 89000, \"gambar_produk\": \"/PBL_OKE/admin/uploads/facemask1.jpg\", \"jumlah_produk\": 2}]', 337000, 'COD', 'Dikemas', 'invoices/TRX_677264aa0e965.pdf', 'Perumahan Griya Surya Kharisma Blok J no5', '2024-12-30 09:15:22'),
(18, 'TRX_6772f200e4864', 8, '[{\"subtotal\": 255800, \"nama_produk\": \"SKINTIFIC - Mugwort Acne Clay Stick 40G \", \"harga_produk\": 127900, \"gambar_produk\": \"/PBL_OKE/admin/uploads/facemask3.jpg\", \"jumlah_produk\": 2}]', 270800, 'COD', 'Menunggu Konfirmasi', 'invoices/TRX_6772f200e4864.pdf', 'Taman raya thp 2 ', '2024-12-30 19:18:24');

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id_produk` int NOT NULL,
  `nama_produk` varchar(150) NOT NULL,
  `kategori` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `gambar_produk` varchar(255) NOT NULL,
  `deskripsi_produk` text NOT NULL,
  `stok_produk` int NOT NULL,
  `harga_produk` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id_produk`, `nama_produk`, `kategori`, `gambar_produk`, `deskripsi_produk`, `stok_produk`, `harga_produk`) VALUES
(14, 'SKINTIFIC - All Day Light Sunscreen Mist SPF 50 PA++++ 70ml ', 'Sunscreen', '/PBL_OKE/admin/uploads/sunscreen3.jpg', 'Skintific All Day Light Sunscreen Mist\r\n\r\nSunscreen mist dengan 0.01nm spray yang halus, mudah dibawa dan tidak merusak makeup pada saat diaplikasikan. Tekstur yang invisible, tidak terasa berat dan menyegarkan kulit. Memberikan rasa segar pada kulit saat digunakan. Perlindungan maksimal dengan SPF 50+ PA++++, 12 jam melindungi dari UVB penyebab sunburn dan 16 x menangkal UVA untuk mencegah tanda penuaan akibat paparan sinar matahari. Teknologi mikrokapsul, memberikan 0.04nm lapisan pelindung transparan pada kulit wajah. Dilengkapi 5X bahan efektif termasuk UV Pearls, Ceramide dan Centella yang melindungi 99% dari bahaya sinar UV, tanda penuaan serta kulit terbakar matahari\r\n\r\n\r\n\r\nUkuran: 120 ml\r\n\r\nNo BPOM:  NA11221700015', 41, 69000),
(15, 'SKINTIFIC - 5X Ceramide Barrier Moisturizer Gel 30g | Cream Pemutih Wajah Calming Whitening Brightening Dark Spot ', 'Moisturizer', '/PBL_OKE/admin/uploads/moisturizer1.jpg', 'Moisturizer dengan tekstur yang ringan dan mudah menyerap namun dapat memberikan kelembaban yang maksimal. Dilengkapi dengan kandungan utama ceramide, bagian dari skin barrier kulit, sehingga tidak hanya dapat melembabkan tetapi juga menutrisi dan menjaga kesehatan skin barrier kulit. Kombinasi kandungan Hyaluronic acid, Centella asiatica, dan Marine collagen membantu menutrisi dan merawat kulit tetap sehat dan bersih. \r\n\r\n\r\n\r\nUkuran:  30 g\r\n\r\n\r\n\r\nNo BPOM: NA11230100167\r\n\r\nNA11230100349', 40, 75000),
(16, 'SKINTIFIC - Mugwort Acne Clay Stick 40G ', 'Face Mask', '/PBL_OKE/admin/uploads/facemask3.jpg', 'Mugwort Acne Clay Stick\r\n\r\nDiformulasikan dengan Mugwort, ingredients yang populer di Korea untuk merawat jerawat. Dilengkapi dengan Niacinamide, Salicylic Acid, dan juga Centella yang dapat mengontrol minyak berlebih, membersihkan pori-pori secara mendalam, membersihkan komedo, dan menenangkan kulit. Mudah digunakan dengan bentuk stick, berfokus untuk menenangkan kulit dan merawat kemerahan, membersihkan pori dan merawat kulit berjerawat, mengontrol minyak dan menjaga skin barrier. Coverage yang baik, dengan 1 usapan dapat menutup satu area pada wajah. Hasil penggunaan pori-pori mengecil, warna kulit lebih cerah dan merata.\r\n\r\nUkuran: 40 gr\r\n\r\nNo BPOM: NA11220200288', 87, 127900),
(17, 'SKINTIFIC - Alaska Volcano Pore Clay Stick 40g | Masker Wajah Komedo Skincare BPOM Sleeping Mask Organik', 'Face Mask', '/PBL_OKE/admin/uploads/facemask1.jpg', 'SKINTIFIC Alaska Volcano Pore Detox Clay Stick 40g\r\n\r\n\r\nAlaska Volcano Pore Detox Clay Stick mengandung partikel halus yang secara alami dapat mengeksfoliasi kulit wajah menjadi lebih bersih dan bebas komedo ataupun tersumbat. Dilengkapi dengan Glycolic Acid dan Probiotic Complex untuk menjaga skin barrier serta menyegarkan kulit. Dengan tekstur yang lembut dan mudah diaplikasikan, tanpa menyebabkan sensasi kulit tertarik atau kencang, memiliki kekuatan pembersihan mendalam untuk membersihkan wajah secara maksimal. Coverage yang baik, dengan 1 usapan dapat menutup satu area pada wajah. Hasil penggunaan pori-pori tampak lebih mengecil, kulit bersih dan halus.\r\n\r\n\r\n\r\nUkuran: 40 gr\r\n\r\nNo BPOM: NA11220200290', 53, 89000),
(18, 'SKINTIFIC - Salicylic Acid Anti Acne Serum 20ml | Serum Mencerahkan Peeling Dark Spot Tumpas Komedo anti aging', 'Serum', '/PBL_OKE/admin/uploads/serum2.jpg', 'AHA BHA PHA Exfoliating Toner\r\n\r\nToner eksfoliasi dengan rasio konsentrasi efektif gabungan antara AHA, BHA, & PHA yang dapat membersihkan sel kulit mati secara maksimal. Dengan kandungan tambahan Niacinamide yang dapat membuat kulit wajah tampak lebih cerah dan Ceramide yang dapat merawat dan menjaga skin barrier agar tetap sehat.\r\n\r\n\r\n\r\nUkuran:  80 ml\r\n\r\n\r\n\r\nNo BPOM: NA11231200041 NA11231201181\r\n\r\n\r\n\r\nManfaat:\r\n\r\n\r\n\r\n● Membersihkan sel kulit mati \r\n\r\n● Membuat tekstur kulit terasa halus dan lembut\r\n\r\n● Merawat skin barrier kulit\r\n\r\n\r\n\r\nHero Ingredients:\r\n\r\nAHA BHA PHA: Eksfoliator yang efektif membersihkan kulit wajah dari sel kulit mati\r\n\r\nNiacinamide: membantu mencerahkan kulit wajah \r\n\r\nCeramide: menutrisi dan menjaga skin barrier kulit\r\n\r\n\r\n\r\nCara pemakaian:\r\n\r\n1. Bersihkan wajah lalu tuangkan produk ke kapas\r\n\r\n2. Oleskan kapas ke seluruh wajah dengan lembut\r\n\r\n3. Lanjutkan dengan skincare rutin berikutnya', 66, 112000),
(19, 'SKINTIFIC - Aqua Light Daily Sunscreen SPF 35 PA+++ 30ml | Matte Fit Serum Sunscreen 30g SPF50+ PA++++', 'Sunscreen', '/PBL_OKE/admin/uploads/sunscreen1.jpg', 'Aqua Light Daily Sunscreen SPF 35 PA+++\r\n\r\nSunscreen dengan kandungan Allantoin, Trehalose, dan Tremella yang berfungsi menenangkan kulit serta memberikan hidrasi. Tekstur water-burst yang ringan, terasa segar saat mengaplikasikan ke kulit wajah. Mengandung SPF 35+ PA+++ dengan teknologi Tinosorb S Lite Aqua, mencegah kekusaman sepanjang hari akibat oksidasi sebum. Tanpa whitecast, tidak lengket, cocok untuk kulit acne prone dan berminyak.\r\n\r\nUkuran: 30 ml\r\nNo BPOM: NA11241700002', 92, 76000),
(20, 'SKINTIFIC - Niacinamide Bright Boost Clay Stick 40g', 'Face Mask', '/PBL_OKE/admin/uploads/facemask2.jpg', 'Niacinamide Bright Boost Clay Stick \r\n\r\nSKINTIFIC Niacinamide Bright Boost Clay Stick adalah produk booster pendukung untuk mencerahkan yang mengandung Niacinamide, Pink Sea Salt, dan Tranexamic Acid untuk mencerahkan dan meratakan warna kulit. Bersinergi untuk mendapatkan kulit yang glowing, cerah dan sehat. \r\n\r\nNetto : 40 gr/ 1.41 FL.OZ\r\nBPOM Number: NA11240200044', 56, 99000),
(21, 'SKINTIFIC - MSH Niacinamide Brightening Moisturizer Gel 30g', 'Moisturizer', '/PBL_OKE/admin/uploads/moisturizer2.jpg', 'MSH Niacinamide Brightening Moisture Gel, with its lightweight texture, absorbs quickly and helps in oil control. Formulated with the novel SKINTIFIC exclusive MSH Niacinamide combined with two lightweight and highly effective brightening agents, Alpha Arbutin and Tranexamic Acid. Helps in significantly brightens the skin. Clinically proven to be 10 times more effective than regular niacinamide in reducing dark spots and blackheads. It also enriched with Centella Asiatica and 5X Ceramide, that provides a soothing effect on the skin while preserving the strength of the skin barrier.\r\n\r\n\r\n\r\nSize: 30 ml\r\n\r\n\r\nNo BPOM:  NA11220100489\r\n\r\nNA11230100360', 65, 138900),
(23, 'SKINTIFIC - Niacinamide Brightening Serum 20ml/50ml', 'Serum', '/PBL_OKE/admin/uploads/serum1.jpg', 'Niacinamide Brightening Serum\r\n\r\nSerum pencerah dengan kandungan Niacinamide dan Alpha arbutin yang bekerja secara efektif mencerahkan kulit wajah. Dengan kandungan ceramide yang dapat menjaga dan menutrisi skin barrier sehingga kulit tidak hanya terlihat cerah dan merata namun juga bersih.  \r\n\r\n\r\nUkuran:  20 ml\r\n\r\nNo BPOM: NA11231900093', 63, 129000),
(24, 'SKINTIFIC - 5X Ceramide Serum Sunscreen 50ml SPF 50+ PA++++', 'Sunscreen', '/PBL_OKE/admin/uploads/sunscreen2.jpg', '5X Ceramide Serum Sunscreen SPF50 PA++++\r\n\r\nSunscreen dengan kandungan utama 5X Ceramide yang dapat menjaga skin barrier. Dilengkapi  UV Filter yang dapat melindungi kulit dari sinar UVA & UVB serta Blue Light, dengan SPF 50 PA++++.  Memiliki tekstur ringan yang menyerap dengan cepat, tidak berminyak & memberikan hasil akhir yang melembabkan. Tambahan kandungan Hyaluronic Acid yang dapat mengembalikan dan mengunci kelembaban pada kulit \r\n\r\n\r\n\r\nUkuran: 50 ml\r\n\r\nNo BPOM: NA11231700056', 88, 99000);

-- --------------------------------------------------------

--
-- Table structure for table `promosi`
--

CREATE TABLE `promosi` (
  `Id_promosi` int NOT NULL,
  `judul_promosi` varchar(255) NOT NULL,
  `id_produk` int NOT NULL,
  `diskon` decimal(5,2) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `status_promosi` enum('Aktif','Nonaktif') NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD PRIMARY KEY (`id_keranjang`);

--
-- Indexes for table `pembeli`
--
ALTER TABLE `pembeli`
  ADD PRIMARY KEY (`id_pembeli`),
  ADD UNIQUE KEY `user` (`username`);

--
-- Indexes for table `penjual`
--
ALTER TABLE `penjual`
  ADD PRIMARY KEY (`id_penjual`);

--
-- Indexes for table `penjualan`
--
ALTER TABLE `penjualan`
  ADD PRIMARY KEY (`id_penjualan`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id_pesanan`),
  ADD KEY `id_pembeli` (`id_pembeli`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`);

--
-- Indexes for table `promosi`
--
ALTER TABLE `promosi`
  ADD PRIMARY KEY (`Id_promosi`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `keranjang`
--
ALTER TABLE `keranjang`
  MODIFY `id_keranjang` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `pembeli`
--
ALTER TABLE `pembeli`
  MODIFY `id_pembeli` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `penjual`
--
ALTER TABLE `penjual`
  MODIFY `id_penjual` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `penjualan`
--
ALTER TABLE `penjualan`
  MODIFY `id_penjualan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id_pesanan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `promosi`
--
ALTER TABLE `promosi`
  MODIFY `Id_promosi` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`id_pembeli`) REFERENCES `pembeli` (`id_pembeli`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
