# EcoCycle
EcoCycle merupakan sistem informasi pengelolaan sampah berbasis web yang dirancang untuk membantu masyarakat dalam proses pelaporan, pengelolaan, pengangkutan, edukasi, serta daur ulang sampah secara digital dan terintegrasi. Sistem ini hadir sebagai solusi terhadap berbagai permasalahan pengelolaan sampah yang masih dilakukan secara manual, seperti keterlambatan pelaporan, kurangnya informasi lokasi sampah, rendahnya kesadaran masyarakat dalam memilah sampah, serta belum optimalnya pemanfaatan sampah yang memiliki nilai ekonomi.

EcoCycle menghubungkan beberapa pihak dalam satu platform, yaitu masyarakat, petugas kebersihan, admin sistem, dan pengepul atau mitra daur ulang. Melalui sistem ini, masyarakat dapat melaporkan titik penumpukan sampah dengan mengunggah foto dan lokasi secara langsung, memperoleh edukasi mengenai pengelolaan dan pemilahan sampah, serta mengetahui lokasi pengepul atau tempat daur ulang sampah di sekitar mereka.

Selain itu, EcoCycle juga menyediakan fitur monitoring dan pengelolaan data bagi admin serta membantu pengepul atau mitra daur ulang dalam memperoleh informasi mengenai ketersediaan sampah yang dapat didaur ulang. Sistem ini dilengkapi dengan fitur poin dan reward untuk meningkatkan partisipasi masyarakat dalam menjaga kebersihan lingkungan dan mendukung konsep ekonomi sirkular.
Dengan adanya EcoCycle, diharapkan proses pengelolaan sampah menjadi lebih efektif, efisien, terorganisir, dan berkelanjutan sehingga dapat menciptakan lingkungan yang lebih bersih, sehat, dan ramah lingkungan. 

## 👥 Team Members

| Nama | NIM | Role | Responsibilities |
|------|------|------|-------------------|
| Nurhayati Ningsih | F1D02410085 | Fullstack Developer | Frontend: Membangun halaman Login, Register, Dashboard Masyarakat, Pelaporan Sampah, Edukasi, Reward, dan Profil menggunakan HTML5, CSS3, dan JavaScript. Backend: Implementasi PHP Native untuk autentikasi, session, pelaporan sampah, reward point, dan pengelolaan profil. |
| Siti Ananda Rahma | F1D02410095 | Fullstack Developer | Frontend: Membangun Dashboard Admin dan Dashboard Petugas. Backend: Implementasi CRUD laporan sampah, status laporan, serta jadwal pengangkutan menggunakan PHP Native dan MySQL. |
| Waritsa Wulan Ramadanis | F1D02410136 | Fullstack Developer | Frontend: Membangun halaman Lokasi Pengepul dan Profil Pengguna. Backend: Pengelolaan data pengepul dan integrasi database MySQL. Dokumentasi: Pengujian dan dokumentasi sistem. |

**Fitur Utama**
| User                   | Informasi yang Diberikan Sistem                                                             | Informasi yang Diterima Sistem                                                                  |
| ---------------------- | ------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------- |
| **Masyarakat**         | Status laporan, konten edukasi, informasi poin/reward, lokasi pengepul, jadwal pengangkutan | Data registrasi/login, laporan sampah (foto + GPS + jenis + deskripsi), permintaan tukar reward |
| **Petugas Kebersihan** | Daftar laporan terverifikasi, rute pengangkutan, jadwal                                     | Konfirmasi pengambilan, berat sampah, pembaruan status laporan                                  |
| **Admin**              | Dashboard monitoring, statistik partisipasi, statistik volume sampah                        | Verifikasi laporan, pengelolaan data master pengguna, edukasi, dan reward                       |
| **Mitra Daur Ulang**   | Informasi stok sampah terpilah, konfirmasi pemesanan                                        | Data pemesanan sampah, jadwal pengambilan                                                       |

## 📂 Sitemap / Menu Structure

```text
Project-EcoCycle
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
│   │
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
│       ├── marketplace_pengepul.php
│       └── profilMitra.php
│
└── index.php
```

## 🛠️ Tech Stack

### Frontend

- HTML5
- CSS3
- JavaScript

### Backend

- PHP Native
- Session Authentication
- CRUD Operations
- Server-side Validation

### Database

- MySQL

### Development Tools

- Visual Studio Code
- XAMPP
- Git
- GitHub

