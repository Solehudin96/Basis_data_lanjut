<?php 
include 'db.php'; // Menghubungkan ke database
session_start(); // Memulai session

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Jika belum login, arahkan ke login
    exit();
}

// Cek apakah ada pesan sukses dari halaman tambah jurusan
$message = '';
if (isset($_GET['success']) && $_GET['success'] == 'true') {
    $message = 'Data jurusan berhasil ditambahkan!';
}

// Query untuk mengambil data jurusan dengan JOIN ke tabel fakultas
$sql = "
    SELECT jurusan.id_jurusan, jurusan.nama_jurusan, fakultas.nama_fakultas
    FROM jurusan
    INNER JOIN fakultas ON jurusan.id_fakultas = fakultas.id_fakultas
";
$result = $conn->query($sql);

// Menyimpan data ke dalam array
$jurusan = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $jurusan[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Jurusan</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Konten -->
    <div class="container mt-4">
        <h3>Daftar Jurusan</h3>

        <!-- Menampilkan Pesan Sukses -->
        <?php if ($message): ?>
            <div class="alert alert-success" role="alert">
                <?= $message; ?>
            </div>
        <?php endif; ?>

        <!-- Tombol Aksi: Tambah Jurusan -->
        <div class="mb-3">
            <a href="tambah_jurusan.php" class="btn btn-primary">Tambah Jurusan</a>
        </div>

        <!-- Tabel Data Jurusan -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Program Studi</th>
                    <th>Fakultas</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($jurusan)): ?>
                    <?php foreach ($jurusan as $index => $data): ?>
                        <tr>
                            <td><?= $index + 1; ?></td>
                            <td><?= htmlspecialchars($data['nama_jurusan']); ?></td>
                            <td><?= htmlspecialchars($data['nama_fakultas']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center">Tidak ada data jurusan yang ditemukan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
