<?php

//jalankan session
session_start();

//periksa apakah user sudah login ditandai dengan adanya session nama -> $_SESSION['nama']
// jika tidak ada maka akan dikembalikan ke halaman login
if (!isset($_SESSION['nama'])) {
    header("location:./login.php");
};

//buat pesan
if (isset($_GET['pesan'])) {
    $pesan = $_GET['pesan'];
}

//Panggil file koneksi ke database
include("./connection.php");

//periksa apakah form telah di submit
if (isset($_POST['submit'])) {

    //ambil data dari form input
    $nim            = htmlentities(strip_tags(trim($_POST['nim'])));

    $nama           = htmlentities(strip_tags(trim($_POST['nama'])));
    $tempat_lahir   = htmlentities(strip_tags(trim($_POST['tempat_lahir'])));
    $tanggal_lahir  = htmlentities(strip_tags(trim($_POST['tanggal_lahir'])));
    $fakultas       = htmlentities(strip_tags(trim($_POST['fakultas'])));
    $jurusan        = htmlentities(strip_tags(trim($_POST['jurusan'])));
    $ipk            = htmlentities(strip_tags(trim($_POST['ipk'])));

    var_dump($_POST);
    // siapkan variabel untuk menampung pesan error
    $pesan_error = "";

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
        $pesan_error .= "jurusan harus diisi! <br>";
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

        //buat query update
        $query = "UPDATE mahasiswa SET ";
        $query .= "nama='$nama', tempat_lahir='$tempat_lahir', tanggal_lahir='$tanggal_lahir', ";
        $query .= "fakultas='$fakultas', tempat_lahir='$tempat_lahir', jurusan='$jurusan', ipk='$ipk' WHERE nim='$nim' ";

        //eksekusi data
        $result = mysqli_query($link, $query);

        //periksa data apakah sudah berhasil = true
        if ($result) {
            $pesan = "Data mahasiswa dengan nama $nama telah berhasil diperbarui!";
            $pesan = urlencode($pesan);

            //redirect ke halaman tampil mahasiswa
            header("location:tampil_mahasiswa.php?msg=$pesan");
        } else {
            #
            $pesan = "Data mahasiswa dengan nama $nama tidak berhasil diperbarui!";
            $pesan = urlencode($pesan);
            header("location:tampil_mahasiswa.php?msg=$pesan");

            // die("Data mahasiswa $nama tidak berhasil diperbarui : err - " . mysqli_errno($link) . " - " . mysqli_error($link));
        }
    }
    #
} else {

    //pilih data dari database sesuai nim yang dipilih tadi saat tekan tombol edit
    $nim = htmlentities(strip_tags(trim($_GET['nim'])));

    //filter anti injeksi
    $nim = mysqli_real_escape_string($link, $nim);

    //pilih data
    $result = mysqli_query($link, "SELECT * FROM mahasiswa WHERE nim='$nim'");

    $data = mysqli_fetch_assoc($result);
    //siapkan variabel sebagai default
    $pesan_error    = "";
    $nim            = $data['nim'];
    $nama           = $data['nama'];
    $tempat_lahir   = $data['tempat_lahir'];
    $tanggal_lahir  = $data['tanggal_lahir'];
    $fakultas       = $data['fakultas'];
    $jurusan        = $data['jurusan'];
    $ipk            = $data['ipk'];
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
    <title>Kampusku - Edit Mahasiswa</title>
</head>

<body class="bg-dark">
    <div class="container">
        <nav class="navbar navbar-expand navbar-light bg-light p-3">
            <div class="nav navbar-nav">
                <a class="nav-item nav-link active" href="./tampil_mahasiswa.php">Mahasiswa</a>
                <a class="nav-item nav-link" href="./tampil_mata_kuliah.php">Mata Kuliah</a>
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
                        <?php if (isset($_GET['msg'])) { ?>
                            <div class="alert alert-danger" role="alert">
                                <?= $_GET['msg'] ?>
                            </div>
                        <?php }; ?>
                        <h4 class="text-center mt-3 mb-4">Form Edit Mahasiswa</h4>
                        <form action="./edit_mahasiswa.php" method="post">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="nim">NIM</label>
                                        <input type="text" name="nim" id="nim" class="form-control form-control-sm border-1 " placeholder="Contoh : 20210120001" value="<?= $nim; ?>" readonly>
                                        <!-- NIM tidak bisa di ganti, klo mau ganti ya hapus data -->
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