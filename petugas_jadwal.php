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

// Tambah jadwal baru
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $area = trim($_POST['area'] ?? '');
    $hari = trim($_POST['hari'] ?? '');
    $tanggal = $_POST['tanggal'] ?? '';
    $waktu_mulai = $_POST['waktu_mulai'] ?? '';
    $jam_selesai = $_POST['jam_selesai'] ?? '';
    $rute = trim($_POST['rute'] ?? '');
    $keterangan = trim($_POST['keterangan'] ?? '');

    if ($area === '' || $hari === '' || $tanggal === '' || $waktu_mulai === '' || $jam_selesai === '') {
        $message = "Area, hari, tanggal, dan jam wajib diisi";
        $messageType = "error";
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO jadwal_pengangkutan (id_petugas, area, hari, tanggal, waktu_mulai, jam_selesai, rute, status, keterangan) VALUES (?, ?, ?, ?, ?, ?, ?, 'terjadwal', ?)");
        mysqli_stmt_bind_param($stmt, "issssss", $id_petugas, $area, $hari, $tanggal, $waktu_mulai, $jam_selesai, $rute, $keterangan);
        if (mysqli_stmt_execute($stmt)) {
            $message = "Jadwal pengangkutan berhasil ditambahkan";
            $messageType = "success";
        } else {
            $message = "Gagal menambahkan jadwal";
            $messageType = "error";
        }
    }
}

$result = mysqli_prepare($conn, "SELECT * FROM jadwal_pengangkutan WHERE id_petugas = ? OR id_petugas IS NULL ORDER BY tanggal ASC");
mysqli_stmt_bind_param($result, "i", $id_petugas);
mysqli_stmt_execute($result);
$jadwalList = mysqli_stmt_get_result($result);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EcoCycle - Atur Jadwal</title>
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
    <li><a href="petugas_laporan.php">📋 Daftar Laporan</a></li>
    <li><a href="petugas_rute.php">🗺 Rute Pengangkutan</a></li>
    <li><a href="petugas_jadwal.php" class="active-link">🗓 Atur Jadwal</a></li>
    <li><a href="profil.php">👤 Profil</a></li>
    <li><a href="logout.php">🚪 Logout</a></li>
  </ul>

  <section class="dashboard-container">
    <div class="form-page">
      <h2>🗓 Tambah Jadwal Pengangkutan</h2>

      <?php if ($message) { ?>
        <div class="alert <?php echo $messageType === 'success' ? 'alert-success' : 'alert-error'; ?>"><?php echo htmlspecialchars($message); ?></div>
      <?php } ?>

      <form method="POST">
        <label for="area">Area</label>
        <input type="text" id="area" name="area" placeholder="Contoh: Wilayah 4 - Sandubaya" required>

        <label for="hari">Hari</label>
        <input type="text" id="hari" name="hari" placeholder="Contoh: Selasa" required>

        <label for="tanggal">Tanggal</label>
        <input type="date" id="tanggal" name="tanggal" required>

        <label for="waktu_mulai">Jam Mulai</label>
        <input type="time" id="waktu_mulai" name="waktu_mulai" required>

        <label for="jam_selesai">Jam Selesai</label>
        <input type="time" id="jam_selesai" name="jam_selesai" required>

        <label for="rute">Rute</label>
        <input type="text" id="rute" name="rute" placeholder="Contoh: Jl. A - Jl. B - Jl. C">

        <label for="keterangan">Keterangan</label>
        <textarea id="keterangan" name="keterangan" rows="3" placeholder="Catatan tambahan (opsional)"></textarea>

        <button type="submit" class="submit-btn" style="margin-top:22px;">Simpan Jadwal</button>
      </form>
    </div>

    <div class="content-card">
      <h2>📋 Jadwal Tersimpan</h2>
      <?php if (mysqli_num_rows($jadwalList) === 0) { ?>
        <div class="alert alert-empty">Belum ada jadwal.</div>
      <?php } else {
        while ($row = mysqli_fetch_assoc($jadwalList)) { ?>
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
            <span class="status status-<?php echo $row['status'] === 'selesai' ? 'selesai' : 'pending'; ?>">
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
