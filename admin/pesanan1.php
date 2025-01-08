<?php
include 'koneksi.php';

session_start();
if (!isset($_SESSION['id_penjual'])) {
    header("Location: login.php");
    exit();
}

$status_filter = isset($_GET['status']) ? trim($_GET['status']) : 'Semua';
$allowed_status = ['Menunggu Konfirmasi', 'Dikemas', 'Dikirim', 'Selesai', 'Dibatalkan', 'Semua'];
if (!in_array($status_filter, $allowed_status)) {
    $status_filter = 'Semua';
}

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$sql = "SELECT id_pesanan, id_transaksi, id_pembeli, produk_dipesan, total_harga, status_pesanan, link_invoice, alamat_pengiriman, tanggal_pesanan FROM pesanan";
// Menambahkan kondisi filter berdasarkan status
$conditions = [];
$params = [];

// Menambahkan filter status pesanan
if ($status_filter !== 'Semua') {
    $conditions[] = "status_pesanan = ?";
    $params[] = $status_filter;
}

// Menambahkan pencarian berdasarkan nama produk atau id transaksi atau username pembeli
if (!empty($search)) {
    $search_condition = "(
        LOWER(produk_dipesan) LIKE LOWER(?) OR
        LOWER(id_transaksi) LIKE LOWER(?) OR
        id_pembeli IN (SELECT id_pembeli FROM pembeli WHERE LOWER(username) LIKE LOWER(?))
    )";
    $conditions[] = $search_condition;
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

// Jika ada kondisi pencarian atau filter status, gabungkan dengan AND
if (count($conditions) > 0) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

// Menambahkan urutan berdasarkan tanggal pesanan
$sql .= " ORDER BY tanggal_pesanan DESC";

// Menyiapkan statement SQL
$stmt = $koneksi->prepare($sql);

// Mengikat parameter untuk pencarian dan status
if (count($params) > 0) {
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
}

