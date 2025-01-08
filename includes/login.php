<?php
include 'koneksi.php';

session_start();

$error_message = ''; // Variabel untuk menyimpan pesan error

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT id_pembeli, username, password FROM pembeli WHERE username = ?";
    if ($stmt = $koneksi->prepare($query)) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['username'] = $row['username'];
                $_SESSION['id_pembeli'] = $row['id_pembeli'];
                header("Location: res_navP.php");
                exit();
            } else {
                $error_message = "Password salah";
            }
        } else {
            $error_message = "Username tidak ditemukan";
        }
        $stmt->close();
    } else {
        $error_message = "Terjadi kesalahan saat memproses login.";
    }
}
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