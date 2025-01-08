<?php
include 'koneksi.php';

session_start();
if (!isset($_SESSION['id_pembeli'])) {
    header("Location: login.php");
    exit();
}

$id_pembeli = $_SESSION['id_pembeli'];

if (isset($_POST['add_to_cart'])) {
    $id_produk = intval($_POST['id_produk']);
    $jumlah_produk = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

    if ($jumlah_produk < 1) {
        echo "Jumlah produk tidak valid.";
        exit();
    }

    // Cek stok produk
    $stmtStok = $koneksi->prepare("SELECT stok_produk FROM produk WHERE id_produk = ?");
    $stmtStok->bind_param("i", $id_produk);
    $stmtStok->execute();
    $resultStok = $stmtStok->get_result();
    $rowStok = $resultStok->fetch_assoc();
    $stok_produk = $rowStok['stok_produk'];

    if ($jumlah_produk > $stok_produk) {
        $_SESSION['error_message'] = "Kuantitas yang Anda pilih melebihi stok yang tersedia. Stok yang tersisa: $stok_produk.";
        header("Location: shop.php");
        exit();
    }

    // Cek stok produk
    $stmtStok = $koneksi->prepare("SELECT stok_produk FROM produk WHERE id_produk = ?");
    $stmtStok->bind_param("i", $id_produk);
    $stmtStok->execute();
    $resultStok = $stmtStok->get_result();
    $rowStok = $resultStok->fetch_assoc();
    $stok_produk = $rowStok['stok_produk'];

    // Hitung total jumlah produk di keranjang untuk produk ini
    $stmtCartQty = $koneksi->prepare("SELECT COALESCE(SUM(jumlah_produk), 0) AS total_qty FROM keranjang WHERE id_pembeli = ? AND id_produk = ?");
    $stmtCartQty->bind_param("ii", $id_pembeli, $id_produk);
    $stmtCartQty->execute();
    $resultCartQty = $stmtCartQty->get_result();
    $rowCartQty = $resultCartQty->fetch_assoc();
    $total_qty_in_cart = $rowCartQty['total_qty'];

    // Total kuantitas yang akan ada di keranjang setelah penambahan
    $new_total_qty = $total_qty_in_cart + $jumlah_produk;

    // Validasi stok berdasarkan total kuantitas yang ada di keranjang
    if ($new_total_qty > $stok_produk) {
        $_SESSION['error_message'] = "Kuantitas total yang ditambahkan ke keranjang melebihi stok. Stok tersisa: $stok_produk, jumlah di keranjang saat ini: $total_qty_in_cart.";
        header("Location: shop.php");
        exit();
    }

    // Cek harga produk
    $stmtHarga = $koneksi->prepare("SELECT harga_produk FROM produk WHERE id_produk = ?");
    $stmtHarga->bind_param("i", $id_produk);
    $stmtHarga->execute();
    $resultHarga = $stmtHarga->get_result();

    if ($resultHarga->num_rows > 0) {
        $rowHarga = $resultHarga->fetch_assoc();
        $harga_produk = $rowHarga['harga_produk'];

        // Cek apakah produk sudah ada di keranjang
        $stmtCheck = $koneksi->prepare("SELECT * FROM keranjang WHERE id_pembeli = ? AND id_produk = ?");
        $stmtCheck->bind_param("ii", $id_pembeli, $id_produk);
        $stmtCheck->execute();
        $resultCheck = $stmtCheck->get_result();

        if ($resultCheck->num_rows > 0) {
            // Update jumlah produk di keranjang
            $stmtUpdate = $koneksi->prepare("UPDATE keranjang SET jumlah_produk = jumlah_produk + ? WHERE id_pembeli = ? AND id_produk = ?");
            $stmtUpdate->bind_param("iii", $jumlah_produk, $id_pembeli, $id_produk);
            $stmtUpdate->execute();
        } else {
            // Insert produk baru ke keranjang
            $stmtInsert = $koneksi->prepare("INSERT INTO keranjang (id_pembeli, id_produk, jumlah_produk, harga_produk) VALUES (?, ?, ?, ?)");
            $stmtInsert->bind_param("iiid", $id_pembeli, $id_produk, $jumlah_produk, $harga_produk);
            $stmtInsert->execute();
        }

        $_SESSION['success_message'] = "Produk berhasil ditambahkan ke keranjang!";
    } else {
        $_SESSION['error_message'] = "Produk tidak ditemukan.";
    }

    header("Location: shop.php");
    exit();
}

