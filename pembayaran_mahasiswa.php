<?php
// Konfigurasi database
include 'db.php';

// Pastikan parameter id_mahasiswa diterima
if (isset($_GET['id'])) {
    $id_mahasiswa = $_GET['id'];

    // Debug: Periksa apakah ID diterima
    if (empty($id_mahasiswa)) {
        echo "<p>ID mahasiswa tidak ditemukan dalam parameter URL.</p>";
        echo "<a href='mahasiswa.php'>Kembali ke daftar mahasiswa</a>";
        exit;
    }

    // Ambil data mahasiswa
    $query = "
        SELECT mahasiswa.nim, users.nama AS nama_mahasiswa, mahasiswa.total_tagihan
        FROM mahasiswa
        INNER JOIN users ON mahasiswa.id_user = users.id_user
        WHERE mahasiswa.id_mahasiswa = ?
    ";
    $stmt = $conn->prepare($query);

    // Debug: Periksa apakah query berhasil dipersiapkan
    if (!$stmt) {
        die("Query gagal dipersiapkan: " . $conn->error);
    }

    $stmt->bind_param("i", $id_mahasiswa);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $data_mahasiswa = $result->fetch_assoc();

        // Debug: Periksa data mahasiswa yang diambil
        if (!$data_mahasiswa) {
            echo "<p>Data mahasiswa tidak dapat diambil dari database.</p>";
            echo "<a href='mahasiswa.php'>Kembali ke daftar mahasiswa</a>";
            exit;
        }
    } else {
        echo "<p>Data mahasiswa tidak ditemukan untuk ID: " . htmlspecialchars($id_mahasiswa) . "</p>";
        echo "<a href='mahasiswa.php'>Kembali ke daftar mahasiswa</a>";
        exit;
    }
} else {
    echo "<p>ID mahasiswa tidak valid atau tidak diberikan.</p>";
    echo "<a href='mahasiswa.php'>Kembali ke daftar mahasiswa</a>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h3>Pembayaran Mahasiswa</h3>

        <!-- Tampilkan data mahasiswa -->
        <p><strong>NIM:</strong> <?= htmlspecialchars($data_mahasiswa['nim'] ?? 'Data tidak ditemukan'); ?></p>
        <p><strong>Nama:</strong> <?= htmlspecialchars($data_mahasiswa['nama_mahasiswa'] ?? 'Data tidak ditemukan'); ?></p>
        <p><strong>Total Tagihan:</strong> Rp <?= isset($data_mahasiswa['total_tagihan']) ? number_format($data_mahasiswa['total_tagihan'], 0, ',', '.') : 'Data tidak ditemukan'; ?></p>

        <!-- Form pembayaran -->
        <form method="post" action="aksi_bayar.php?id=<?= htmlspecialchars($id_mahasiswa); ?>">
            <div class="mb-3">
                <label for="jumlah_bayar" class="form-label">Jumlah Pembayaran</label>
                <input type="number" class="form-control" id="jumlah_bayar" name="jumlah_bayar" 
                       min="1" max="<?= $data_mahasiswa['total_tagihan'] ?? 1; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Bayar</button>
            <a href="mahasiswa.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
