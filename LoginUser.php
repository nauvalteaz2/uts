<?php
require 'koneksi.php';
session_start();
$isLoggedIn = isset($_SESSION['username']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM user WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            header('Location: index.php');
            exit;
        } else {
            $error = "Password salah.";
        }
    } else {
        $error = "Username tidak ditemukan.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
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
        <h2>Login</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <form action="LoginUser.php" method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
            <p>Belum punya akun? <a href="RegistrasiUser.php">Register</a></p>
        </form>
    </div>
</body>

</html>