if (isset($_SESSION['success_message'])) {
    echo "<div class='alert alert-success text-center' role='alert'>{$_SESSION['success_message']}</div>";
    unset($_SESSION['success_message']);
}

if (isset($_SESSION['error_message'])) {
    echo "<div class='alert alert-danger text-center' role='alert'>{$_SESSION['error_message']}</div>";
    unset($_SESSION['error_message']);
}

$keyword = isset($_GET['keyword']) ? $koneksi->real_escape_string($_GET['keyword']) : '';
$category = isset($_GET['category']) ? $koneksi->real_escape_string($_GET['category']) : '';

$sql = "SELECT * FROM produk";

if (!empty($keyword) && !empty($category)) {
    $sql .= " WHERE produk.nama_produk LIKE '%$keyword%' AND produk.kategori = '$category'";
} elseif (!empty($keyword)) {
    $sql .= " WHERE produk.nama_produk LIKE '%$keyword%'";
} elseif (!empty($category)) {
    $sql .= " WHERE produk.kategori = '$category'";
}

$result = $koneksi->query($sql);
$koneksi->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
        rel="stylesheet" />
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" />
    <link
        href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Belleza&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="\PBL_OKE\assets\css\shop.css" />
    <title>Shop | PureBeauty</title>
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

    <!-- Browse by and Product Grid -->
    <div class="shop-container d-flex">
        <!-- Tombol Filter untuk Layar Kecil -->
        <div class="filter-button d-md-none" id="filter-toggle">
            <i class="fa-solid fa-filter"></i>
        </div>

        <!-- Sidebar Kategori -->
        <div class="sidebar hidden d-md-block" id="sidebar"> <!-- Tambahkan class="hidden" -->
            <h4>Browse by</h4>
            <ul>
                <li><a href="shop.php">All Products</a></li>
                <li><a href="shop.php?category=Face Mask">Face Mask</a></li>
                <li><a href="shop.php?category=Moisturizer">Moisturizer</a></li>
                <li><a href="shop.php?category=Serum">Serum</a></li>
                <li><a href="shop.php?category=Sunscreen">Sunscreen</a></li>
            </ul>
        </div>

        <!-- Grid Produk -->
        <div class="shop-container flex-grow-1">
            <div class="container">
                <div class="row row-cols-2 row-cols-sm-2 row-cols-md-4 row-cols-lg-5 row-cols-xl-6 g-4">
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $id_produk = $row['id_produk'];
                            $nama_produk = $row['nama_produk'];
                            $harga_produk = number_format($row['harga_produk'], 0, ',', '.');
                            $gambar_produk = $row['gambar_produk'];
                            $stok_produk = $row['stok_produk'];

                            echo "
                <div class='col'>
                    <div class='product-item text-center'>
                        <a href='detail_produk.php?id=$id_produk'>
                                <img src='" . htmlspecialchars($gambar_produk) . "' alt='" . htmlspecialchars($nama_produk) . "' class='img-fluid' />
                            </a>
                            <h5 class='mt-2'>$nama_produk</h5>
                            <p>Rp$harga_produk</p>
                            <div class='quantity-control d-flex align-items-center justify-content-center' data-max-stock='$stok_produk'>
                                <button type='button' class='btn btn-quantity decrement'>-</button>
                                <span class='quantity mx-2'>1</span>
                                <button type='button' class='btn btn-quantity increment'>+</button>
                            </div>
                            <form method='POST' action='shop.php'>
                                <input type='hidden' name='id_produk' value='$id_produk'>
                                <input type='hidden' name='quantity' value='1' class='quantity-input'> <!-- Akan diperbarui oleh JS -->
                                <button type='submit' name='add_to_cart' class='btn btn-add-to-cart mt-2'>Add to Cart</button>
                            </form>
                    </div>
                </div>
                ";
                        }
                    } else {
                        echo "<p class='text-center'>No products available.</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Import Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="\PBL_OKE\assets\js\tes.js"></script>
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