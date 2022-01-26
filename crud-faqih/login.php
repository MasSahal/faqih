<?php
//ambil pesan jika ada
if (isset($_GET['msg'])) {
    $pesan = $_GET['msg'];
}

//cek apakah form telah di submit
if (isset($_POST['submit'])) {  //submit berasal dari name

    //ambil data yang di imput dalam form
    $nim = htmlentities(strip_tags(trim($_POST['nim'])));

    // siapkan variabel untuk menampung pesan error
    $pesan_error = "";

    //cek apakah nim telah di isi apa tidak
    if (empty($nim)) {
        $pesan_error .= "nim harus diisi! <br>";
    }

    //Panggil file koneksi ke database
    include("./connection.php");

    //filter data input dengan mysqli real escape
    $nim = mysqli_real_escape_string($link, $nim);

    //cek apakah admin yang masuk
    if ($nim === "admin") {

        //nyalakan session
        session_start();

        //buat session nama sebagai bukti telah berhasil masuk
        $_SESSION['is_login'] = "admin";
        $_SESSION['nim'] = 00000000;
        $_SESSION['nama'] = "Admin";


        //redirect / lempar ke halaman tampil mahasiswa
        header("location:tampil_mahasiswa.php");
        #
    } else {

        //cek apakah nim dan password ada di table admin
        $query = "SELECT * FROM mahasiswa WHERE nim='$nim'";

        $result = mysqli_query($link, $query);
        if (mysqli_num_rows($result) == 0) {
            #
            //jika data ada 0 di table
            $pesan_error .= "nim dan/atau Password tidak sesuai! <br>";
        }


        //jika tidak ada pesan error berarti lolos seleksi
        if ($pesan_error === "") {
            #
            //nyalakan session
            session_start();

            //buat session nama sebagai bukti telah berhasil masuk
            $_SESSION['is_login'] = "mahasiswa";
            $_SESSION['nim'] = $nim;

            $data = mysqli_fetch_assoc($result);
            $_SESSION['nama'] = $data['nama'];

            //redirect / lempar ke halaman tampil mahasiswa
            header("location:tampil_mahasiswa.php");
        }
    }
} else {
    #jika tidak ada form yang disubmit

    //buat variabel dengan nilai ksosong sebagai default
    $nim = "";
    $pesan_error = "";
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
    <title>Login</title>
</head>

<body class="bg-dark">
    <div class="container">
        <div class="row mt-3">
            <div class="col-4">
                <h4 class="m-2 text-white-50">KampusKu</h4>
            </div>
            <hr>
        </div>
        <div class="row justify-content-center pt-5">
            <div class="col-md-5 col-sm-10">

                <!-- Tampilkan pesan error jika ada  -->
                <?php if ($pesan_error !== "") { ?>
                    <div class="alert alert-danger" role="alert">
                        <?= $pesan_error; ?>
                    </div>
                <?php }; ?>
                <?php if (isset($pesan)) { ?>
                    <div class="alert alert-primary" role="alert">
                        <?= $pesan; ?>
                    </div>
                <?php }; ?>
                <div class="card">
                    <div class="card-body">
                        <div class="text-center">
                            <h4>Halaman Login</h4>
                            <strong>Sistem Informasi KampusKu</strong>
                        </div>

                        <div class="p-md-3 p-sm-1">
                            <form action="login.php" method="post">
                                <div class="form-group">
                                    <label for="nim">NIM</label>
                                    <input type="text" name="nim" id="nim" class="form-control border-1" placeholder="Masukan nim anda" value="<?= $nim; ?>">
                                </div>
                                <br>
                                <div class="d-grid gap-2">
                                    <button type="submit" name="submit" class="btn btn-primary btn-sm">Login</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>