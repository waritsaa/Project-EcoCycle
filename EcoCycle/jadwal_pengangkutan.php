<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index2.html");
    exit;
}
include 'koneksi.php';

$result = mysqli_query($conn, "SELECT * FROM jadwal_pengangkutan ORDER BY tanggal ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EcoCycle - Jadwal Pengangkutan</title>
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
    <li><a href="edukasi.php">📚 Edukasi Cara Memilah Sampah</a></li>
    <li><a href="lokasi_pengepul.php">📍 Lokasi Daur Ulang</a></li>
    <li><a href="jadwal_pengangkutan.php" class="active-link">🗓 Jadwal Pengangkutan</a></li>
    <li><a href="profil.php">👤 Profil</a></li>
    <li><a href="logout.php">🚪 Logout</a></li>
  </ul>

  <section class="dashboard-container">
    <div class="content-card">
      <h2>🗓 Jadwal Pengangkutan Sampah</h2>

      <?php if (mysqli_num_rows($result) === 0) { ?>
        <div class="alert alert-empty">Belum ada jadwal pengangkutan.</div>
      <?php } else {
        while ($row = mysqli_fetch_assoc($result)) { ?>
        <div class="jadwal-item">
          <div class="jadwal-day">
            <?php echo htmlspecialchars($row['hari']); ?><br>
            <?php echo date('d M', strtotime($row['tanggal'])); ?>
          </div>
          <div>
            <div class="area">📍 <?php echo htmlspecialchars($row['area']); ?></div>
            <div class="time">⏰ <?php echo date('H:i', strtotime($row['waktu_mulai'])); ?> - <?php echo date('H:i', strtotime($row['jam_selesai'])); ?></div>
            <?php if ($row['rute']) { ?>
              <div class="time" style="margin-top:4px;">🛣 <?php echo htmlspecialchars($row['rute']); ?></div>
            <?php } ?>
          </div>
          <div class="action">
            <span class="status status-<?php echo htmlspecialchars($row['status']) === 'selesai' ? 'selesai' : 'pending'; ?>">
              <?php echo ucfirst($row['status']); ?>
            </span>
          </div>
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
