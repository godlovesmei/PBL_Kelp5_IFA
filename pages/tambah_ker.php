<?php
session_start();
include 'koneksi.php';

session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_produk = intval($_POST['id_produk']);
    $quantity = intval($_POST['quantity']);

    // Query untuk mengambil detail produk berdasarkan ID
    $query = $koneksi->prepare("SELECT id_produk, nama_produk, harga_produk, gambar_produk FROM produk WHERE id_produk = ?");
    $query->bind_param("i", $id_produk);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $produk = $result->fetch_assoc();

        // Tambahkan produk ke keranjang di sesi
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $cart = &$_SESSION['cart'];

        if (isset($cart[$id_produk])) {
            // Jika produk sudah ada di keranjang, tambahkan kuantitas
            $cart[$id_produk]['quantity'] += $quantity;
        } else {
            // Jika produk baru, tambahkan ke keranjang
            $cart[$id_produk] = [
                'id_produk' => $produk['id_produk'],
                'nama_produk' => $produk['nama_produk'],
                'harga_produk' => $produk['harga_produk'],
                'gambar_produk' => 'data:image/jpeg;base64,' . base64_encode($produk['gambar_produk']),
                'quantity' => $quantity,
            ];
        }

        echo json_encode(['success' => true, 'message' => 'Produk berhasil ditambahkan ke keranjang']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Produk tidak ditemukan']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Metode tidak valid']);
}

$koneksi->close();
