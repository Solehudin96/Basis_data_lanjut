<?php
session_start();
include 'db.php'; // Menghubungkan ke database

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Jika belum login, arahkan ke login
    exit();
}

// Query untuk mengambil data fakultas
$sql = "SELECT id_fakultas, nama_fakultas FROM fakultas"; // Pastikan nama tabel dan kolom sesuai
$result = $conn->query($sql);

// Menyimpan data ke dalam array
$fakultas = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $fakultas[] = $row; // Menyimpan seluruh data fakultas (id dan nama)
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Fakultas</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Konten -->
    <div class="container mt-4">
        <h3>Daftar Fakultas</h3>

        <!-- Tombol Aksi: Edit dan Hapus -->
        <div class="mb-3">
            <a href="tambah_fakultas.php" class="btn btn-primary">Tambah Fakultas</a>
        </div>

        <!-- Tabel Data Fakultas -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Fakultas</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($fakultas)): ?>
                    <?php foreach ($fakultas as $index => $data): ?>
                        <tr>
                            <td><?= $index + 1; ?></td>
                            <td><?= htmlspecialchars($data['nama_fakultas']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center">Tidak ada data fakultas yang ditemukan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
