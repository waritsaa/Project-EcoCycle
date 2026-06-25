<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../../index.html");
    exit;
}
include '../../config/koneksi.php';

$id_user = $_SESSION['user']['id_user'];
$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id_user = ?");
mysqli_stmt_bind_param($stmt, "i", $id_user);
mysqli_stmt_execute($stmt);
$user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
$_SESSION['user'] = $user;

$message = '';
$messageType = '';

// Hapus user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aksi']) && $_POST['aksi'] === 'hapus') {
    $target_id = (int)$_POST['id_user'];
    if ($target_id === (int)$id_user) {
        $message = "Tidak dapat menghapus akun Anda sendiri.";
        $messageType = "error";
    } else {
        $stmt_del = mysqli_prepare($conn, "DELETE FROM users WHERE id_user = ?");
        mysqli_stmt_bind_param($stmt_del, "i", $target_id);
        if (mysqli_stmt_execute($stmt_del)) {
            $message = "User berhasil dihapus.";
            $messageType = "success";
        } else {
            $message = "Gagal menghapus user.";
            $messageType = "error";
        }
    }
}

// Filter
$filterRole = isset($_GET['role']) ? $_GET['role'] : '';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$where = "WHERE 1=1";
$params = [];
$types = "";

if ($filterRole !== '') {
    $where .= " AND role = ?";
    $params[] = $filterRole;
    $types .= "s";
}
if ($search !== '') {
    $where .= " AND (nama LIKE ? OR email LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $types .= "ss";
}

$query = "SELECT * FROM users $where ORDER BY created_at DESC";
$stmt_users = mysqli_prepare($conn, $query);
if ($types) {
    mysqli_stmt_bind_param($stmt_users, $types, ...$params);
}
mysqli_stmt_execute($stmt_users);
$userList = mysqli_stmt_get_result($stmt_users);

// Hitung total per role
$totalAll      = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM users"))['t'];
$totalAdmin    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM users WHERE role='admin'"))['t'];
$totalMasyarakat = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM users WHERE role='masyarakat'"))['t'];
$totalPetugas  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM users WHERE role='petugas'"))['t'];
$totalPengepul = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM users WHERE role='pengepul'"))['t'];

