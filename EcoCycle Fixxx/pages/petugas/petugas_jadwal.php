<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'petugas') {
    header("Location: ../../index.html");
    exit;
}
include '../../config/koneksi.php';

$id_petugas = $_SESSION['user']['id_user'];
$message = '';
$messageType = '';

$result = mysqli_prepare($conn, "SELECT lokasi, jenis_sampah, jadwal_pengangkutan, status FROM laporan WHERE status='diproses' ORDER BY jadwal_pengangkutan ASC");
mysqli_stmt_execute($result);
$jadwalList = mysqli_stmt_get_result($result);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EcoCycle - Jadwal Pengangkutan</title>
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
    <li><a href="../../pages/petugas/petugas_rute.php">🗺 Rute Pengangkutan</a></li>
    <li><a href="../../pages/petugas/petugas_jadwal.php" class="active-link">🗓 Jadwal Pengangkutan</a></li>
    <li><a href="../../pages/petugas/profilPetugas.php">👤 Profil</a></li>
    <li><a href="../../actions/logout.php" onclick="return confirm('Apakah Anda yakin ingin keluar?')">🚪 Logout</a></li>
  </ul>
  <div class="nav-overlay" id="navOverlay"></div>

    <div class="content-card">
      <h2>📋 Jadwal Pengangkutan</h2>
      <?php if (mysqli_num_rows($jadwalList) === 0) { ?>
        <div class="alert alert-empty">Belum ada jadwal.</div>
      <?php } else {
        while ($row = mysqli_fetch_assoc($jadwalList)) { ?>
        <div class="jadwal-item">
          <div class="jadwal-day">
            <?php echo date('l', strtotime($row['jadwal_pengangkutan'])); ?><br>
            <?php echo date('d M Y', strtotime($row['jadwal_pengangkutan'])); ?>
          </div>
          <div>
            <div class="area"> 📍 <?php echo htmlspecialchars($row['lokasi']); ?></div>
            <div class="time"> 🕗 <?php echo date('H:i', strtotime($row['jadwal_pengangkutan'])); ?></div>
            <div class="time"> ♻ <?php echo htmlspecialchars($row['jenis_sampah']); ?></div>
          </div>
          <div class="action">
            <span class="status status-pending"> Dijadwalkan </span>
          </div>

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
