<?php
include 'koneksi.php';

session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$id_produk = $_POST['id_produk'];
$nama_produk = $_POST['nama_produk'];
$kategori = $_POST['kategori'];
$deskripsi_produk = $_POST['deskripsi_produk'];
$stok_produk = $_POST['stok_produk'];
$harga_produk = $_POST['harga_produk'];

// Jika ada gambar baru, upload dan update gambar
if (!empty($_FILES['gambar_produk']['name'])) {
    $gambar_produk = '/PBL_OKE/admin/uploads/products' . $_FILES['gambar_produk']['name'];
    move_uploaded_file($_FILES['gambar_produk']['tmp_name'], $gambar_produk);

    $query = "UPDATE produk SET 
        nama_produk = '$nama_produk',
        kategori = '$kategori',
        gambar_produk = '$gambar_produk',
        deskripsi_produk = '$deskripsi_produk',
        stok_produk = $stok_produk,
        harga_produk = $harga_produk
        WHERE id_produk = $id_produk";
} else {
    $query = "UPDATE produk SET 
        nama_produk = '$nama_produk',
        kategori = '$kategori',
        deskripsi_produk = '$deskripsi_produk',
        stok_produk = $stok_produk,
        harga_produk = $harga_produk
        WHERE id_produk = $id_produk";
}

mysqli_query($koneksi, $query);
header('Location: produk1.php');

$koneksi->close();
