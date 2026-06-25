# EcoCycle

## 📌 Nama Website
**EcoCycle** — Sistem Informasi Pengelolaan Sampah Berbasis Web

---

## 📖 Deskripsi Singkat

EcoCycle merupakan sistem informasi pengelolaan sampah berbasis web yang dirancang untuk membantu masyarakat dalam proses pelaporan, pengelolaan, pengangkutan, edukasi, serta daur ulang sampah secara digital dan terintegrasi. Sistem ini hadir sebagai solusi terhadap berbagai permasalahan pengelolaan sampah yang masih dilakukan secara manual, seperti keterlambatan pelaporan, kurangnya informasi lokasi sampah, rendahnya kesadaran masyarakat dalam memilah sampah, serta belum optimalnya pemanfaatan sampah yang memiliki nilai ekonomi.

EcoCycle menghubungkan beberapa pihak dalam satu platform, yaitu **masyarakat**, **petugas kebersihan**, **admin sistem**, dan **pengepul / mitra daur ulang**. Sistem ini dilengkapi dengan fitur poin dan reward untuk meningkatkan partisipasi masyarakat dalam menjaga kebersihan lingkungan dan mendukung konsep ekonomi sirkular.

---

## 👥 Anggota Tim & Tanggung Jawab

| Nama | NIM | Role | Tanggung Jawab |
|------|-----|------|----------------|
| Nurhayati Ningsih | F1D02410085 | Fullstack Developer | **Frontend:** Membangun halaman Login, Register, Dashboard Masyarakat, Pelaporan Sampah, Edukasi, Reward, dan Profil. **Backend:** Implementasi PHP Native untuk autentikasi, session, pelaporan sampah (`simpan_laporan.php`), sistem reward point, dan pengelolaan profil. |
| Siti Ananda Rahma | F1D02410095 | Fullstack Developer | **Frontend:** Membangun Dashboard Admin dan Dashboard Petugas. **Backend:** Implementasi CRUD laporan sampah, manajemen status laporan, serta jadwal pengangkutan menggunakan PHP Native dan MySQL. |
| Waritsa Wulan Ramadanis | F1D02410136 | Fullstack Developer | **Frontend:** Membangun halaman Lokasi Pengepul, Marketplace, dan Profil Pengguna. **Backend:** Pengelolaan data mitra daur ulang (`simpan_marketplace.php`), integrasi database MySQL. **Dokumentasi:** Pengujian dan dokumentasi sistem. |

---

## 👤 Pengguna / Aktor Sistem

### 1. Masyarakat
Fitur yang tersedia:
- Login & Register
- Dashboard Masyarakat
- Pelaporan Sampah (foto + GPS + jenis + deskripsi)
- Status Laporan
- Jadwal Pengangkutan
- Lokasi Pengepul
- Marketplace Sampah
- Edukasi Pengelolaan Sampah
- Sistem Poin & Reward
- Profil & Edit Profil

### 2. Petugas Kebersihan
Fitur yang tersedia:
- Login
- Dashboard Petugas
- Manajemen Laporan Masuk
- Jadwal Pengangkutan
- Rute Pengangkutan
- Profil Petugas

### 3. Admin
Fitur yang tersedia:
- Login
- Dashboard Admin
- Manajemen & Verifikasi Laporan
- Manajemen User (lihat, cari, filter, dan hapus akun)
- Profil Admin

### 4. Pengepul / Mitra Daur Ulang
Fitur yang tersedia:
- Login
- Dashboard Pengepul (termasuk daftar & konfirmasi listing Marketplace)
- Profil Mitra

---

## 🗂️ Sitemap / Struktur Menu

```
Public
├── Landing Page (index.html)
├── Login
└── Register

Masyarakat
├── Dashboard
├── Lapor Sampah
├── Status Laporan
├── Jadwal Pengangkutan
├── Lokasi Pengepul
├── Marketplace
├── Edukasi
├── Reward & Poin
└── Profil

Petugas
├── Dashboard
├── Laporan Masuk
├── Jadwal Pengangkutan
├── Rute Pengangkutan
└── Profil

Admin
├── Dashboard
├── Manajemen Laporan
├── Manajemen User
└── Profil

Pengepul
├── Dashboard (termasuk konfirmasi Marketplace)
└── Profil
```

