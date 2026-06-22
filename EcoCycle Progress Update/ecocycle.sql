-- =========================================================
-- DATABASE ECOCYCLE
-- =========================================================
CREATE DATABASE IF NOT EXISTS ecocycle;
USE ecocycle;

-- =========================================================
-- TABEL USERS
-- =========================================================
CREATE TABLE IF NOT EXISTS users (
  id_user INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  email VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role VARCHAR(50) DEFAULT 'masyarakat',
  point INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================================================
-- TABEL LAPORAN (Pelaporan Sampah)
-- =========================================================
CREATE TABLE IF NOT EXISTS laporan (
  id_laporan INT AUTO_INCREMENT PRIMARY KEY,
  id_user INT NOT NULL,
  foto VARCHAR(500),
  lokasi TEXT,
  jenis_sampah VARCHAR(100),
  deskripsi TEXT,
  status VARCHAR(50) DEFAULT 'pending', -- pending, diproses, selesai, ditolak
  tanggal DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE
);

-- =========================================================
-- TABEL PENGANGKUTAN
-- =========================================================
CREATE TABLE IF NOT EXISTS pengangkutan (
  id_pengangkutan INT AUTO_INCREMENT PRIMARY KEY,
  id_laporan INT NOT NULL,
  id_petugas INT,
  tanggal DATE,
  berat_sampah FLOAT,
  status VARCHAR(50) DEFAULT 'menunggu',
  FOREIGN KEY (id_laporan) REFERENCES laporan(id_laporan) ON DELETE CASCADE,
  FOREIGN KEY (id_petugas) REFERENCES users(id_user) ON DELETE SET NULL
);

-- =========================================================
-- TABEL EDUKASI
-- =========================================================
CREATE TABLE IF NOT EXISTS edukasi (
  id_edukasi INT AUTO_INCREMENT PRIMARY KEY,
  judul VARCHAR(200) NOT NULL,
  konten TEXT NOT NULL,
  kategori VARCHAR(50), -- organik, anorganik, B3
  gambar VARCHAR(500),
  tanggal_upload DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- =========================================================
-- TABEL MITRA DAUR ULANG (Pengepul)
-- =========================================================
CREATE TABLE IF NOT EXISTS mitra_daur_ulang (
  id_mitra INT AUTO_INCREMENT PRIMARY KEY,
  nama_mitra VARCHAR(100) NOT NULL,
  alamat TEXT,
  no_telpon VARCHAR(20),
  jenis_sampah VARCHAR(100),
  latitude DECIMAL(10,8),
  longitude DECIMAL(11,8),
  status_aktif TINYINT(1) DEFAULT 1
);

-- =========================================================
-- TABEL JADWAL PENGANGKUTAN
-- =========================================================
CREATE TABLE IF NOT EXISTS jadwal_pengangkutan (
  id_jadwal INT AUTO_INCREMENT PRIMARY KEY,
  id_petugas INT,
  area VARCHAR(100),
  hari VARCHAR(20),
  tanggal DATE,
  waktu_mulai TIME,
  jam_selesai TIME,
  rute TEXT,
  status VARCHAR(50) DEFAULT 'terjadwal',
  keterangan TEXT,
  FOREIGN KEY (id_petugas) REFERENCES users(id_user) ON DELETE SET NULL
);

-- =========================================================
-- TABEL REWARD
-- =========================================================
CREATE TABLE IF NOT EXISTS reward (
  id_reward INT AUTO_INCREMENT PRIMARY KEY,
  nama_reward VARCHAR(100) NOT NULL,
  point_required INT NOT NULL,
  stok INT DEFAULT 0
);

-- =========================================================
-- TABEL PENUKARAN REWARD
-- =========================================================
CREATE TABLE IF NOT EXISTS penukaran_reward (
  id_tukar INT AUTO_INCREMENT PRIMARY KEY,
  id_user INT NOT NULL,
  id_reward INT NOT NULL,
  tanggal_tukar DATETIME DEFAULT CURRENT_TIMESTAMP,
  status VARCHAR(50) DEFAULT 'diproses',
  FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE,
  FOREIGN KEY (id_reward) REFERENCES reward(id_reward) ON DELETE CASCADE
);

-- =========================================================
-- TABEL MARKETPLACE
-- =========================================================
CREATE TABLE IF NOT EXISTS marketplace (
  id_item INT AUTO_INCREMENT PRIMARY KEY,
  id_user INT,
  id_mitra INT,
  jenis_sampah VARCHAR(50),
  berat_kg FLOAT,
  harga DECIMAL(10,2),
  status VARCHAR(20) DEFAULT 'tersedia',
  tanggal DATETIME DEFAULT CURRENT_TIMESTAMP,
  foto VARCHAR(255),
  FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE,
  FOREIGN KEY (id_mitra) REFERENCES mitra_daur_ulang(id_mitra) ON DELETE SET NULL
);

-- =========================================================
-- MENAMBAHKAN KOLOM TAMBAHAN PADA TABEL USERS
-- =========================================================
ALTER TABLE users
ADD COLUMN foto VARCHAR(255) NULL,
ADD COLUMN no_hp VARCHAR(20) NULL,
ADD COLUMN alamat TEXT NULL,
ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

-- =========================================================
-- DATA AWAL (SEED)
-- =========================================================

-- Edukasi sampah
INSERT INTO edukasi (judul, konten, kategori) VALUES
('Cara Memilah Sampah Organik', 'Sampah organik adalah sampah yang dapat terurai secara alami, seperti sisa makanan, daun, dan kulit buah. Pisahkan sampah organik ke tempat sampah berwarna hijau agar dapat diolah menjadi kompos.', 'Organik'),
('Mengenal Sampah Anorganik', 'Sampah anorganik adalah sampah yang sulit terurai seperti plastik, kaca, dan logam. Sampah ini sebaiknya dibersihkan dan dikumpulkan terpisah agar dapat didaur ulang oleh mitra pengepul.', 'Anorganik'),
('Bahaya Sampah B3 dan Cara Penanganannya', 'Sampah B3 (Bahan Berbahaya dan Beracun) seperti baterai, lampu bekas, dan obat kadaluarsa harus ditangani secara khusus karena dapat mencemari lingkungan. Jangan dicampur dengan sampah rumah tangga biasa.', 'B3');

-- Reward
INSERT INTO reward (nama_reward, point_required, stok) VALUES
('Voucher Pulsa Rp10.000', 50, 20),
('Voucher Belanja Rp20.000', 100, 15),
('Tumbler EcoCycle', 150, 10),
('Voucher Rp50.000', 250, 5);

-- Mitra Daur Ulang
INSERT INTO mitra_daur_ulang (nama_mitra, alamat, no_telpon, jenis_sampah, latitude, longitude, status_aktif) VALUES
('CV Daur Ulang NTB', 'Jl. Industri No.5, Mataram','081111222333', 'Plastik, Kertas', -8.583333, 116.116667, 1),
('Bank Sampah Hijau Lestari', 'Jl. Pejanggik No.10, Mataram', '081234567890', 'Plastik, Logam', -8.585000, 116.110000, 1),
('Komunitas Daur Ulang Sasambo', 'Jl. Majapahit No.22, Mataram', '081298765432', 'Kertas, Kaca, Plastik', -8.580000, 116.120000, 1);

INSERT INTO mitra_daur_ulang (nama_mitra, alamat, no_telpon, jenis_sampah, latitude, longitude, status_aktif) VALUES
('CV Daur Ulang NTB', 'Jl. Industri No.5, Mataram', '081111222333', 'Plastik, Kertas', -8.583333, 116.116667, 1),
('Bank Sampah Hijau Lestari', 'Jl. Pejanggik No.10, Mataram', '081234567890', 'Plastik, Logam', -8.585000, 116.110000, 1),
('Komunitas Daur Ulang Sasambo', 'Jl. Majapahit No.22, Mataram', '081298765432', 'Kertas, Kaca, Plastik', -8.580000, 116.120000, 1);

-- Jadwal Pengangkutan contoh
INSERT INTO jadwal_pengangkutan (id_petugas, area, hari, tanggal, waktu_mulai, jam_selesai, rute, status, keterangan) VALUES
(NULL, 'Wilayah 1 - Cakranegara', 'Senin', '2026-06-15', '08:00:00', '11:00:00', 'Jl. Pejanggik - Jl. Selaparang - Jl. Panca Usaha', 'terjadwal', 'Bawa kendaraan roda tiga'),
(NULL, 'Wilayah 2 - Ampenan', 'Rabu', '2026-06-17', '08:00:00', '11:00:00', 'Jl. Niaga - Jl. Pabean - Jl. Yos Sudarso', 'terjadwal', NULL),
(NULL, 'Wilayah 3 - Mataram', 'Jumat', '2026-06-19', '08:00:00', '11:00:00', 'Jl. Majapahit - Jl. Catur Warga - Jl. Airlangga', 'terjadwal', NULL);
