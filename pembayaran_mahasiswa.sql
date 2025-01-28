-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 28 Jan 2025 pada 07.02
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pembayaran_mahasiswa`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `bayaran_mahasiswa`
--

CREATE TABLE `bayaran_mahasiswa` (
  `id_bayaran` int(11) NOT NULL,
  `id_mahasiswa` int(11) NOT NULL,
  `jumlah_bayaran` decimal(15,2) NOT NULL,
  `tanggal_pembayaran` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `bayaran_mahasiswa`
--

INSERT INTO `bayaran_mahasiswa` (`id_bayaran`, `id_mahasiswa`, `jumlah_bayaran`, `tanggal_pembayaran`) VALUES
(17, 16, 4500000.00, '2025-01-28'),
(18, 17, 3500000.00, '2025-01-28'),
(19, 18, 3000000.00, '2025-01-28'),
(20, 19, 3500000.00, '2025-01-28'),
(21, 21, 2500000.00, '2025-01-28');

--
-- Trigger `bayaran_mahasiswa`
--
DELIMITER $$
CREATE TRIGGER `after_bayaran_mahasiswa` AFTER INSERT ON `bayaran_mahasiswa` FOR EACH ROW BEGIN
    DECLARE nama_mahasiswa VARCHAR(100);
    DECLARE nim_mahasiswa VARCHAR(20);

    -- Mendapatkan nama dan NIM mahasiswa berdasarkan id_mahasiswa
    SELECT u.nama, m.nim
    INTO nama_mahasiswa, nim_mahasiswa
    FROM Mahasiswa m
    INNER JOIN Users u ON m.id_user = u.id_user
    WHERE m.id_mahasiswa = NEW.id_mahasiswa;

    -- Menyimpan laporan pembayaran yang lebih detail ke tabel log
    INSERT INTO log (deskripsi_laporan, tanggal)
    VALUES (
        CONCAT(
            'Pembayaran sebesar ', NEW.jumlah_bayaran, 
            ' untuk mahasiswa ', nama_mahasiswa, 
            ' (NIM: ', nim_mahasiswa, 
            ') pada tanggal ', NEW.tanggal_pembayaran
        ),
        NEW.tanggal_pembayaran
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `fakultas`
--

CREATE TABLE `fakultas` (
  `id_fakultas` int(11) NOT NULL,
  `nama_fakultas` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `fakultas`
--

INSERT INTO `fakultas` (`id_fakultas`, `nama_fakultas`) VALUES
(1, 'Fakultas Teknik'),
(2, 'Fakultas Ekonomi'),
(3, 'FSIP (Fakultas Ilmu Sosial dan Ilmu Politik)'),
(4, 'Fakultas Hukum'),
(5, 'Fakultas Kesehatan'),
(6, 'FKIP (Fakultas Keguruan dan Ilmu Pendidikan)');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jurusan`
--