// Eksekusi query
if ($stmt->execute()) {
    $result = $stmt->get_result();
} else {
    die("Query Error: " . $stmt->error);
}
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
    <link rel="stylesheet" href="\PBL_OKE\assets\css\pesanan.css" />
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

    <div class="content">
        <h2><i class="fas fa-clipboard-list"></i> Daftar Pesanan</h2>
        <hr>
        <div class="search-container mb-4 d-flex justify-content-start">
            <form method="GET" action="pesanan1.php" class="search-form d-flex">
                <input
                    type="text"
                    class="form-control search-input me-2"
                    name="search"
                    placeholder="Cari pesanan..."
                    value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <button type="submit" class="btn btn-primary search-button">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>

        <div class="tabs">
            <a href="?status=" class="tab <?= $status_filter == 'Semua' ? 'active' : '' ?>">Semua</a>
            <a href="?status=Menunggu%20Konfirmasi" class="tab <?= $status_filter == 'Menunggu Konfirmasi' ? 'active' : '' ?>">Konfirmasi</a>
            <a href="?status=Dikemas" class="tab <?= $status_filter == 'Dikemas' ? 'active' : '' ?>">Dikemas</a>
            <a href="?status=Dikirim" class="tab <?= $status_filter == 'Dikirim' ? 'active' : '' ?>">Dikirim</a>
            <a href="?status=Selesai" class="tab <?= $status_filter == 'Selesai' ? 'active' : '' ?>">Selesai</a>
            <a href="?status=Dibatalkan" class="tab <?= $status_filter == 'Dibatalkan' ? 'active' : '' ?>">Dibatalkan</a>
        </div>

        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Produk</th>
                        <th>Total Pesanan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        $no = 1;
                        while ($row = $result->fetch_assoc()) {
                            // Query untuk mendapatkan username
                            $id_pembeli = $row['id_pembeli'];
                            $query_pembeli = "SELECT username FROM pembeli WHERE id_pembeli = $id_pembeli"; // Sesuaikan nama tabel Anda
                            $result_pembeli = $koneksi->query($query_pembeli);
                            $username = $result_pembeli->num_rows > 0 ? $result_pembeli->fetch_assoc()['username'] : 'Tidak diketahui';

                            $produk_dipesan = json_decode($row['produk_dipesan'], true);
                            echo "<tr>";
                            echo "<td>" . $no++ . "</td>";
                            echo "<td>";
                            // Tampilkan username dan ID transaksi dengan tata letak lebih rapi
                            echo "<div style='display: flex; justify-content: space-between; align-items: center; background-color: #f9f9f9; padding: 10px; border-radius: 1px; border: 1px solid #ddd; margin-bottom: 10px;'>
                            <span style='font-size: 16px; font-weight: bold; color: #333;'>$username</span>
                            <span style='font-size: 14px; font-style: italic; color: #666;'>ID Transaksi: " . $row['id_transaksi'] . "</span>
                            </div>";

                            // Tampilkan produk
                            if ($produk_dipesan) {
                                foreach ($produk_dipesan as $produk) {
                                    $nama_produk = mb_substr($produk['nama_produk'], 0, 50) . (strlen($produk['nama_produk']) > 50 ? '...' : '');
                                    echo "<div style='display: flex; align-items: center; margin-bottom: 5px;'>";
                                    echo "<img src='" . $produk['gambar_produk'] . "' alt='Produk' width='100' height='100' style='margin-right: 10px;'>";
                                    echo "<span>" . $nama_produk . "</span>";
                                    echo "</div>";
                                }
                            } else {
                                echo "Data produk tidak valid.";
                            }
                            echo "</td>";
                            echo "<td>Rp " . number_format($row['total_harga'], 0, ',', '.') . "</td>";
                            echo "<td>" . $row['status_pesanan'] . "</td>";
                            echo "<td>";
                            echo "<div class='action-buttons'>";
                            $link_invoice = "/PBL_OKE/pages/" . $row['link_invoice']; // Sesuaikan path proyek
                            $file_path = $_SERVER['DOCUMENT_ROOT'] . $link_invoice;
                            if ($row['status_pesanan'] == 'Menunggu Konfirmasi') {
                                echo "<button class='btn confirm' onclick='openConfirmModal(" . $row['id_pesanan'] . ")'>Confirm</button>";
                                echo "<button class='btn reject' onclick='openRejectModal(" . $row['id_pesanan'] . ")'>Reject</button>";
                                echo "<a href='" . $link_invoice . "' target='_blank'><button class='btn print'>Print</button></a>";
                            } else {
                                echo "<a href='" . $link_invoice . "' target='_blank'><button class='btn print'>Print</button></a>";
                            }
                            echo "</div>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>Tidak ada pesanan yang cocok dengan pencarian.</td></tr>";
                    }

                    $koneksi->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Confirm -->
    <div id="modalConfirm" class="modal">
        <div class="modal-content">
            <h4>Konfirmasi Pesanan</h4>
            <p>Apakah Anda yakin ingin mengonfirmasi pesanan ini?</p>
            <form action="konfirmasi.php" method="POST">
                <input type="hidden" name="id_pesanan" id="confirmId" />
                <button type="submit" class="btn confirm">Confirm</button>
                <button type="button" class="btn cancel" onclick="closeModal('modalConfirm')">Cancel</button>
            </form>
        </div>
    </div>

    <!-- Modal Reject -->
    <div id="modalReject" class="modal">
        <div class="modal-content">
            <h4>Tolak Pesanan</h4>
            <p>Apakah Anda yakin ingin menolak pesanan ini?</p>
            <form action="tolak.php" method="POST">
                <input type="hidden" name="id_pesanan" id="rejectId" />
                <button type="submit" class="btn reject">Reject</button>
                <button type="button" class="btn cancel" onclick="closeModal('modalReject')">Cancel</button>
            </form>
        </div>
    </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="\PBL_OKE\assets\js\pesanan.js"></script>

</body>

</html>