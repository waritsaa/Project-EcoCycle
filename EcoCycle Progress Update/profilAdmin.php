<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: index2.html");
    exit;
}

include 'koneksi.php';

$id_user = $_SESSION['user']['id_user'];

$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id_user = ?");
mysqli_stmt_bind_param($stmt, "i", $id_user);
mysqli_stmt_execute($stmt);

$user = mysqli_fetch_assoc(
    mysqli_stmt_get_result($stmt)
);

// Statistik Admin //
$totalUser = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) total FROM users")
)['total'];

$totalLaporan = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) total FROM laporan")
)['total'];

$totalPending = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) total FROM laporan WHERE status='pending'")
)['total'];

$totalSelesai = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) total FROM laporan WHERE status='selesai'")
)['total'];
?>

<!DOCTYPE html>

<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>EcoCycle - Profil Admin</title>
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
    <li><a href="admin_dashboard.php">🏠 Home</a></li>
    <li><a href="admin_laporan.php">📋 Verifikasi Laporan</a></li>
    <li><a href="profilAdmin.php" class="active-link">👤 Profil</a></li>
    <li><a href="logout.php">🚪 Logout</a></li>
</ul>

<section class="dashboard-container">
<div class="profile-modern-card">
<div class="profile-header-modern">
    <div class="profile-avatar-modern">
        <?php if(!empty($user['foto'])){ ?>
            <img src="<?php echo htmlspecialchars($user['foto']); ?>" alt="Foto Profil">
        <?php } else { ?>
            👤
        <?php } ?>
    </div>

    <h2><?php echo htmlspecialchars($user['nama']); ?></h2>
    <span class="role-badge"> Administrator </span>

    <p class="joined-date">
        Bergabung :
        <?php
        echo !empty($user['created_at'])
        ? date('d M Y', strtotime($user['created_at']))
        : '-';
        ?>
    </p>
</div>

<div class="profile-info-grid">
    <div class="profile-info-item"><label>Nama Lengkap</label><span><?php echo htmlspecialchars($user['nama']); ?></span></div>
    <div class="profile-info-item"><label>Email</label><span><?php echo htmlspecialchars($user['email']); ?></span></div>
    <div class="profile-info-item"><label>Nomor HP</label><span><?php echo !empty($user['no_hp']) ? htmlspecialchars($user['no_hp']) : '-'; ?></span></div>
    <div class="profile-info-item"><label>Alamat</label><span><?php echo !empty($user['alamat']) ? htmlspecialchars($user['alamat']) : '-'; ?></span></div>
</div>
</div>

<div class="content-card">
<h2>📊 Statistik Administrator</h2>
<div class="status-item"><div class="info"><span>Total User</span></div>
    <span class="status status-selesai"><?php echo $totalUser; ?></span>
</div>

<div class="status-item">
    <div class="info"><span>Total Laporan</span></div>
    <span class="status status-diproses"><?php echo $totalLaporan; ?></span>
</div>
<div class="status-item">
    <div class="info"><span>Laporan Pending</span></div>
    <span class="status status-pending"><?php echo $totalPending; ?></span>
</div>
<div class="status-item">
    <div class="info"><span>Laporan Selesai</span></div>
    <span class="status status-selesai"><?php echo $totalSelesai; ?></span>
</div>
</div>

<div class="content-card">
<h2>⚙ Pengaturan Akun</h2>
<a href="edit_profil.php"><button>Edit Profil</button></a>
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