---

## 📁 Struktur Proyek

```
EcoCycle-Rapi
│
├── assets
│   ├── css
│   │   └── style2.css
│   └── js
│       └── script2.js
│
├── actions
│   ├── login.php
│   ├── signUp.php
│   ├── logout.php
│   ├── simpan_laporan.php
│   ├── edit_profil.php
│   └── simpan_marketplace.php
│
├── config
│   └── koneksi.php
│
├── database
│   └── ecocycle.sql
│
├── uploads
│   ├── laporan
│   ├── marketplace
│   └── profil
│
├── pages
│   ├── masyarakat
│   │   ├── dashboard2.php
│   │   ├── lapor.php
│   │   ├── status_laporan.php
│   │   ├── jadwal_pengangkutan.php
│   │   ├── lokasi_pengepul.php
│   │   ├── marketplace_masyarakat.php
│   │   ├── edukasi.php
│   │   ├── reward.php
│   │   └── profilMasyarakat.php
│   │
│   ├── admin
│   │   ├── admin_dashboard.php
│   │   ├── admin_laporan.php
│   │   ├── admin_users.php
│   │   └── profilAdmin.php
│   │
│   ├── petugas
│   │   ├── petugas_dashboard.php
│   │   ├── petugas_laporan.php
│   │   ├── petugas_jadwal.php
│   │   ├── petugas_rute.php
│   │   └── profilPetugas.php
│   │
│   └── pengepul
│       ├── pengepul_dashboard.php
│       └── profilMitra.php
│
└── index.html
```

---

## 🛠️ Tech Stack

| Layer | Teknologi |
|-------|-----------|
| Frontend | HTML5, CSS3, JavaScript |
| Backend | PHP Native |
| Database | MySQL |
| Dev Tools | Visual Studio Code, XAMPP, Git, GitHub |

**Fitur Backend:**
- Session-based Authentication
- Role-based Access Control (masyarakat / petugas / admin / pengepul)
- CRUD Operations
- File Upload Handling (foto laporan, profil, marketplace)
- Server-side Validation
- Database Transaction (mysqli_begin_transaction)

---

## 🗄️ Konfigurasi Database

| Konfigurasi | Detail |
|-------------|--------|
| DBMS | MySQL |
| Nama Database | `ecocycle` |
| Port Default | 3306 |

**Contoh Koneksi Database:**
```php
<?php
$conn = mysqli_connect("localhost", "root", "", "ecocycle");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
```

---

## 📋 Tabel Database Utama

### 1. `users`
Menyimpan data seluruh akun pengguna sistem lintas role.

| Field | Type | Keterangan |
|-------|------|------------|
| id_user | INT (PK, AI) | ID unik pengguna |
| nama | VARCHAR(100) | Nama lengkap |
| email | VARCHAR(50) | Email (UNIQUE) |
| password | VARCHAR(255) | Password ter-hash (bcrypt) |
| role | VARCHAR(50) | `masyarakat` / `petugas` / `admin` / `pengepul` |
| point | INT | Poin reward yang dimiliki (default: 0) |
| foto | VARCHAR(255) | Path foto profil |
| no_hp | VARCHAR(20) | Nomor handphone |
| alamat | TEXT | Alamat lengkap |
| created_at | TIMESTAMP | Waktu registrasi |

### 2. `laporan`
Menyimpan laporan titik penumpukan sampah dari masyarakat.

| Field | Type | Keterangan |
|-------|------|------------|
| id_laporan | INT (PK, AI) | ID laporan |
| id_user | INT (FK → users) | Pelapor |
| foto | VARCHAR(500) | Path foto sampah |
| lokasi | TEXT | Koordinat GPS (lat,lng) |
| jenis_sampah | VARCHAR(100) | Jenis sampah dilaporkan |
| deskripsi | TEXT | Deskripsi kondisi sampah |
| status | VARCHAR(50) | `pending` / `diproses` / `selesai` |
| tanggal | DATETIME | Waktu laporan dibuat |

