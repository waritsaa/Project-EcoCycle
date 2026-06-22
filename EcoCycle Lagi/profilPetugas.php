<?php
session_start();

if (!isset($_SESSION['user'])) {
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

$_SESSION['user'] = $user;

/* =========================
    Statistik Petugas
========================= */

$stmtP = mysqli_prepare( $conn,
"SELECT COUNT(*) as total
FROM pengangkutan
WHERE id_petugas = ?"
);

mysqli_stmt_bind_param(
$stmtP,
"i",
$id_user
);

mysqli_stmt_execute($stmtP);

$totalPengangkutan =
mysqli_fetch_assoc(
mysqli_stmt_get_result($stmtP)
)['total'];

$stmtB = mysqli_prepare(
$conn,
"SELECT COALESCE(SUM(berat_sampah),0) as total
FROM pengangkutan
WHERE id_petugas = ?"
);

mysqli_stmt_bind_param(
$stmtB,
"i",
$id_user
);

mysqli_stmt_execute($stmtB);

$totalBerat =
mysqli_fetch_assoc(
mysqli_stmt_get_result($stmtB)
)['total'];

?>

<!DOCTYPE html>

<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>EcoCycle - Profil Petugas</title>

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
<li><a href="petugas_dashboard.php"> 🏠 Home </a></li>
<li><a href="petugas_laporan.php"> 📋 Daftar Laporan </a></li>
<li><a href="petugas_rute.php"> 🗺 Rute Pengangkutan </a></li>
<li><a href="petugas_jadwal.php"> 🗓 Atur Jadwal</a></li>
<li><a href="profilPetugas.php" class="active-link">👤 Profil</a></li>
<li><a href="logout.php" onclick="return confirmLogout()"> 🚪 Logout</a></li>
</ul>

<section class="dashboard-container">
<div class="profile-modern-card">
    <div class="profile-header-modern">
        <div class="profile-avatar-modern">
            <?php if(!empty($user['foto'])){ ?>
                <img
                src="<?php echo htmlspecialchars($user['foto']); ?>"
                alt="Foto Profil">
            <?php } else { ?>
                👤
            <?php } ?>
        </div>

        <h2><?php echo htmlspecialchars($user['nama']); ?></h2>
        <span class="role-badge"> Petugas </span>
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
    <h2>📊 Statistik Petugas</h2>
    <div class="status-item">
        <div class="info"><span>Total Pengangkutan</span></div>
        <span class="status status-selesai"><?php echo (int)$totalPengangkutan; ?></span>
    </div>

    <div class="status-item">
        <div class="info"><span>Total Sampah Diangkut</span></div>
        <span class="status status-diproses"><?php echo round($totalBerat,1); ?> Kg</span>
    </div>
</div>

<div class="content-card">
    <h2>⚙ Pengaturan Akun</h2>
    <a href="edit_profil.php">
        <button class="submit-btn-sm"> Edit Profil </button>
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

<script>
function confirmLogout() { return confirm("Apakah Anda yakin ingin logout?");}
let menuToggle = document.getElementById("menuToggle");
let navMenu = document.getElementById("navMenu");
menuToggle.onclick = function () { navMenu.classList.toggle("show");};
</script>

</body>
</html>