SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `edukasi` (
  `id_edukasi` int(11) NOT NULL,
  `judul` varchar(200) NOT NULL,
  `konten` text NOT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `gambar` varchar(500) DEFAULT NULL,
  `tanggal_upload` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `edukasi`
--

INSERT INTO `edukasi` (`id_edukasi`, `judul`, `konten`, `kategori`, `gambar`, `tanggal_upload`) VALUES
(1, 'Cara Memilah Sampah Organik', 'Sampah organik adalah sampah yang dapat terurai secara alami, seperti sisa makanan, daun, dan kulit buah. Pisahkan sampah organik ke tempat sampah berwarna hijau agar dapat diolah menjadi kompos.', 'Organik', NULL, '2026-06-23 10:03:51'),
(2, 'Mengenal Sampah Anorganik', 'Sampah anorganik adalah sampah yang sulit terurai seperti plastik, kaca, dan logam. Sampah ini sebaiknya dibersihkan dan dikumpulkan terpisah agar dapat didaur ulang oleh mitra pengepul.', 'Anorganik', NULL, '2026-06-23 10:03:51'),
(3, 'Bahaya Sampah B3 dan Cara Penanganannya', 'Sampah B3 (Bahan Berbahaya dan Beracun) seperti baterai, lampu bekas, dan obat kadaluarsa harus ditangani secara khusus karena dapat mencemari lingkungan. Jangan dicampur dengan sampah rumah tangga biasa.', 'B3', NULL, '2026-06-23 10:03:51');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jadwal_pengangkutan`
--

CREATE TABLE `jadwal_pengangkutan` (
  `id_jadwal` int(11) NOT NULL,
  `id_petugas` int(11) DEFAULT NULL,
  `area` varchar(100) DEFAULT NULL,
  `hari` varchar(20) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `waktu_mulai` time DEFAULT NULL,
  `jam_selesai` time DEFAULT NULL,
  `rute` text DEFAULT NULL,
  `status` varchar(50) DEFAULT 'terjadwal',
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jadwal_pengangkutan`
--

INSERT INTO `jadwal_pengangkutan` (`id_jadwal`, `id_petugas`, `area`, `hari`, `tanggal`, `waktu_mulai`, `jam_selesai`, `rute`, `status`, `keterangan`) VALUES
(1, NULL, 'Wilayah 1 - Cakranegara', 'Senin', '2026-06-15', '08:00:00', '11:00:00', 'Jl. Pejanggik - Jl. Selaparang - Jl. Panca Usaha', 'terjadwal', 'Bawa kendaraan roda tiga'),
(2, NULL, 'Wilayah 2 - Ampenan', 'Rabu', '2026-06-17', '08:00:00', '11:00:00', 'Jl. Niaga - Jl. Pabean - Jl. Yos Sudarso', 'terjadwal', NULL),
(3, NULL, 'Wilayah 3 - Mataram', 'Jumat', '2026-06-19', '08:00:00', '11:00:00', 'Jl. Majapahit - Jl. Catur Warga - Jl. Airlangga', 'terjadwal', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `laporan`
--

CREATE TABLE `laporan` (
  `id_laporan` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `foto` varchar(500) DEFAULT NULL,
  `lokasi` text DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `jenis_sampah` varchar(100) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `tanggal` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `laporan`
--

INSERT INTO `laporan` (`id_laporan`, `id_user`, `foto`, `lokasi`, `jenis_sampah`, `deskripsi`, `status`, `tanggal`) VALUES
(1, 9, 'uploads/laporan/lap_9_1782181288.jpeg', '-8.577815,116.074297', 'Campuran', 'numpuk', 'diproses', '2026-06-23 10:21:28');

-- --------------------------------------------------------

--
-- Struktur dari tabel `harga_sampah`
--

CREATE TABLE `harga_sampah` (
  `id_harga` int(11) NOT NULL AUTO_INCREMENT,
  `jenis_sampah` varchar(50) NOT NULL,
  `harga_per_kg` int(11) NOT NULL,
  PRIMARY KEY (`id_harga`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `harga_sampah`
--

INSERT INTO `harga_sampah` (`jenis_sampah`, `harga_per_kg`) VALUES
('Plastik', 3000),
('Kertas', 1500),
('Logam', 5000),
('Kaca', 800),
('Campuran', 4000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `marketplace`
--

CREATE TABLE `marketplace` (
  `id_item` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_mitra_pengambil` int(11) DEFAULT NULL,
  `jenis_sampah` varchar(50) DEFAULT NULL,
  `berat_kg` float DEFAULT NULL,
  `harga` decimal(10,2) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'tersedia',
  `tanggal` datetime DEFAULT current_timestamp(),
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `mitra_daur_ulang`
--

CREATE TABLE `mitra_daur_ulang` (
  `id_mitra` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `nama_mitra` varchar(100) NOT NULL,
  `alamat` text DEFAULT NULL,
  `no_telpon` varchar(20) DEFAULT NULL,
  `jenis_sampah` varchar(100) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `status_aktif` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `mitra_daur_ulang`
--

INSERT INTO `mitra_daur_ulang` (`id_mitra`, `id_user`, `nama_mitra`, `alamat`, `no_telpon`, `jenis_sampah`, `latitude`, `longitude`, `status_aktif`) VALUES
(1, 2, 'CV Daur Ulang NTB', 'Jl. Industri No.5, Mataram', '081111222333', 'Plastik, Kertas', -8.58333300, 116.11666700, 1),
(2, 3, 'Bank Sampah Hijau Lestari', 'Jl. Pejanggik No.10, Mataram', '081234567890', 'Plastik, Logam', -8.58500000, 116.11000000, 1),
(3, 4, 'Komunitas Daur Ulang Sasambo', 'Jl. Majapahit No.22, Mataram', '081298765432', 'Kertas, Kaca, Plastik', -8.58000000, 116.12000000, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengangkutan`
--

CREATE TABLE `pengangkutan` (
  `id_pengangkutan` int(11) NOT NULL,
  `id_laporan` int(11) NOT NULL,
  `id_petugas` int(11) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `berat_sampah` float DEFAULT NULL,
  `status` varchar(50) DEFAULT 'menunggu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `penukaran_reward`
--

CREATE TABLE `penukaran_reward` (
  `id_tukar` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_reward` int(11) NOT NULL,
  `tanggal_tukar` datetime DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT 'diproses'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `reward`
--

CREATE TABLE `reward` (
  `id_reward` int(11) NOT NULL,
  `nama_reward` varchar(100) NOT NULL,
  `point_required` int(11) NOT NULL,
  `stok` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `reward`
--

INSERT INTO `reward` (`id_reward`, `nama_reward`, `point_required`, `stok`) VALUES
(1, 'Voucher Pulsa Rp10.000', 50, 20),
(2, 'Voucher Belanja Rp20.000', 100, 15),
(3, 'Tumbler EcoCycle', 150, 10),
(4, 'Voucher Rp50.000', 250, 5);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) DEFAULT 'masyarakat',
  `point` int(11) DEFAULT 0,
  `foto` varchar(255) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `nama`, `email`, `password`, `role`, `point`, `foto`, `no_hp`, `alamat`, `created_at`) VALUES
(1, 'Ningsih', 'admin@ecocycle.com', '$2y$10$usuzQ4fsy39OAmMmGUBiguWSgLoy/r5QnygTJGBTWef.xYryMgXoe', 'admin', 0, 'uploads/profil/profil_1_1782181860.jpg', '08772345678', 'montong golong', '2026-06-23 02:03:51'),
(2, 'CV Daur Ulang NTB', 'cvdaurulangntb@ecocycle.com', '$2y$10$LISmTl3ZpnyVhw3Imc2jeOu3NWcLIFt21V9cbnjiCBIIPCQeRp8ia', 'pengepul', 0, NULL, NULL, NULL, '2026-06-23 02:03:51'),
(3, 'Bank Sampah Hijau Lestari', 'banksampahhijau@ecocycle.com', '$2y$10$LmQQXv2PGl5YGDkkGOmIbu3Agayi0K60Ge23x3lVlxwbbayIuJ/wa', 'pengepul', 0, NULL, NULL, NULL, '2026-06-23 02:03:51'),
(4, 'Komunitas Daur Ulang Sasambo', 'sasambo@ecocycle.com', '$2y$10$593tQ/6eZ6Cqhjf0UN8kAeJq9.wqiqIsECXRVNwr5DOxlZpCXHi4m', 'pengepul', 0, NULL, NULL, NULL, '2026-06-23 02:03:51'),
(5, 'Nurhayati Ningsih', 'ningsih@gmail.com', '$2y$10$l8drrkq22MRtKYMTfwuixuIptc4biA8jv9IccWx8VG7FyxjFIGyRu', 'masyarakat', 0, NULL, NULL, NULL, '2026-06-23 02:05:23'),
(6, 'Abdul Hayi', 'abdul@gmail.com', '$2y$10$Rv5QjtTaQ5ORQP1x0uQ.mOmh/j.//zzRYV790Cp5aLCiZkOMrEs1a', 'pengepul', 0, NULL, NULL, NULL, '2026-06-23 02:05:47'),
(7, 'Zulpahrianto', 'jull@gmail.com', '$2y$10$3YGQprvuq9foOQBSN74yiuWKWEWQPEkkZoCOM6e6ENiPRZ/bLr1Ae', 'petugas', 0, NULL, NULL, NULL, '2026-06-23 02:06:14'),
(8, 'Nurhayati Ningsih', 'nn462@gmail.com', '$2y$10$WQw8Fg8Yk1f7VU1j.tY/WOvyuwRnpmZikC1Ussz9UE48/P5V5BOTG', 'masyarakat', 0, NULL, NULL, NULL, '2026-06-23 02:06:53'),
(9, 'Wida Ariyani', 'widaaryani11@gmail.com', '$2y$10$P/2yb2NFNH9on89tPE0rPO5Bv1bycU0boRTwjp8tmq2u4KYIKgyN2', 'masyarakat', 10, 'uploads/profil/profil_9_1782182501.jpg', '08772345678', 'Montong Golong', '2026-06-23 02:16:07');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `edukasi`
--
ALTER TABLE `edukasi`
  ADD PRIMARY KEY (`id_edukasi`);

--
-- Indeks untuk tabel `jadwal_pengangkutan`
--
ALTER TABLE `jadwal_pengangkutan`
  ADD PRIMARY KEY (`id_jadwal`),
  ADD KEY `id_petugas` (`id_petugas`);

--
-- Indeks untuk tabel `laporan`
--
ALTER TABLE `laporan`
  ADD PRIMARY KEY (`id_laporan`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `marketplace`
--
ALTER TABLE `marketplace`
  ADD PRIMARY KEY (`id_item`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_mitra_pengambil` (`id_mitra_pengambil`);

--
-- Indeks untuk tabel `mitra_daur_ulang`
--
ALTER TABLE `mitra_daur_ulang`
  ADD PRIMARY KEY (`id_mitra`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `pengangkutan`
--
ALTER TABLE `pengangkutan`
  ADD PRIMARY KEY (`id_pengangkutan`),
  ADD KEY `id_laporan` (`id_laporan`),
  ADD KEY `id_petugas` (`id_petugas`);

--
-- Indeks untuk tabel `penukaran_reward`
--
ALTER TABLE `penukaran_reward`
  ADD PRIMARY KEY (`id_tukar`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_reward` (`id_reward`);

--
-- Indeks untuk tabel `reward`
--
ALTER TABLE `reward`
  ADD PRIMARY KEY (`id_reward`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `edukasi`
--
ALTER TABLE `edukasi`
  MODIFY `id_edukasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `jadwal_pengangkutan`
--
ALTER TABLE `jadwal_pengangkutan`
  MODIFY `id_jadwal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `laporan`
--
ALTER TABLE `laporan`
  MODIFY `id_laporan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `marketplace`
--
ALTER TABLE `marketplace`
  MODIFY `id_item` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `mitra_daur_ulang`
--
ALTER TABLE `mitra_daur_ulang`
  MODIFY `id_mitra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `pengangkutan`
--
ALTER TABLE `pengangkutan`
  MODIFY `id_pengangkutan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `penukaran_reward`
--
ALTER TABLE `penukaran_reward`
  MODIFY `id_tukar` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `reward`
--
ALTER TABLE `reward`
  MODIFY `id_reward` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `jadwal_pengangkutan`
--
ALTER TABLE `jadwal_pengangkutan`
  ADD CONSTRAINT `jadwal_pengangkutan_ibfk_1` FOREIGN KEY (`id_petugas`) REFERENCES `users` (`id_user`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `laporan`
--
ALTER TABLE `laporan`
  ADD CONSTRAINT `laporan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `marketplace`
--
ALTER TABLE `marketplace`
  ADD CONSTRAINT `marketplace_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `marketplace_ibfk_2` FOREIGN KEY (`id_mitra_pengambil`) REFERENCES `users` (`id_user`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `mitra_daur_ulang`
--
ALTER TABLE `mitra_daur_ulang`
  ADD CONSTRAINT `mitra_daur_ulang_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `pengangkutan`
--
ALTER TABLE `pengangkutan`
  ADD CONSTRAINT `pengangkutan_ibfk_1` FOREIGN KEY (`id_laporan`) REFERENCES `laporan` (`id_laporan`) ON DELETE CASCADE,
  ADD CONSTRAINT `pengangkutan_ibfk_2` FOREIGN KEY (`id_petugas`) REFERENCES `users` (`id_user`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `penukaran_reward`
--
ALTER TABLE `penukaran_reward`
  ADD CONSTRAINT `penukaran_reward_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `penukaran_reward_ibfk_2` FOREIGN KEY (`id_reward`) REFERENCES `reward` (`id_reward`) ON DELETE CASCADE;
COMMIT;