### 3. `jadwal_pengangkutan`
Menyimpan jadwal pengangkutan sampah per wilayah oleh petugas.

| Field | Type | Keterangan |
|-------|------|------------|
| id_jadwal | INT (PK, AI) | ID jadwal |
| id_petugas | INT (FK → users) | Petugas bertugas |
| area | VARCHAR(100) | Nama wilayah/zona |
| hari | VARCHAR(20) | Nama hari (Senin, dst.) |
| tanggal | DATE | Tanggal pelaksanaan |
| waktu_mulai | TIME | Jam mulai |
| jam_selesai | TIME | Jam selesai |
| rute | TEXT | Rute pengangkutan |
| status | VARCHAR(50) | `terjadwal` / `selesai` / `dibatalkan` |
| keterangan | TEXT | Catatan tambahan |

### 4. `pengangkutan`
Menyimpan data realisasi pengangkutan berdasarkan laporan.

| Field | Type | Keterangan |
|-------|------|------------|
| id_pengangkutan | INT (PK, AI) | ID pengangkutan |
| id_laporan | INT (FK → laporan) | Laporan yang ditangani |
| id_petugas | INT (FK → users) | Petugas yang mengangkut |
| tanggal | DATE | Tanggal pengangkutan |
| berat_sampah | FLOAT | Berat sampah (kg) |
| status | VARCHAR(50) | `menunggu` / `selesai` |

### 5. `edukasi`
Menyimpan konten artikel edukasi pengelolaan sampah.

| Field | Type | Keterangan |
|-------|------|------------|
| id_edukasi | INT (PK, AI) | ID artikel |
| judul | VARCHAR(200) | Judul artikel |
| konten | TEXT | Isi konten |
| kategori | VARCHAR(50) | `Organik` / `Anorganik` / `B3` |
| gambar | VARCHAR(500) | Path gambar ilustrasi |
| tanggal_upload | DATETIME | Waktu upload |

### 6. `harga_sampah`
Menyimpan referensi harga per kg untuk setiap jenis sampah, digunakan sebagai acuan di halaman marketplace masyarakat.

| Field | Type | Keterangan |
|-------|------|------------|
| id_harga | INT (PK, AI) | ID harga |
| jenis_sampah | VARCHAR(50) | Jenis sampah (Plastik, Kertas, Logam, Kaca, Campuran) |
| harga_per_kg | INT | Harga referensi per kilogram (Rp) |

### 7. `marketplace`
Menyimpan listing sampah daur ulang yang dijual masyarakat ke pengepul.

| Field | Type | Keterangan |
|-------|------|------------|
| id_item | INT (PK, AI) | ID listing |
| id_user | INT (FK → users) | Penjual (masyarakat) |
| id_mitra_pengambil | INT (FK → users) | Pengepul yang mengambil |
| jenis_sampah | VARCHAR(50) | Jenis sampah |
| berat_kg | FLOAT | Berat (kg) |
| harga | DECIMAL(10,2) | Harga yang diminta |
| status | VARCHAR(20) | `tersedia` / `diambil` |
| tanggal | DATETIME | Waktu listing dibuat |
| foto | VARCHAR(255) | Path foto sampah (wajib) |

### 8. `mitra_daur_ulang`
Menyimpan data dan lokasi pengepul / mitra daur ulang.

| Field | Type | Keterangan |
|-------|------|------------|
| id_mitra | INT (PK, AI) | ID mitra |
| id_user | INT (FK → users) | Akun terkait |
| nama_mitra | VARCHAR(100) | Nama pengepul/mitra |
| alamat | TEXT | Alamat lengkap |
| no_telpon | VARCHAR(20) | Nomor telepon |
| jenis_sampah | VARCHAR(100) | Jenis sampah diterima |
| latitude | DECIMAL(10,8) | Koordinat latitude |
| longitude | DECIMAL(11,8) | Koordinat longitude |
| status_aktif | TINYINT(1) | 1 = aktif, 0 = nonaktif |

