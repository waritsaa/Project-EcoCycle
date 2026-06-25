<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../index.html");
    exit;
}

include '../config/koneksi.php';

$id_user = $_SESSION['user']['id_user'];

$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id_user=?");
mysqli_stmt_bind_param($stmt, "i", $id_user);
mysqli_stmt_execute($stmt);
$user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = trim($_POST['nama']);
    $no_hp = trim($_POST['no_hp']);
    $alamat = trim($_POST['alamat']);
    $fotoPath = $user['foto'];

    if (isset($_FILES['foto']) && $_FILES['foto']['size'] > 0) {
        $uploadError = $_FILES['foto']['error'];

        if ($uploadError !== UPLOAD_ERR_OK) {
            $errorMessages = [
                UPLOAD_ERR_INI_SIZE   => "Foto terlalu besar (melebihi batas php.ini). Gunakan foto di bawah 2MB.",
                UPLOAD_ERR_FORM_SIZE  => "Foto terlalu besar (melebihi batas form).",
                UPLOAD_ERR_PARTIAL    => "Upload tidak selesai, coba lagi.",
                UPLOAD_ERR_NO_TMP_DIR => "Folder temporary tidak ditemukan di server.",
                UPLOAD_ERR_CANT_WRITE => "Gagal menulis file ke disk.",
            ];
            $message = $errorMessages[$uploadError] ?? "Error upload (kode: $uploadError)";
            $messageType = "error";
        } else {
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
            $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));

            if (in_array($ext, $allowed)) { $folder = __DIR__ . "/../uploads/profil/";
                if (!is_dir($folder)) { mkdir($folder, 0755, true);}

                $namaFile = "profil_" . $id_user . "_" . time() . "." . $ext;
                $target = $folder . $namaFile;
                $fotoPathPublic = "uploads/profil/" . $namaFile;

                if (move_uploaded_file($_FILES['foto']['tmp_name'], $target)) {
                    $fotoPath = $fotoPathPublic;
                } else {
                    $message = "Gagal menyimpan foto. Pastikan folder uploads/profil/ ada dan bisa ditulis.";
                    $messageType = "error";
                }
            } else {
                $message = "Format tidak didukung (gunakan jpg, jpeg, png, atau webp)";
                $messageType = "error";
            }
        }
    }

    if ($messageType !== 'error') {
        $update = mysqli_prepare($conn,"UPDATE users SET nama=?, no_hp=?, alamat=?, foto=? WHERE id_user=?");
        mysqli_stmt_bind_param($update, "ssssi", $nama, $no_hp, $alamat, $fotoPath, $id_user);

        if (mysqli_stmt_execute($update)) {
            $user['nama'] = $nama;
            $user['no_hp'] = $no_hp;
            $user['alamat'] = $alamat;
            $user['foto'] = $fotoPath;
            $_SESSION['user'] = $user;

            $message = "Profil berhasil diperbarui";
            $messageType = "success";
        } else {
            $message = "Gagal memperbarui profil";
            $messageType = "error";
        }
    }
}

$profilPage = '../pages/masyarakat/profilMasyarakat.php';
if ($user['role'] == 'admin') {
    $profilPage = '../pages/admin/profilAdmin.php';
} elseif ($user['role'] == 'petugas') {
    $profilPage = '../pages/petugas/profilPetugas.php';
} elseif ($user['role'] == 'pengepul') {
    $profilPage = '../pages/pengepul/profilMitra.php';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Profil</title>
<link rel="stylesheet" href="../assets/css/style2.css">
</head>

<body class="dashboard-body">
    <section class="dashboard-container">
        <div class="content-card">
            <h2>✏ Edit Profil</h2>

<?php if ($message) { ?>
<div class="alert <?php echo $messageType === 'success' ? 'alert-success' : 'alert-error'; ?>">
<?= htmlspecialchars($message) ?>
</div>
<?php } ?>

<form
method="POST"
enctype="multipart/form-data"
class="edit-profile-form"
>

<label>Foto Profil</label>
<?php if (!empty($user['foto'])) { ?>
  <img src="<?php echo htmlspecialchars(asset_url($user['foto'])); ?>" alt="Foto Profil" style="width:80px;height:80px;border-radius:50%;object-fit:cover;margin-bottom:8px;">
<?php } ?>
<input type="file" name="foto" accept=".jpg,.jpeg,.png,.webp">

<label>Nama Lengkap</label>
<input
type="text"
name="nama"
value="<?= htmlspecialchars($user['nama']) ?>"
required>

<label>No HP</label>
<input
type="text"
name="no_hp"
value="<?= htmlspecialchars($user['no_hp'] ?? '') ?>">

<label>Alamat</label>
<textarea
name="alamat"
rows="4"><?= htmlspecialchars($user['alamat'] ?? '') ?></textarea>

<button
type="submit"
class="submit-btn">
💾 Simpan Perubahan
</button>

<a href="<?php echo $profilPage; ?>"
class="btn-primary"
style="margin-top:10px;">
← Kembali
</a>

</form>

</div>

</section>

</body>
</html>
