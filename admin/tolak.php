<?php
// Koneksi ke database
include('koneksi.php');

// Periksa apakah ID pesanan dikirimkan melalui POST
if (isset($_POST['id_pesanan'])) {
    $id_pesanan = $_POST['id_pesanan'];

    // Update status pesanan ke "Dibatalkan"
    $sql = "UPDATE pesanan SET status_pesanan = 'Dibatalkan' WHERE id_pesanan = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("i", $id_pesanan);
    $stmt->execute();

    // Redirect ke halaman daftar pesanan
    header("Location: pesanan1.php");
    exit();
}

$koneksi->close();