### 8. `reward`
Menyimpan daftar hadiah yang dapat ditukar dengan poin.

| Field | Type | Keterangan |
|-------|------|------------|
| id_reward | INT (PK, AI) | ID reward |
| nama_reward | VARCHAR(100) | Nama hadiah |
| point_required | INT | Poin yang dibutuhkan |
| stok | INT | Jumlah stok tersedia |

### 9. `penukaran_reward`
Menyimpan riwayat penukaran poin oleh masyarakat.

| Field | Type | Keterangan |
|-------|------|------------|
| id_tukar | INT (PK, AI) | ID transaksi tukar |
| id_user | INT (FK → users) | Pengguna penukar |
| id_reward | INT (FK → reward) | Reward yang ditukar |
| tanggal_tukar | DATETIME | Waktu penukaran |
| status | VARCHAR(50) | `diproses` / `selesai` |

**Relasi Antar Tabel:**
- `laporan.id_user` → `users.id_user` (CASCADE DELETE)
- `pengangkutan.id_laporan` → `laporan.id_laporan` (CASCADE DELETE)
- `marketplace.id_user` → `users.id_user` (CASCADE DELETE)
- `mitra_daur_ulang.id_user` → `users.id_user` (SET NULL)
- `penukaran_reward.id_user` → `users.id_user` (CASCADE DELETE)
- `jadwal_pengangkutan.id_petugas` → `users.id_user` (SET NULL)

---

## 🚀 Fitur Utama

### 1. Pelaporan Sampah
Masyarakat dapat melaporkan titik penumpukan sampah dengan mengunggah foto, koordinat GPS otomatis, jenis sampah, dan deskripsi kondisi. Setiap laporan yang berhasil dikirim memberikan +10 poin reward kepada pelapor.

### 2. Sistem Poin & Reward
Masyarakat mendapatkan poin setiap kali berpartisipasi aktif (melaporkan sampah). Poin dapat ditukar dengan berbagai reward seperti voucher pulsa, voucher belanja, atau merchandise EcoCycle.

### 3. Marketplace Sampah
Platform jual-beli sampah daur ulang antara masyarakat dan mitra pengepul. Masyarakat dapat memasang listing sampah terpilah beserta foto (wajib), berat, dan harga yang diinginkan. Pengepul dapat langsung mengonfirmasi pengambilan listing dari dashboard mereka.

### 4. Lokasi Pengepul
Menampilkan peta lokasi mitra daur ulang terdekat beserta informasi jenis sampah yang diterima, alamat, dan kontak.

### 5. Jadwal & Rute Pengangkutan
Masyarakat dapat melihat jadwal pengangkutan sampah di wilayahnya. Petugas mendapatkan informasi rute dan daftar laporan yang perlu ditangani.

### 6. Edukasi Sampah
Menyajikan artikel edukasi mengenai cara memilah dan mengelola sampah berdasarkan kategori: Organik, Anorganik, dan B3 (Bahan Berbahaya dan Beracun).

### 7. Manajemen Laporan (Admin & Petugas)
Admin dapat memverifikasi laporan masuk. Petugas mengelola status pengangkutan dan memperbarui progress penanganan laporan.

### 8. Manajemen User (Admin)
Admin dapat melihat seluruh daftar akun pengguna, melakukan pencarian berdasarkan nama/email, filter berdasarkan role, serta menghapus akun (kecuali akun sendiri).

### 9. Role-based Access Control
Sistem menerapkan autentikasi berbasis sesi dengan pembatasan akses per role. Registrasi akun Admin tidak tersedia melalui form publik dan hanya dapat dibuat langsung di database.

---

## 📊 Status Proyek

✅ **Functional Prototype Completed**

