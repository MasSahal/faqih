<?php

//Panggil file koneksi ke database
include("./connection.php");

//pilih data dari database sesuai id_matkul yang dipilih tadi saat tekan tombol hapus
$id_matkul = htmlentities(strip_tags(trim($_GET['id_matkul'])));

//filter anti injeksi
$id_matkul = mysqli_real_escape_string($link, $id_matkul);

//pilih data untuk dapet nama
$result = mysqli_query($link, "SELECT * FROM mata_kuliah WHERE id_matkul='$id_matkul'");
$result = mysqli_fetch_assoc($result);
$nama = $result['matkul'];

//pilih data untuk dihapus
$result = mysqli_query($link, "DELETE FROM mata_kuliah WHERE id_matkul='$id_matkul'");

//buat pesan
$pesan = "Data mata kuliah $nama berhasil dihapus!";
$pesan = urlencode($pesan);

if ($result) {
    header("location:./tampil_mata_kuliah.php?msg=$pesan");
} else {
    die("Gagal menghapus data mata kuliah $nama - Error Code :" . mysqli_connect_errno() . " - " . mysqli_connect_error());
}
