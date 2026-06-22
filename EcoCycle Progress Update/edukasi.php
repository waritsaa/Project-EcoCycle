<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index2.html");
    exit;
}
include 'koneksi.php';

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
  <link rel="stylesheet" href="style2.css">
</head>
<body class="dashboard-body">
  <nav class="navbar">
    <div class="left-navbar">
      <div class="menu-toggle" id="menuToggle">☰</div>
      <div class="logo">♻ EcoCycle</div>
    </div>
  </nav>

  <ul class="nav-menu" id="navMenu">
    <li><a href="dashboard2.php">🏠 Home</a></li>
    <li><a href="lapor.php">🗑 Lapor Sampah</a></li>
    <li><a href="status_laporan.php">📋 Status Laporan</a></li>
    <li><a href="edukasi.php" class="active-link">📚 Edukasi Cara Memilah Sampah</a></li>
    <li><a href="lokasi_pengepul.php">📍 Lokasi Daur Ulang</a></li>
    <li><a href="jadwal_pengangkutan.php">🗓 Jadwal Pengangkutan</a></li>
    <li><a href="profilMasyarakat.php">👤 Profil</a></li>
    <li><a href="logout.php">🚪 Logout</a></li>
  </ul>

  <section class="dashboard-container">
    <div class="content-card">
      <h2>📚 Edukasi Pengelolaan Sampah</h2>

      <div class="edukasi-grid">
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
          <div class="edukasi-item <?php echo kategoriClass($row['kategori']); ?>">
            <span class="tag"><?php echo htmlspecialchars($row['kategori']); ?></span>
            <h3><?php echo htmlspecialchars($row['judul']); ?></h3>
            <p><?php echo nl2br(htmlspecialchars($row['konten'])); ?></p>
          </div>
        <?php } ?>
      </div>
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
