<?php

//jalankan session
session_start();

//periksa apakah user sudah login ditandai dengan adanya session nama -> $_SESSION['nama']
// jika tidak ada maka akan dikembalikan ke halaman login
if (!isset($_SESSION['nim'])) {
    header("location:./login.php");
};

//buat pesan
if (isset($_GET['msg'])) {
    $pesan = $_GET['msg'];
}

//Panggil file koneksi ke database
include("./connection.php");

//periksa apakah form telah di submit
if (isset($_POST['submit'])) {

    //buat variabel kosong fakultas
    $fakultas = "";

    //ambil data dari form input
    $id_mks         = htmlentities(strip_tags(trim($_POST['id_mks'])));
    $matkul         = htmlentities(strip_tags(trim($_POST['matkul'])));
    $nim           = htmlentities(strip_tags(trim($_POST['nim'])));

    // siapkan variabel untuk menampung pesan error
    $pesan_error = "";

    //cek apakah matkul telah di isi apa tidak
    if (empty($nim)) {
        #
        $pesan_error .= "NIM harus diisi! <br>";
    }

    //cek apakah matkul telah di isi apa tidak
    if (empty($matkul)) {
        #
        $pesan_error .= "Mata Kuliah harus diisi! <br>";
    }



    //jika tidak ada pesan erro maka data akan di input ke database
    if ($pesan_error === "") {

        //filter semua data dengan mysqli real escape
        $id_mks         = mysqli_real_escape_string($link, $id_mks);
        $matkul         = mysqli_real_escape_string($link, $matkul);
        $nim           = mysqli_real_escape_string($link, $nim);

        //buat query insert
        $query = "UPDATE matkul_saya SET id_matkul='$matkul', nim='$nim' WHERE id_mks='$id_mks'";

        //eksekusi data
        $result = mysqli_query($link, $query);

        //periksa data apakah sudah berhasil : true
        if ($result) {
            $pesan = "Data jadwal matkul $matkul telah berhasil diperbarui!";

            //redirect ke halaman tampil matkul_saya
            header("location:tampil_mata_kuliah_saya.php?pesan=$pesan");
        } else {
            die("Data jadwal matkul $matkul tidak berhasil diperbarui : err - " . mysqli_errno($link) . " - " . mysqli_error($link));
        }
    }
} else {

    //ambil id_mks dari get di url
    $id_mks = htmlentities(strip_tags(trim($_GET['id_mks'])));

    //filter anti injeksi
    $id_mks = mysqli_real_escape_string($link, $id_mks);

    //ambil data
    $result = mysqli_query($link, "SELECT * FROM matkul_saya JOIN mahasiswa ON matkul_saya.nim=mahasiswa.nim WHERE id_mks='$id_mks'");

    $data = mysqli_fetch_assoc($result);
    //siapkan variabel sebagai default
    $pesan_error = "";
    $matkul = $data['id_matkul'];
    $nim = $data['nim'];
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="bootstrap.css">
    <title>Kampusku - Tambah Mata Kuliah</title>
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
        <div class="card mb-5">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">

                        <!-- jika ada pesan error -->
                        <?php if ($pesan_error !== "") { ?>
                            <div class="alert alert-danger" role="alert">
                                <?= $pesan_error; ?>
                            </div>
                        <?php } ?>

                        <h4 class="text-center mt-3 mb-4">Form Tambah</h4>
                        <form action="./edit_mata_kuliah_saya.php" method="post">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="nim">Mahasiswa</label>
                                        <?php if ($_SESSION['is_login'] == "admin") { ?>
                                            <select class="form-control" name="nim" id="nim">
                                                <?php
                                                $result = mysqli_query($link, "SELECT * FROM mahasiswa ORDER BY nama");

                                                foreach ($result as $r) { ?>
                                                    <option value="<?= $r['nim']; ?>" <?= ($r['nim'] === $nim) ? "selected" : ""; ?>><?= $r['nim'] . " - " . $r['nama']; ?></option>
                                                <?php } ?>
                                            </select>
                                        <?php } else { ?>
                                            <input type="text" class="form-control" value="<?= $data['nim'] . " - " . $data['nama']; ?>" readonly>
                                            <input type="hidden" name="nim" value="<?= $data['nim']; ?>">
                                        <?php } ?>
                                        <input type="hidden" name="id_mks" value="<?= $data['id_mks']; ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="matkul">Nama Mata Kuliah</label>
                                        <select class="form-control" name="matkul" id="matkul">
                                            <option hidden disabled value <?= ("" === "") ? "selected" : ""; ?>>- Pilih Mata Kuliah -</option>
                                            <?php
                                            $result = mysqli_query($link, "SELECT * FROM mata_kuliah ORDER BY matkul");

                                            foreach ($result as $r) { ?>
                                                <option value="<?= $r['id_matkul']; ?>" <?= ($r['id_matkul'] === $matkul) ? "selected" : ""; ?>><?= $r['id_matkul'] . " - " . $r['matkul']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-end">
                                <div class="col">
                                    <button type="submit" name="submit" class="btn btn-primary px-3 float-end">Simpan Perubahan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>