<?php
include 'koneksi.php';

session_start();
if (!isset($_SESSION['id_pembeli'])) {
    header("Location: login.php");
    exit();
}

// Cek ID produk 
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Produk tidak ditemukan.";
    exit();
}

$id_produk = intval($_GET['id']);

// Ambil detail produk dari database
$stmt = $koneksi->prepare("SELECT * FROM produk WHERE id_produk = ?");
$stmt->bind_param("i", $id_produk);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Produk tidak ditemukan.";
    exit();
}

$produk = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $id_pembeli = $_SESSION['id_pembeli'];
    $id_produk = intval($_POST['id_produk']);
    $quantity = intval($_POST['quantity']);

    // Ambil stok produk dan harga dari database
    $stmt = $koneksi->prepare("SELECT stok_produk, harga_produk FROM produk WHERE id_produk = ?");
    $stmt->bind_param('i', $id_produk);
    $stmt->execute();
    $result = $stmt->get_result();
    $produk = $result->fetch_assoc();

    if (!$produk) {
        die("Produk tidak ditemukan.");
    }

    $stok_produk = $produk['stok_produk'];
    $harga_produk = $produk['harga_produk'];

    // Cek jumlah produk yang sudah ada di keranjang
    $stmtCart = $koneksi->prepare("SELECT COALESCE(SUM(jumlah_produk), 0) AS total_qty FROM keranjang WHERE id_pembeli = ? AND id_produk = ?");
    $stmtCart->bind_param('ii', $id_pembeli, $id_produk);
    $stmtCart->execute();
    $resultCart = $stmtCart->get_result();
    $rowCart = $resultCart->fetch_assoc();
    $total_qty_in_cart = $rowCart['total_qty'];

    // Hitung total kuantitas setelah penambahan
    $new_total_qty = $total_qty_in_cart + $quantity;

    // Validasi kuantitas total
    if ($new_total_qty > $stok_produk) {
        die("Jumlah total produk di keranjang melebihi stok yang tersedia. 
        Stok tersedia: $stok_produk, di keranjang: $total_qty_in_cart.");
    }

    // Masukkan data ke tabel keranjang
    $stmt = $koneksi->prepare("INSERT INTO keranjang (id_pembeli, id_produk, jumlah_produk, harga_produk) VALUES (?, ?, ?, ?) 
        ON DUPLICATE KEY UPDATE jumlah_produk = jumlah_produk + ?");
    $stmt->bind_param('siiii', $id_pembeli, $id_produk, $quantity, $harga_produk, $quantity);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        header("Location: cart.php"); // Redirect ke halaman keranjang
        exit();
    } else {
        die("Gagal menambahkan ke keranjang.");
    }
}

$koneksi->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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
    <link rel="stylesheet" href="\PBL_OKE\assets\css\det_pro.css" />
    <title>Product Details | PureBeauty</title>
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
                <form action="shop.php" method="GET" class="d-flex align-items-center w-100">
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
                    <li class="nav-item"><a class="nav-link" href="shop.php">Shop</a></li>
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

    <!-- Kontainer Detail Produk -->
    <div class="product-detail-container">
        <!-- Gambar Produk -->
        <div class="product-image">
            <?php
            $path_gambar = $produk['gambar_produk'];

            if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $path_gambar)) {
                echo "<p>File gambar tidak ditemukan: $path_gambar</p>";
            } else {
                echo "<img src='$path_gambar' alt='" . htmlspecialchars($produk['nama_produk']) . "' />";
            }
            ?>
        </div>

        <!-- Informasi Produk -->
        <div class="product-info">
            <h2 class="product-title"><?= htmlspecialchars($produk['nama_produk']) ?></h2>
            <p class="product-price">Rp<?= number_format($produk['harga_produk'], 0, ',', '.') ?></p>
            <p class="product-description"><?= nl2br(htmlspecialchars($produk['deskripsi_produk'])) ?></p>

            <div class="quantity-control" data-max-stock="<?= $produk['stok_produk'] ?>">
                <button class="btn btn-outline-secondary btn-sm">-</button>
                <span class="quantity">1</span>
                <button class="btn btn-outline-secondary btn-sm">+</button>
            </div>

            <form method="POST" action="shop.php">
                <input type="hidden" name="id_produk" value="<?= $id_produk ?>">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" name="add_to_cart" class="btn-add-to-cart">Add to Cart</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="\PBL_OKE\assets\js\detail_produk.js"></script>
</body>

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