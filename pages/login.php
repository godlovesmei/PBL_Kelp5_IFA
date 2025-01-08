<?php
include 'koneksi.php';

session_start();

$error_message = ''; // Variabel untuk menyimpan pesan error

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (checkUser($username, $password, 'pembeli')) {
        // Jika pengguna adalah pembeli dan login berhasil, arahkan ke halaman home.
        header("Location: \PBL_OKE\pages\home.php");
        exit();
    } elseif (checkUser($username, $password, 'penjual')) {
        // Jika pengguna adalah penjual dan login berhasil, arahkan ke halaman dashboard penjual.
        header("Location: \PBL_OKE\admin\dashboard1.php");
        exit();
    } else {
        // Jika username atau password salah.
        $error_message = "Username atau password salah";
    }
}

/**
 * Fungsi untuk memeriksa username dan password di tabel pembeli atau penjual.
 * 
 * @param string $username Username yang dimasukkan pengguna.
 * @param string $password Password yang dimasukkan pengguna.
 * @param string $role Role pengguna (pembeli atau penjual).
 * @return bool Mengembalikan true jika login berhasil, false jika gagal.
 */
function checkUser($username, $password, $role) {
    global $koneksi;

    // Menentukan query berdasarkan role pengguna.
    $query = $role === 'pembeli' ? 
             "SELECT id_pembeli AS id, username, password FROM pembeli WHERE username = ?" :
             "SELECT id_penjual AS id, username, password, role FROM penjual WHERE username = ?";
    
    // Menyiapkan dan menjalankan query.
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Menyimpan informasi pengguna dalam sesi.
            $_SESSION['username'] = $row['username'];
            $_SESSION[$role === 'pembeli' ? 'id_pembeli' : 'id_penjual'] = $row['id'];
            if ($role === 'penjual') {
                $_SESSION['role'] = $row['role']; // Menyimpan role penjual
            }
            return true;
        }
    }

    $stmt->close();
    return false;
}

// Menutup koneksi ke database.
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

        <!-- Menampilkan pesan error jika ada. -->
        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?= htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        <br>
        <p>New here? <a href="regist.php">Create an account</a></p>
    </div>

</body>

</html>
