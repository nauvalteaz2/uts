<?php
session_start();
require 'koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: LoginUser.php");
    exit;
}

// Inisialisasi variabel
$nama = "";
$alamat = "";
$telepon = "";
$edit_id = null;

// Proses Hapus data pasien
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $query = "DELETE FROM pasien WHERE id = $id";
    mysqli_query($conn, $query);
    header("Location: pasien.php"); // Refresh halaman setelah hapus
    exit;
}

// Proses Edit data pasien
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $query = "SELECT * FROM pasien WHERE id = $edit_id";
    $result = mysqli_query($conn, $query);
    $pasien = mysqli_fetch_assoc($result);
    $nama = $pasien['nama'];
    $alamat = $pasien['alamat'];
    $telepon = $pasien['telepon'];
}

// Proses Simpan atau Update data pasien
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $telepon = $_POST['telepon'];

    if ($edit_id) {
        // Update data jika edit_id ada
        $query = "UPDATE pasien SET nama = '$nama', alamat = '$alamat', telepon = '$telepon' WHERE id = $edit_id";
    } else {
        // Simpan data baru jika edit_id tidak ada
        $query = "INSERT INTO pasien (nama, alamat, telepon) VALUES ('$nama', '$alamat', '$telepon')";
    }

    mysqli_query($conn, $query);
    header("Location: Pasien.php"); // Refresh halaman setelah simpan atau update
    exit;
}

// Mengambil data pasien dari database
$query = "SELECT * FROM pasien";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Data Pasien</title>
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
                <li class="nav-item">
                    <a class="nav-link" href="Logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <h2>Pasien</h2>
        <form method="post" action="Pasien.php<?= $edit_id ? '?edit=' . $edit_id : '' ?>">
            <div class="form-group">
                <label>Nama</label>
                <input type="text" class="form-control" name="nama" value="<?= htmlspecialchars($nama) ?>" required>
            </div>
            <div class="form-group">
                <label>Alamat</label>
                <input type="text" class="form-control" name="alamat" value="<?= htmlspecialchars($alamat) ?>" required>
            </div>
            <div class="form-group">
                <label>Telepon</label>
                <input type="text" class="form-control" name="telepon" value="<?= htmlspecialchars($telepon) ?>" required>
            </div>
            <button type="submit" class="btn btn-primary"><?= $edit_id ? 'Update' : 'Simpan' ?></button>
        </form>

        <h3 class="mt-5">Data Pasien</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>Telepon</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($row['nama']); ?></td>
                        <td><?= htmlspecialchars($row['alamat']); ?></td>
                        <td><?= htmlspecialchars($row['telepon']); ?></td>
                        <td>
                            <a href="Pasien.php?edit=<?= $row['id']; ?>" class="btn btn-success btn-sm">Ubah</a>
                            <a href="Pasien.php?hapus=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?');">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>