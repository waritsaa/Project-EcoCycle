<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'masyarakat') {
    header("Location: ../../index.html");
    exit;
}
include '../../config/koneksi.php';

$hargaList = [];
$resultHarga = mysqli_query($conn, "SELECT * FROM harga_sampah");

while ($h = mysqli_fetch_assoc($resultHarga)) {
    $hargaList[$h['jenis_sampah']] = $h['harga_per_kg'];
}

$id_user = $_SESSION['user']['id_user'];
$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id_user = ?");
mysqli_stmt_bind_param($stmt, "i", $id_user);
mysqli_stmt_execute($stmt);
$user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
$_SESSION['user'] = $user;

// Listing milik saya sendiri (riwayat jual)
$stmtMine = mysqli_prepare($conn,
    "SELECT m.*, u2.nama AS nama_pengambil, u2.no_hp AS hp_pengambil
     FROM marketplace m
     LEFT JOIN users u2 ON m.id_mitra_pengambil = u2.id_user
     WHERE m.id_user = ? ORDER BY m.tanggal DESC"
);
mysqli_stmt_bind_param($stmtMine, "i", $id_user);
mysqli_stmt_execute($stmtMine);
$myListings = mysqli_stmt_get_result($stmtMine);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EcoCycle - Jual Sampah Terpilah</title>
  <link rel="stylesheet" href="../../assets/css/style2.css">
