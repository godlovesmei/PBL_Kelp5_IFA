<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    // Ambil ID produk dan jumlah produk
    $idProduk = isset($_POST['id_produk']) ? intval($_POST['id_produk']) : 0;
    $jumlahProduk = isset($_POST['jumlah_produk']) ? intval($_POST['jumlah_produk']) : 0;

    // Debugging: Catat input
    file_put_contents('debug.log', "Input ID Produk: $idProduk, Jumlah Produk: $jumlahProduk\n", FILE_APPEND | LOCK_EX);

    if ($idProduk <= 0 || $jumlahProduk <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'ID produk atau jumlah produk tidak valid.']);
        exit();
    }

    // Cek stok produk
    $queryStok = "SELECT stok_produk FROM produk WHERE id_produk = ?";
    $stmtStok = $koneksi->prepare($queryStok);
    $stmtStok->bind_param("i", $idProduk);
    $stmtStok->execute();
    $resultStok = $stmtStok->get_result();
    $dataStok = $resultStok->fetch_assoc();

    // Debugging: Catat stok yang ditemukan
    file_put_contents('debug.log', "Data Stok: " . print_r($dataStok, true), FILE_APPEND | LOCK_EX);

    if (!$dataStok) {
        echo json_encode(['status' => 'error', 'message' => 'Produk tidak ditemukan.']);
        exit();
    }

    // Validasi stok
    $stokProduk = intval($dataStok['stok_produk']);
    if ($jumlahProduk > $stokProduk) {
        echo json_encode(['status' => 'error', 'message' => 'Stok tidak mencukupi.']);
        exit();
    }

    // Update jumlah produk di keranjang
    $queryUpdate = "UPDATE keranjang SET jumlah_produk = ? WHERE id_produk = ?";
    $stmtUpdate = $koneksi->prepare($queryUpdate);
    $stmtUpdate->bind_param("ii", $jumlahProduk, $idProduk);

    if ($stmtUpdate->execute()) {
        // Debugging: Catat hasil update
        file_put_contents('debug.log', "Keranjang berhasil diperbarui untuk ID Produk $idProduk\n", FILE_APPEND | LOCK_EX);
        echo json_encode(['status' => 'success', 'message' => 'Jumlah produk berhasil diperbarui.']);
    } else {
        // Debugging: Catat jika query gagal
        file_put_contents('debug.log', "Gagal memperbarui keranjang untuk ID Produk $idProduk: " . $stmtUpdate->error . "\n", FILE_APPEND | LOCK_EX);
        echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui keranjang.']);
    }

    $stmtUpdate->close();
    $stmtStok->close();
    $koneksi->close();
}

$koneksi->close();
