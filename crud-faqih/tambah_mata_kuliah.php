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
    $matkul         = htmlentities(strip_tags(trim($_POST['matkul'])));
    $hari           = htmlentities(strip_tags(trim($_POST['hari'])));
    $jam_masuk      = htmlentities(strip_tags(trim($_POST['jam_masuk'])));
    $jam_keluar     = htmlentities(strip_tags(trim($_POST['jam_keluar'])));

    // siapkan variabel untuk menampung pesan error
    $pesan_error = "";

    //cek apakah matkul telah di isi apa tidak
    if (empty($matkul)) {
        #
        $pesan_error .= "Mata Kuliah harus diisi! <br>";
    }

    //cek apakah hari telah di isi apa tidak
    if (empty($hari)) {
        #
        $pesan_error .= "Hari harus diisi! <br>";
    }

    //cek apakah jam_masuk telah di isi apa tidak
    if (empty($jam_masuk)) {
        #
        $pesan_error .= "Jam masuk harus diisi! <br>";
    }

    //cek apakah jam_keluar telah di isi apa tidak
    if (empty($jam_keluar)) {
        #
        $pesan_error .= "Jam keluar harus diisi! <br>";
    }


    //jika tidak ada pesan erro maka data akan di input ke database
    if ($pesan_error === "") {

        //filter semua data dengan mysqli real escape
        $matkul         = mysqli_real_escape_string($link, $matkul);
        $hari           = mysqli_real_escape_string($link, $hari);
        $jam_masuk      = mysqli_real_escape_string($link, $jam_masuk);
        $jam_keluar     = mysqli_real_escape_string($link, $jam_keluar);

        //buat query insert
        $query = "INSERT INTO mata_kuliah (matkul, hari, jam_masuk, jam_keluar) VALUES ('$matkul','$hari', '$jam_masuk','$jam_keluar')";

        //eksekusi data
        $result = mysqli_query($link, $query);

        //periksa data apakah sudah berhasil : true
        if ($result) {
            $pesan = "Data matkul $matkul telah berhasil di tambahkan!";

            //redirect ke halaman tampil matkul
            header("location:tampil_mata_kuliah.php?pesan=$pesan");
        } else {
            die("Data matkul $matkul tidak berhasil di tambahkan : err - " . mysqli_errno($link) . " - " . mysqli_error($link));
        }
    }
} else {

    //siapkan variabel sebagai default
    $pesan_error = "";
    $matkul = "";
    $hari = "";
    $jam_masuk = "";
    $jam_keluar = "";
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
                <a class="nav-item nav-link active" href="./tampil_mata_kuliah.php">Mata Kuliah</a>
                <a class="nav-item nav-link" href="./tampil_mata_kuliah_saya.php">Matkul Saya</a>
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
                        <form action="./tambah_mata_kuliah.php" method="post">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="matkul">Nama Mata Kuliah</label>
                                        <input type="text" name="matkul" id="matkul" class="form-control form-control-sm border-1 " placeholder="Masukan matkul..." value="<?= $matkul; ?>">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="hari">Hari</label>
                                        <select class="form-control form-control-sm border-1 " name="hari" id="hari" required>
                                            <option hidden disabled value selected <?= ($hari === "") ? "selected" : ""; ?>>- Pilih Hari -</option>
                                            <?php $days = [
                                                'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'
                                            ];
                                            foreach ($days as $d) {

                                            ?>
                                                <option value="<?= $d; ?>" <?= ($hari === $d) ? "selected" : ""; ?>><?= $d; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="jam_masuk">Jam Masuk</label>
                                        <input type="time" name="jam_masuk" id="jam_masuk" class="form-control form-control-sm border-1 " placeholder="Masukan jam masuk..." value="<?= $jam_masuk; ?>">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="jam_keluar">Jam Keluar</label>
                                        <input type="time" name="jam_keluar" id="jam_keluar" class="form-control form-control-sm border-1 " placeholder="Masukan jam_keluar..." value="<?= $jam_keluar; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-end">
                                <div class="col">
                                    <button type="submit" name="submit" class="btn btn-primary px-3 float-end">Tambahkan</button>
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