<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'petugas') {
    header("Location: index2.html");
    exit;
}
include 'koneksi.php';

$id_petugas = $_SESSION['user']['id_user'];
$message = '';
$messageType = '';

// Konfirmasi pengambilan + input berat
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_laporan'])) {
    $id_laporan = (int)$_POST['id_laporan'];
    $berat = (float)$_POST['berat_sampah'];

    if ($berat <= 0) {
        $message = "Berat sampah harus diisi dan lebih dari 0";
        $messageType = "error";
    } else {
        mysqli_begin_transaction($conn);
        try {
            // Update status laporan jadi selesai
            $stmt1 = mysqli_prepare($conn, "UPDATE laporan SET status = 'selesai' WHERE id_laporan = ?");
            mysqli_stmt_bind_param($stmt1, "i", $id_laporan);
            mysqli_stmt_execute($stmt1);

            // Simpan data pengangkutan
            $stmt2 = mysqli_prepare($conn, "INSERT INTO pengangkutan (id_laporan, id_petugas, tanggal, berat_sampah, status) VALUES (?, ?, CURDATE(), ?, 'selesai')");
            mysqli_stmt_bind_param($stmt2, "iid", $id_laporan, $id_petugas, $berat);
            mysqli_stmt_execute($stmt2);

            // Tambah poin tambahan ke pelapor berdasarkan berat (misal 2 poin per kg)
            $stmtUser = mysqli_prepare($conn, "SELECT id_user FROM laporan WHERE id_laporan = ?");
            mysqli_stmt_bind_param($stmtUser, "i", $id_laporan);
            mysqli_stmt_execute($stmtUser);
            $pelapor = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtUser));

            $poinTambahan = (int)round($berat * 2);
            $stmt3 = mysqli_prepare($conn, "UPDATE users SET point = point + ? WHERE id_user = ?");
            mysqli_stmt_bind_param($stmt3, "ii", $poinTambahan, $pelapor['id_user']);
            mysqli_stmt_execute($stmt3);

            mysqli_commit($conn);
            $message = "Laporan #$id_laporan berhasil ditandai selesai. Berat: {$berat}kg, +{$poinTambahan} poin diberikan ke pelapor.";
            $messageType = "success";
        } catch (Exception $e) {
            mysqli_rollback($conn);
            $message = "Terjadi kesalahan saat memproses laporan";
            $messageType = "error";
        }
    }
}

// Ambil semua laporan diproses
$laporanList = mysqli_query($conn, "SELECT l.*, u.nama FROM laporan l JOIN users u ON l.id_user = u.id_user WHERE l.status = 'diproses' ORDER BY l.tanggal ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EcoCycle - Daftar Laporan</title>
  <link rel="stylesheet" href="style2.css">
</head>
<body class="dashboard-body theme-petugas">
  <nav class="navbar">
    <div class="left-navbar">
      <div class="menu-toggle" id="menuToggle">☰</div>
      <div class="logo">♻ EcoCycle</div>
    </div>
  </nav>

  <ul class="nav-menu" id="navMenu">
    <li><a href="petugas_dashboard.php">🏠 Home</a></li>
    <li><a href="petugas_laporan.php" class="active-link">📋 Daftar Laporan</a></li>
    <li><a href="petugas_rute.php">🗺 Rute Pengangkutan</a></li>
    <li><a href="petugas_jadwal.php">🗓 Atur Jadwal</a></li>
    <li><a href="profil.php">👤 Profil</a></li>
    <li><a href="logout.php">🚪 Logout</a></li>
  </ul>

  <section class="dashboard-container">
    <div class="content-card">
      <h2>📋 Laporan Terverifikasi — Menunggu Diangkut</h2>

      <?php if ($message) { ?>
        <div class="alert <?php echo $messageType === 'success' ? 'alert-success' : 'alert-error'; ?>"><?php echo htmlspecialchars($message); ?></div>
      <?php } ?>

      <?php if (mysqli_num_rows($laporanList) === 0) { ?>
        <div class="alert alert-empty">Tidak ada laporan untuk ditangani saat ini.</div>
      <?php } else {
        while ($row = mysqli_fetch_assoc($laporanList)) { ?>
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

          <form method="POST" style="display:flex; gap:10px; align-items:flex-end; flex-wrap:wrap;">
            <input type="hidden" name="id_laporan" value="<?php echo $row['id_laporan']; ?>">
            <div style="flex:1; min-width:160px;">
              <label style="display:block; font-size:12px; font-weight:700; margin-bottom:6px; color:#3c4a3a;">Berat Sampah (kg)</label>
              <input type="number" step="0.1" min="0.1" name="berat_sampah" required style="width:100%; padding:10px 12px; border:1.5px solid #dde5d8; border-radius:10px; font-size:13.5px;">
            </div>
            <button type="submit" class="btn-approve">✔ Konfirmasi Pengambilan</button>
          </form>
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