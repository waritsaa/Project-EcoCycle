<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user'])) {
    echo "Sesi habis, silakan login kembali";
    exit;
}

$id_user = $_SESSION['user']['id_user'];
$lokasi = $_POST['lokasi'] ?? '';
$jenis_sampah = $_POST['jenis_sampah'] ?? '';
$deskripsi = $_POST['deskripsi'] ?? '';

if ($lokasi === '' || $jenis_sampah === '' || $deskripsi === '') {
    echo "Semua field wajib diisi";
    exit;
}

$fotoPath = '';

if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];
    $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        echo "Format foto tidak didukung";
        exit;
    }

    $uploadDir = 'uploads/laporan/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $fileName = 'lap_' . $id_user . '_' . time() . '.' . $ext;
    $target = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['foto']['tmp_name'], $target)) {
        $fotoPath = $target;
    } else {
        echo "Gagal mengunggah foto";
        exit;
    }
} else {
    echo "Foto wajib diunggah";
    exit;
}

mysqli_begin_transaction($conn);

try {
    // Simpan laporan
    $stmt = mysqli_prepare($conn, "INSERT INTO laporan (id_user, foto, lokasi, jenis_sampah, deskripsi, status) VALUES (?, ?, ?, ?, ?, 'pending')");
    mysqli_stmt_bind_param($stmt, "issss", $id_user, $fotoPath, $lokasi, $jenis_sampah, $deskripsi);
    mysqli_stmt_execute($stmt);

    // Tambah poin reward atas partisipasi (sesuai activity diagram)
    $poin = 10;
    $stmtPoin = mysqli_prepare($conn, "UPDATE users SET point = point + ? WHERE id_user = ?");
    mysqli_stmt_bind_param($stmtPoin, "ii", $poin, $id_user);
    mysqli_stmt_execute($stmtPoin);

    mysqli_commit($conn);

    // update session poin
    $_SESSION['user']['point'] += $poin;

    echo "sukses";
} catch (Exception $e) {
    mysqli_rollback($conn);
    echo "Terjadi kesalahan saat menyimpan laporan";
}
?>
