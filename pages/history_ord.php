<?php
include 'koneksi.php';

session_start();
if (!isset($_SESSION['id_pembeli'])) {
    header("Location: login.php");
    exit();
}

$id_pembeli = $_SESSION['id_pembeli'];

// Gabungkan query pembeli dan transaksi
$query_pembeli = "SELECT username, foto_profil FROM pembeli WHERE id_pembeli = ?";
$query_transaksi = "SELECT id_transaksi, tanggal_pesanan, total_harga, status_pesanan
                    FROM pesanan WHERE id_pembeli = ? ORDER BY tanggal_pesanan DESC";

// Ambil data pembeli
$stmt = $koneksi->prepare($query_pembeli);
$stmt->bind_param("i", $id_pembeli);
$stmt->execute();
$result_pembeli = $stmt->get_result();
$user = $result_pembeli->fetch_assoc();

// Ambil data transaksi
$stmt = $koneksi->prepare($query_transaksi);
$stmt->bind_param("i", $id_pembeli);
$stmt->execute();
$result = $stmt->get_result();

if (isset($_SESSION['flash_message'])) {
    echo "<div class='alert alert-success'>" . $_SESSION['flash_message'] . "</div>";
    unset($_SESSION['flash_message']); // Hapus pesan flash setelah ditampilkan
}


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
    <link rel="stylesheet" href="\PBL_OKE\assets\css\history_ord.css" />
    <title>Order History | PureBeauty</title>
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

    <div class="profile-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="user-info">
                <img src="<?= $user['foto_profil'] ? $user['foto_profil'] : '/PBL_OKE/assets/img/person-circle.svg'; ?>" alt="Profile Picture" class="editable-profile-picture">
                <p><?= htmlspecialchars($user['username'] ?: 'Your Name'); ?></p>
            </div>
            <nav class="menu">
                <ul>
                    <li>
                        <div><i class="fa-solid fa-user"></i> My Account</div>
                        <ul class="submenu">
                            <li><a href="profile.php">Profile</a></li>
                            <li><a href="change_pw.php">Change Password</a></li>
                        </ul>
                    </li>
                    <li><a href="history_ord.php"><i class="fa-solid fa-clock-rotate-left"></i> Order History</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Konten Utama -->
        <main class="main-content">
            <div class="order-history-container">
                <h2>Order History</h2>
                <table class="order-history-table">
                    <thead>
                        <tr>
                            <th>No. Pesanan</th>
                            <th>Tanggal</th>
                            <th>Jumlah Total</th>
                            <th>Status</th>
                            <th>Invoice</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['id_transaksi']) . "</td>";
                                echo "<td>" . htmlspecialchars(date("d/m/Y", strtotime($row['tanggal_pesanan']))) . "</td>";
                                // Pastikan kolom 'total_belanja' digunakan di sini
                                echo "<td>Rp." . number_format($row['total_harga'], 0, ',', '.') . "</td>";
                                // Pastikan kolom 'status_pesanan' digunakan di sini
                                echo "<td>" . htmlspecialchars($row['status_pesanan']) . "</td>";
                                echo "<td><a href='invoice.php?id_transaksi=" . $row['id_transaksi'] . "' target='_blank'>Detail</a></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>Belum ada riwayat pembelian.</td></tr>";
                        }

                        $koneksi->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
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