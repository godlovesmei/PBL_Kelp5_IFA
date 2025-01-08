<?php
include 'koneksi.php';

session_start();
if (!isset($_SESSION['id_penjual'])) {
    header("Location: login.php");
    exit();
}

$search = '';
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = mysqli_real_escape_string($koneksi, $_GET['search']);
}

// Query SQL untuk menampilkan produk
$sql = "SELECT * FROM produk";

if ($search) {
    $sql .= " WHERE nama_produk LIKE '%$search%' OR kategori LIKE '%$search%'";
}

$result = mysqli_query($koneksi, $sql);

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
    <link rel="stylesheet" href="\PBL_OKE\assets\css\produk.css" />


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
        <a href="export_pro.php"><i class="fas fa-file-pdf"></i>Export Produk</a>
    </div>

    <!-- Content -->
    <div class="content">
        <h2><i class="fas fa-box me-2"></i> Daftar Produk</h2>
        <hr>
        <button type="button" class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#tambahDataModal">
            <i class="fas fa-plus-circle me-2"></i>TAMBAH PRODUK
        </button>

        <!-- Modal Tambah Data Produk -->
        <div class="modal fade" id="tambahDataModal" tabindex="-1" aria-labelledby="tambahDataLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tambahDataLabel">Tambah Data Produk</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="tambah_produk.php" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="nama_produk" class="form-label">Nama Produk</label>
                                <input type="text" class="form-control" id="nama_produk" name="nama_produk" required>
                            </div>
                            <div class="mb-3">
                                <label for="kategori" class="form-label">Kategori</label>
                                <input type="text" class="form-control" id="kategori" name="kategori" required>
                            </div>
                            <div class="mb-3">
                                <label for="gambar" class="form-label">Gambar Produk</label>
                                <input type="file" class="form-control" id="gambar" name="gambar" required>
                            </div>
                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi Produk</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="stok" class="form-label">Stok</label>
                                <input type="number" class="form-control" id="stok" name="stok" required>
                            </div>
                            <div class="mb-3">
                                <label for="harga" class="form-label">Harga</label>
                                <input type="number" class="form-control" id="harga" name="harga" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="search-container mb-4 d-flex justify-content-start">
            <form method="GET" action="produk1.php" class="search-form d-flex align-items-center">
                <input
                    type="text"
                    class="form-control search-input me-2"
                    name="search"
                    placeholder="Cari produk..."
                    value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <button type="submit" class="btn btn-primary search-button w-auto">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-success text-center">
                    <tr>
                        <th scope="col">NO</th>
                        <th scope="col">PRODUK</th>
                        <th scope="col"></th>
                        <th scope="col">PENJUALAN</th>
                        <th scope="col">HARGA</th>
                        <th scope="col">STOK</th>
                        <th scope="col">AKSI</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <?php
                    include 'koneksi.php';

                    $search = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : '';
                    $filter_kategori = isset($_GET['filter_kategori']) ? mysqli_real_escape_string($koneksi, $_GET['filter_kategori']) : '';

                    // Query dasar untuk produk
                    $query = "
                    SELECT 
                        p.id_produk, 
                        p.nama_produk, 
                        p.gambar_produk, 
                        p.harga_produk, 
                        p.stok_produk, 
                        p.kategori, 
                        p.deskripsi_produk,
                        COALESCE(SUM(j.jumlah_terjual), 0) AS total_penjualan
                    FROM produk p
                    LEFT JOIN penjualan j ON p.id_produk = j.id_produk
                    ";

                    // Tambahkan kondisi pencarian atau filter kategori
                    $conditions = [];
                    if (!empty($search)) {
                        $conditions[] = "(p.nama_produk LIKE '%$search%' OR p.kategori LIKE '%$search%')";
                    }
                    if (!empty($filter_kategori)) {
                        $conditions[] = "p.kategori = '$filter_kategori'";
                    }

                    // Gabungkan kondisi ke query
                    if (count($conditions) > 0) {
                        $query .= " WHERE " . implode(' AND ', $conditions);
                    }

                    $query .= " GROUP BY p.id_produk";

                    // Eksekusi query
                    $result = mysqli_query($koneksi, $query);

                    if (!$result) {
                        die("Query Error: " . mysqli_error($koneksi));
                    }

                    // Tampilkan data
                    $no = 1;
                    while ($data = mysqli_fetch_assoc($result)) {
                    ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td>
                                <img src="<?php echo $data['gambar_produk']; ?>" alt="Gambar Produk" width="50" class="img-thumbnail">
                            </td>
                            <td>
                                <?php
                                $max_length = 50; // Maksimal karakter
                                $nama_produk = strlen($data['nama_produk']) > $max_length
                                    ? substr($data['nama_produk'], 0, $max_length) . '...'
                                    : $data['nama_produk'];
                                echo $nama_produk;
                                ?>
                            </td>
                            <td><?php echo $data['total_penjualan']; ?> unit</td>
                            <td>Rp <?php echo number_format($data['harga_produk'], 0, ',', '.'); ?></td>
                            <td><?php echo $data['stok_produk']; ?></td>
                            <td>
                                <!-- Tombol Edit -->
                                <button class="btn btn-success btn-sm me-1 edit-button"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editDataModal"
                                    data-id-produk="<?php echo $data['id_produk']; ?>"
                                    data-nama-produk="<?php echo $data['nama_produk']; ?>"
                                    data-harga-produk="<?php echo $data['harga_produk']; ?>"
                                    data-stok-produk="<?php echo $data['stok_produk']; ?>"
                                    data-kategori="<?php echo $data['kategori']; ?>"
                                    data-deskripsi="<?php echo htmlspecialchars($data['deskripsi_produk']); ?>">
                                    <i class="fas fa-edit"></i> Edit
                                </button>

                                <!-- Tombol Delete -->
                                <a href="hapus_produk.php?id_produk=<?php echo $data['id_produk']; ?>"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin ingin menghapus produk ini?');">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </a>
                            </td>
                        </tr>
                    <?php
                    }

                    $koneksi->close();
                    ?>
                </tbody>
            </table>

            <!-- Modal Edit -->
            <div class="modal fade" id="editDataModal" tabindex="-1" aria-labelledby="editDataLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editDataLabel">Edit Data Produk</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="edit_produk.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" id="edit-id-produk" name="id_produk">

                                <div class="mb-3">
                                    <label for="edit-nama-produk" class="form-label">Nama Produk</label>
                                    <input type="text" class="form-control" id="edit-nama-produk" name="nama_produk" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit-kategori" class="form-label">Kategori</label>
                                    <input type="text" class="form-control" id="edit-kategori" name="kategori" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit-gambar-produk" class="form-label">Gambar Produk</label>
                                    <input type="file" class="form-control" id="edit-gambar-produk" name="gambar_produk">
                                </div>
                                <div class="mb-3">
                                    <label for="edit-deskripsi" class="form-label">Deskripsi Produk</label>
                                    <textarea class="form-control" id="edit-deskripsi" name="deskripsi_produk" rows="3" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="edit-harga-produk" class="form-label">Harga Produk</label>
                                    <input type="number" class="form-control" id="edit-harga-produk" name="harga_produk" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit-stok-produk" class="form-label">Stok Produk</label>
                                    <input type="number" class="form-control" id="edit-stok-produk" name="stok_produk" required>
                                </div>

                                <button type="submit" class="btn btn-primary">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editButtons = document.querySelectorAll('.edit-button');
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const idProduk = this.getAttribute('data-id-produk');
                    const namaProduk = this.getAttribute('data-nama-produk');
                    const hargaProduk = this.getAttribute('data-harga-produk');
                    const stokProduk = this.getAttribute('data-stok-produk');
                    const kategori = this.getAttribute('data-kategori');
                    const deskripsi = this.getAttribute('data-deskripsi');
                    const gambarProduk = this.getAttribute('data-gambar-produk');

                    // Set data ke modal
                    document.getElementById('edit-id-produk').value = idProduk;
                    document.getElementById('edit-nama-produk').value = namaProduk;
                    document.getElementById('edit-harga-produk').value = hargaProduk;
                    document.getElementById('edit-stok-produk').value = stokProduk;
                    document.getElementById('edit-kategori').value = kategori; // Set kategori
                    document.getElementById('edit-deskripsi').value = deskripsi; // Set deskripsi
                });
            });
        });
    </script>

</body>

</html>