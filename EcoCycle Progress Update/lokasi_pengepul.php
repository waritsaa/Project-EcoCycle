<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index2.html");
    exit;
}
include 'koneksi.php';

$result = mysqli_query($conn, "SELECT * FROM mitra_daur_ulang WHERE status_aktif = 1 ORDER BY nama_mitra ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EcoCycle - Lokasi Daur Ulang</title>
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
    <li><a href="lokasi_pengepul.php" class="active-link">📍 Lokasi Daur Ulang</a></li>
    <li><a href="jadwal_pengangkutan.php">🗓 Jadwal Pengangkutan</a></li>
    <li><a href="profilMasyarakat.php">👤 Profil</a></li>
    <li><a href="logout.php">🚪 Logout</a></li>
  </ul>

  <section class="dashboard-container">
    <div class="content-card">
      <h2>📍 Lokasi Pengepul / Mitra Daur Ulang</h2>

      <?php if (mysqli_num_rows($result) === 0) { ?>
        <div class="alert alert-empty">Belum ada data mitra daur ulang.</div>
      <?php } else {
        while ($row = mysqli_fetch_assoc($result)) {
          $mapsUrl = "https://www.google.com/maps?q=" . $row['latitude'] . "," . $row['longitude'];
      ?>
        <div class="mitra-item">
          <div>
            <h3><?php echo htmlspecialchars($row['nama_mitra']); ?></h3>
            <p>📌 <?php echo htmlspecialchars($row['alamat']); ?></p>
            <p>📞 <?php echo htmlspecialchars($row['no_telpon']); ?></p>
            <p>♻ Menerima: <?php echo htmlspecialchars($row['jenis_sampah']); ?></p>
          </div>
          <a href="<?php echo $mapsUrl; ?>" target="_blank" class="btn-primary">Lihat Peta</a>
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
