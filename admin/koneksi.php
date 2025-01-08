<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "pure_beauty_db"; // Nama Database

// Melakukan koneksi ke database
$koneksi = mysqli_connect($host, $user, $pass, $db);

// Memeriksa apakah koneksi berhasil
if (!$koneksi) {
    die("Gagal konek: " . mysqli_connect_error());
}
