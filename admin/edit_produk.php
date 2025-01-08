<?php
include 'koneksi.php';

session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Ambil data dari form
$id_produk = $_POST['id_produk'];
$nama_produk = $_POST['nama_produk'];
$kategori = $_POST['kategori'];
$deskripsi = $_POST['deskripsi_produk'];
$harga_produk = $_POST['harga_produk'];
$stok_produk = $_POST['stok_produk'];

// Inisialisasi variabel untuk gambar
// Inisialisasi variabel untuk gambar
$gambar_produk = "";

// Cek apakah ada gambar yang diunggah
if ($_FILES['gambar_produk']['error'] == UPLOAD_ERR_OK) {
    // Path absolut ke folder uploads
    $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/PBL_OKE/admin/uploads/';
    $target_file = $target_dir . basename($_FILES['gambar_produk']['name']);

    // Proses upload file
    if (move_uploaded_file($_FILES['gambar_produk']['tmp_name'], $target_file)) {
        $gambar_produk = '/PBL_OKE/admin/uploads/' . basename($_FILES['gambar_produk']['name']); // Path relatif untuk disimpan di database
    } else {
        echo "Error saat mengunggah gambar!";
    }
} else {
    // Jika tidak ada gambar baru, ambil gambar lama dari database
    $query = "SELECT gambar_produk FROM produk WHERE id_produk = '$id_produk'";
    $result = mysqli_query($koneksi, $query);
    $row = mysqli_fetch_assoc($result);
    $gambar_produk = $row['gambar_produk'];
}

// Jika ada gambar baru, upload dan update gambar
if (!empty($_FILES['gambar']['name'])) {
    $gambar_produk = 'uploads/' . $_FILES['gambar']['name'];
    move_uploaded_file($_FILES['gambar']['tmp_name'], $gambar_produk);

    $query = "UPDATE produk SET 
        nama_produk = '$nama_produk',
        kategori = '$kategori',
        gambar_produk = '$gambar_produk',  // Perbaiki nama kolom
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


if ($_FILES['gambar_produk']['error'] == UPLOAD_ERR_OK) {
    // Path absolut ke folder uploads
    $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/PBL_OKE/admin/uploads/';
    $target_file = $target_dir . basename($_FILES['gambar_produk']['name']);

    // Proses upload file
    if (move_uploaded_file($_FILES['gambar_produk']['tmp_name'], $target_file)) {
        $gambar_produk = '/PBL_OKE/admin/uploads/' . basename($_FILES['gambar_produk']['name']); // Path relatif untuk disimpan di database
    } else {
        echo "Error saat mengunggah gambar!";
    }
}


// Update data produk di database, termasuk kategori dan deskripsi
$query = "UPDATE produk SET 
            nama_produk = '$nama_produk', 
            kategori = '$kategori', 
            deskripsi_produk = '$deskripsi', 
            harga_produk = '$harga_produk', 
            stok_produk = '$stok_produk', 
            gambar_produk = '$gambar_produk' 
          WHERE id_produk = '$id_produk'";

if (mysqli_query($koneksi, $query)) {
    // Redirect ke halaman daftar produk setelah berhasil update
    header('Location: produk1.php');
    exit();
} else {
    echo "Error: " . mysqli_error($koneksi);
}

$koneksi->close();
