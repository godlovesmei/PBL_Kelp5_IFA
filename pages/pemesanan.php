<?php
session_start();
include 'koneksi.php';

// Pastikan pengguna login
if (!isset($_SESSION['id_pembeli'])) {
    header("Location: login.php");
    exit();
}

// Ambil ID pembeli dari sesi
$id_pembeli = $_SESSION['id_pembeli'];

// Ambil data pembeli
$queryPembeli = "SELECT * FROM pembeli WHERE id_pembeli = ?";
$stmtPembeli = $koneksi->prepare($queryPembeli);
$stmtPembeli->bind_param("i", $id_pembeli);
$stmtPembeli->execute();
$resultPembeli = $stmtPembeli->get_result();
$dataPembeli = $resultPembeli->fetch_assoc();

if (!$dataPembeli) {
    echo "Data pembeli tidak ditemukan.";
    exit();
}

// Ambil data produk di keranjang
$queryKeranjang = "SELECT produk.nama_produk, produk.harga_produk, keranjang.jumlah_produk 
FROM keranjang 
JOIN produk ON keranjang.id_produk = produk.id_produk 
WHERE keranjang.id_pembeli = ?";

$stmtKeranjang = $koneksi->prepare($queryKeranjang);
$stmtKeranjang->bind_param("i", $id_pembeli);
$stmtKeranjang->execute();
$resultKeranjang = $stmtKeranjang->get_result();

$produkDipesan = [];
$totalHargaProduk = 0;

while ($row = $resultKeranjang->fetch_assoc()) {
    $row['subtotal'] = $row['harga_produk'] * $row['jumlah_produk']; // Hitung subtotal per produk
    $produkDipesan[] = $row; // Simpan data produk
    $totalHargaProduk += $row['subtotal']; // Akumulasi total harga
}

$queryProduk = "SELECT produk.nama_produk, produk.harga_produk, produk.gambar_produk, keranjang.jumlah_produk, 
             (produk.harga_produk * keranjang.jumlah_produk) AS subtotal 
             FROM keranjang 
             JOIN produk ON keranjang.id_produk = produk.id_produk 
             WHERE keranjang.id_pembeli = ?";

$stmtProduk = $koneksi->prepare($queryProduk);
$stmtProduk->bind_param("i", $id_pembeli);
$stmtProduk->execute();
$resultProduk = $stmtProduk->get_result();

// Definisikan base URL dinamis
$baseUrl = "http://localhost/PBL_OKE"; // Sesuaikan ini dengan base URL kamu

// Debugging untuk hasil query
$produkDipesan = $resultProduk->fetch_all(MYSQLI_ASSOC);

// Biaya pengiriman tetap Rp15.000
$biayaPengiriman = 15000;

// Hitung total pembayaran
$totalPembayaran = $totalHargaProduk + $biayaPengiriman;

// Proses pesanan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buat_pesanan'])) {
    $id_transaksi = uniqid('TRX_');
    $alamat_pengiriman = $dataPembeli['alamat'];
    $produkJson = json_encode($produkDipesan);
    $tanggalPesanan = date('Y-m-d H:i:s');
    $link_invoice = '';

    // Masukkan data pesanan
    $queryPesanan = "INSERT INTO pesanan (id_transaksi, id_pembeli, produk_dipesan, total_harga, metode_pembayaran, status_pesanan, alamat_pengiriman, tanggal_pesanan) 
    VALUES (?, ?, ?, ?, 'COD', 'Menunggu Konfirmasi', ?, ?)";
    $stmtPesanan = $koneksi->prepare($queryPesanan);
    if ($stmtPesanan) {
        $stmtPesanan->bind_param("sisiss", $id_transaksi, $id_pembeli, $produkJson, $totalPembayaran, $alamat_pengiriman, $tanggalPesanan);
        if ($stmtPesanan->execute()) {
            $_SESSION['flash_message'] = "Pesanan berhasil dibuat! Mohon tunggu konfirmasi dari penjual.";
        } else {
            $_SESSION['flash_message'] = "Gagal membuat pesanan. Silakan coba lagi.";
        }

        // Hapus keranjang
        $queryHapusKeranjang = "DELETE FROM keranjang WHERE id_pembeli = ?";
        $stmtHapusKeranjang = $koneksi->prepare($queryHapusKeranjang);
        $stmtHapusKeranjang->bind_param("i", $id_pembeli);
        $stmtHapusKeranjang->execute();
    } else {
        $_SESSION['flash_message'] = "Gagal memproses pesanan. Silakan coba lagi.";
    }

    header("Location: history_ord.php");
    exit();
}
$koneksi->close();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
        rel="stylesheet" />
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap"
        rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Belleza&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="\PBL_OKE\assets\css\pemesanan.css" />
    <title>Checkout | PureBeauty</title>
</head>

