-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 22 Okt 2023 pada 03.11
-- Versi server: 10.6.14-MariaDB-1:10.6.14+maria~ubu2004
-- Versi PHP: 8.1.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `adm_cibeber_apem`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_data_pajak`
--

CREATE TABLE `tb_data_pajak` (
  `nik` varchar(35) NOT NULL,
  `nopol` varchar(15) NOT NULL,
  `isMilikSendiri` varchar(5) NOT NULL,
  `alasanTidakMilikLagi` text NOT NULL,
  `tindakan` varchar(50) NOT NULL,
  `jawabanTindakan` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `tb_data_pajak`
--
ALTER TABLE `tb_data_pajak`
  ADD PRIMARY KEY (`nik`,`nopol`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


ALTER TABLE `tb_data_pajak` ADD `noPol_ori` VARCHAR(30) NOT NULL AFTER `nopol`;
