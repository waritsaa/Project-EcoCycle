<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index2.html");
    exit;
}
include 'koneksi.php';

$id_user = $_SESSION['user']['id_user'];
$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id_user = ?");
mysqli_stmt_bind_param($stmt, "i", $id_user);
mysqli_stmt_execute($stmt);
$user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
$_SESSION['user'] = $user;

// total laporan
$stmtL = mysqli_prepare($conn, "SELECT COUNT(*) as total FROM laporan WHERE id_user = ?");
mysqli_stmt_bind_param($stmtL, "i", $id_user);
mysqli_stmt_execute($stmtL);
$totalLaporan = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtL))['total'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EcoCycle - Profil</title>
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
    <li><a href="jadwal_pengangkutan.php">🗓 Jadwal Pengangkutan</a></li>
    <li><a href="profil.php" class="active-link">👤 Profil</a></li>
    <li><a href="logout.php">🚪 Logout</a></li>
  </ul>

  <section class="dashboard-container">
    <div class="profile-card">
      <div class="profile-left">
        <div class="profile-image">👤</div>
        <div>
          <h2 style="font-family:'Fraunces',serif; color:#1f4d2c; margin-bottom:6px;"><?php echo htmlspecialchars($user['nama']); ?></h2>
          <p>Email : <?php echo htmlspecialchars($user['email']); ?></p>
          <p style="margin-top:4px; color:#768472; font-size:13px; text-transform:capitalize;">Role: <?php echo htmlspecialchars($user['role']); ?></p>
        </div>
      </div>
    </div>

    <div class="point-card">
      <h2>🌟 Total Poin</h2>
      <h1><?php echo (int)$user['point']; ?></h1>
      <a href="reward.php"><button>Tukar Rewards</button></a>
    </div>

    <div class="content-card">
      <h2>📊 Statistik Partisipasi</h2>
      <div class="status-item">
        <div class="info"><span>Total Laporan Dikirim</span></div>
        <span class="status status-selesai"><?php echo (int)$totalLaporan; ?></span>
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
