<?php 
include 'db.php'; // Mengimpor koneksi database

// Cek apakah ada pencarian
$cari = isset($_GET['cari']) ? $_GET['cari'] : '';

// Query untuk mengambil data dosen dengan INNER JOIN dan pencarian
$sql = "
    SELECT dosen.id_dosen, users.nama AS nama_dosen, jurusan.nama_jurusan, dosen.nidn 
    FROM dosen
    INNER JOIN users ON dosen.id_user = users.id_user
    INNER JOIN jurusan ON dosen.id_jurusan = jurusan.id_jurusan
    WHERE users.nama LIKE '%$cari%' OR jurusan.nama_jurusan LIKE '%$cari%'"; 

$result = $conn->query($sql);

// Menyimpan data ke dalam array
$dosen = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $dosen[] = $row; // Menyimpan data dosen
    }
}

// Menutup koneksi
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Dosen</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Konten -->
    <div class="container mt-4">
        <h3>Daftar Dosen</h3>

        <!-- Form Pencarian Dosen -->
        <form class="mb-3" method="get" action="dosen.php">
            <div class="input-group">
                <input type="text" class="form-control" name="cari" placeholder="Cari Dosen atau Jurusan" value="<?= htmlspecialchars($cari); ?>">
                <button class="btn btn-primary" type="submit">Cari</button>
            </div>
        </form>

        <!-- Tombol Tambah Dosen -->
        <a href="tambah_dosen.php" class="btn btn-success mb-3">Tambah Dosen</a>

        <!-- Tabel Data Dosen -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Dosen</th>
                    <th>Jurusan</th>
                    <th>NIDN</th>
                    <th>Aksi</th> <!-- Kolom Aksi untuk Edit dan Hapus -->
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($dosen)): ?>
                    <?php foreach ($dosen as $index => $data_dosen): ?>
                        <tr>
                            <td><?= $index + 1; ?></td>
                            <td><?= htmlspecialchars($data_dosen['nama_dosen']); ?></td>
                            <td><?= htmlspecialchars($data_dosen['nama_jurusan']); ?></td>
                            <td><?= htmlspecialchars($data_dosen['nidn']); ?></td>
                            <td>
                                <!-- Tombol Edit -->
                                <a href="edit_dosen.php?id=<?= $data_dosen['id_dosen']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                
                                <!-- Tombol Hapus -->
                                <a href="hapus_dosen.php?id=<?= $data_dosen['id_dosen']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus dosen ini?');">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data dosen yang ditemukan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
