<?php
session_start();
include 'db.php'; // Menghubungkan ke database

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Jika belum login, arahkan ke login
    exit();
}

// Proses jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_fakultas = trim($_POST['nama_fakultas']);

    if (!empty($nama_fakultas)) {
        // Query untuk menambahkan data fakultas
        $sql = "INSERT INTO fakultas (nama_fakultas) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $nama_fakultas);

        if ($stmt->execute()) {
            // Berhasil, kembali ke halaman daftar fakultas
            $_SESSION['message'] = "Data fakultas berhasil ditambahkan!";
            header("Location: daftar_fakultas.php");
            exit();
        } else {
            $error = "Gagal menambahkan data fakultas. Silakan coba lagi.";
        }

        $stmt->close();
    } else {
        $error = "Nama fakultas tidak boleh kosong!";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Fakultas</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Konten -->
    <div class="container mt-4">
        <h3>Tambah Fakultas</h3>

        <!-- Pesan Error -->
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <!-- Form Tambah Fakultas -->
        <form method="POST" action="">
            <div class="mb-3">
                <label for="nama_fakultas" class="form-label">Nama Fakultas</label>
                <input type="text" class="form-control" id="nama_fakultas" name="nama_fakultas" placeholder="Masukkan nama fakultas">
            </div>
            <button type="submit" class="btn btn-primary">Tambah</button>
            <a href="daftar_fakultas.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
