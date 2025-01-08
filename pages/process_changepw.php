<?php
session_start();
include 'koneksi.php'; // Koneksi ke database

// Cek apakah user sudah login
if (!isset($_SESSION['id_pembeli'])) {
    header("Location: login.php");
    exit();
}

// Ambil ID pengguna dari sesi
$id_pembeli = $_SESSION['id_pembeli'];

// Ambil data dari form
$current_password = $_POST['current-password'] ?? '';
$new_password = $_POST['new-password'] ?? '';
$confirm_password = $_POST['confirm-password'] ?? '';

// Validasi input
if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
    die("Semua field harus diisi!");
}

if ($new_password !== $confirm_password) {
    die("Password baru dan konfirmasi password tidak cocok!");
}

// Cek apakah password saat ini benar
$query = "SELECT password FROM pembeli WHERE id_pembeli = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("i", $id_pembeli);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    die("Pengguna tidak ditemukan!");
}

$stmt->bind_result($hashed_password);
$stmt->fetch();

if (!password_verify($current_password, $hashed_password)) {
    die("Password saat ini salah!");
}

// Hash password baru
$new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

// Update password di database
$update_query = "UPDATE pembeli SET password = ? WHERE id_pembeli = ?";
$update_stmt = $koneksi->prepare($update_query);
$update_stmt->bind_param("si", $new_hashed_password, $id_pembeli);

if ($update_stmt->execute()) {
    echo "Password berhasil diperbarui!";
    header("Location: profile.php");
} else {
    echo "Terjadi kesalahan saat memperbarui password.";
}

$koneksi->close();
