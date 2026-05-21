<?php
$conn = mysqli_connect("localhost", "root", "", "ecocycle");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>