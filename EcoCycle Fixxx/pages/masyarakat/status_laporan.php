<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../../index.html");
    exit;
}
include '../../config/koneksi.php';

$id_user = $_SESSION['user']['id_user'];

$stmt = mysqli_prepare($conn, "SELECT * FROM laporan WHERE id_user = ? ORDER BY tanggal DESC");
mysqli_stmt_bind_param($stmt, "i", $id_user);
mysqli_stmt_execute($stmt);
$laporanList = mysqli_stmt_get_result($stmt);

function statusLabel($status) {
    $map = [
        'pending'  => ['Menunggu Verifikasi', 'status-pending'],
        'diproses' => ['Sedang Diproses', 'status-diproses'],
        'selesai'  => ['Selesai', 'status-selesai'],
        'ditolak'  => ['Ditolak', 'status-ditolak'],
    ];
    return $map[$status] ?? [ucfirst($status), 'status-pending'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EcoCycle - Status Laporan</title>
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
    <li><a href="../../pages/masyarakat/status_laporan.php" class="active-link">📋 Status Laporan</a></li>
    <li><a href="../../pages/masyarakat/marketplace_masyarakat.php">🛒 Jual Sampah Terpilah</a></li>
    <li><a href="../../pages/masyarakat/edukasi.php">📚 Edukasi Cara Memilah Sampah</a></li>
    <li><a href="../../pages/masyarakat/lokasi_pengepul.php">📍 Lokasi Daur Ulang</a></li>
    <li><a href="../../pages/masyarakat/jadwal_pengangkutan.php">🗓 Jadwal Pengangkutan</a></li>
    <li><a href="../../pages/masyarakat/profilMasyarakat.php">👤 Profil</a></li>
    <li><a href="../../actions/logout.php" onclick="return confirm('Apakah Anda yakin ingin keluar?')">🚪 Logout</a></li>
  </ul>
  <div class="nav-overlay" id="navOverlay"></div>

  <section class="dashboard-container">
    <div class="content-card">
      <h2>📋 Status Laporan Saya</h2>

      <?php if (mysqli_num_rows($laporanList) === 0) { ?>
        <div class="alert alert-empty">Belum ada laporan yang dikirim. <a href="../../pages/masyarakat/lapor.php">Buat laporan pertamamu</a>.</div>
      <?php } else {
        while ($row = mysqli_fetch_assoc($laporanList)) {
          [$label, $class] = statusLabel($row['status']);
      ?>
        <div class="status-item">
          <div class="info">
            <span><strong><?php echo htmlspecialchars($row['jenis_sampah']); ?></strong> — <?php echo htmlspecialchars(mb_strimwidth($row['deskripsi'], 0, 60, '...')); ?></span>
            <small>📍 <?php echo htmlspecialchars($row['lokasi']); ?> · <?php echo date('d M Y, H:i', strtotime($row['tanggal'])); ?></small>
          </div>
          <span class="status <?php echo $class; ?>"><?php echo $label; ?></span>
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