### Fitur yang Sudah Diimplementasikan
- [x] Autentikasi & Sesi (Login, Register, Logout)
- [x] Role-based Access Control (4 role)
- [x] Pelaporan Sampah dengan Upload Foto & GPS
- [x] Sistem Poin Reward Otomatis
- [x] Penukaran Reward
- [x] Marketplace Sampah (termasuk konfirmasi oleh Pengepul)
- [x] Jadwal & Rute Pengangkutan
- [x] Lokasi Pengepul
- [x] Edukasi Sampah
- [x] Edit Profil (semua role)
- [x] Dashboard per Role
- [x] Manajemen Laporan (Admin & Petugas)
- [x] Manajemen User (Admin)

---

## 🐛 Bug Log & Changelog

### v1.1.0 — Update (Juni 2026)

**Fitur Baru:**
- Halaman manajemen user untuk admin (`admin_users.php`): lihat, cari, filter per role, dan hapus akun
- Konfirmasi pengambilan listing marketplace oleh pengepul langsung dari dashboard
- Tabel referensi harga sampah (`harga_sampah`) ditampilkan di halaman marketplace masyarakat

**Perubahan:**
- Marketplace pengepul diintegrasikan ke dalam `pengepul_dashboard.php` (halaman `marketplace_pengepul.php` dihapus)
- Foto pada listing marketplace menjadi wajib diunggah
- Status listing marketplace diubah dari `terjual` menjadi `diambil`

---

### v1.0.0 — Rilis Prototype (Juni 2026)

**Fitur Baru:**
- Sistem autentikasi lengkap dengan role masyarakat, petugas, admin, dan pengepul
- Pelaporan sampah dengan foto dan koordinat GPS
- Sistem poin otomatis (+10 poin) saat laporan berhasil dikirim
- Marketplace sampah daur ulang
- Jadwal dan rute pengangkutan untuk petugas
- Halaman edukasi pengelolaan sampah (Organik, Anorganik, B3)
- Sistem reward & penukaran poin
- Dashboard khusus per role

**Bug yang Ditemukan & Diperbaiki:**

| # | Modul | Deskripsi Bug | Status | Solusi |
|---|-------|---------------|--------|--------|
| 1 | `login.php` | Login tidak memvalidasi role — pengguna bisa masuk ke dashboard role lain | ✅ Diperbaiki | Menambahkan pengecekan `$user['role'] !== $role` sebelum membuat session |
| 2 | `signUp.php` | Role `admin` bisa didaftarkan melalui form registrasi publik | ✅ Diperbaiki | Whitelist role: hanya `masyarakat`, `petugas`, `pengepul` yang diizinkan di form register; admin hanya dibuat langsung di database |
| 3 | `simpan_laporan.php` | Path foto laporan berubah-ubah tergantung dari mana file dieksekusi | ✅ Diperbaiki | Menggunakan `__DIR__` untuk path absolut upload, menyimpan path relatif dari root ke database |
| 4 | `simpan_laporan.php` | Poin ditambahkan meski laporan gagal tersimpan ke database | ✅ Diperbaiki | Menggunakan `mysqli_begin_transaction()` — jika salah satu query gagal, seluruh transaksi di-rollback |
| 5 | `simpan_marketplace.php` | Path foto marketplace sama bermasalahnya seperti foto laporan | ✅ Diperbaiki | Menggunakan `__DIR__` untuk path absolut, konsisten dengan perbaikan pada laporan |
| 6 | `edit_profil.php` | Upload foto profil tidak menampilkan pesan error yang spesifik saat gagal | ✅ Diperbaiki | Menambahkan pemetaan kode error upload PHP (`UPLOAD_ERR_INI_SIZE`, `UPLOAD_ERR_PARTIAL`, dll.) dengan pesan yang informatif |
| 7 | `edit_profil.php` | Redirect profil tidak sesuai role setelah edit — semua diarahkan ke profil masyarakat | ✅ Diperbaiki | Menambahkan conditional redirect berdasarkan `$user['role']` untuk petugas, admin, dan pengepul |
| 8 | `simpan_laporan.php` | Session poin tidak diperbarui setelah laporan berhasil, tampilan poin di dashboard tertinggal hingga logout | ✅ Diperbaiki | Menambahkan `$_SESSION['user']['point'] += $poin` setelah commit transaksi berhasil |

