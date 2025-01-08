<?php
include 'koneksi.php';

session_start();

$error_message = ''; // Variabel untuk menyimpan pesan error

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cek di tabel pembeli terlebih dahulu
    $query_pembeli = "SELECT id_pembeli, username, password FROM pembeli WHERE username = ?";
    $stmt_pembeli = $koneksi->prepare($query_pembeli);
    $stmt_pembeli->bind_param("s", $username);
    $stmt_pembeli->execute();
    $result_pembeli = $stmt_pembeli->get_result();

    // Jika username ditemukan di tabel pembeli
    if ($result_pembeli->num_rows > 0) {
        $row = $result_pembeli->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $row['username'];
            $_SESSION['id_pembeli'] = $row['id_pembeli'];
            header("Location: \PBL_OKE\pages\home.php");
            exit();
        } else {
            $error_message = "Password salah";
        }
    } else {
        // Jika username tidak ditemukan di pembeli, cek di tabel penjual
        $query_penjual = "SELECT id_penjual, username, password, role FROM penjual WHERE username = ?";
        $stmt_penjual = $koneksi->prepare($query_penjual);
        $stmt_penjual->bind_param("s", $username);
        $stmt_penjual->execute();
        $result_penjual = $stmt_penjual->get_result();

        if ($result_penjual->num_rows > 0) {
            $row = $result_penjual->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['username'] = $row['username'];
                $_SESSION['id_penjual'] = $row['id_penjual'];
                $_SESSION['role'] = $row['role']; // Menyimpan role penjual
                header("Location: \PBL_OKE\admin\dashboard1.php"); // Halaman khusus penjual
                exit();
            } else {
                $error_message = "Password salah";
            }
        } else {
            $error_message = "Username tidak ditemukan";
        }
    }

    $stmt_pembeli->close();
    $stmt_penjual->close();
}

$koneksi->close();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in to the site | PureBeauty</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&amp;display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="\PBL_OKE\assets\css\login.css" />
</head>

<body>
    <div class="form-container">
        <h1 class="logo">PureBeauty</h1>
        <p>Login to see our products!</p>

        <!-- Form login -->
        <form method="POST" action="login.php">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <div class="success-message" id="success-message" style="display: none;"> Welcome to PUREBEAUTY!</div>

        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?= htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        <br>
        <p>New here? <a href="regist.php">Create an account</a></p>
    </div>

</body>

</html>