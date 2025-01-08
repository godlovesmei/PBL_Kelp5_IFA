<?php
include 'koneksi.php';

session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Mengambil data dari form
$nama_produk = $_POST['nama_produk'];
$kategori = $_POST['kategori'];
$deskripsi = $_POST['deskripsi'];
$stok = $_POST['stok'];
$harga = $_POST['harga'];

// Direktori untuk menyimpan gambar
$target_dir = $_SERVER['DOCUMENT_ROOT'] . "/PBL_OKE/admin/uploads/products";

// Pastikan direktori ada
if (!is_dir($target_dir)) {
    if (!mkdir($target_dir, 0777, true)) {
        echo "<script>
            alert('Gagal membuat direktori tujuan.');
            window.location.href = 'produk1.php';
        </script>";
        exit;
    }
}

$gambar_path = ""; // Inisialisasi path gambar
if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
    $allowed_extensions = ['jpg', 'jpeg', 'png'];
    $file_extension = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));

    if (!in_array($file_extension, $allowed_extensions)) {
        echo "<script>
            alert('Hanya file gambar dengan format JPG, JPEG, atau PNG yang diizinkan.');
            window.location.href = 'produk1.php';
        </script>";
        exit;
    }

    $max_file_size = 2 * 1024 * 1024; // 2MB
    if ($_FILES['gambar']['size'] > $max_file_size) {
        echo "<script>
            alert('Ukuran file terlalu besar. Maksimal 2MB.');
            window.location.href = 'produk1.php';
        </script>";
        exit;
    }

    $file_tmp = $_FILES['gambar']['tmp_name'];
    $file_name = uniqid() . "-" . basename($_FILES['gambar']['name']);
    $target_file = $target_dir . $file_name;

    if (move_uploaded_file($file_tmp, $target_file)) {
        $gambar_path = "/PBL_OKE/admin/uploads/products" . $file_name;
    } else {
        echo "<script>
            alert('Gagal menyimpan file gambar ke server.');
            window.location.href = 'produk1.php';
        </script>";
        exit;
    }
} else {
    echo "<script>
        alert('Gagal mengunggah gambar. Pastikan Anda telah memilih file yang valid.');
        window.location.href = 'produk1.php';
    </script>";
    exit;
}

// Simpan data ke database menggunakan prepared statements
$query = "INSERT INTO produk (nama_produk, kategori, gambar_produk, deskripsi_produk, stok_produk, harga_produk) 
          VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("ssssii", $nama_produk, $kategori, $gambar_path, $deskripsi, $stok, $harga);
$result = $stmt->execute();

if ($result) {
    echo "<script>
        alert('Produk berhasil ditambahkan.');
        window.location.href = 'produk1.php';
    </script>";
} else {
    echo "<script>
        alert('Gagal menambahkan produk. Kesalahan: " . $koneksi->error . "');
        window.location.href = 'produk1.php';
    </script>";
}

$koneksi->close();
