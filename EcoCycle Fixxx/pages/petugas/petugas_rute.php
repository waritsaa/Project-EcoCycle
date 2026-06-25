<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'petugas') {
    header("Location: ../../index.html");
    exit;
}
include '../../config/koneksi.php';

$id_petugas = $_SESSION['user']['id_user'];

// Laporan yang sudah diverifikasi (diproses) -> jadi titik rute petugas
$result = mysqli_query($conn, "SELECT l.*, u.nama FROM laporan l JOIN users u ON l.id_user = u.id_user WHERE l.status = 'diproses' ORDER BY l.tanggal ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EcoCycle - Rute Pengangkutan</title>
  <link rel="stylesheet" href="../../assets/css/style2.css">
</head>
<body class="dashboard-body theme-petugas">
  <nav class="navbar">
    <div class="left-navbar">
      <div class="menu-toggle" id="menuToggle">☰</div>
      <div class="logo">♻ EcoCycle</div>
    </div>
  </nav>

  <ul class="nav-menu" id="navMenu">
    <li><a href="../../pages/petugas/petugas_dashboard.php">🏠 Home</a></li>
    <li><a href="../../pages/petugas/petugas_laporan.php">📋 Daftar Laporan</a></li>
    <li><a href="../../pages/petugas/petugas_rute.php" class="active-link">🗺 Rute Pengangkutan</a></li>
    <li><a href="../../pages/petugas/petugas_jadwal.php">🗓 Atur Jadwal</a></li>
    <li><a href="../../pages/petugas/profilPetugas.php">👤 Profil</a></li>
    <li><a href="../../actions/logout.php" onclick="return confirm('Apakah Anda yakin ingin keluar?')">🚪 Logout</a></li>
  </ul>
  <div class="nav-overlay" id="navOverlay"></div>

  <section class="dashboard-container">
    <div class="content-card">
      <h2>🗺 Rute Pengangkutan Hari Ini</h2>
      <p class="subtitle">Urutan titik laporan yang menunggu diangkut, diurutkan berdasarkan waktu masuk laporan.</p>

      <?php if (mysqli_num_rows($result) === 0) { ?>
        <div class="alert alert-empty">Tidak ada titik laporan yang perlu diangkut saat ini.</div>
      <?php } else {
        $no = 1;
        while ($row = mysqli_fetch_assoc($result)) { ?>
        <div class="status-item">
          <div class="info">
            <span><strong><?php echo $no++; ?>. <?php echo htmlspecialchars($row['jenis_sampah']); ?></strong> — Pelapor: <?php echo htmlspecialchars($row['nama']); ?></span>
            <small>📍 <?php echo htmlspecialchars($row['lokasi']); ?> · <?php echo date('d M Y, H:i', strtotime($row['tanggal'])); ?></small>
          </div>
          <a href="https://www.google.com/maps?q=<?php echo htmlspecialchars($row['lokasi']); ?>" target="_blank" class="btn-primary">Buka Peta</a>
        </div>
      <?php } } ?>
    </div>
  </section>

  <script>
    const menuToggle = document.getElementById("menuToggle");
    const navMenu   = document.getElementById("navMenu");
    const navOverlay = document.getElementById("navOverlay");
    function openMenu()  { navMenu.classList.add("show"); navOverlay.classList.add("show"); }
    function closeMenu() { navMenu.classList.remove("show"); navOverlay.classList.remove("show"); }
    menuToggle.onclick = () => navMenu.classList.contains("show") ? closeMenu() : openMenu();
    if (navOverlay) navOverlay.onclick = closeMenu;
    // Tutup menu saat klik link
    navMenu.querySelectorAll("a").forEach(a => a.addEventListener("click", closeMenu));
  </script>
</body>
</html>
