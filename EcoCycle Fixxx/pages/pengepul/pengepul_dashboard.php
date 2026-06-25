<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'pengepul') {
    header("Location: ../../index.html");
    exit;
}
include '../../config/koneksi.php';

$id_user = $_SESSION['user']['id_user'];

// Ambil ulang data user terbaru dari DB, supaya nama/foto yang baru
// diubah lewat Edit Profil langsung tampil di dashboard ini.
$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id_user = ?");
mysqli_stmt_bind_param($stmt, "i", $id_user);
mysqli_stmt_execute($stmt);
$user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
$_SESSION['user'] = $user;

$message = '';
$messageType = '';

// Mitra/pengepul mengambil (konfirmasi) sebuah listing marketplace
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_item']) && isset($_POST['aksi']) && $_POST['aksi'] === 'ambil') {
    $id_item = (int)$_POST['id_item'];

    $stmtAmbil = mysqli_prepare($conn,
        "UPDATE marketplace SET status = 'diambil', id_mitra_pengambil = ? WHERE id_item = ? AND status = 'tersedia'"
    );
    mysqli_stmt_bind_param($stmtAmbil, "ii", $id_user, $id_item);

    if (mysqli_stmt_execute($stmtAmbil) && mysqli_stmt_affected_rows($stmtAmbil) > 0) {
        $message = "Listing #$id_item berhasil diambil. Silakan hubungi pelapor untuk koordinasi penjemputan.";
        $messageType = "success";
    } else {
        $message = "Listing ini sudah diambil mitra lain atau tidak tersedia.";
        $messageType = "error";
    }
}

// Listing sampah terpilah yang masih tersedia di marketplace
$result = mysqli_query($conn, "SELECT m.*, u.nama, u.no_hp FROM marketplace m JOIN users u ON m.id_user = u.id_user WHERE m.status = 'tersedia' ORDER BY m.tanggal DESC");

// Listing yang sudah diambil oleh mitra ini sendiri
$stmtMine = mysqli_prepare($conn,
    "SELECT m.*, u.nama, u.no_hp FROM marketplace m JOIN users u ON m.id_user = u.id_user WHERE m.id_mitra_pengambil = ? ORDER BY m.tanggal DESC"
);
mysqli_stmt_bind_param($stmtMine, "i", $id_user);
mysqli_stmt_execute($stmtMine);
$myPickups = mysqli_stmt_get_result($stmtMine);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EcoCycle - Dashboard Mitra / Pengepul</title>
  <link rel="stylesheet" href="../../assets/css/style2.css">
</head>
<body class="dashboard-body theme-pengepul">
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
    <li><a href="../../pages/pengepul/pengepul_dashboard.php" class="active-link">🏠 Home</a></li>
    <li><a href="../../pages/pengepul/profilMitra.php">👤 Profil</a></li>
    <li><a href="../../actions/logout.php" onclick="return confirm('Apakah Anda yakin ingin keluar?')">🚪 Logout</a></li>
  </ul>
  <div class="nav-overlay" id="navOverlay"></div>

  <section class="dashboard-container">
    <div class="welcome-card">
      <h1>Halo, <?php echo htmlspecialchars($user['nama']); ?> 👋</h1>
      <p>Berikut daftar sampah terpilah dari masyarakat yang tersedia untuk dijemput / dibeli.</p>
    </div>

    <?php if ($message) { ?>
      <div class="alert <?php echo $messageType === 'success' ? 'alert-success' : 'alert-error'; ?>"><?php echo htmlspecialchars($message); ?></div>
    <?php } ?>

    <div class="content-card">
      <h2>♻ Marketplace Sampah Terpilah</h2>

      <?php if (mysqli_num_rows($result) === 0) { ?>
        <div class="alert alert-empty">Belum ada listing sampah terpilah yang tersedia saat ini.</div>
      <?php } else {
        while ($row = mysqli_fetch_assoc($result)) { ?>
        <div class="status-item" style="flex-direction:column; align-items:stretch; gap:12px;">
          <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:12px; flex-wrap:wrap;">
            <div class="info">
              <span><strong><?php echo htmlspecialchars($row['jenis_sampah']); ?></strong> — <?php echo (float)$row['berat_kg']; ?> kg, oleh <?php echo htmlspecialchars($row['nama']); ?></span>
              <small>💰 Rp<?php echo number_format($row['harga'], 0, ',', '.'); ?> · <?php echo date('d M Y', strtotime($row['tanggal'])); ?></small>
              <?php if (!empty($row['no_hp'])) { ?>
                <small>📞 <?php echo htmlspecialchars($row['no_hp']); ?></small>
              <?php } ?>
            </div>
            <?php if (!empty($row['foto'])) { ?>
              <img src="<?php echo htmlspecialchars(asset_url($row['foto'])); ?>" class="thumb" alt="Foto sampah">
            <?php } ?>
          </div>
          <form method="POST">
            <input type="hidden" name="id_item" value="<?php echo $row['id_item']; ?>">
            <input type="hidden" name="aksi" value="ambil">
            <button type="submit" class="btn-approve">✔ Ambil Listing Ini</button>
          </form>
        </div>
      <?php } } ?>
    </div>

    <div class="content-card">
      <h2>📦 Listing yang Saya Ambil</h2>
      <?php if (mysqli_num_rows($myPickups) === 0) { ?>
        <div class="alert alert-empty">Anda belum mengambil listing apa pun.</div>
      <?php } else {
        while ($row = mysqli_fetch_assoc($myPickups)) { ?>
        <div class="status-item">
          <div class="info">
            <span><strong><?php echo htmlspecialchars($row['jenis_sampah']); ?></strong> — <?php echo (float)$row['berat_kg']; ?> kg, dari <?php echo htmlspecialchars($row['nama']); ?></span>
            <small>💰 Rp<?php echo number_format($row['harga'], 0, ',', '.'); ?></small>
          </div>
          <span class="status <?php echo $row['status'] === 'selesai' ? 'status-selesai' : 'status-diproses'; ?>">
            <?php echo $row['status'] === 'selesai' ? 'Selesai' : 'Diambil'; ?>
          </span>
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
    // Tutup menu saat klik link
    navMenu.querySelectorAll("a").forEach(a => a.addEventListener("click", closeMenu));
  </script>
</body>
</html>
