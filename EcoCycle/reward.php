<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index2.html");
    exit;
}
include 'koneksi.php';

$id_user = $_SESSION['user']['id_user'];

// refresh poin
$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id_user = ?");
mysqli_stmt_bind_param($stmt, "i", $id_user);
mysqli_stmt_execute($stmt);
$user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
$_SESSION['user'] = $user;

$message = '';
$messageType = '';

// Proses penukaran
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_reward'])) {
    $id_reward = (int)$_POST['id_reward'];

    $stmtR = mysqli_prepare($conn, "SELECT * FROM reward WHERE id_reward = ?");
    mysqli_stmt_bind_param($stmtR, "i", $id_reward);
    mysqli_stmt_execute($stmtR);
    $reward = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtR));

    if (!$reward) {
        $message = "Reward tidak ditemukan";
        $messageType = "error";
    } elseif ($reward['stok'] <= 0) {
        $message = "Stok reward sudah habis";
        $messageType = "error";
    } elseif ($user['point'] < $reward['point_required']) {
        $message = "Poin kamu belum cukup untuk menukar reward ini. Sisa kebutuhan: " . ($reward['point_required'] - $user['point']) . " poin";
        $messageType = "error";
    } else {
        mysqli_begin_transaction($conn);
        try {
            // kurangi poin user
            $stmtU = mysqli_prepare($conn, "UPDATE users SET point = point - ? WHERE id_user = ?");
            mysqli_stmt_bind_param($stmtU, "ii", $reward['point_required'], $id_user);
            mysqli_stmt_execute($stmtU);

            // kurangi stok reward
            $stmtS = mysqli_prepare($conn, "UPDATE reward SET stok = stok - 1 WHERE id_reward = ?");
            mysqli_stmt_bind_param($stmtS, "i", $id_reward);
            mysqli_stmt_execute($stmtS);

            // catat penukaran
            $stmtP = mysqli_prepare($conn, "INSERT INTO penukaran_reward (id_user, id_reward, status) VALUES (?, ?, 'diproses')");
            mysqli_stmt_bind_param($stmtP, "ii", $id_user, $id_reward);
            mysqli_stmt_execute($stmtP);

            mysqli_commit($conn);

            $user['point'] -= $reward['point_required'];
            $_SESSION['user']['point'] = $user['point'];

            $message = "Berhasil menukar poin dengan \"" . $reward['nama_reward'] . "\"!";
            $messageType = "success";
        } catch (Exception $e) {
            mysqli_rollback($conn);
            $message = "Terjadi kesalahan saat menukar reward";
            $messageType = "error";
        }
    }
}

$rewards = mysqli_query($conn, "SELECT * FROM reward WHERE stok > 0 ORDER BY point_required ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EcoCycle - Tukar Reward</title>
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
    <li><a href="profil.php">👤 Profil</a></li>
    <li><a href="logout.php">🚪 Logout</a></li>
  </ul>

  <section class="dashboard-container">
    <div class="point-card">
      <h2>🌟 Poin Kamu Saat Ini</h2>
      <h1><?php echo (int)$user['point']; ?></h1>
    </div>

    <div class="content-card">
      <h2>🎁 Tukar Poin dengan Reward</h2>

      <?php if ($message) { ?>
        <div class="alert <?php echo $messageType === 'success' ? 'alert-success' : 'alert-error'; ?>"><?php echo htmlspecialchars($message); ?></div>
      <?php } ?>

      <?php if (mysqli_num_rows($rewards) === 0) { ?>
        <div class="alert alert-empty">Belum ada reward tersedia saat ini.</div>
      <?php } else {
        while ($r = mysqli_fetch_assoc($rewards)) {
          $cukup = $user['point'] >= $r['point_required'];
      ?>
        <div class="status-item">
          <div class="info">
            <span><strong><?php echo htmlspecialchars($r['nama_reward']); ?></strong></span>
            <small><?php echo (int)$r['point_required']; ?> poin · Stok: <?php echo (int)$r['stok']; ?></small>
          </div>
          <form method="POST">
            <input type="hidden" name="id_reward" value="<?php echo $r['id_reward']; ?>">
            <button type="submit" class="submit-btn-sm" <?php echo $cukup ? '' : 'disabled style="opacity:0.45; cursor:not-allowed;"'; ?>>
              Tukar
            </button>
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
