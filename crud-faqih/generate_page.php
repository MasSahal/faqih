<?php

//buat koneksi ke database
$dbhost   = "localhost";
$dbroot   = "root";
$dbpass   = "";


//kneksi ke database
$link = mysqli_connect($dbhost, $dbroot, $dbpass);


//cek apakah ada error
if (!$link) {
    die("Koneksi database gagal! : " . mysqli_connect_errno() . " - " . mysqli_connect_error());
};


//buat database kampusku, jika belum ada
$result = mysqli_query($link, "CREATE DATABASE IF NOT EXISTS kampusku");
if (!$result) {
    #
    die("Gagal membuat database kampusku : " . mysqli_connect_errno() . " - " . mysqli_connect_error());
} else {
    #
    //jika berhasil membuat database
    echo "Selamat, database <b>kampusku</b> berhasil di buat! <br>";
}


//pilih database kampusku
$result = mysqli_select_db($link, "kampusku");
if (!$result) {
    #
    die("Gagal memilih database kampusku! : " . mysqli_connect_errno() . " - " . mysqli_connect_error());
} else {
    #
    //jika berhasil memilih database
    echo "Selamat, database <b>kampusku</b> berhasil dipilih! <br>";
}


//cek apakah ada table mahasiswa, jika ada akan di hapus
$result = mysqli_query($link, "DROP TABLE IF EXISTS mahasiswa");
if (!$result) {
    #
    die("Gagal menghapus table <b>mahasiswa</b>! : " . mysqli_connect_errno() . " - " . mysqli_connect_error());
} else {
    #
    //jika berhasil menghapus table
    echo "Selamat, table <b>mahasiswa</b> berhasil dihapus*! <br>";
}


//query untuk membuat table mahasiswa
$query = "CREATE TABLE mahasiswa( nim CHAR(8), nama VARCHAR(100), tempat_lahir VARCHAR(50), tanggal_lahir DATE, fakultas VARCHAR(50), jurusan VARCHAR(50), ipk DECIMAL(3,2), PRIMARY KEY(nim))";
$result = mysqli_query($link, $query);
if (!$result) {
    #
    die("Gagal membuat table <b>mahasiswa</b>! : " . mysqli_connect_errno() . " - " . mysqli_connect_error());
} else {
    #
    //jika berhasil membuat table mahasiswa
    echo "Selamat, table  <b>mahasiswa</b> berhasil dibuat! <br>";
}

$query  = "INSERT INTO mahasiswa VALUES ";
$query .= "('13002011', 'Sahal', 'Yogyakarta', '2002-01-26', 'Teknik', 'Teknik Informatika', 3.9),";
$query .= "('13012012', 'James Dulari', 'Medan', '1995-12-02', 'Kedokteran', 'Kedokteran Gigi', 2.7),";
$query .= "('13011135', 'Sri Mulyani', 'Palembang', '1994-02-11', 'Pendidikan', 'Sastra Indonesia', 3.6),";
$query .= "('13012022', 'Hilman KA', 'Medan', '1991-05-02', 'Teknologi', 'Teknik Informatika', 4.0),";
$query .= "('13012018', 'Cecep Firmansyah', 'Jayapura', '1994-12-02', 'Teknologi', 'Teknik Mesin', 3.0)";

$result = mysqli_query($link, $query);
if (!$result) {
    #
    // jika tidak berhasil menginput data ke table mahasiswa
    die("Gagal memasukkan data ke table <b>mahasiswa</b>! : " . mysqli_connect_errno() . " - " . mysqli_connect_error());
} else {
    #
    // jika berhasil menginput data ke table mahasiswa
    echo "Selamat, data telah berhasil di input ke table <b>mahasiswa</b>! <br>";
}

## BAGIAN ADMIN ##

//cek apakah ada table admin, jika ada akan di hapus
$result = mysqli_query($link, "DROP TABLE IF EXISTS admin");
if (!$result) {
    #
    die("Gagal menghapus table <b>admin</b>! : " . mysqli_connect_errno() . " - " . mysqli_connect_error());
} else {
    #
    //jika berhasil menghapus table
    echo "Selamat, table <b>admin</b> berhasil dihapus*! <br>";
}


//query untuk membuat table admin
$query = "CREATE TABLE admin( id INT(11) AUTO_INCREMENT, username VARCHAR(50), password VARCHAR(40), PRIMARY KEY(id))";
$result = mysqli_query($link, $query);
if (!$result) {
    #
    die("Gagal membuat table <b>admin</b>! : " . mysqli_connect_errno() . " - " . mysqli_connect_error());
} else {
    #
    //jika berhasil membuat table admin
    echo "Selamat, table  <b>admin</b> berhasil dibuat! <br>";
}

// Membuat username dan password admin
$username = "admin";
$password = sha1("admin123"); //menggunakan hash enskripsi metode sha1

$query = "INSERT INTO admin VALUES('1','$username','$password')";
$result = mysqli_query($link, $query);
if (!$result) {
    #
    die("Gagal menambahkan data ke table <b>admin</b>! : " . mysqli_connect_errno() . " - " . mysqli_connect_error());
} else {
    #
    //jika berhasil membuat table admin
    echo "Selamat, menambahkan data ke table <b>admin</b>! <br>";
}


//tutup koneksi ke database
mysqli_close($link);

//buat pesan
$pesan = "Generate data telah berhasil!";
$pesan = urlencode($pesan);


//jalankan session
session_start();

//periksa apakah user sudah login ditandai dengan adanya session nama -> $_SESSION['nama']
// jika tidak ada maka akan dikembalikan ke halaman login, jika sudah maka ke halaman tampil mahasiswa
if (!isset($_SESSION['nama'])) {
    header("location:./login.php?msg=$pesan");
    #
} else {
    //return redirect
    header("location:./tampil_mahasiswa.php?msg=$pesan");
}
