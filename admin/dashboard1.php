<?php
include 'koneksi.php';

session_start();
if (!isset($_SESSION['id_penjual'])) {
    header("Location: login.php");
    exit();
}

// Query untuk menghitung total produk
$sql_produk = "SELECT COUNT(*) as total_produk FROM produk";
$result_produk = $koneksi->query($sql_produk);
$row_produk = $result_produk->fetch_assoc();
$total_produk = $row_produk['total_produk'];

// Query untuk menghitung total pesanan
$sql_pesanan = "SELECT COUNT(*) as total_pesanan FROM pesanan";
$result_pesanan = $koneksi->query($sql_pesanan);
$row_pesanan = $result_pesanan->fetch_assoc();
$total_pesanan = $row_pesanan['total_pesanan'];

// Query untuk menghitung total promosi
$sql_promosi = "SELECT COUNT(*) as total_promosi FROM promosi";
$result_promosi = $koneksi->query($sql_promosi);
$row_promosi = $result_promosi->fetch_assoc();
$total_promosi = $row_promosi['total_promosi'];

$koneksi->close();
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="\PBL_OKE\assets\css\dashboard.css" />
    <title>PureBeauty Seller Center</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <div class="branding">
                <h1 class="title">PureBeauty</h1>
                <h2 class="subtitle">Seller Center</h2>
            </div>
            <!-- Button toggle untuk layar kecil -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <div class="ms-auto d-flex align-items-center">
                    <div class="icon d-flex align-items-center">
                        <a href="logout.php" class="btn btn-danger btn-sm text-white d-flex align-items-center">
                            <i class="fas fa-sign-out-alt me-1"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="sidebar">
        <a href="dashboard1.php" class="active"><i class="fas fa-home"></i> Dashboard</a>
        <a href="produk1.php"><i class="fas fa-box"></i> Daftar Produk</a>
        <a href="pesanan1.php"><i class="fas fa-clipboard-list"></i> Daftar Pesanan</a>
    </div>

    <!-- Content -->
    <div class="content">
        <h2><i class="fas fa-clipboard-list"></i>Dashboard</h2>
        <hr>

        <!-- Dashboard Cards -->
        <div class="row cards">
            <div class="col-md-4">
                <div class="card bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-box me-2"></i>Produk</h5>
                        <p class="card-text">Total: <?php echo $total_produk; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-clipboard-list me-2"></i>Pesanan</h5>
                        <p class="card-text">Total: <?php echo $total_pesanan; ?></p>
                    </div>
                </div>
            </div>
        </div>



    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>