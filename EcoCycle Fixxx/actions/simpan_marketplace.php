<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'masyarakat') {
    echo "Sesi habis atau akses tidak valid, silakan login kembali";
    exit;
}

$id_user = $_SESSION['user']['id_user'];
$jenis_sampah = $_POST['jenis_sampah'] ?? '';
$berat_kg = $_POST['berat_kg'] ?? '';
$harga = $_POST['harga'] ?? '';

if ($jenis_sampah === '' || $berat_kg === '' || $harga === '') {
    echo "Semua field wajib diisi";
    exit;
}

if (!is_numeric($berat_kg) || (float)$berat_kg <= 0) {
    echo "Berat tidak valid";
    exit;
}

if (!is_numeric($harga) || (float)$harga < 0) {
    echo "Harga tidak valid";
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

    // __DIR__ memastikan file selalu disimpan di uploads/marketplace/
    // di root project, terlepas dari folder mana file ini dieksekusi.
    $uploadDir = __DIR__ . '/../uploads/marketplace/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $fileName = 'mkt_' . $id_user . '_' . time() . '.' . $ext;
    $target = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['foto']['tmp_name'], $target)) {
        // Path yang disimpan ke database tetap relatif dari root project,
        // supaya bisa ditampilkan dari halaman manapun lewat asset_url().
        $fotoPath = 'uploads/marketplace/' . $fileName;
    } else {
        echo "Gagal mengunggah foto";
        exit;
    }
} else {
    echo "Foto wajib diunggah";
    exit;
}

$stmt = mysqli_prepare($conn,
    "INSERT INTO marketplace (id_user, jenis_sampah, berat_kg, harga, foto, status) VALUES (?, ?, ?, ?, ?, 'tersedia')"
);
mysqli_stmt_bind_param($stmt, "isdds", $id_user, $jenis_sampah, $berat_kg, $harga, $fotoPath);

if (mysqli_stmt_execute($stmt)) {
    echo "sukses";
} else {
    echo "Gagal memasang listing, coba lagi";
}
?>
