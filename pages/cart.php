<?php
include 'koneksi.php';

session_start();
if (!isset($_SESSION['id_pembeli'])) {
  header("Location: login.php");
  exit();
}

$id_pembeli = $_SESSION['id_pembeli']; // Ambil id_pembeli dari session
$queryCart = "SELECT k.id_produk, k.jumlah_produk, p.harga_produk, 
                     p.nama_produk, p.gambar_produk, p.stok_produk 
              FROM keranjang k 
              JOIN produk p ON k.id_produk = p.id_produk 
              WHERE k.id_pembeli = '$id_pembeli'";

$resultCart = mysqli_query($koneksi, $queryCart);
$totalHarga = 0;

function potongNamaProduk($nama, $maxKata = 5)
{
  $kata = explode(' ', $nama);
  if (count($kata) > $maxKata) {
    return implode(' ', array_slice($kata, 0, $maxKata)) . '...';
  }
  return $nama;
}

if (isset($_GET['status'])) {
  if ($_GET['status'] === 'sukses') {
    echo '<div class="alert alert-success">Produk berhasil dihapus dari keranjang!</div>';
  } elseif ($_GET['status'] === 'gagal') {
    echo '<div class="alert alert-danger">Gagal menghapus produk dari keranjang.</div>';
  } elseif ($_GET['status'] === 'error') {
    echo '<div class="alert alert-warning">Terjadi kesalahan. Silakan coba lagi.</div>';
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
    rel="stylesheet" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Belleza&display=swap"
    rel="stylesheet" />
  <link
    href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap"
    rel="stylesheet" />
  <link rel="stylesheet" href="\PBL_OKE\assets\css\cart.css" />

  <title>My Cart | PureBeauty</title>
  <!-- Font Awesome for Back Icon -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
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

  <!-- Back Button Icon -->

  <div class="container my-5">
    <div class="back-button">
      <i> My Cart</i>
    </div>
    <table class="table table-bordered text-center">
      <thead>
        <tr>
          <th>Gambar Produk</th>
          <th>Nama Produk</th>
          <th>Harga Satuan</th>
          <th>Kuantitas</th>
          <th>Total Harga</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if (mysqli_num_rows($resultCart) > 0) {
          while ($row = mysqli_fetch_assoc($resultCart)) {
            $subtotal = $row['harga_produk'] * $row['jumlah_produk'];
            $totalHarga += $subtotal;

            echo '<tr>';
            echo '<td>';
            $gambarPath = '/PBL_OKE/admin/uploads/' . basename($row['gambar_produk']);
            if (!empty($row['gambar_produk']) && file_exists($_SERVER['DOCUMENT_ROOT'] . $gambarPath)) {
              echo '<img src="' . htmlspecialchars($gambarPath) . '" alt="Gambar Produk" style="width: 100px; height: 100px;">';
            } else {
              echo '<img src="/PBL_OKE/assets/images/default-image.jpg" alt="Gambar Tidak Tersedia" style="width: 100px; height: 100px;">';
            }
            echo '</td>';
            echo '<td>' . htmlspecialchars(potongNamaProduk($row['nama_produk'])) . '</td>';
            echo '<td class="product-price" data-harga-produk="' . htmlspecialchars($row['harga_produk']) . '">
            Rp' . number_format($row['harga_produk'], 0, ',', '.') . '
          </td>';
            echo '<td>
              <div class="d-flex justify-content-center align-items-center">
                  <button class="btn btn-sm btn-outline-secondary mx-1 btn-quantity" 
                          data-action="minus" 
                          data-id-produk="' . $row['id_produk'] . '" 
                          data-stok-produk="' . $row['stok_produk'] . '">-</button>
                  <span class="quantity-value">' . $row['jumlah_produk'] . '</span>
                  <button class="btn btn-sm btn-outline-secondary mx-1 btn-quantity" 
                          data-action="plus" 
                          data-id-produk="' . $row['id_produk'] . '" 
                          data-stok-produk="' . $row['stok_produk'] . '">+</button>
              </div>
          </td>';
            echo '<td class="product-subtotal" data-harga-produk="' . htmlspecialchars($row['harga_produk']) . '">
            Rp' . number_format($subtotal, 0, ',', '.') . '
          </td>';
            echo '<td><a href="hapus_keranjang.php?id_produk=' . $row['id_produk'] . '" class="btn btn-danger btn-sm">Hapus</a></td>';
            echo '</tr>';
          }
        } else {
          echo '<tr><td colspan="6">Keranjang Anda kosong.</td></tr>';
        }

        $koneksi->close();
        ?>
      </tbody>
    </table>
    <div class="cart-summary">
      <h3>Total: <span id="cart-total">Rp<?= number_format($totalHarga, 0, ',', '.') ?></span></h3>
      <form action="pemesanan.php" method="POST">
        <button class="checkout-button">Checkout</button>
      </form>
    </div>

  </div>

  <!-- Toast Container -->
  <div aria-live="polite" aria-atomic="true" class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="toastNotification" class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body">
          Stok produk tidak mencukupi. Silakan kurangi jumlah produk.
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script src="\PBL_OKE\assets\js\cart.js"></script>
</body>
<footer>
  <div class="footer-container">
    <div class="footer-left">
      <h2>THANK YOU</h2>
      <p>For Visiting Our Website!</p>
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
        <li>
          <a
            href="https://www.instagram.com/purebeautys_id?igsh=MTAybzdrZmQ3aW5jYQ=="
            target="_blank">INSTAGRAM</a>
        </li>
        <li>
          <a
            href="https://x.com/purebeautyid?t=wcEwXx9N9U5m4DQhaAGrRQ&s=09"
            target="_blank">TWITTER</a>
        </li>
        <li><a href="mailto:purebeautysofficial@gmail.com">EMAIL</a></li>
      </ul>
    </div>
  </div>
</footer>

</html>