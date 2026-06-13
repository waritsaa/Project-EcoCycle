<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index2.html");
    exit;
}
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EcoCycle - Lapor Sampah</title>
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
    <li><a href="lapor.php" class="active-link">🗑 Lapor Sampah</a></li>
    <li><a href="status_laporan.php">📋 Status Laporan</a></li>
    <li><a href="edukasi.php">📚 Edukasi Cara Memilah Sampah</a></li>
    <li><a href="lokasi_pengepul.php">📍 Lokasi Daur Ulang</a></li>
    <li><a href="jadwal_pengangkutan.php">🗓 Jadwal Pengangkutan</a></li>
    <li><a href="profil.php">👤 Profil</a></li>
    <li><a href="logout.php">🚪 Logout</a></li>
  </ul>

  <section class="dashboard-container">
    <div class="form-page">
      <h2>🗑 Laporkan Sampah</h2>
      <p class="subtitle">Unggah foto, aktifkan GPS, lalu isi detail sampah yang ditemukan.</p>

      <div id="formAlert"></div>

      <form id="laporForm" enctype="multipart/form-data">
        <label for="foto">Foto Sampah</label>
        <input type="file" id="foto" name="foto" accept="image/*" required>

        <div class="gps-box" id="gpsBox">
          <span id="gpsStatus">📍 GPS belum aktif</span>
          <button type="button" id="btnGps">Aktifkan GPS</button>
        </div>
        <input type="hidden" id="lokasi" name="lokasi" required>

        <label for="jenis_sampah">Jenis Sampah</label>
        <select id="jenis_sampah" name="jenis_sampah" required>
          <option value="">-- Pilih jenis sampah --</option>
          <option value="Organik">Organik</option>
          <option value="Anorganik">Anorganik</option>
          <option value="B3">Bahan Berbahaya (B3)</option>
          <option value="Campuran">Campuran</option>
        </select>

        <label for="deskripsi">Deskripsi</label>
        <textarea id="deskripsi" name="deskripsi" rows="4" placeholder="Jelaskan kondisi sampah, volume, dan lokasi sekitar..." required></textarea>

        <button type="submit" class="submit-btn" style="margin-top:22px;">Kirim Laporan</button>
      </form>
    </div>
  </section>

  <script>
    let menuToggle = document.getElementById("menuToggle");
    let navMenu = document.getElementById("navMenu");
    menuToggle.onclick = function () {
      navMenu.classList.toggle("show");
    };

    const gpsBox = document.getElementById("gpsBox");
    const gpsStatus = document.getElementById("gpsStatus");
    const lokasiInput = document.getElementById("lokasi");
    const btnGps = document.getElementById("btnGps");

    btnGps.onclick = function () {
      if (!navigator.geolocation) {
        gpsStatus.textContent = "❌ Browser tidak mendukung GPS";
        return;
      }
      gpsStatus.textContent = "📍 Mendeteksi lokasi...";
      navigator.geolocation.getCurrentPosition(
        function (pos) {
          const lat = pos.coords.latitude.toFixed(6);
          const lng = pos.coords.longitude.toFixed(6);
          lokasiInput.value = lat + "," + lng;
          gpsBox.classList.add("active");
          gpsStatus.textContent = "✅ Lokasi terdeteksi: " + lat + ", " + lng;
          btnGps.textContent = "Perbarui";
        },
        function () {
          gpsStatus.textContent = "❌ Gagal mengambil lokasi. Izinkan akses GPS.";
        }
      );
    };

    const laporForm = document.getElementById("laporForm");
    const formAlert = document.getElementById("formAlert");

    laporForm.addEventListener("submit", function (e) {
      e.preventDefault();

      if (!lokasiInput.value) {
        formAlert.innerHTML = '<div class="alert alert-error">Aktifkan GPS terlebih dahulu sebelum mengirim laporan.</div>';
        return;
      }

      const formData = new FormData(laporForm);
      formAlert.innerHTML = '<div class="alert alert-empty">Mengirim laporan...</div>';

      fetch("simpan_laporan.php", {
        method: "POST",
        body: formData
      })
        .then(res => res.text())
        .then(data => {
          if (data.trim().startsWith("sukses")) {
            formAlert.innerHTML = '<div class="alert alert-success">Laporan berhasil dikirim! Kamu mendapat +10 poin. Mengalihkan...</div>';
            setTimeout(() => window.location = "status_laporan.php", 1200);
          } else {
            formAlert.innerHTML = '<div class="alert alert-error">' + data + '</div>';
          }
        })
        .catch(() => {
          formAlert.innerHTML = '<div class="alert alert-error">Terjadi kesalahan, coba lagi.</div>';
        });
    });
  </script>
</body>
</html>
