<?php
// Mulai sesi
session_start();

// Sertakan file koneksi
include_once 'koneksi.php';

// Cek apakah pengguna sudah login
if (isset($_SESSION['username'])) {
    if (basename($_SERVER['PHP_SELF']) !== 'index.html') {
        header("Location: index.html");
        exit();
    }
}

$error = "";

// Proses form login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk memeriksa username di database
    $stmt = $conn->prepare("SELECT password FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        // Verifikasi password
        if (hash('sha256', $password) === $hashed_password) {
            // Login berhasil
            $_SESSION['username'] = $username;
            $_SESSION['admin'] = true;
            setcookie("username", $username, time() + 60, "/"); // Cookie berlaku 1 menit
            header("Location: index.html");
            exit();
        } else {
            // Password salah
            $error = "Password salah.";
        }
    } else {
        // Cek jika username tidak ditemukan
        $stmt = $conn->prepare("SELECT username FROM admin WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 0) {
            // Username tidak ditemukan
            $error = "Username salah.";
        } else {
            // Jika username ditemukan tapi password salah
            $error = "Username dan password salah.";
        }
    }
    $stmt->close();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="Assets/css/login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="body-login bg-dark">
<div class="container mt-5">
    <h2 class="text-center"><strong>LOGIN ADMIN</strong></h2>
    <?php if ($error): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>
    <form method="POST" action="login.php" class="login-form">
        <div class="mb-3">
            <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
        </div>
        <div class="mb-3">
            <input type="password" class="form-control " id="password" name="password" placeholder="Password" required>
        </div>
        <div class="text-center">
          <button type="submit" class="btn-login">Login</button>
          <button type="reset" class="btn-cancel">Cancel</button>
        </div>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>