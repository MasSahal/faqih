<?php
//jalankan session
session_start();

//hancurkan semua session login
session_destroy();

//buat pesan
$pesan = "Anda telah keluar!. Silahkan masuk untuk memulai";
$pesan = urlencode($pesan);
//return redirect
header("location:./login.php?msg=$pesan");
