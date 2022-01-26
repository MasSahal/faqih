<?php

//jalankan session
session_start();

//periksa apakah user sudah login ditandai dengan adanya session nama -> $_SESSION['nama']
// jika tid_matkulak ada maka akan dikembalikan ke halaman login
if (!isset($_SESSION['nim'])) {
    header("location:./login.php");
};

//buat pesan
if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
}

//Panggil file koneksi ke database
include("./connection.php");

//cek apakah form untuk pencarian telah di submit
if (isset($_GET["cari"])) {

    //ambil data input dari form
    $cari = htmlentities(strip_tags(trim($_GET['cari'])));

    //filter untuk mencegah sql injection
    $cari = mysqli_real_escape_string($link, $cari);

    //buat query pencarian
    $query  = "SELECT * FROM matkul_saya JOIN mahasiswa ON matkul_saya.nim=mahasiswa.nim JOIN mata_kuliah ON matkul_saya.id_matkul=mata_kuliah.id_matkul WHERE ";
    $query .= "matkul LIKE '%$cari%' OR ";
    $query .= "hari LIKE '%$cari%' OR ";
    $query .= "jam_masuk LIKE '%$cari%' OR ";
    $query .= "jam_keluar LIKE '%$cari%' ";

    //ambil nim dari session ssaat login
    $nim = $_SESSION['nim'];

    if ($nim === 0) {
        $query .= "OR mahasiswa.nim LIKE '%$cari%' ";
        $query .= "OR mahasiswa.nama LIKE '%$cari%' ";
    } else {
        $query .= "AND nim='$nim'";
    }
    //buat pesan
    $pesan = "Menampilkan Hasil Pencarian <b>$cari</b>";
    #
} else {

    //ambil nim dari session ssaat login
    $nim = $_SESSION['nim'];
    //cek apakah admin yg login, maka ambil semua nim
    if ($nim === 0) {
        //mengambil seluruh data di table mahasiswa
        $pesan = "Menampilkan Seluruh Data Mata Kuliah Dan Mahasiswa";
        $query = "SELECT * FROM matkul_saya JOIN mahasiswa ON matkul_saya.nim=mahasiswa.nim JOIN mata_kuliah ON matkul_saya.id_matkul=mata_kuliah.id_matkul ORDER BY matkul ASC;";
    } else {

        //mengambil seluruh data di table mahasiswa
        $query = "SELECT * FROM matkul_saya JOIN mahasiswa ON matkul_saya.nim=mahasiswa.nim JOIN mata_kuliah ON matkul_saya.id_matkul=mata_kuliah.id_matkul WHERE mahasiswa.nim='$nim' ORDER BY matkul ASC";
        $pesan = "Menampilkan Seluruh Data Mata kuliah Saya";
    }
    //buat pesan
}
?>

<!DOCTYPE html>
<html lang="id_matkul">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="wid_matkulth=device-wid_matkulth, initial-scale=1.0">
    <link rel="shortcut icon" href="./favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="bootstrap.css">
    <title>Kampusku - Data Mata Kuliah</title>
</head>

<body class="bg-dark">
    <div class="container">
        <nav class="navbar navbar-expand navbar-light bg-light p-3">
            <div class="nav navbar-nav">
                <a class="nav-item nav-link" href="./tampil_mahasiswa.php">Mahasiswa</a>
                <a class="nav-item nav-link" href="./tampil_mata_kuliah.php">Mata Kuliah</a>
                <a class="nav-item nav-link active" href="./tampil_mata_kuliah_saya.php">Matkul Saya</a>
                <a class="nav-item nav-link" href="./logout.php" onclick="return confirm('Yakin ingin keluar?')">Log-Out</a>
                <a class="nav-item nav-link float-right" href="#">Halo, <?= $_SESSION['nama']; ?></a>

            </div>
        </nav>
        <div class="card">
            <div class="card-body">
                <div class="row mt-3 mb-4">
                    <div class="col-12 text-center">
                        <h4><?= $pesan; ?></h4>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-8 col-sm-12">
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <a href="./tambah_mata_kuliah_saya.php" class="btn btn-success btn-sm">Tambah</a>
                            <a href="./tampil_mata_kuliah_saya.php" class="btn btn-warning btn-sm">Refresh</a>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <form method="get">
                            <div class="form-group">
                                <input type="search" name="cari" id_matkul="cari" class="form-control form-control-sm border-1" placeholder="Cari data ...">
                            </div>
                        </form>
                    </div>
                </div>

                <?php if (isset($msg)) { ?>
                    <div class="alert alert-success p-2 mb-2" role="alert">
                        <?= $msg; ?>
                    </div>
                <?php }; ?>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm">
                        <thead class="bg-dark text-white">
                            <tr>
                                <th>#</th>
                                <?php if ($_SESSION['is_login'] == "admin") { ?>
                                    <th>NIM</th>
                                    <th>Nama Mahasiswa</th>
                                    <th>Jurusan</th>
                                <?php }; ?>
                                <th>Mata Kuliah</th>
                                <th>Hari</th>
                                <th>Jam</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <?php
                        $no = 0;

                        //eksekusi query
                        $result = mysqli_query($link, $query);
                        foreach ($result as $data) { ?>
                            <tr>
                                <td><?= $no += 1; ?></td>
                                <?php if ($_SESSION['is_login'] == "admin") { ?>
                                    <td><?= $data['nim']; ?></td>
                                    <td><?= $data['nama']; ?></td>
                                    <td><?= $data['jurusan']; ?></td>
                                <?php } ?>
                                <td><?= $data['matkul']; ?></td>
                                <td><?= $data['hari']; ?></td>
                                <td><?= $data['jam_masuk'] . " - " .  $data['jam_keluar'] ?></td>
                                <td>
                                    <a class="btn btn-warning btn-xs" href="./edit_mata_kuliah_saya.php?id_mks=<?= $data['id_mks']; ?>">Edit</a>
                                    <a class="btn btn-danger btn-xs" onclick="return confirm('Yakin ingin menghapus mata_kuliah <?= $data['matkul']; ?>?')" href="./hapus_mata_kuliah_saya.php?id_mks=<?= $data['id_mks']; ?>">Hapus</a>
                                </td>
                            </tr>
                        <?php  }

                        //bebaskan memory
                        mysqli_free_result($result);

                        //tutup koneksi
                        mysqli_close($link);
                        ?>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <div class="text-center">
                    Copyright &copy; <?= date("Y"); ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>