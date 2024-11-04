<?php
session_start();
require 'koneksi.php';
if (!isset($_SESSION['username'])) {
    header("Location: LoginUser.php");
    exit();
}

$pasien_id = $dokter_id = $tanggal_periksa = $catatan = $obat = "";
$edit_id = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pasien_id = $_POST['pasien_id'];
    $dokter_id = $_POST['dokter_id'];
    $tanggal_periksa = $_POST['tanggal_periksa'];
    $catatan = $_POST['catatan'];
    $obat = $_POST['obat'];

    if (isset($_POST['edit_id']) && !empty($_POST['edit_id'])) {
        // Update the existing record
        $edit_id = $_POST['edit_id'];
        $query = "UPDATE periksa SET pasien_id = '$pasien_id', dokter_id = '$dokter_id', tanggal_periksa = '$tanggal_periksa', catatan = '$catatan', obat = '$obat' WHERE id = $edit_id";
    } else {
        // Insert a new record
        $query = "INSERT INTO periksa (pasien_id, dokter_id, tanggal_periksa, catatan, obat) VALUES ('$pasien_id', '$dokter_id', '$tanggal_periksa', '$catatan', '$obat')";
    }

    mysqli_query($conn, $query);
    header("Location: Periksa.php");
    exit;
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $query = "DELETE FROM periksa WHERE id = $delete_id";
    mysqli_query($conn, $query);
    header("Location: Periksa.php");
    exit;
}

if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $query = "SELECT * FROM periksa WHERE id = $edit_id";
    $result = mysqli_query($conn, $query);
    $periksa = mysqli_fetch_assoc($result);

    $pasien_id = $periksa['pasien_id'];
    $dokter_id = $periksa['dokter_id'];
    $tanggal_periksa = $periksa['tanggal_periksa'];
    $catatan = $periksa['catatan'];
    $obat = $periksa['obat'];
}
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

    <div class="container mt-4">
        <h2>Form Pemeriksaan</h2>
        <form method="POST">
            <input type="hidden" name="edit_id" value="<?= $edit_id ?? '' ?>">

            <div class="form-group">
                <label>Pasien</label>
                <select name="pasien_id" class="form-control">
                    <?php
                    $pasien_query = "SELECT * FROM pasien";
                    $pasien_result = mysqli_query($conn, $pasien_query);
                    while ($row = mysqli_fetch_assoc($pasien_result)) {
                        $selected = $pasien_id == $row['id'] ? 'selected' : '';
                        echo "<option value='{$row['id']}' $selected>{$row['nama']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label>Dokter</label>
                <select name="dokter_id" class="form-control">
                    <?php
                    $dokter_query = "SELECT * FROM dokter";
                    $dokter_result = mysqli_query($conn, $dokter_query);
                    while ($row = mysqli_fetch_assoc($dokter_result)) {
                        $selected = $dokter_id == $row['id'] ? 'selected' : '';
                        echo "<option value='{$row['id']}' $selected>{$row['nama']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label>Tanggal Periksa</label>
                <input type="date" name="tanggal_periksa" class="form-control" value="<?= $tanggal_periksa ?>">
            </div>

            <div class="form-group">
                <label>Catatan</label>
                <input type="text" name="catatan" class="form-control" value="<?= $catatan ?>">
            </div>

            <div class="form-group">
                <label>Obat</label>
                <input type="text" name="obat" class="form-control" value="<?= $obat ?>">
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>

        <h2 class="mt-4">Daftar Pemeriksaan</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pasien</th>
                    <th>Nama Dokter</th>
                    <th>Tanggal Periksa</th>
                    <th>Catatan</th>
                    <th>Obat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT periksa.id, pasien.nama AS nama_pasien, dokter.nama AS nama_dokter, periksa.tanggal_periksa, periksa.catatan, periksa.obat FROM periksa 
                          JOIN pasien ON periksa.pasien_id = pasien.id 
                          JOIN dokter ON periksa.dokter_id = dokter.id";
                $result = mysqli_query($conn, $query);
                $no = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td>{$no}</td>
                            <td>{$row['nama_pasien']}</td>
                            <td>{$row['nama_dokter']}</td>
                            <td>{$row['tanggal_periksa']}</td>
                            <td>{$row['catatan']}</td>
                            <td>{$row['obat']}</td>
                            <td>
                                <a href='Periksa.php?edit={$row['id']}' class='btn btn-success btn-sm'>Ubah</a>
                                <a href='Periksa.php?delete={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Apakah Anda yakin ingin menghapus data ini?\")'>Hapus</a>
                            </td>
                          </tr>";
                    $no++;
                }
                ?>
            </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>