</head>
<body class="dashboard-body">
  <nav class="navbar">
    <div class="left-navbar">
      <div class="menu-toggle" id="menuToggle">☰</div>
      <div class="logo">♻ EcoCycle</div>
    </div>
  </nav>

  <ul class="nav-menu" id="navMenu">
    <li><a href="../../pages/masyarakat/dashboard2.php">🏠 Home</a></li>
    <li><a href="../../pages/masyarakat/lapor.php">🗑 Lapor Sampah</a></li>
    <li><a href="../../pages/masyarakat/status_laporan.php">📋 Status Laporan</a></li>
    <li><a href="../../pages/masyarakat/marketplace_masyarakat.php" class="active-link">🛒 Jual Sampah Terpilah</a></li>
    <li><a href="../../pages/masyarakat/edukasi.php">📚 Edukasi Cara Memilah Sampah</a></li>
    <li><a href="../../pages/masyarakat/lokasi_pengepul.php">📍 Lokasi Daur Ulang</a></li>
    <li><a href="../../pages/masyarakat/jadwal_pengangkutan.php">🗓 Jadwal Pengangkutan</a></li>
    <li><a href="../../pages/masyarakat/profilMasyarakat.php">👤 Profil</a></li>
    <li><a href="../../actions/logout.php" onclick="return confirm('Apakah Anda yakin ingin keluar?')">🚪 Logout</a></li>
  </ul>
  <div class="nav-overlay" id="navOverlay"></div>

  <section class="dashboard-container">
    <div class="form-page">
      <h2>🛒 Jual Sampah Terpilah</h2>
      <p class="subtitle">Sudah pilah sampah anorganik di rumah? Unggah ke sini supaya mitra/pengepul bisa langsung menjemput atau membelinya.</p>

      <div id="formAlert"></div>

      <form id="marketForm" enctype="multipart/form-data">
        <label for="jenis_sampah">Jenis Sampah</label>
        <select id="jenis_sampah" name="jenis_sampah" required>
          <option value="">-- Pilih jenis sampah --</option>
          <option value="Plastik">Plastik</option>
          <option value="Kertas">Kertas</option>
          <option value="Logam">Logam</option>
          <option value="Kaca">Kaca</option>
          <option value="Campuran">Campuran Anorganik</option>
        </select>

        <label for="berat_kg">Berat (kg)</label>
        <input type="number" id="berat_kg" name="berat_kg" min="0.1" step="0.1" required>
        <label for="hargaDisplay">Estimasi Harga (Rp)</label>
        <input type="text" id="hargaDisplay" value="Rp 0" readonly>
        <input type="hidden" id="harga" name="harga">

        <label for="foto">Foto Sampah</label>
        <input type="file" id="foto" name="foto" accept="image/*" required>

        <button type="submit" class="submit-btn" style="margin-top:22px;">Pasang ke Marketplace</button>
      </form>
    </div>

    <div class="content-card" style="margin-top:24px;">
      <h2>📦 Listing Saya</h2>
      <?php if (mysqli_num_rows($myListings) === 0) { ?>
        <div class="alert alert-empty">Anda belum memasang listing apa pun.</div>
      <?php } else {
        while ($row = mysqli_fetch_assoc($myListings)) { ?>
        <div class="status-item" style="flex-direction:column; align-items:stretch; gap:12px;">
          <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:12px; flex-wrap:wrap;">
            <div class="info">
              <span><strong><?php echo htmlspecialchars($row['jenis_sampah']); ?></strong> — <?php echo (float)$row['berat_kg']; ?> kg</span>
              <small>💰 Rp<?php echo number_format($row['harga'], 0, ',', '.'); ?> · <?php echo date('d M Y', strtotime($row['tanggal'])); ?></small>
              <?php if ($row['status'] !== 'tersedia' && !empty($row['nama_pengambil'])) { ?>
                <small>🤝 Diambil oleh: <?php echo htmlspecialchars($row['nama_pengambil']); ?><?php echo !empty($row['hp_pengambil']) ? ' · 📞 ' . htmlspecialchars($row['hp_pengambil']) : ''; ?></small>
              <?php } ?>
            </div>
            <?php if (!empty($row['foto'])) { ?>
              <img src="<?php echo htmlspecialchars(asset_url($row['foto'])); ?>" class="thumb" alt="Foto sampah">
            <?php } ?>
          </div>
          <span class="status <?php
            echo $row['status'] === 'tersedia' ? 'status-pending' : ($row['status'] === 'selesai' ? 'status-selesai' : 'status-diproses');
          ?>">
            <?php
              $labelStatus = ['tersedia' => 'Tersedia', 'diambil' => 'Diambil Mitra', 'selesai' => 'Selesai'];
              echo $labelStatus[$row['status']] ?? ucfirst($row['status']);
            ?>
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

    const marketForm = document.getElementById("marketForm");
    const formAlert = document.getElementById("formAlert");

    marketForm.addEventListener("submit", function (e) {
      e.preventDefault();

      const formData = new FormData(marketForm);
      formAlert.innerHTML = '<div class="alert alert-empty">Memasang listing...</div>';

      fetch("../../actions/simpan_marketplace.php", {
        method: "POST",
        body: formData
      })
        .then(res => res.text())
        .then(data => {
          if (data.trim().startsWith("sukses")) {
            formAlert.innerHTML = '<div class="alert alert-success">Listing berhasil dipasang ke marketplace!</div>';
            setTimeout(() => window.location.reload(), 1200);
          } else {
            formAlert.innerHTML = '<div class="alert alert-error">' + data + '</div>';
          }
        })
        .catch(() => {
          formAlert.innerHTML = '<div class="alert alert-error">Terjadi kesalahan, coba lagi.</div>';
        });
    });
  </script>

  <script>
  const hargaSampah = <?php echo json_encode($hargaList); ?>;
  const jenis = document.getElementById("jenis_sampah");
  const berat = document.getElementById("berat_kg");
  const harga = document.getElementById("harga");
  const hargaDisplay = document.getElementById("hargaDisplay");

  function hitungHarga(){
    const jenisDipilih = jenis.value;
    const kg = parseFloat(berat.value) || 0;
    const hargaKg = hargaSampah[jenisDipilih] || 0;
    const total = hargaKg * kg;
    harga.value = total;
    hargaDisplay.value = "Rp " + total.toLocaleString("id-ID");
  }

  jenis.addEventListener("change", hitungHarga);
  berat.addEventListener("input", hitungHarga);
</script>
</body>
</html>