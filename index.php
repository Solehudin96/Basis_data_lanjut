<?php 
session_start(); // Memulai session

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Jika belum login, arahkan ke login
    exit();
}

$nama = $_SESSION['nama']; // Ambil username dari session
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <!-- Impor Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Konten -->
    <div class="container mt-5">
        <div class="alert alert-danger" role="alert"> <!-- Menggunakan alert-danger untuk warna merah -->
            <h3>Selamat datang, <?= htmlspecialchars($nama); ?>!</h3>
            <p>Anda telah berhasil login ke sistem.</p>
        </div>
    </div>

    <!-- Impor Bootstrap JS (Optional, jika diperlukan untuk beberapa komponen interaktif) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
