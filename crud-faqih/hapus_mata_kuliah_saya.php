<?php

//Panggil file koneksi ke database
include("./connection.php");

//pilih data dari database sesuai id_mks yang dipilih tadi saat tekan tombol hapus
$id_mks = htmlentities(strip_tags(trim($_GET['id_mks'])));

//filter anti injeksi
$id_mks = mysqli_real_escape_string($link, $id_mks);

//pilih data untuk dapet nama
$result = mysqli_query($link, "SELECT * FROM matkul_saya JOIN mata_kuliah ON matkul_saya.id_matkul=mata_kuliah.id_matkul WHERE id_mks='$id_mks'");
$result = mysqli_fetch_assoc($result);

$nama = $result['matkul'];

//pilih data untuk dihapus
$result = mysqli_query($link, "DELETE FROM matkul_saya WHERE id_mks='$id_mks'");

//buat pesan
$pesan = "Data mata kuliah $nama berhasil dihapus!";
$pesan = urlencode($pesan);

if ($result) {
    header("location:./tampil_mata_kuliah_saya.php?msg=$pesan");
} else {
    die("Gagal menghapus data mata kuliah $nama - Error Code :" . mysqli_connect_errno() . " - " . mysqli_connect_error());
}
