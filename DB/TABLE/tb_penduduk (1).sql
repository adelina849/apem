-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 12 Sep 2023 pada 05.18
-- Versi server: 10.4.22-MariaDB
-- Versi PHP: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_apem`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_penduduk`
--

CREATE TABLE `tb_penduduk` (
  `id_penduduk` varchar(25) NOT NULL,
  `nik` varchar(25) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `jenis_kelamin` varchar(15) NOT NULL,
  `status_menikah` varchar(15) NOT NULL,
  `tempat_lahir` varchar(20) NOT NULL,
  `tgl_lahir` date NOT NULL,
  `tlp` varchar(25) NOT NULL,
  `email` varchar(100) NOT NULL,
  `alamat` text NOT NULL,
  `from_db` varchar(15) NOT NULL,
  `kode_kantor` varchar(25) NOT NULL,
  `tgl_ins` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `tb_penduduk`
--

INSERT INTO `tb_penduduk` (`id_penduduk`, `nik`, `nama`, `jenis_kelamin`, `status_menikah`, `tempat_lahir`, `tgl_lahir`, `tlp`, `email`, `alamat`, `from_db`, `kode_kantor`, `tgl_ins`) VALUES
('KABCJR2023091200001', '3203042104900001', 'Mulyana Yusuf Ina', 'PRIA', '', 'Cianjur', '2023-09-12', '23456789', 'admin@gmail.com', '-', 'ADMIN', 'KABCJR', '2023-09-12 06:37:24');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `tb_penduduk`
--
ALTER TABLE `tb_penduduk`
  ADD PRIMARY KEY (`id_penduduk`,`nik`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
