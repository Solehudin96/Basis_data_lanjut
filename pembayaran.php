<?php 
include 'db.php';

// Cek apakah pengguna sudah login
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Mahasiswa</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Konten -->
    <div class="container mt-4">
        <h3>Data Pembayaran Mahasiswa</h3>
        <div class="mb-3">
    <a href="laporan_tunggakan.php" target="_blank" class="btn btn-primary">Riwayat Pembayaran</a>
</div>
        <!-- Tabel Data Pembayaran -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Mahasiswa</th>
                    <th>Jumlah Bayaran</th>
                    <th>Tanggal Pembayaran</th>
                </tr>
            </thead>
                <tbody>
                    <?php
                    // Query untuk mengambil data pembayaran mahasiswa dengan JOIN ke tabel users
                    $sql = "
SELECT bayaran_mahasiswa.id_bayaran, bayaran_mahasiswa.id_mahasiswa, bayaran_mahasiswa.jumlah_bayaran, bayaran_mahasiswa.tanggal_pembayaran, users.nama AS nama_mahasiswa
FROM bayaran_mahasiswa
INNER JOIN mahasiswa ON bayaran_mahasiswa.id_mahasiswa = mahasiswa.id_mahasiswa
INNER JOIN users ON mahasiswa.id_user = users.id_user


                    ";
                    $result = $conn->query($sql);

                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            ?>
                            <tr>
                                <td><?= $row['nama_mahasiswa']; ?></td> <!-- Menampilkan nama mahasiswa -->
                                <td>Rp <?= number_format($row['jumlah_bayaran'], 2, ',', '.'); ?></td>
                                <td><?= $row['tanggal_pembayaran']; ?></td>

                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data pembayaran.</td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
        </table>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
