<?php

//jalankan session
session_start();

//periksa apakah user sudah login ditandai dengan adanya session nama -> $_SESSION['nama']
// jika tidak ada maka akan dikembalikan ke halaman login
if (!isset($_SESSION['nama'])) {
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
    $nim            = htmlentities(strip_tags(trim($_POST['nim'])));
    $nama           = htmlentities(strip_tags(trim($_POST['nama'])));
    $tempat_lahir   = htmlentities(strip_tags(trim($_POST['tempat_lahir'])));
    $tanggal_lahir  = htmlentities(strip_tags(trim($_POST['tanggal_lahir'])));
    $fakultas       = htmlentities(strip_tags(trim($_POST['fakultas'])));
    $jurusan          = htmlentities(strip_tags(trim($_POST['jurusan'])));
    $ipk            = htmlentities(strip_tags(trim($_POST['ipk'])));

    // siapkan variabel untuk menampung pesan error
    $pesan_error = "";

    //cek apakah nim telah di isi apa tidak
    if (empty($nim)) {
        #
        $pesan_error .= "NIM harus diisi! <br>";
    } elseif (!preg_match("/^[0-9]{8}$/", $nim)) {
        #
        $pesan_error .= "NIM harus berupa 8 digit angka! <br>";
    }

    //filter data
    $nim = mysqli_real_escape_string($link, $nim);
    $result = mysqli_query($link, "SELECT * FROM mahasiswa WHERE nim='$nim'");

    // die();
    //cek apakah ada data nim yang sama di database
    $data_mhs = mysqli_num_rows($result);
    if ($data_mhs >= 1) {
        $pesan_error .= "NIM yang sama sudah digunakan oleh mahasiswa lain! <br>";
    }

    //cek apakah nama telah di isi apa tidak
    if (empty($nama)) {
        #
        $pesan_error .= "Nama harus diisi! <br>";
    }

    //cek apakah tempat lahiir telah di isi apa tidak
    if (empty($tempat_lahir)) {
        #
        $pesan_error .= "Tempat lahir harus diisi! <br>";
    }

    //cek apakah tanggal lahir telah di isi apa tidak
    if (empty($tanggal_lahir)) {
        #
        $pesan_error .= "Tanggal lahir harus diisi! <br>";
    }

    //cek apakah jurusan telah di isi apa tidak
    if (empty($jurusan)) {
        #
        $pesan_error .= "Jurusan harus diisi! <br>";
    }

    //cek apakah jurusan telah di isi apa tidak
    if (empty($ipk)) {
        #
        $pesan_error .= "IPK harus diisi! <br>";
    }

    //cek apakah ipk berupa angka dan tidak boleh negatif
    if (!is_numeric($ipk) or $ipk <= 0) {
        $pesan_error .= "IPK harus bernilai angka dan tidak negatif! <br>";
    }

    // var_dump($_POST);
    // die();

    //jika tidak ada pesan erro maka data akan di input ke database
    if ($pesan_error === "") {

        //filter semua data dengan mysqli real escape
        $nim            = mysqli_real_escape_string($link, $nim);
        $nama           = mysqli_real_escape_string($link, $nama);
        $tempat_lahir   = mysqli_real_escape_string($link, $tempat_lahir);
        $tanggal_lahir  = mysqli_real_escape_string($link, $tanggal_lahir);
        $fakultas       = mysqli_real_escape_string($link, $fakultas);
        $jurusan        = mysqli_real_escape_string($link, $jurusan);
        $ipk            = (float) mysqli_real_escape_string($link, $ipk);

        //buat query insert
        $query = "INSERT INTO mahasiswa VALUES ('$nim','$nama','$tempat_lahir','$tanggal_lahir','$fakultas','$jurusan','$ipk')";

        //eksekusi data
        $result = mysqli_query($link, $query);

        //periksa data apakah sudah berhasil : true
        if ($result) {
            $pesan = "Mahasiswa dengan nama $nama telah berhasil di tambahkan!";

            //redirect ke halaman tampil mahasiswa
            header("location:tampil_mahasiswa.php?msg=$pesan");
        } else {
            die("Data mahasiswa $nama tidak berhasil di tambahkan : err - " . mysqli_errno($link) . " - " . mysqli_error($link));
        }
    }
} else {

    //siapkan variabel sebagai default
    $pesan_error = "";
    $nim = "";
    $nama = "";
    $tempat_lahir = "";
    $tanggal_lahir = "";
    $fakultas = "";
    $jurusan = "";
    $ipk = "";
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
    <title>Kampusku - Tambah Mahasiswa</title>
</head>

<body class="bg-dark">
    <div class="container">
        <nav class="navbar navbar-expand navbar-light bg-light p-3">
            <div class="nav navbar-nav">
                <a class="nav-item nav-link active" href="./tampil_mahasiswa.php">Mahasiswa</a>
                <a class="nav-item nav-link" href="./tampil_admin.php">Admin</a>
                <a class="nav-item nav-link" onclick="return confirm('Yakin ingin keluar?')" href="./logout.php">Log-Out</a>
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

                        <h4 class="text-center mt-3 mb-4">Form Tambah Mahasiswa</h4>
                        <form action="./tambah_mahasiswa.php" method="post">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="nim">NIM</label>
                                        <input type="text" name="nim" id="nim" class="form-control form-control-sm border-1 " placeholder="Contoh : 20210120001" value="<?= $nim; ?>">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="nama">Nama</label>
                                        <input type="text" name="nama" id="nama" class="form-control form-control-sm border-1 " placeholder="Masukan nama..." value="<?= $nama; ?>">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="fakultas">Fakultas</label>
                                        <select class="form-control form-control-sm border-1 " name="fakultas" id="fakultas" required>
                                            <option hidden disabled value <?= ($fakultas != "") ? "" : "selected"; ?>>- Pilih Fakultas -</option>
                                            <?php $data_fakultas = [
                                                'Kedokteran', 'FMIPA', 'Ekonomi', 'Teknik', 'Sastra', 'FASILKOM'
                                            ];
                                            foreach ($data_fakultas as $fak) {

                                            ?>
                                                <option value="<?= $fak; ?>" <?= ($fakultas === $fak) ? "selected" : ""; ?>><?= $fak; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="ipk">Nilai IPK</label>
                                        <input type="text" name="ipk" id="ipk" class="form-control form-control-sm border-1 " placeholder="Masukan IPK..." value="<?= $ipk; ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="tempat_lahir">Tempat Lahir</label>
                                        <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control form-control-sm border-1 " placeholder="Masukan tempat lahir..." value="<?= $tempat_lahir; ?>">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="tanggal_lahir">Tanggal Lahir</label>
                                        <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control form-control-sm border-1 " placeholder="Masukan tanggal lahir..." value="<?= $tanggal_lahir; ?>">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="jurusan">Jurusan</label>
                                        <input type="text" name="jurusan" id="jurusan" class="form-control form-control-sm border-1 " placeholder="Masukan jurusan..." value="<?= $jurusan; ?>">
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