**Bug yang Diketahui (Belum Diperbaiki):**

| # | Modul | Deskripsi | Prioritas |
|---|-------|-----------|-----------|
| 1 | Admin | Tidak ada fitur manajemen data master (edukasi, reward, jadwal) dari UI admin | Medium |
| 2 | Petugas | Fitur update berat sampah setelah pengangkutan belum terintegrasi ke tabel `pengangkutan` | Low |

---

## 🔮 Rencana Pengembangan

- Notifikasi real-time status laporan (email / push notification)
- Integrasi peta interaktif (Google Maps / Leaflet.js) untuk lokasi pengepul dan rute petugas
- Dashboard statistik pengelolaan sampah (volume, partisipasi, tren per wilayah)
- Panel admin lengkap untuk manajemen data master (edukasi, reward, jadwal)
- Versi aplikasi mobile (Android / iOS)
- Sistem tracking status laporan real-time

---

## 🎯 Tujuan Proyek

EcoCycle bertujuan untuk mendigitalisasi dan mengintegrasikan proses pengelolaan sampah dari hulu ke hilir — mulai dari pelaporan oleh masyarakat, penanganan oleh petugas, hingga pemanfaatan kembali oleh mitra daur ulang. Dengan pendekatan berbasis insentif (poin & reward), sistem ini diharapkan meningkatkan partisipasi aktif masyarakat dalam menjaga kebersihan lingkungan, mendorong kebiasaan memilah sampah, serta mendukung terwujudnya ekonomi sirkular di tingkat lokal.

---

## 🖼️ Tampilan Website

### Halaman Login
Halaman autentikasi dengan pilihan role (masyarakat, petugas, admin, pengepul). Validasi role dilakukan di sisi server untuk mencegah akses tidak sah.

### Dashboard Masyarakat
Halaman utama masyarakat yang menampilkan ringkasan poin reward, shortcut ke fitur pelaporan, status laporan terakhir, dan informasi jadwal pengangkutan.

### Dashboard Pengepul / Mitra
Halaman utama pengepul yang menampilkan listing sampah tersedia di marketplace beserta informasi jenis, berat, dan harga yang ditawarkan masyarakat.

### Edit Profil
Form pengeditan data profil yang dapat diakses oleh semua role (masyarakat, petugas, admin, pengepul) dengan redirect otomatis ke halaman profil masing-masing role setelah perubahan disimpan.

---

## 🤖 Penggunaan AI dalam Kode

Kami menggunakan AI sebagai alat bantu dalam beberapa bagian pengembangan, di antaranya:

- **`simpan_laporan.php`**: AI membantu menyusun logika transaksi database (`mysqli_begin_transaction`) untuk memastikan konsistensi data antara penyimpanan laporan dan penambahan poin reward.
- **`edit_profil.php`**: AI membantu menyusun pemetaan kode error upload PHP menjadi pesan yang lebih informatif bagi pengguna.
- **`login.php`**: AI membantu menyusun logika validasi role mismatch dan mekanisme redirect berbasis role ke dashboard yang sesuai.

---

## Beberapa Tampilan Website
Halaman Login EcoCycle
<img width="1600" height="838" alt="login" src="https://github.com/user-attachments/assets/358a67d4-848e-472e-b673-f112bc59c5a0" />

Halaman Dashboard Mitra/Pengepul EcoCycle
<img width="1600" height="838" alt="hm pengepul" src="https://github.com/user-attachments/assets/b654d11b-9807-47af-bc06-44f43182b44a" />

Halaman Edit Profil Admin
<img width="1600" height="835" alt="edit profil" src="https://github.com/user-attachments/assets/f0a0833d-975f-4dcb-98b7-34bb6ca6f23d" />




