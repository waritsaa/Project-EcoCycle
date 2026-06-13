<?php
session_start();
include 'koneksi.php';

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$role = $_POST['role'] ?? 'masyarakat';

$allowedRoles = ['masyarakat', 'petugas', 'admin', 'pengepul'];
if (!in_array($role, $allowedRoles)) {
    $role = 'masyarakat';
}

$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email = ?");
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if ($user && password_verify($password, $user['password'])) {

    if ($user['role'] !== $role) {
        echo "role_mismatch";
        exit;
    }

    $_SESSION['user'] = $user;

    $targetMap = [
        'masyarakat' => 'dashboard2.php',
        'petugas'    => 'petugas_dashboard.php',
        'admin'      => 'admin_dashboard.php',
        'pengepul'   => 'pengepul_dashboard.php',
    ];

    $target = $targetMap[$user['role']] ?? 'dashboard2.php';

    echo "sukses|" . $target;
} else {
    echo "gagal";
}
?>
