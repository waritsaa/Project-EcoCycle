<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: index2.html");
    exit;
}
include 'koneksi.php';

$user = $_SESSION['user'];

// Statistik ringkas
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

    $stmt = mysqli_prepare($conn, "UPDATE laporan SET status = ? WHERE id_laporan = ?");
    mysqli_stmt_bind_param($stmt, "si", $aksi, $id_laporan);
    if (mysqli_stmt_execute($stmt)) {
        $message = $aksi === 'diproses' ? "Laporan #$id_laporan diverifikasi & diteruskan ke petugas." : "Laporan #$id_laporan ditolak.";
        $messageType = "success";
    } else {
        $message = "Gagal memperbarui laporan";
        $messageType = "error";
    }
}

// Laporan pending menunggu verifikasi
$laporanPending = mysqli_query($conn, "SELECT l.*, u.nama FROM laporan l JOIN users u ON l.id_user = u.id_user WHERE l.status = 'pending' ORDER BY l.tanggal ASC LIMIT 10");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EcoCycle - Dashboard Admin</title>
  <link rel="stylesheet" href="style2.css">
</head>
<body class="dashboard-body theme-admin">
  <nav class="navbar">
    <div class="left-navbar">
      <div class="menu-toggle" id="menuToggle">☰</div>
      <div class="logo">♻ EcoCycle</div>
    </div>
  </nav>

  <ul class="nav-menu" id="navMenu">
    <li><a href="admin_dashboard.php" class="active-link">🏠 Home</a></li>
    <li><a href="admin_laporan.php">📋 Verifikasi Laporan</a></li>
    <li><a href="profil.php">👤 Profil</a></li>
    <li><a href="logout.php">🚪 Logout</a></li>
  </ul>

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
              <small><?php echo htmlspecialchars($row['deskripsi']); ?></small>
              <small>📍 <a href="https://www.google.com/maps?q=<?php echo htmlspecialchars($row['lokasi']); ?>" target="_blank">Buka Lokasi (<?php echo htmlspecialchars($row['lokasi']); ?>)</a></small>
              <small>🗓 <?php echo date('d M Y, H:i', strtotime($row['tanggal'])); ?></small>
            </div>
            <?php if ($row['foto']) { ?>
              <img src="<?php echo htmlspecialchars($row['foto']); ?>" class="thumb" alt="Foto sampah">
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
    let menuToggle = document.getElementById("menuToggle");
    let navMenu = document.getElementById("navMenu");
    menuToggle.onclick = function () {
      navMenu.classList.toggle("show");
    };
  </script>
</body>
</html>
