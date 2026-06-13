<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'pengepul') {
    header("Location: index2.html");
    exit;
}
include 'koneksi.php';

$user = $_SESSION['user'];

// Listing sampah terpilah yang tersedia di marketplace
$result = mysqli_query($conn, "SELECT m.*, u.nama FROM marketplace m JOIN users u ON m.id_user = u.id_user WHERE m.status = 'tersedia' ORDER BY m.tanggal DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EcoCycle - Dashboard Mitra Daur Ulang</title>
  <link rel="stylesheet" href="style2.css">
</head>
<body class="dashboard-body theme-pengepul">
  <nav class="navbar">
    <div class="left-navbar">
      <div class="menu-toggle" id="menuToggle">☰</div>
      <div class="logo">♻ EcoCycle</div>
    </div>
  </nav>

  <ul class="nav-menu" id="navMenu">
    <li><a href="pengepul_dashboard.php" class="active-link">🏠 Home</a></li>
    <li><a href="profil.php">👤 Profil</a></li>
    <li><a href="logout.php">🚪 Logout</a></li>
  </ul>

  <section class="dashboard-container">
    <div class="welcome-card">
      <h1>Halo, <?php echo htmlspecialchars($user['nama']); ?> 👋</h1>
      <p>Berikut daftar sampah terpilah dari masyarakat yang tersedia untuk dijemput / dibeli.</p>
    </div>

    <div class="content-card">
      <h2>♻ Marketplace Sampah Terpilah</h2>

      <?php if (mysqli_num_rows($result) === 0) { ?>
        <div class="alert alert-empty">Belum ada listing sampah terpilah yang tersedia saat ini.</div>
      <?php } else {
        while ($row = mysqli_fetch_assoc($result)) { ?>
        <div class="status-item">
          <div class="info">
            <span><strong><?php echo htmlspecialchars($row['jenis_sampah']); ?></strong> — <?php echo (float)$row['berat_kg']; ?> kg, oleh <?php echo htmlspecialchars($row['nama']); ?></span>
            <small>💰 Rp<?php echo number_format($row['harga'], 0, ',', '.'); ?> · <?php echo date('d M Y', strtotime($row['tanggal'])); ?></small>
          </div>
          <span class="status status-pending">Tersedia</span>
        </div>
      <?php } } ?>
    </div>
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
