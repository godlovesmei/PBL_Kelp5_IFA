<?php
require('fpdf/fpdf.php');
include 'koneksi.php';

session_start();
if (!isset($_SESSION['id_pembeli'])) {
    header("Location: login.php");
    exit();
}

$id_transaksi = mysqli_real_escape_string($koneksi, $_GET['id_transaksi']); // Escape input
$id_pembeli = mysqli_real_escape_string($koneksi, $_SESSION['id_pembeli']); // Escape input

// Query pertama
$query = mysqli_query($koneksi, "
    SELECT pesanan.id_transaksi, pesanan.tanggal_pesanan, pembeli.username, pembeli.name, pembeli.alamat, pembeli.nomor_telepon, pesanan.status_pesanan, pesanan.total_harga, pesanan.produk_dipesan
    FROM pesanan
    JOIN pembeli ON pesanan.id_pembeli = pembeli.id_pembeli
    WHERE pesanan.id_transaksi = '$id_transaksi'
");
$data_transaksi = mysqli_fetch_assoc($query);

// Decode produk_dipesan (JSON) menjadi array PHP
$produkDipesan = json_decode($data_transaksi['produk_dipesan'], true);

// Periksa apakah decoding berhasil
if ($produkDipesan === null) {
    die("Error decoding JSON: " . json_last_error_msg());
}

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

// HEADER
$pdf->SetFont('Times', 'I', 24);
$pdf->Cell(0, 10, 'PureBeauty', 0, 1, 'L');
$pdf->Ln(5);

$pdf->SetFont('Arial', '', 12);
// "To" dan "Shipping Address" dari database
$pdf->Cell(0, 7, 'To: ' . $data_transaksi['name'], 0, 1);
$pdf->Cell(0, 7, 'Shipping Address:', 0, 1);
$pdf->MultiCell(0, 7, $data_transaksi['alamat'], 0, 'L');
$pdf->Cell(0, 7, 'Phone: ' . $data_transaksi['nomor_telepon'], 0, 1);
$pdf->Ln(5);

// INFORMASI TRANSAKSI
$pdf->Cell(50, 7, 'ID Transaksi', 0, 0);
$pdf->Cell(50, 7, ': ' . $data_transaksi['id_transaksi'], 0, 1);
$pdf->Cell(50, 7, 'Tanggal', 0, 0);
$pdf->Cell(50, 7, ': ' . $data_transaksi['tanggal_pesanan'], 0, 1);
$pdf->Cell(50, 7, 'Username', 0, 0);
$pdf->Cell(50, 7, ': ' . $data_transaksi['username'], 0, 1);
$pdf->Cell(50, 7, 'Status Pesanan', 0, 0);
$pdf->Cell(50, 7, ': ' . $data_transaksi['status_pesanan'], 0, 1);

$pdf->Ln(10);

// Header tabel produk
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(80, 10, "Nama Produk", 1, 0, 'C');
$pdf->Cell(35, 10, "Harga Satuan", 1, 0, 'C');
$pdf->Cell(20, 10, "Jumlah", 1, 0, 'C');
$pdf->Cell(45, 10, "Subtotal", 1, 1, 'C');

// Isi tabel produk
$pdf->SetFont('Arial', '', 10);
foreach ($produkDipesan as $produk) {
    $nama_produk = $produk['nama_produk'];
    $harga_produk = $produk['harga_produk'];
    $jumlah_produk = $produk['jumlah_produk'];
    $subtotal = $produk['subtotal'];

    // MultiCell untuk nama produk
    $cellWidth = 80;
    $cellHeight = 10;

    if ($pdf->GetStringWidth($nama_produk) > $cellWidth) {
        $line = ceil($pdf->GetStringWidth($nama_produk) / $cellWidth);
        $rowHeight = $line * $cellHeight;
    } else {
        $rowHeight = $cellHeight;
    }

    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->MultiCell($cellWidth, $cellHeight, $nama_produk, 1, 'L');
    $pdf->SetXY($x + $cellWidth, $y);

    // Kolom lainnya
    $pdf->Cell(35, $rowHeight, 'Rp ' . number_format($harga_produk, 0, ',', '.'), 1, 0, 'C');
    $pdf->Cell(20, $rowHeight, $jumlah_produk, 1, 0, 'C');
    $pdf->Cell(45, $rowHeight, 'Rp ' . number_format($subtotal, 0, ',', '.'), 1, 1, 'C');
}


// Total Harga
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(135, 10, "Total Harga", 1, 0, 'C');
$pdf->Cell(45, 10, 'Rp ' . number_format($data_transaksi['total_harga'], 0, ',', '.'), 1, 1, 'C');

$path_folder = 'invoices/';
$nama_file = $id_transaksi . '.pdf';
$full_path = $path_folder . $nama_file;

// Simpan PDF ke folder
$pdf->Output('F', $full_path);

$querySimpanPath = "UPDATE pesanan SET link_invoice = ? WHERE id_transaksi = ?";
$stmtSimpanPath = $koneksi->prepare($querySimpanPath);
$stmtSimpanPath->bind_param("ss", $full_path, $id_transaksi);
$stmtSimpanPath->execute();

if ($stmtSimpanPath->execute()) {
    // Tampilkan PDF langsung di browser
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="' . $nama_file . '"');
    readfile($full_path);
    exit();
} else {
    die("Gagal menyimpan link invoice ke database.");
}

$koneksi->close();
