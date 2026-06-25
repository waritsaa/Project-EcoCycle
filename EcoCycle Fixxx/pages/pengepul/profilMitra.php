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
$user = mysqli_fetch_assoc(
    mysqli_stmt_get_result($stmt)
);

$_SESSION['user'] = $user;

$stmtTotal = mysqli_prepare($conn, "SELECT COUNT(*) total FROM marketplace WHERE id_mitra_pengambil = ?");
mysqli_stmt_bind_param($stmtTotal, "i", $id_user);
mysqli_stmt_execute($stmtTotal);
$totalListing = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtTotal))['total'];

$stmtSelesai = mysqli_prepare($conn, "SELECT COUNT(*) total FROM marketplace WHERE id_mitra_pengambil = ? AND status = 'selesai'");
mysqli_stmt_bind_param($stmtSelesai, "i", $id_user);
mysqli_stmt_execute($stmtSelesai);
$totalSelesai = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtSelesai))['total'];

$stmtBerat = mysqli_prepare($conn, "SELECT COALESCE(SUM(berat_kg),0) total FROM marketplace WHERE id_mitra_pengambil = ?");
mysqli_stmt_bind_param($stmtBerat, "i", $id_user);
mysqli_stmt_execute($stmtBerat);
$totalBerat = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtBerat))['total'];

$stmtNilai = mysqli_prepare($conn, "SELECT COALESCE(SUM(harga),0) total FROM marketplace WHERE id_mitra_pengambil = ?");
mysqli_stmt_bind_param($stmtNilai, "i", $id_user);
mysqli_stmt_execute($stmtNilai);
$totalNilai = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtNilai))['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>EcoCycle - Profil Mitra</title>
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
    <li><a href="../../pages/pengepul/pengepul_dashboard.php"> 🏠 Home</a></li>
    <li><a href="../../pages/pengepul/profilMitra.php" class="active-link">👤 Profil</a></li>
    <li><a href="../../actions/logout.php" onclick="return confirm('Apakah Anda yakin ingin keluar?')"> 🚪 Logout</a></li>
</ul>

<section class="dashboard-container">
    <div class="profile-modern-card">
        <div class="profile-header-modern">
            <div class="profile-avatar-modern">
                <?php if(!empty($user['foto'])){ ?>
                    <img
                    src="<?php echo htmlspecialchars(asset_url($user['foto'])); ?>"
                    alt="Foto Profil">
                <?php } else { ?>
                    👤
                <?php } ?>
            </div>

            <h2> <?php echo htmlspecialchars($user['nama']); ?></h2>
            <span class="role-badge"> Mitra / Pengepul</span>
            <p class="joined-date">
                Bergabung :
                <?php echo date('d M Y', strtotime($user['created_at'])); ?>
            </p>
        </div>

        <div class="profile-info-grid">
            <div class="profile-info-item">
                <label>Nama Lengkap</label><span><?php echo htmlspecialchars($user['nama']); ?></span>
            </div>
            <div class="profile-info-item">
                <label>Email</label><span><?php echo htmlspecialchars($user['email']); ?></span>
            </div>
            <div class="profile-info-item">
                <label>Nomor HP</label><span><?php echo !empty($user['no_hp']) ? htmlspecialchars($user['no_hp']) : '-'; ?></span>
            </div>
            <div class="profile-info-item">
                <label>Alamat</label><span><?php echo !empty($user['alamat']) ? htmlspecialchars($user['alamat']) : '-'; ?></span>
            </div>
        </div>
    </div>

<div class="content-card">
    <h2>📊 Statistik Saya sebagai Mitra Daur Ulang</h2>
    <div class="status-item">
        <div class="info"><span>Total Listing Diambil</span></div>
        <span class="status status-selesai"><?php echo $totalListing; ?></span>
    </div>

    <div class="status-item">
        <div class="info"><span>Sudah Selesai</span></div>
        <span class="status status-diproses"><?php echo $totalSelesai; ?></span>
    </div>

    <div class="status-item">
        <div class="info"><span>Total Berat Sampah Diambil</span></div>
        <span class="status status-selesai"><?php echo round($totalBerat,1); ?> Kg</span>
    </div>

    <div class="status-item">
        <div class="info"><span>Estimasi Nilai Transaksi</span></div>
        <span class="status status-pending">Rp<?php echo number_format($totalNilai,0,',','.'); ?></span>
    </div>
</div>

    <div class="content-card">
        <h2>⚙ Pengaturan Akun</h2>
        <a href="../../actions/edit_profil.php">
            <button class="submit-btn-sm">Edit Profil</button>
        </a>
    </div>
</section>

<script>
let menuToggle =
document.getElementById("menuToggle");

let navMenu =
document.getElementById("navMenu");

menuToggle.onclick = function () {
    navMenu.classList.toggle("show");
};

</script>

</body>
</html>