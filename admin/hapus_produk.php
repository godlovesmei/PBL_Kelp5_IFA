<?php
include 'koneksi.php';

session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$id_produk = $_GET['id_produk'];

// Query untuk menghapus data produk
$query = "DELETE FROM produk WHERE id_produk = $id_produk";
mysqli_query($koneksi, $query);

header('Location: produk1.php');

$koneksi->close();
