<?php
include '../config/koneksi.php';

if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password'])) {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'] ?? 'masyarakat';

    $allowedRoles = ['masyarakat', 'petugas', 'pengepul'];
    if (!in_array($role, $allowedRoles)) {
        $role = 'masyarakat';
    }

    if ($name === '' || $email === '' || $password === '') {
        echo "Semua field harus diisi";
        exit;
    }

    // Cek email sudah terdaftar
    $check = mysqli_prepare($conn, "SELECT id_user FROM users WHERE email = ?");
    mysqli_stmt_bind_param($check, "s", $email);
    mysqli_stmt_execute($check);
    mysqli_stmt_store_result($check);

    if (mysqli_stmt_num_rows($check) > 0) {
        echo "Email sudah terdaftar";
        exit;
    }

    $hashPassword = password_hash($password, PASSWORD_DEFAULT);
    $point = 0;

    $stmt = mysqli_prepare($conn, "INSERT INTO users (nama, email, password, role, point) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssssi", $name, $email, $hashPassword, $role, $point);

    if (mysqli_stmt_execute($stmt)) {
    echo "Daftar berhasil";
    } else {
        echo "ERROR: " . mysqli_stmt_error($stmt);
    }

} else {
    echo "Akses tidak valid";
}
?>
