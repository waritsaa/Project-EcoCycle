<?php
include 'koneksi.php';

if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password'])){

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $hashPassword = password_hash($password, PASSWORD_DEFAULT);
    $point = 0;

    $query = "INSERT INTO users (name, email, password, point) VALUES ('$name', '$email', '$hashPassword', '$point')";

    if(mysqli_query($conn, $query)){
        echo "Daftar berhasil";
    } else {
        echo mysqli_error($conn);
    }

} else {
    echo "Akses tidak valid";
}
?>