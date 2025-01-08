<?php
session_start();
include 'koneksi.php'; // Pastikan koneksi database terhubung

// Periksa apakah form sudah dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pembeli = $_SESSION['id_pembeli']; // Pastikan sesi sudah diinisialisasi
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $foto_profil = $_FILES['profile_picture']['name'];

    // Direktori untuk menyimpan gambar
    $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/PBL_OKE/admin/uploads/profile_pictures/';

    // Periksa apakah folder upload ada
    if (!is_dir($upload_dir)) {
        die("Folder $upload_dir tidak ditemukan. Harap periksa struktur direktori Anda.");
    }

    // Proses upload file foto profil jika ada
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $file_name = uniqid('profile-picture') . '_' . basename($_FILES['profile_picture']['name']);
        $target_file = $upload_dir . $file_name;

        // Validasi jenis file
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        if (!in_array($file_type, ['jpg', 'jpeg', 'png', 'gif'])) {
            die("Hanya file gambar yang diizinkan.");
        }

        // Upload file ke folder tujuan
        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
            // Simpan path relatif untuk database
            $foto_profil = '/PBL_OKE/admin/uploads/profile_pictures/' . $file_name;
        } else {
            die("Gagal mengunggah foto profil.");
        }
    }

    // Update data di database
    $query = "UPDATE pembeli SET name = ?, nomor_telepon = ?, alamat = ?, foto_profil = ? WHERE id_pembeli = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param('ssssi', $name, $phone, $address, $foto_profil, $id_pembeli);

    if ($stmt->execute()) {
        echo "Profil berhasil diperbarui.";
        header('Location: profile.php'); // Redirect ke halaman profil
        exit;
    } else {
        die("Terjadi kesalahan saat memperbarui profil.");
    }
}

$koneksi->close();
