<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: index2.html");
    exit;
}

include 'koneksi.php';

$id_user = $_SESSION['user']['id_user'];

$stmt = mysqli_prepare($conn,
"SELECT * FROM users WHERE id_user=?");

mysqli_stmt_bind_param($stmt,"i",$id_user);
mysqli_stmt_execute($stmt);

$user =
mysqli_fetch_assoc(
mysqli_stmt_get_result($stmt)
);

$message = '';

if($_SERVER['REQUEST_METHOD']=='POST'){

    $nama = trim($_POST['nama']);
    $no_hp = trim($_POST['no_hp']);
    $alamat = trim($_POST['alamat']);

    $fotoPath = $user['foto'];

    if(
        isset($_FILES['foto']) &&
        $_FILES['foto']['error']==0
    ){

        $allowed =
        ['jpg','jpeg','png','webp'];

        $ext =
        strtolower(
        pathinfo(
        $_FILES['foto']['name'],
        PATHINFO_EXTENSION
        ));

        if(in_array($ext,$allowed)){

            $folder =
            "uploads/profil/";

            if(!is_dir($folder)){
                mkdir($folder,0755,true);
            }

            $namaFile =
            "profil_" .
            $id_user .
            "_" .
            time() .
            "." .
            $ext;

            $target =
            $folder .
            $namaFile;

            if(
                move_uploaded_file(
                $_FILES['foto']['tmp_name'],
                $target
                )
            ){
                $fotoPath = $target;
            }
        }
    }

    $update =
    mysqli_prepare(
    $conn,
    "UPDATE users
    SET nama=?,
        no_hp=?,
        alamat=?,
        foto=?
    WHERE id_user=?"
    );

    mysqli_stmt_bind_param(
    $update,
    "ssssi",
    $nama,
    $no_hp,
    $alamat,
    $fotoPath,
    $id_user
    );

    if(mysqli_stmt_execute($update)){

        $_SESSION['user']['nama']=$nama;

        $message =
        "Profil berhasil diperbarui";

    }else{

        $message =
        "Gagal memperbarui profil";
    }
}
?>

$profilPage = 'profil.php';

if($user['role'] == 'admin'){
    $profilPage = 'profilAdmin.php';
}
elseif($user['role'] == 'petugas'){
    $profilPage = 'profilPetugas.php';
}
elseif($user['role'] == 'pengepul'){
    $profilPage = 'profilMitra.php';
}

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Profil</title>
<link rel="stylesheet" href="style2.css">
</head>

<body class="dashboard-body">

<section class="dashboard-container">

<div class="content-card">

<h2>✏ Edit Profil</h2>

<?php if($message){ ?>
<div class="alert alert-success">
<?= $message ?>
</div>
<?php } ?>

<form
method="POST"
enctype="multipart/form-data"
class="edit-profile-form"
>

<label>Foto Profil</label>
<input type="file" name="foto">

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
value="<?= htmlspecialchars($user['no_hp']) ?>">

<label>Alamat</label>
<textarea
name="alamat"
rows="4"><?= htmlspecialchars($user['alamat']) ?></textarea>

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