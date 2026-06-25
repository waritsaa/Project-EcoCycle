<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../../index.html");
    exit;
}
include '../../config/koneksi.php';

$id_user = $_SESSION['user']['id_user'];
$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id_user = ?");
mysqli_stmt_bind_param($stmt, "i", $id_user);
mysqli_stmt_execute($stmt);
$user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
$_SESSION['user'] = $user;

// TOTAL_LAPORAN
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
  <link rel="stylesheet" href="../../assets/css/style2.css">
</head>
<body class="dashboard-body">
  <nav class="navbar">
    <div class="left-navbar">
      <div class="menu-toggle" id="menuToggle">☰</div>
      <div class="logo">♻ EcoCycle</div>
    </div>
    <div class="navbar-right">
      <div class="nav-user-avatar"><?php if(!empty($user['foto'])){ ?><img src="<?php echo htmlspecialchars(asset_url($user['foto'])); ?>"><?php } else { ?>👤<?php } ?></div>
      <span class="nav-user-name"><?php echo htmlspecialchars($user['nama']); ?></span>
    </div>
  </nav>

  <ul class="nav-menu" id="navMenu">
    <li><a href="../../pages/masyarakat/dashboard2.php">🏠 Home</a></li>
    <li><a href="../../pages/masyarakat/lapor.php">🗑 Lapor Sampah</a></li>
    <li><a href="../../pages/masyarakat/status_laporan.php">📋 Status Laporan</a></li>
    <li><a href="../../pages/masyarakat/marketplace_masyarakat.php">🛒 Jual Sampah Terpilah</a></li>
    <li><a href="../../pages/masyarakat/edukasi.php">📚 Edukasi Cara Memilah Sampah</a></li>
    <li><a href="../../pages/masyarakat/lokasi_pengepul.php">📍 Lokasi Daur Ulang</a></li>
    <li><a href="../../pages/masyarakat/jadwal_pengangkutan.php">🗓 Jadwal Pengangkutan</a></li>
    <li><a href="../../pages/masyarakat/profilMasyarakat.php" class="active-link">👤 Profil</a></li>
    <li><a href="../../actions/logout.php" onclick="return confirm('Apakah Anda yakin ingin keluar?')">🚪 Logout</a></li>
  </ul>
  <div class="nav-overlay" id="navOverlay"></div>

  <section class="dashboard-container">
  <div class="profile-modern-card">
    <div class="profile-header-modern">
      <div class="profile-avatar-modern">
        <?php if(!empty($user['foto'])){ ?>
          <img src="<?php echo htmlspecialchars(asset_url($user['foto'])); ?>" alt="Foto Profil">
        <?php } else { ?>
          👤
        <?php } ?>
      </div>

      <h2><?php echo htmlspecialchars($user['nama']); ?></h2>
      <span class="role-badge"><?php echo ucfirst($user['role']); ?></span>
      <p class="joined-date">
        Bergabung :
        <?php echo date('d M Y', strtotime($user['created_at'])); ?>
      </p>
    </div>

    <div class="profile-info-grid">
      <div class="profile-info-item"><label>Nama Lengkap</label><span><?php echo htmlspecialchars($user['nama']); ?></span></div>
      <div class="profile-info-item"><label>Email</label><span><?php echo htmlspecialchars($user['email']); ?></span></div>
      <div class="profile-info-item"><label>Nomor HP</label><span><?php echo !empty($user['no_hp']) ? htmlspecialchars($user['no_hp']) : '-'; ?></span></div>
      <div class="profile-info-item"><label>Alamat</label><span><?php echo !empty($user['alamat']) ? htmlspecialchars($user['alamat']) : '-'; ?></span></div>
    </div>
  </div>

  <div class="point-card">
      <h2>🌟 Total Poin</h2> <h1><?php echo (int)$user['point']; ?></h1>
      <a href="../../pages/masyarakat/reward.php"><button>Tukar Rewards</button></a>
      <br><br>
      <a href="../../actions/edit_profil.php"><button>Edit Profil</button></a>
  </div>

  <div class="content-card">
      <h2>📊 Statistik Partisipasi</h2>
      <div class="status-item">
          <div class="info"><span>Total Laporan Dikirim</span>
          </div><span class="status status-selesai"> <?php echo (int)$totalLaporan; ?></span>
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
