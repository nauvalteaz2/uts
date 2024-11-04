<?php
$host = 'localhost';
$user = 'root';
$password = '';
$db = 'poliklinik';

$conn = mysqli_connect($host, $user, $password, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
