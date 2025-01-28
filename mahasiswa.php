<?php  
session_start();
// Konfigurasi database
include 'db.php';

// Menangkap input pencarian
$cari = isset($_GET['cari']) ? $_GET['cari'] : '';

// Query untuk mengambil data mahasiswa dengan INNER JOIN
$sql = "
    SELECT 
        mahasiswa.id_mahasiswa,
        mahasiswa.nim, 
        mahasiswa.total_tagihan, 
        users.nama AS nama_mahasiswa, 
        jurusan.nama_jurusan 
    FROM mahasiswa
    INNER JOIN users ON mahasiswa.id_user = users.id_user
    INNER JOIN jurusan ON mahasiswa.id_jurusan = jurusan.id_jurusan
    WHERE users.nama LIKE ? OR jurusan.nama_jurusan LIKE ?
"; 

// Menyiapkan query dan binding parameter
$stmt = $conn->prepare($sql);
$search_term = "%" . $cari . "%";
$stmt->bind_param("ss", $search_term, $search_term);
$stmt->execute();

// Mendapatkan hasil query
$result = $stmt->get_result();

// Menyimpan data ke dalam array
$mahasiswa = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $mahasiswa[] = $row; // Menyimpan data mahasiswa
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
    <title>Daftar Mahasiswa</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Konten -->
    <div class="container mt-4">
        <h3>Daftar Mahasiswa</h3>

        <!-- Form Pencarian Mahasiswa -->
        <form class="mb-3" method="get" action="mahasiswa.php">
            <div class="input-group">
                <input type="text" class="form-control" name="cari" placeholder="Cari Mahasiswa atau Jurusan" value="<?= htmlspecialchars($cari ?? ''); ?>">
                <button class="btn btn-primary" type="submit">Cari</button>
            </div>
        </form>

        <!-- Tombol Tambah Mahasiswa -->
        <a href="tambah_mahasiswa.php" class="btn btn-success mb-3">Tambah Mahasiswa</a>

        <!-- Tabel Data Mahasiswa -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIM</th>
                    <th>Nama Mahasiswa</th>
                    <th>Progam Studi</th>
                    <th>Total Tagihan</th>
                    <th>Aksi</th> <!-- Kolom Aksi untuk Edit dan Hapus -->
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($mahasiswa)): ?>
                    <?php foreach ($mahasiswa as $index => $data_mahasiswa): ?>
                        <tr>
                            <td><?= $index + 1; ?></td>
                            <td><?= htmlspecialchars($data_mahasiswa['nim']); ?></td>
                            <td><?= htmlspecialchars($data_mahasiswa['nama_mahasiswa']); ?></td>
                            <td><?= htmlspecialchars($data_mahasiswa['nama_jurusan']); ?></td>
                            <td><?= number_format($data_mahasiswa['total_tagihan'], 0, ',', '.'); ?></td>
                            <td>
                                <!-- Tombol Edit -->
                                <a href="edit_mahasiswa.php?id=<?= $data_mahasiswa['id_mahasiswa']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                
                                <!-- Tombol Hapus -->
                                <a href="hapus_mahasiswa.php?id=<?= $data_mahasiswa['id_mahasiswa']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus mahasiswa ini?');">Hapus</a>
                                <a href="pembayaran_mahasiswa.php?id=<?= $data_mahasiswa['id_mahasiswa']; ?>" class="btn btn-primary btn-sm" >Bayar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data mahasiswa yang ditemukan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
