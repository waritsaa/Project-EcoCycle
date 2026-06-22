<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index2.html");
    exit;
}
include 'koneksi.php';

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
    <li><a href="status_laporan.php" class="active-link">📋 Status Laporan</a></li>
    <li><a href="edukasi.php">📚 Edukasi Cara Memilah Sampah</a></li>
    <li><a href="lokasi_pengepul.php">📍 Lokasi Daur Ulang</a></li>
    <li><a href="jadwal_pengangkutan.php">🗓 Jadwal Pengangkutan</a></li>
    <li><a href="profilMasyarakat.php">👤 Profil</a></li>
    <li><a href="logout.php" onclick="return confirmLogout()"> 🚪 Logout</a></li>
  </ul>

  <section class="dashboard-container">
    <div class="content-card">
      <h2>📋 Status Laporan Saya</h2>

      <?php if (mysqli_num_rows($laporanList) === 0) { ?>
        <div class="alert alert-empty">Belum ada laporan yang dikirim. <a href="lapor.php">Buat laporan pertamamu</a>.</div>
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
    let menuToggle = document.getElementById("menuToggle");
    let navMenu = document.getElementById("navMenu");
    menuToggle.onclick = function () {
      navMenu.classList.toggle("show");
    };
  </script>
  <script>
  function confirmLogout() { return confirm("Apakah Anda yakin ingin logout?");}
  let menuToggle = document.getElementById("menuToggle");
  let navMenu = document.getElementById("navMenu");
  menuToggle.onclick = function () { navMenu.classList.toggle("show");};
  </script>

</body>
</html>