<body>
    <!-- Nama toko -->
    <div class="top-bar d-flex justify-content-between align-items-center p-3">
        <h2 class="site-name">PureBeauty</h2>
        <h3 class="tagline text-center mx-auto">Skin Protection</h3>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light p-2">
        <div class="container-fluid">

            <!-- Search bar (Tetap di kiri, dan di luar navbar-toggler) -->
            <div class="search-container d-flex align-items-center">
                <form action="shopp.php" method="GET" class="d-flex align-items-center w-100">
                    <i class="bi bi-search"></i>
                    <input class="form-control me-2 border-0" type="search" name="keyword" placeholder="Search..." aria-label="Search" value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>" />
                </form>
            </div>

            <!-- Navbar Toggler -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar Collapse -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Navigasi -->
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="shopp.php">Shop</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                </ul>

                <div class="d-flex align-items-center ms-auto">
                    <!-- Icon User login -->
                    <?php if (isset($_SESSION['username'])): ?>
                        <!-- Pas pengguna sudah login -->
                        <div class="user-menu position-relative">
                            <i class="bi bi-person me-2 user-icon"></i>
                            <ul class="dropdown-menu">
                                <li><a href="profile.php">My Account</a></li>
                                <li><a href="history_ord.php">History Order</a></li>
                                <li><a href="logout.php">Logout</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <!-- Pas pengguna belum login -->
                        <i class="bi bi-person me-2"></i>
                        <a href="login.php" class="me-4">Log In</a>
                    <?php endif; ?>

                    <!-- Icon keranjang -->
                    <a href="cart.php"><i class="bi bi-bag"></i></a>
                </div>

            </div>
        </div>
    </nav>

    <!-- Checkout Section -->
    <div class="checkout-container">
        <h2>Checkout</h2>

        <!-- Alamat Pengiriman -->
        <div class="address-section">
            <h3><i class="fas fa-map-marker-alt"></i> Alamat Pengiriman</h3>
            <div class="address-info">
                <p><strong><?= htmlspecialchars($dataPembeli['name']) ?></strong> (<?= htmlspecialchars($dataPembeli['nomor_telepon']) ?>)</p>
                <p>
                    <?= nl2br(htmlspecialchars($dataPembeli['alamat'])) ?>
                </p>
                <a href="profile.php" class="edit-link">Ubah</a>
            </div>
        </div>

        <!-- Produk Dipesan -->
        <div class="product-section">
            <table class="product-table">
                <thead>
                    <tr>
                        <th>Produk Dipesan</th>
                        <th>Harga Satuan</th>
                        <th>Jumlah</th>
                        <th>Subtotal Produk</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produkDipesan as $produk): ?>
                        <tr>
                            <td>
                                <?php if (strpos($produk['gambar_produk'], '/PBL_OKE/') === 0) {
                                    $pathGambar = $produk['gambar_produk'];
                                } else {
                                    // Jika tidak, tambahkan $baseUrl sebagai prefix
                                    $pathGambar = $baseUrl . '/' . ltrim($produk['gambar_produk'], '/');
                                }
                                ?>
                                <img src="<?= htmlspecialchars($pathGambar) ?>" alt="Product Image">
                                <span><?= htmlspecialchars($produk['nama_produk']) ?></span>
                            </td>
                            <td>Rp<?= number_format($produk['harga_produk'], 0, ',', '.') ?></td>
                            <td><?= $produk['jumlah_produk'] ?></td>
                            <td>Rp<?= number_format($produk['subtotal'], 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Opsi Pengiriman -->
        <div class="shipping-section">
            <h3>Opsi Pengiriman</h3>
            <table>
                <tr>
                    <td>Reguler</td>
                    <td>Rp15.000</td>
                </tr>
            </table>
        </div>

        <hr class="divider">

        <!-- Rincian Pembayaran -->
        <div class="payment-section">
            <h3>Rincian Pembayaran</h3>
            <table>
                <tr>
                    <td>Subtotal Produk</td>
                    <td>Rp<?= number_format($totalHargaProduk, 0, ',', '.') ?></td>
                </tr>
                <tr>
                    <td>Subtotal Pengiriman</td>
                    <td>Rp<?= number_format($biayaPengiriman, 0, ',', '.') ?></td>
                </tr>
                <tr>
                    <td><strong>Total Pembayaran</strong></td>
                    <td><strong>Rp<?= number_format($totalPembayaran, 0, ',', '.') ?></strong></td>
                </tr>
            </table>
        </div>

        <hr class="divider">

        <!-- Opsi Pengiriman -->
        <div class="shipping-section">
            <h3>Pembayaran</h3>
            <table>
                <tr>
                    <td><strong>COD</strong></td>
                </tr>
            </table>
        </div>

        <!-- Form untuk Buat Pesanan -->
        <form method="POST" action="pemesanan.php">
            <div class="button-container">
                <button type="submit" name="buat_pesanan" class="order-button">Place Order</button>
            </div>
        </form>
    </div>
</body>
<!-- Footer -->
<footer>
    <div class="footer-container">
        <div class="footer-left">
            <h2>THANK YOU</h2>
            <p>For Visiting Our Website</p>
            <div class="copyright">
                <span>©2024 by PureBeauty</span>
            </div>
        </div>

        <div class="footer-middle">
            <ul>
                <li><a href="about.php">ABOUT</a></li>
                <li><a href="history_ord.php">SHIPPING & RETURNS</a></li>
                <li><a href="contact.php">CONTACT</a></li>
            </ul>
        </div>

        <div class="footer-right">
            <ul>
                <li><a href="https://www.instagram.com/purebeautys_id?igsh=MTAybzdrZmQ3aW5jYQ==" target="_blank">INSTAGRAM</a></li>
                <li><a href="https://x.com/purebeautyid?t=wcEwXx9N9U5m4DQhaAGrRQ&s=09" target="_blank">TWITTER</a></li>
                <li><a href="#">EMAIL</a></li>
            </ul>
        </div>
    </div>
</footer>

</html>