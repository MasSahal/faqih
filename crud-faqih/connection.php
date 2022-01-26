<?php

//buat koneksi ke database
$dbhost   = "localhost";
$dbroot   = "root";
$dbpass   = "";
$dbname   = "kampusku";

//kneksi ke database
$link = mysqli_connect($dbhost, $dbroot, $dbpass, $dbname);

//cek apakah ada error
if (!$link) {
    die("Koneksi database gagal! : " . mysqli_connect_errno() . " - " . mysqli_connect_error());
};

function dd($param)
{
    return die(var_dump($param));
}
