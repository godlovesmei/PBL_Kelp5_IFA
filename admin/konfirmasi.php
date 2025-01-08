<?php
// Koneksi ke database
include('koneksi.php');

// Periksa apakah ID pesanan dikirimkan melalui POST
if (isset($_POST['id_pesanan'])) {
    $id_pesanan = $_POST['id_pesanan'];

    // Ambil data pesanan dari database berdasarkan id_pesanan
    $sql_pesanan = "SELECT * FROM pesanan WHERE id_pesanan = ?";
    $stmt_pesanan = $koneksi->prepare($sql_pesanan);
    $stmt_pesanan->bind_param("i", $id_pesanan);
    $stmt_pesanan->execute();
    $result_pesanan = $stmt_pesanan->get_result();

    if ($result_pesanan->num_rows > 0) {
        $pesanan = $result_pesanan->fetch_assoc();
        $produk_dipesan = json_decode($pesanan['produk_dipesan'], true); // Mendecode JSON produk_dipesan

        // Cek jika produk_dipesan mengandung data
        if (!empty($produk_dipesan)) {
            // Loop untuk setiap produk yang dipesan
            foreach ($produk_dipesan as $produk) {
                $nama_produk = $produk['nama_produk'];  // Ambil nama produk dari JSON
                $jumlah_produk = $produk['jumlah_produk'];  // Ambil jumlah produk

                // Cari ID Produk berdasarkan nama_produk di database
                $sql_produk = "SELECT id_produk, stok_produk FROM produk WHERE nama_produk = ?";
                $stmt_produk = $koneksi->prepare($sql_produk);
                $stmt_produk->bind_param("s", $nama_produk); // "s" untuk string
                $stmt_produk->execute();
                $result_produk = $stmt_produk->get_result();

                // Cek apakah produk ditemukan
                if ($result_produk->num_rows > 0) {
                    $data_produk = $result_produk->fetch_assoc();
                    $id_produk = $data_produk['id_produk'];
                    $stok_produk = $data_produk['stok_produk'];

                    // Validasi apakah stok cukup
                    if ($stok_produk >= $jumlah_produk) {
                        // Update stok produk
                        $sql_update_stok = "UPDATE produk SET stok_produk = stok_produk - ? WHERE id_produk = ?";
                        $stmt_stok = $koneksi->prepare($sql_update_stok);
                        $stmt_stok->bind_param("ii", $jumlah_produk, $id_produk);
                        $stmt_stok->execute();

                        // Update penjualan
                        $sql_update_penjualan = "INSERT INTO penjualan (id_produk, jumlah_terjual, tanggal) 
                                                 VALUES (?, ?, NOW())
                                                 ON DUPLICATE KEY UPDATE jumlah_terjual = jumlah_terjual + ?";
                        $stmt_penjualan = $koneksi->prepare($sql_update_penjualan);
                        $stmt_penjualan->bind_param("iii", $id_produk, $jumlah_produk, $jumlah_produk);
                        $stmt_penjualan->execute();
                    } else {
                        throw new Exception("Stok produk '{$nama_produk}' tidak cukup.");
                    }
                } else {
                    throw new Exception("Produk dengan nama '{$nama_produk}' tidak ditemukan.");
                }
            }

            // Update status pesanan ke "Dikemas"
            $sql_update_status = "UPDATE pesanan SET status_pesanan = 'Dikemas' WHERE id_pesanan = ?";
            $stmt_status = $koneksi->prepare($sql_update_status);
            $stmt_status->bind_param("i", $id_pesanan);
            $stmt_status->execute();

            // Redirect ke halaman daftar pesanan setelah konfirmasi
            header("Location: pesanan1.php");
            exit();
        } else {
            throw new Exception("Data pesanan tidak ditemukan.");
        }
    } else {
        throw new Exception("Pesanan tidak ditemukan.");
    }
} else {
    throw new Exception("ID pesanan tidak valid.");
}

$koneksi->close();
