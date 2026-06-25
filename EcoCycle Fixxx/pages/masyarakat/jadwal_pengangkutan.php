<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../../index.html");
    exit;
}
include '../../config/koneksi.php';

$id_user = $_SESSION['user']['id_user'];
$stmt = mysqli_prepare($conn, "SELECT lokasi, jenis_sampah, jadwal_pengangkutan,status FROM laporan WHERE id_user=? AND status='diproses' ORDER BY jadwal_pengangkutan ASC");
mysqli_stmt_bind_param($stmt, "i", $id_user);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EcoCycle - Jadwal Pengangkutan</title>
  <link rel="stylesheet" href="../../assets/css/style2.css">
</head>
<body class="dashboard-body">
  <nav class="navbar">
    <div class="left-navbar">
      <div class="menu-toggle" id="menuToggle">☰</div>
      <div class="logo">♻ EcoCycle</div>
    </div>
  </nav>

  <ul class="nav-menu" id="navMenu">
    <li><a href="../../pages/masyarakat/dashboard2.php">🏠 Home</a></li>
    <li><a href="../../pages/masyarakat/lapor.php">🗑 Lapor Sampah</a></li>
    <li><a href="../../pages/masyarakat/status_laporan.php">📋 Status Laporan</a></li>
    <li><a href="../../pages/masyarakat/marketplace_masyarakat.php">🛒 Jual Sampah Terpilah</a></li>
    <li><a href="../../pages/masyarakat/edukasi.php">📚 Edukasi Cara Memilah Sampah</a></li>
    <li><a href="../../pages/masyarakat/lokasi_pengepul.php">📍 Lokasi Daur Ulang</a></li>
    <li><a href="../../pages/masyarakat/jadwal_pengangkutan.php" class="active-link">🗓 Jadwal Pengangkutan</a></li>
    <li><a href="../../pages/masyarakat/profilMasyarakat.php">👤 Profil</a></li>
    <li><a href="../../actions/logout.php" onclick="return confirm('Apakah Anda yakin ingin keluar?')">🚪 Logout</a></li>
  </ul>
  <div class="nav-overlay" id="navOverlay"></div>

  <section class="dashboard-container">
    <div class="content-card">
      <h2>🗓 Jadwal Pengangkutan Sampah</h2>

      <?php if (mysqli_num_rows($result) === 0) { ?>
        <div class="alert alert-empty">Belum ada jadwal pengangkutan.</div>
      <?php } else {
        while ($row = mysqli_fetch_assoc($result)) { ?>
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
        <?php
        }
    }
    ?>
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
