<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
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

$totalUser = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role = 'masyarakat'"))['total'];
$totalLaporan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM laporan"))['total'];
$totalPending = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM laporan WHERE status = 'pending'"))['total'];
$totalSelesai = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM laporan WHERE status = 'selesai'"))['total'];

$message = '';
$messageType = '';

// Verifikasi laporan: setujui (pending -> diproses) atau tolak (pending -> ditolak)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_laporan']) && isset($_POST['aksi'])) {
    $id_laporan = (int)$_POST['id_laporan'];
    $aksi = $_POST['aksi'] === 'setuju' ? 'diproses' : 'ditolak';
    if($aksi=="diproses"){$jadwal = date( "Y-m-d 08:00:00", strtotime("+1 day"));
    $stmt = mysqli_prepare($conn, "UPDATE laporan SET status=?, jadwal_pengangkutan=? WHERE id_laporan=?");
    mysqli_stmt_bind_param($stmt, "ssi", $aksi, $jadwal, $id_laporan);
    }else{ $stmt = mysqli_prepare($conn, "UPDATE laporan SET status=? WHERE id_laporan=?");
    mysqli_stmt_bind_param($stmt, "si", $aksi, $id_laporan);
    }

    if (mysqli_stmt_execute($stmt)) { $message = $aksi === 'diproses'
        ? "Laporan #$id_laporan diverifikasi & diteruskan ke petugas."
        : "Laporan #$id_laporan ditolak.";
        $messageType = "success";
    } else {
      $message = "Gagal memperbarui laporan";
      $messageType = "error";
    }
}
// Laporan pending menunggu verifikasi
$laporanPending = mysqli_query($conn, " SELECT l.*, u.nama, u.no_hp FROM laporan l JOIN users u ON l.id_user = u.id_user WHERE l.status = 'pending' ORDER BY l.tanggal ASC LIMIT 10");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EcoCycle - Dashboard Admin</title>
  <link rel="stylesheet" href="../../assets/css/style2.css">
</head>
<body class="dashboard-body theme-admin">
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
    <li><a href="../../pages/admin/admin_dashboard.php" class="active-link">🏠 Home</a></li>
    <li><a href="../../pages/admin/admin_laporan.php">📋 Verifikasi Laporan</a></li>
    <li><a href="../../pages/admin/admin_users.php">👥 Manajemen User</a></li>
    <li><a href="../../pages/admin/profilAdmin.php">👤 Profil</a></li>
    <li><a href="../../actions/logout.php" onclick="return confirm('Apakah Anda yakin ingin keluar?')">🚪 Logout</a></li>
  </ul>
  <div class="nav-overlay" id="navOverlay"></div>

  <section class="dashboard-container">
    <div class="welcome-card">
      <h1>Halo, <?php echo htmlspecialchars($user['nama']); ?> 👋</h1>
      <p>Berikut ringkasan statistik partisipasi dan laporan pada sistem EcoCycle.</p>
    </div>

    <div class="stat-grid">
      <div class="stat-box">
        <div class="num"><?php echo (int)$totalUser; ?></div>
        <div class="label">Total Masyarakat Terdaftar</div>
      </div>
      <div class="stat-box">
        <div class="num"><?php echo (int)$totalLaporan; ?></div>
        <div class="label">Total Laporan Masuk</div>
      </div>
      <div class="stat-box">
        <div class="num"><?php echo (int)$totalPending; ?></div>
        <div class="label">Menunggu Verifikasi</div>
      </div>
      <div class="stat-box">
        <div class="num"><?php echo (int)$totalSelesai; ?></div>
        <div class="label">Laporan Selesai</div>
      </div>
    </div>

    <div class="content-card">
      <h2>📋 Laporan Menunggu Verifikasi</h2>
      <?php if ($message) { ?>
        <div class="alert <?php echo $messageType === 'success' ? 'alert-success' : 'alert-error'; ?>"><?php echo htmlspecialchars($message); ?></div>
      <?php } ?>

      <?php if (mysqli_num_rows($laporanPending) === 0) { ?>
        <div class="alert alert-empty">Tidak ada laporan yang menunggu verifikasi.</div>
      <?php } else {
        while ($row = mysqli_fetch_assoc($laporanPending)) { ?>
        <div class="status-item" style="flex-direction:column; align-items:stretch; gap:14px;">
          <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:12px; flex-wrap:wrap;">
            <div class="info">
              <span><strong><?php echo htmlspecialchars($row['jenis_sampah']); ?></strong> — Pelapor: <?php echo htmlspecialchars($row['nama']); ?></span>
              <small> 📝 <?php echo htmlspecialchars($row['deskripsi']); ?></small>
              <small> 📞 Nomor HP : <?php echo htmlspecialchars($row['no_hp']); ?></small>
              <small> 🗓 Waktu Laporan : <?php echo date('d M Y H:i', strtotime($row['tanggal'])); ?></small>
              <small> 📍 Lokasi : <?php echo htmlspecialchars($row['lokasi']); ?>
              <br><a href="https://www.google.com/maps?q=<?php echo urlencode($row['lokasi']); ?>" target="_blank" class="btn-secondary" style="margin-top:6px; display:inline-block;"> 📍 Buka di Google Maps </a> </small>
            </div>
            <?php if ($row['foto']) { ?>
            <img src="<?php echo htmlspecialchars(asset_url($row['foto'])); ?>" class="thumb" alt="Foto sampah" style="width:350px;height:350px;object-fit:cover;border-radius:20px;">
            <?php } ?>
          </div>
          <div style="display:flex; gap:10px;">
            <form method="POST">
              <input type="hidden" name="id_laporan" value="<?php echo $row['id_laporan']; ?>">
              <input type="hidden" name="aksi" value="setuju">
              <button type="submit" class="btn-approve">✔ Setujui & Teruskan ke Petugas</button>
            </form>
            <form method="POST">
              <input type="hidden" name="id_laporan" value="<?php echo $row['id_laporan']; ?>">
              <input type="hidden" name="aksi" value="tolak">
              <button type="submit" class="btn-reject">✘ Tolak</button>
            </form>
          </div>
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

    navMenu.querySelectorAll("a").forEach(a => a.addEventListener("click", closeMenu));
  </script>
</body>
</html>
