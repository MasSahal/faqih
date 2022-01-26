<?php

//Panggil file koneksi ke database
include("./connection.php");

//pilih data dari database sesuai nim yang dipilih tadi saat tekan tombol hapus
$nim = htmlentities(strip_tags(trim($_GET['nim'])));

//filter anti injeksi
$nim = mysqli_real_escape_string($link, $nim);

//pilih data untuk dapet nama
$result = mysqli_query($link, "SELECT * FROM mahasiswa WHERE nim='$nim'");
$result = mysqli_fetch_assoc($result);
$nama = $result['nama'];

//pilih data untuk dihapus
$result = mysqli_query($link, "DELETE FROM mahasiswa WHERE nim='$nim'");

//buat pesan
$pesan = "Data mahasiswa $nama berhasil dihapus!";
$pesan = urlencode($pesan);

if ($result) {
    header("location:./tampil_mahasiswa.php?msg=$pesan");
} else {
    die("Gagal menghapus seluruh data mahasiswa - Error Code :" . mysqli_connect_errno() . " - " . mysqli_connect_error());
}
