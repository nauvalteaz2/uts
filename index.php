<?php
session_start();
require 'koneksi.php';

// Cek apakah user sudah login
$isLoggedIn = isset($_SESSION['username']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sistem Informasi Poliklinik</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="index.php">Sistem Informasi Poliklinik</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <?php if ($isLoggedIn): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dataMasterDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Data Master
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dataMasterDropdown">
                            <a class="dropdown-item" href="Dokter.php">Data Dokter</a>
                            <a class="dropdown-item" href="Pasien.php">Data Pasien</a>
                        </div>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="Periksa.php">Periksa</a></li>
                <?php endif; ?>
                <li class="nav-item">
                    <?php if ($isLoggedIn): ?>
                        <a class="nav-link" href="Logout.php">Logout</a>
                    <?php else: ?>
                <li><a class="nav-link" href="RegistrasiUser.php">Register</a></li>
                <li><a class="nav-link" href="LoginUser.php">Login</a></li>
            <?php endif; ?>
            </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <h1>Selamat Datang di Sistem Informasi Poliklinik</h1>
        <p>Silahkan login untuk mengakses fitur lengkap.</p>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>