function roleBadge($role) {
    $map = [
        'admin'      => ['Admin', '#e74c3c'],
        'masyarakat' => ['Masyarakat', '#27ae60'],
        'petugas'    => ['Petugas', '#2980b9'],
        'pengepul'   => ['Pengepul', '#8e44ad'],
    ];
    $r = $map[$role] ?? [ucfirst($role), '#7f8c8d'];
    return "<span style=\"background:{$r[1]};color:#fff;padding:3px 10px;border-radius:12px;font-size:0.78em;font-weight:600;\">{$r[0]}</span>";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EcoCycle - Manajemen User</title>
  <link rel="stylesheet" href="../../assets/css/style2.css">
  <style>
    .user-table-wrap { overflow-x: auto; margin-top: 16px; }
    .user-table { width: 100%; border-collapse: collapse; font-size: 0.92em; }
    .user-table th { background: var(--color-primary, #2e7d32); color: #fff; padding: 10px 12px; text-align: left; }
    .user-table td { padding: 10px 12px; border-bottom: 1px solid #e0e0e0; vertical-align: middle; }
    .user-table tr:hover td { background: #f1f8e9; }
    .user-avatar { width: 38px; height: 38px; border-radius: 50%; object-fit: cover; background: #c8e6c9; }
    .user-avatar-placeholder { width: 38px; height: 38px; border-radius: 50%; background: #c8e6c9; display:inline-flex; align-items:center; justify-content:center; font-size: 1.2em; }
    .filter-bar { display:flex; gap:10px; flex-wrap:wrap; align-items:center; margin-bottom:16px; }
    .filter-bar input[type=text] { padding: 8px 12px; border:1px solid #ccc; border-radius:8px; font-size:0.93em; flex:1; min-width:160px; }
    .filter-bar select { padding: 8px 12px; border:1px solid #ccc; border-radius:8px; font-size:0.93em; }
    .filter-bar button { padding: 8px 18px; background: var(--color-primary, #2e7d32); color:#fff; border:none; border-radius:8px; cursor:pointer; font-size:0.93em; }
    .filter-bar a.btn-reset { padding: 8px 14px; background:#eee; color:#555; border-radius:8px; text-decoration:none; font-size:0.93em; }
    .stat-role-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(120px,1fr)); gap:12px; margin-bottom:20px; }
    .stat-role-box { background:#fff; border-radius:12px; padding:14px; text-align:center; box-shadow:0 1px 6px rgba(0,0,0,.08); }
    .stat-role-box .num { font-size:1.7em; font-weight:700; color: var(--color-primary, #2e7d32); }
    .stat-role-box .label { font-size:0.8em; color:#666; margin-top:4px; }
    .btn-hapus { background:#e74c3c; color:#fff; border:none; padding:5px 12px; border-radius:7px; cursor:pointer; font-size:0.83em; }
    .btn-hapus:hover { background:#c0392b; }
    .empty-state { text-align:center; padding:30px; color:#888; }
    .point-badge { background:#fff3e0; color:#e65100; padding:2px 8px; border-radius:10px; font-size:0.82em; font-weight:600; }
  </style>
</head>
<body class="dashboard-body theme-admin">
  <nav class="navbar">
    <div class="left-navbar">
      <div class="menu-toggle" id="menuToggle">☰</div>
      <div class="logo">♻ EcoCycle</div>
    </div>
    <div class="navbar-right">
      <div class="nav-user-avatar"><?php if(!empty($user['foto'])){ ?><img src="<?php echo htmlspecialchars($user['foto']); ?>"><?php } else { ?>👤<?php } ?></div>
      <span class="nav-user-name"><?php echo htmlspecialchars($user['nama']); ?></span>
    </div>
  </nav>

  <ul class="nav-menu" id="navMenu">
    <li><a href="../../pages/admin/admin_dashboard.php">🏠 Home</a></li>
    <li><a href="../../pages/admin/admin_laporan.php">📋 Verifikasi Laporan</a></li>
    <li><a href="../../pages/admin/admin_users.php" class="active-link">👥 Manajemen User</a></li>
    <li><a href="../../pages/admin/profilAdmin.php">👤 Profil</a></li>
    <li><a href="../../actions/logout.php" onclick="return confirm('Apakah Anda yakin ingin keluar?')">🚪 Logout</a></li>
  </ul>
  <div class="nav-overlay" id="navOverlay"></div>

  <section class="dashboard-container">
    <div class="welcome-card">
      <h1>👥 Manajemen User</h1>
      <p>Kelola seluruh akun pengguna yang terdaftar di sistem EcoCycle.</p>
    </div>

    <!-- Statistik Role -->
    <div class="stat-role-grid">
      <div class="stat-role-box">
        <div class="num"><?php echo $totalAll; ?></div>
        <div class="label">Total User</div>
      </div>
      <div class="stat-role-box">
        <div class="num"><?php echo $totalMasyarakat; ?></div>
        <div class="label">Masyarakat</div>
      </div>
      <div class="stat-role-box">
        <div class="num"><?php echo $totalPetugas; ?></div>
        <div class="label">Petugas</div>
      </div>
      <div class="stat-role-box">
        <div class="num"><?php echo $totalPengepul; ?></div>
        <div class="label">Pengepul</div>
      </div>
      <div class="stat-role-box">
        <div class="num"><?php echo $totalAdmin; ?></div>
        <div class="label">Admin</div>
      </div>
    </div>

    <div class="content-card">
      <h2>📋 Daftar Semua User</h2>

      <?php if ($message) { ?>
        <div class="alert <?php echo $messageType === 'success' ? 'alert-success' : 'alert-error'; ?>"><?php echo htmlspecialchars($message); ?></div>
      <?php } ?>

      <!-- Filter & Search -->
      <form method="GET" class="filter-bar">
        <input type="text" name="search" placeholder="Cari nama atau email..." value="<?php echo htmlspecialchars($search); ?>">
        <select name="role">
          <option value="">Semua Role</option>
          <option value="admin"      <?php echo $filterRole === 'admin'      ? 'selected' : ''; ?>>Admin</option>
          <option value="masyarakat" <?php echo $filterRole === 'masyarakat' ? 'selected' : ''; ?>>Masyarakat</option>
          <option value="petugas"    <?php echo $filterRole === 'petugas'    ? 'selected' : ''; ?>>Petugas</option>
          <option value="pengepul"   <?php echo $filterRole === 'pengepul'   ? 'selected' : ''; ?>>Pengepul</option>
        </select>
        <button type="submit">🔍 Cari</button>
        <a href="admin_users.php" class="btn-reset">✕ Reset</a>
      </form>

      <?php if (mysqli_num_rows($userList) === 0) { ?>
        <div class="empty-state">
          <p>😔 Tidak ada user yang ditemukan.</p>
        </div>
      <?php } else { ?>
      <div class="user-table-wrap">
        <table class="user-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Foto</th>
              <th>Nama</th>
              <th>Email</th>
              <th>Role</th>
              <th>No. HP</th>
              <th>Alamat</th>
              <th>Point</th>
              <th>Terdaftar</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php $no = 1; while ($row = mysqli_fetch_assoc($userList)) { ?>
            <tr>
              <td><?php echo $no++; ?></td>
              <td>
                <?php if (!empty($row['foto'])) { ?>
                  <img src="../../<?php echo htmlspecialchars($row['foto']); ?>" class="user-avatar" alt="foto" onerror="this.style.display='none';this.nextElementSibling.style.display='inline-flex';">
                  <span class="user-avatar-placeholder" style="display:none;">👤</span>
                <?php } else { ?>
                  <span class="user-avatar-placeholder">👤</span>
                <?php } ?>
              </td>
              <td><strong><?php echo htmlspecialchars($row['nama']); ?></strong></td>
              <td><?php echo htmlspecialchars($row['email']); ?></td>
              <td><?php echo roleBadge($row['role']); ?></td>
              <td><?php echo $row['no_hp'] ? htmlspecialchars($row['no_hp']) : '<span style="color:#aaa">-</span>'; ?></td>
              <td><?php echo $row['alamat'] ? htmlspecialchars($row['alamat']) : '<span style="color:#aaa">-</span>'; ?></td>
              <td><span class="point-badge">⭐ <?php echo (int)$row['point']; ?></span></td>
              <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
              <td>
                <?php if ((int)$row['id_user'] !== (int)$id_user) { ?>
                <form method="POST" onsubmit="return confirm('Hapus user <?php echo htmlspecialchars($row['nama']); ?>? Data terkait juga akan terhapus.');">
                  <input type="hidden" name="aksi" value="hapus">
                  <input type="hidden" name="id_user" value="<?php echo $row['id_user']; ?>">
                  <button type="submit" class="btn-hapus">🗑 Hapus</button>
                </form>
                <?php } else { ?>
                  <span style="color:#aaa;font-size:0.82em;">Akun Anda</span>
                <?php } ?>
              </td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
      <?php } ?>
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
    navMenu.querySelectorAll("a").forEach(a => a.addEventListener("click", closeMenu));
  </script>
</body>
</html>
