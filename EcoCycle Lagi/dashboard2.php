<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index2.html");
    exit;
}
include 'koneksi.php';

$id_user = $_SESSION['user']['id_user'];

// Refresh data user (poin terbaru)
$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id_user = ?");
mysqli_stmt_bind_param($stmt, "i", $id_user);
mysqli_stmt_execute($stmt);
$user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
$_SESSION['user'] = $user;

// Ambil 3 laporan terbaru milik user
$stmt2 = mysqli_prepare($conn, "SELECT * FROM laporan WHERE id_user = ? ORDER BY tanggal DESC LIMIT 3");
mysqli_stmt_bind_param($stmt2, "i", $id_user);
mysqli_stmt_execute($stmt2);
$laporanList = mysqli_stmt_get_result($stmt2);

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
  <title>EcoCycle - Dashboard</title>
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
    <li><a href="dashboard2.php" class="active-link">🏠 Home</a></li>
    <li><a href="lapor.php">🗑 Lapor Sampah</a></li>
    <li><a href="status_laporan.php">📋 Status Laporan</a></li>
    <li><a href="edukasi.php">📚 Edukasi Cara Memilah Sampah</a></li>
    <li><a href="lokasi_pengepul.php">📍 Lokasi Daur Ulang</a></li>
    <li><a href="jadwal_pengangkutan.php">🗓 Jadwal Pengangkutan</a></li>
    <li><a href="profilMasyarakat.php">👤 Profil</a></li>
    <li><a href="logout.php" onclick="return confirmLogout()"> 🚪 Logout</a></li>
  </ul>

  <section class="dashboard-container">
    <div class="welcome-card">
      <h1>Halo, <?php echo htmlspecialchars($user['nama']); ?> 👋</h1>
      <p>Terima kasih telah menggunakan EcoCycle untuk menjaga lingkungan tetap bersih dan sehat.</p>
    </div>

    <div class="profile-card">
      <div class="profile-left">
        <div class="profile-image">👤</div>
        <div>
          <h2 style="font-family:'Fraunces',serif; color:#1f4d2c; margin-bottom:6px;">Profil Pengguna</h2>
          <p>Email : <?php echo htmlspecialchars($user['email']); ?></p>
          <p style="margin-top:4px; color:#768472; font-size:13px; text-transform:capitalize;">Role: <?php echo htmlspecialchars($user['role']); ?></p>
        </div>
      </div>
    </div>

    <div class="point-card">
      <h2>🌟 Poin EcoCycle</h2>
      <h1><?php echo (int)$user['point']; ?></h1>
      <a href="reward.php" class="point-card-link"><button>Tukar Rewards</button></a>
    </div>

    <div class="status-card">
      <h2>📋 Laporan Terbaru</h2>
      <?php if (mysqli_num_rows($laporanList) === 0) { ?>
        <div class="alert alert-empty">Belum ada laporan. Yuk laporkan titik sampah pertamamu!</div>
      <?php } else {
        while ($row = mysqli_fetch_assoc($laporanList)) {
          [$label, $class] = statusLabel($row['status']);
      ?>
        <div class="status-item">
          <div class="info">
            <span><?php echo htmlspecialchars($row['jenis_sampah'] ?: 'Sampah'); ?></span>
            <small><?php echo date('d M Y, H:i', strtotime($row['tanggal'])); ?></small>
          </div>
          <span class="status <?php echo $class; ?>"><?php echo $label; ?></span>
        </div>
      <?php } } ?>
      <div style="margin-top:16px; text-align:right;">
        <a href="status_laporan.php" class="btn-primary">Lihat Semua Laporan</a>
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
  <script>
  function confirmLogout() { return confirm("Apakah Anda yakin ingin logout?");}
  let menuToggle = document.getElementById("menuToggle");
  let navMenu = document.getElementById("navMenu");
  menuToggle.onclick = function () { navMenu.classList.toggle("show");};
  </script>

</body>
</html>
