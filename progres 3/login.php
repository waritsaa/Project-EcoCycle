<?php
session_start();
include 'koneksi.php';

$email = $_POST['email'];
$password = $_POST['password'];

$data = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
$user = mysqli_fetch_assoc($data);

if($user && password_verify($password, $user['password'])){
    $_SESSION['user'] = $user;
    echo "sukses";
}else{
    echo "gagal";
}
?>