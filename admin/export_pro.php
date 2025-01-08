<?php
require('fpdf/fpdf.php');
include 'koneksi.php';

// Query untuk mengambil data dari tabel produk
$query = "SELECT id_produk, nama_produk, kategori, gambar_produk, deskripsi_produk, stok_produk, harga_produk FROM produk";
$result = $koneksi->query($query);

// Membuat instance PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);

// Header tabel
$pdf->Cell(10, 10, 'No', 1, 0, 'C');
$pdf->Cell(80, 10, 'Nama Produk', 1, 0, 'C');
$pdf->Cell(30, 10, 'Harga', 1, 0, 'C');
$pdf->Cell(20, 10, 'Stok', 1, 0, 'C');
$pdf->Cell(20, 10, 'Penjualan', 1, 1, 'C');

// Isi tabel
$pdf->SetFont('Arial', '', 10);
$no = 1; // Variabel untuk nomor urut
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Query untuk menghitung total penjualan untuk produk ini
        $query_penjualan = "SELECT SUM(jumlah_terjual) as total_penjualan FROM penjualan WHERE id_produk = " . $row['id_produk'];
        $result_penjualan = $koneksi->query($query_penjualan);
        $penjualan = 0;
        if ($result_penjualan->num_rows > 0) {
            $data_penjualan = $result_penjualan->fetch_assoc();
            $penjualan = $data_penjualan['total_penjualan'];
        }

        // Menghitung tinggi baris yang dibutuhkan oleh kolom nama produk
        $cellWidth = 80; // Lebar kolom Nama Produk
        $cellHeight = 10; // Tinggi default baris
        if ($pdf->GetStringWidth($row['nama_produk']) > $cellWidth) {
            // Jika teks lebih panjang dari lebar kolom, hitung jumlah baris
            $lineCount = ceil($pdf->GetStringWidth($row['nama_produk']) / $cellWidth);
            $rowHeight = $lineCount * $cellHeight; // Hitung tinggi baris
        } else {
            $rowHeight = $cellHeight; // Jika tidak, gunakan tinggi default
        }

        // Output setiap sel dengan menyesuaikan tinggi baris
        $pdf->Cell(10, $rowHeight, $no, 1, 0, 'C'); // Kolom nomor
        $x = $pdf->GetX(); // Simpan posisi X sebelum MultiCell
        $y = $pdf->GetY(); // Simpan posisi Y sebelum MultiCell

        $pdf->MultiCell($cellWidth, $cellHeight, $row['nama_produk'], 1, 'L'); // Kolom nama produk

        $pdf->SetXY($x + $cellWidth, $y); // Geser posisi ke kolom berikutnya
        $pdf->Cell(30, $rowHeight, 'Rp ' . number_format($row['harga_produk'], 0, ',', '.'), 1, 0, 'R'); // Kolom harga
        $pdf->Cell(20, $rowHeight, $row['stok_produk'], 1, 0, 'C'); // Kolom stok
        $pdf->Cell(20, $rowHeight, $penjualan, 1, 1, 'C'); // Kolom penjualan

        $no++; // Tambahkan nomor urut
    }
} else {
    $pdf->Cell(160, 10, 'Tidak ada data', 1, 1, 'C');
}

// Output file PDF
$pdf->Output('D', 'produk_dari_db.pdf');

// Tutup koneksi
$koneksi->close();