CREATE TABLE `jurusan` (
  `id_jurusan` int(11) NOT NULL,
  `nama_jurusan` varchar(100) NOT NULL,
  `id_fakultas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jurusan`
--

INSERT INTO `jurusan` (`id_jurusan`, `nama_jurusan`, `id_fakultas`) VALUES
(1, 'S1 Teknik Informatika', 1),
(2, 'D3 Teknik Informatika', 1),
(3, 'S1 Manajemen', 2),
(4, 'S1 Ilmu Komunikasi', 3),
(5, 'S1 Hukum Islam', 4),
(6, 'S1 Pendidikan Guru Sekolah Dasar', 6),
(7, 'S1 Pendidikan Matematika', 6),
(8, 'D3 Keperawatan', 5);

-- --------------------------------------------------------

--
-- Struktur dari tabel `log`
--

CREATE TABLE `log` (
  `id_log` int(11) NOT NULL,
  `deskripsi_laporan` text NOT NULL,
  `tanggal` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `log`
--

INSERT INTO `log` (`id_log`, `deskripsi_laporan`, `tanggal`) VALUES
(17, 'Pembayaran sebesar 4500000.00 untuk mahasiswa Rita (NIM: M0001) pada tanggal 2025-01-28', '2025-01-28'),
(18, 'Pembayaran sebesar 3500000.00 untuk mahasiswa Irwan (NIM: M0002) pada tanggal 2025-01-28', '2025-01-28'),
(19, 'Pembayaran sebesar 3000000.00 untuk mahasiswa Lina (NIM: M0003) pada tanggal 2025-01-28', '2025-01-28'),
(20, 'Pembayaran sebesar 3500000.00 untuk mahasiswa leo (NIM: M0004) pada tanggal 2025-01-28', '2025-01-28'),
(21, 'Pembayaran sebesar 2500000.00 untuk mahasiswa Siti (NIM: M0005) pada tanggal 2025-01-28', '2025-01-28');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `id_mahasiswa` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nim` varchar(20) NOT NULL,
  `total_tagihan` decimal(20,0) NOT NULL,
  `id_jurusan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `mahasiswa`
--

INSERT INTO `mahasiswa` (`id_mahasiswa`, `id_user`, `nim`, `total_tagihan`, `id_jurusan`) VALUES
(16, 4, 'M0001', 4500000, 1),
(17, 5, 'M0002', 3500000, 2),
(18, 10, 'M0003', 4000000, 5),
(19, 87, 'M0004', 5000000, 5),
(21, 6, 'M0005', 2500000, 7);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','tu','dosen','mahasiswa') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `nama`, `username`, `password`, `role`) VALUES
(1, 'Budi', 'budi', 'password1', 'admin'),
(2, 'Ani', 'ani', 'password2', 'tu'),
(3, 'Dono', 'dono', 'password2', 'tu'),
(4, 'Rita', 'rita', 'password3', 'mahasiswa'),
(5, 'Irwan', 'irwan', 'password3', 'mahasiswa'),
(6, 'Siti', 'siti', 'password3', 'mahasiswa'),
(7, 'Andi', 'andi', 'password3', 'mahasiswa'),
(8, 'Nina', 'nina', 'password3', 'mahasiswa'),
(9, 'Farid', 'farid', 'password3', 'mahasiswa'),
(10, 'Lina', 'lina', 'password3', 'mahasiswa'),
(11, 'Bayu', 'bayu', 'password3', 'mahasiswa'),
(12, 'Rina', 'rina', 'password3', 'mahasiswa'),
(13, 'Deni', 'deni', 'password3', 'mahasiswa'),
(14, 'Citra', 'citra', 'password3', 'mahasiswa'),
(15, 'Fajar', 'fajar', 'password3', 'mahasiswa'),
(16, 'Novi', 'novi', 'password3', 'mahasiswa'),
(17, 'Agus', 'agus', 'password3', 'mahasiswa'),
(18, 'Wati', 'wati', 'password3', 'mahasiswa'),
(19, 'Yoga', 'yoga', 'password3', 'mahasiswa'),
(20, 'Tina', 'tina', 'password3', 'mahasiswa'),
(85, 'maul', 'maul', 'password3', 'mahasiswa'),
(86, 'amin', 'amin', 'password3', 'mahasiswa'),
(87, 'leo', 'leo', 'password3', 'mahasiswa');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `bayaran_mahasiswa`
--
ALTER TABLE `bayaran_mahasiswa`
  ADD PRIMARY KEY (`id_bayaran`),
  ADD KEY `id_mahasiswa` (`id_mahasiswa`);

--
-- Indeks untuk tabel `fakultas`
--
ALTER TABLE `fakultas`
  ADD PRIMARY KEY (`id_fakultas`);

--
-- Indeks untuk tabel `jurusan`
--
ALTER TABLE `jurusan`
  ADD PRIMARY KEY (`id_jurusan`),
  ADD KEY `id_fakultas` (`id_fakultas`);

--
-- Indeks untuk tabel `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id_log`);

--
-- Indeks untuk tabel `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`id_mahasiswa`),
  ADD UNIQUE KEY `nim` (`nim`),
  ADD KEY `id_jurusan` (`id_jurusan`),
  ADD KEY `mahasiswa_ibfk_1` (`id_user`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `bayaran_mahasiswa`
--
ALTER TABLE `bayaran_mahasiswa`
  MODIFY `id_bayaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `fakultas`
--
ALTER TABLE `fakultas`
  MODIFY `id_fakultas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `jurusan`
--
ALTER TABLE `jurusan`
  MODIFY `id_jurusan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `log`
--
ALTER TABLE `log`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `mahasiswa`
--
ALTER TABLE `mahasiswa`
  MODIFY `id_mahasiswa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `bayaran_mahasiswa`
--
ALTER TABLE `bayaran_mahasiswa`
  ADD CONSTRAINT `bayaran_mahasiswa_ibfk_1` FOREIGN KEY (`id_mahasiswa`) REFERENCES `mahasiswa` (`id_mahasiswa`);

--
-- Ketidakleluasaan untuk tabel `jurusan`
--
ALTER TABLE `jurusan`
  ADD CONSTRAINT `jurusan_ibfk_1` FOREIGN KEY (`id_fakultas`) REFERENCES `fakultas` (`id_fakultas`);

--
-- Ketidakleluasaan untuk tabel `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD CONSTRAINT `mahasiswa_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `mahasiswa_ibfk_2` FOREIGN KEY (`id_jurusan`) REFERENCES `jurusan` (`id_jurusan`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
