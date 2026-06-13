<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'petugas') {
    header("Location: index2.html");
    exit;
}
include 'koneksi.php';

$id_petugas = $_SESSION['user']['id_user'];

// refresh user
$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id_user = ?");
mysqli_stmt_bind_param($stmt, "i", $id_petugas);
mysqli_stmt_execute($stmt);
$user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
$_SESSION['user'] = $user;

// laporan yang sudah diverifikasi admin (diproses) menunggu petugas
$laporanDiproses = mysqli_query($conn, "SELECT l.*, u.nama FROM laporan l JOIN users u ON l.id_user = u.id_user WHERE l.status = 'diproses' ORDER BY l.tanggal ASC LIMIT 5");

// jumlah laporan selesai oleh petugas ini (lewat tabel pengangkutan)
$stmtSelesai = mysqli_prepare($conn, "SELECT COUNT(*) as total FROM pengangkutan WHERE id_petugas = ? AND status = 'selesai'");
mysqli_stmt_bind_param($stmtSelesai, "i", $id_petugas);
mysqli_stmt_execute($stmtSelesai);
$totalSelesai = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtSelesai))['total'];

// jadwal terdekat untuk petugas ini
$stmtJadwal = mysqli_prepare($conn, "SELECT * FROM jadwal_pengangkutan WHERE id_petugas = ? OR id_petugas IS NULL ORDER BY tanggal ASC LIMIT 1");
mysqli_stmt_bind_param($stmtJadwal, "i", $id_petugas);
mysqli_stmt_execute($stmtJadwal);
$jadwalTerdekat = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtJadwal));
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EcoCycle - Dashboard Petugas</title>
  <link rel="stylesheet" href="style2.css">
</head>
<body class="dashboard-body theme-petugas">
  <nav class="navbar">
    <div class="left-navbar">
      <div class="menu-toggle" id="menuToggle">☰</div>
      <div class="logo">♻ EcoCycle</div>
    </div>
  </nav>

  <ul class="nav-menu" id="navMenu">
    <li><a href="petugas_dashboard.php" class="active-link">🏠 Home</a></li>
    <li><a href="petugas_laporan.php">📋 Daftar Laporan</a></li>
    <li><a href="petugas_rute.php">🗺 Rute Pengangkutan</a></li>
    <li><a href="petugas_jadwal.php">🗓 Atur Jadwal</a></li>
    <li><a href="profil.php">👤 Profil</a></li>
    <li><a href="logout.php">🚪 Logout</a></li>
  </ul>

  <section class="dashboard-container">
    <div class="welcome-card">
      <h1>Halo, <?php echo htmlspecialchars($user['nama']); ?> 👋</h1>
      <p>Berikut ringkasan tugas pengangkutan sampah hari ini.</p>
    </div>

    <div class="stat-grid" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(160px,1fr)); gap:16px;">
      <div class="stat-box" style="background:#fff; border-radius:16px; padding:22px; text-align:center; box-shadow:0 6px 18px rgba(0,0,0,0.05);">
        <div class="num" style="font-family:'Fraunces',serif; font-size:34px; font-weight:700; color:#1565c0;"><?php echo mysqli_num_rows($laporanDiproses); ?></div>
        <div class="label" style="font-size:13px; color:#8a8a8a; margin-top:6px;">Laporan Menunggu Diangkut</div>
      </div>
      <div class="stat-box" style="background:#fff; border-radius:16px; padding:22px; text-align:center; box-shadow:0 6px 18px rgba(0,0,0,0.05);">
        <div class="num" style="font-family:'Fraunces',serif; font-size:34px; font-weight:700; color:#1565c0;"><?php echo (int)$totalSelesai; ?></div>
        <div class="label" style="font-size:13px; color:#8a8a8a; margin-top:6px;">Total Selesai Ditangani</div>
      </div>
    </div>

    <div class="content-card">
      <h2>📋 Laporan Terbaru untuk Ditangani</h2>
      <?php if (mysqli_num_rows($laporanDiproses) === 0) { ?>
        <div class="alert alert-empty">Tidak ada laporan yang menunggu diangkut saat ini.</div>
      <?php } else {
        while ($row = mysqli_fetch_assoc($laporanDiproses)) { ?>
        <div class="status-item">
          <div class="info">
            <span><strong><?php echo htmlspecialchars($row['jenis_sampah']); ?></strong> — oleh <?php echo htmlspecialchars($row['nama']); ?></span>
            <small>📍 <?php echo htmlspecialchars($row['lokasi']); ?> · <?php echo date('d M Y, H:i', strtotime($row['tanggal'])); ?></small>
          </div>
          <span class="status status-diproses">Diproses</span>
        </div>
      <?php } } ?>
      <div style="margin-top:16px; text-align:right;">
        <a href="petugas_laporan.php" class="btn-primary">Lihat Semua Laporan</a>
      </div>
    </div>

    <?php if ($jadwalTerdekat) { ?>
    <div class="content-card">
      <h2>🗓 Jadwal Pengangkutan Terdekat</h2>
      <div class="jadwal-item">
        <div class="jadwal-day">
          <?php echo htmlspecialchars($jadwalTerdekat['hari']); ?><br>
          <?php echo date('d M', strtotime($jadwalTerdekat['tanggal'])); ?>
        </div>
        <div>
          <div class="area">📍 <?php echo htmlspecialchars($jadwalTerdekat['area']); ?></div>
          <div class="time">⏰ <?php echo date('H:i', strtotime($jadwalTerdekat['waktu_mulai'])); ?> - <?php echo date('H:i', strtotime($jadwalTerdekat['jam_selesai'])); ?></div>
        </div>
      </div>
    </div>
    <?php } ?>
  </section>

  <script>
    let menuToggle = document.getElementById("menuToggle");
    let navMenu = document.getElementById("navMenu");
    menuToggle.onclick = function () {
      navMenu.classList.toggle("show");
    };
  </script>
</body>
</html>