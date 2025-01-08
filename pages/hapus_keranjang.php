<?php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id_produk'])) {
    $id_produk = intval($_GET['id_produk']);
    $id_pembeli = $_SESSION['id_pembeli']; // Pastikan session id_pembeli tersedia

    // Query untuk menghapus produk dari keranjang
    $query = "DELETE FROM keranjang WHERE id_produk = $id_produk AND id_pembeli = $id_pembeli";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        // Redirect ke halaman keranjang dengan pesan sukses
        header("Location: cart.php?status=sukses");
    } else {
        // Redirect ke halaman keranjang dengan pesan gagal
        header("Location: cart.php?status=gagal");
    }
} else {
    // Jika id_produk tidak ditemukan, kembali ke halaman keranjang
    header("Location: cart.php?status=error");
}
exit();

$koneksi->close();
