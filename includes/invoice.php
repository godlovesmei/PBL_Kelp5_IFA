<?php
require('fpdf/fpdf.php');
include 'koneksi.php';

// Ambil data transaksi dan detail produk
$id_transaksi = 1; // Ganti dengan ID yang sesuai
$query = mysqli_query($koneksi, "
    SELECT t.id_transaksi, t.tanggal_transaksi, p.username, p.name, p.alamat, p.nomor_telepon, t.status_pesanan, t.total_belanja
    FROM transaksi t
    JOIN pembeli p ON t.id_pembeli = p.id_pembeli
    WHERE t.id_transaksi = $id_transaksi
");
$data_transaksi = mysqli_fetch_assoc($query);

$query_produk = mysqli_query($koneksi, "
    SELECT dp.jumlah, dp.subtotal, pr.nama_produk, pr.harga_produk
    FROM detail_transaksi dp
    JOIN produk pr ON dp.id_produk = pr.id_produk
    WHERE dp.id_transaksi = $id_transaksi
");

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
$pdf->Cell(50, 7, ': ' . $data_transaksi['tanggal_transaksi'], 0, 1);
$pdf->Cell(50, 7, 'Username', 0, 0);
$pdf->Cell(50, 7, ': ' . $data_transaksi['username'], 0, 1);
$pdf->Cell(50, 7, 'Status Pesanan', 0, 0);
$pdf->Cell(50, 7, ': ' . $data_transaksi['status_pesanan'], 0, 1);

$pdf->Ln(10);

// HEADER TABEL
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(80, 10, 'Product', 1, 0, 'C');
$pdf->Cell(35, 10, 'Unit Price', 1, 0, 'C');
$pdf->Cell(20, 10, 'Qty', 1, 0, 'C');
$pdf->Cell(45, 10, 'Paid Price', 1, 1, 'C');

// DETAIL PRODUK
$pdf->SetFont('Arial', '', 12);

while ($row = mysqli_fetch_assoc($query_produk)) {
    // Atur tinggi baris berdasarkan MultiCell
    $cellWidth = 80;
    $cellHeight = 10;

    // Periksa panjang teks produk
    if ($pdf->GetStringWidth($row['nama_produk']) > $cellWidth) {
        $line = ceil($pdf->GetStringWidth($row['nama_produk']) / $cellWidth);
        $rowHeight = $line * $cellHeight;
    } else {
        $rowHeight = $cellHeight;
    }

    // MultiCell untuk nama produk
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->MultiCell($cellWidth, $cellHeight, $row['nama_produk'], 1, 'L');

    // Pindah ke posisi berikutnya
    $pdf->SetXY($x + $cellWidth, $y);
    $pdf->Cell(35, $rowHeight, 'Rp ' . number_format($row['harga_produk'], 0, ',', '.'), 1, 0, 'C');
    $pdf->Cell(20, $rowHeight, $row['jumlah'], 1, 0, 'C');
    $pdf->Cell(45, $rowHeight, 'Rp ' . number_format($row['subtotal'], 0, ',', '.'), 1, 1, 'C');
}

// TOTAL
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(135, 10, 'Total', 1, 0, 'C');
$pdf->Cell(45, 10, 'Rp ' . number_format($data_transaksi['total_belanja'], 0, ',', '.'), 1, 1, 'C');

$pdf->Output();
