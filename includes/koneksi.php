<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "pure_beauty_db"; //Nama Database 
// melakukan koneksi ke db 
$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) {
    echo "Gagal konek: " . die(mysqli_error($koneksi));
}
