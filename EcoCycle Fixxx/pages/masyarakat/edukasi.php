<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../../index.html");
    exit;
}
include '../../config/koneksi.php';

$result = mysqli_query($conn, "SELECT * FROM edukasi ORDER BY tanggal_upload DESC");

function kategoriClass($kategori) {
    $map = [
        'Organik' => 'tag-organik',
        'Anorganik' => 'tag-anorganik',
        'B3' => 'tag-b3',
    ];
    return $map[$kategori] ?? '';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EcoCycle - Edukasi Sampah</title>
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
    <li><a href="../../pages/masyarakat/edukasi.php" class="active-link">📚 Edukasi Cara Memilah Sampah</a></li>
    <li><a href="../../pages/masyarakat/lokasi_pengepul.php">📍 Lokasi Daur Ulang</a></li>
    <li><a href="../../pages/masyarakat/jadwal_pengangkutan.php">🗓 Jadwal Pengangkutan</a></li>
    <li><a href="../../pages/masyarakat/profilMasyarakat.php">👤 Profil</a></li>
    <li><a href="../../actions/logout.php" onclick="return confirm('Apakah Anda yakin ingin keluar?')">🚪 Logout</a></li>
  </ul>
  <div class="nav-overlay" id="navOverlay"></div>

  <section class="dashboard-container">
    <div class="content-card">
      <h2>📚 Edukasi Pengelolaan Sampah</h2>

      <div class="edukasi-grid">
        <?php if (mysqli_num_rows($result) === 0) { ?>
          <div class="alert alert-empty">Belum ada konten edukasi. Admin belum menambahkan materi edukasi sampah.</div>
        <?php } else {
          while ($row = mysqli_fetch_assoc($result)) { ?>
          <div class="edukasi-item <?php echo kategoriClass($row['kategori']); ?>">
            <span class="tag"><?php echo htmlspecialchars($row['kategori']); ?></span>
            <h3><?php echo htmlspecialchars($row['judul']); ?></h3>
            <p><?php echo nl2br(htmlspecialchars($row['konten'])); ?></p>
          </div>
        <?php } } ?>
      </div>
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
