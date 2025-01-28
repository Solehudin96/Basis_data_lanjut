<?php 
session_start();
include 'db.php'; // Menghubungkan ke database

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Jika belum login, arahkan ke login
    exit();
}

// Ambil daftar fakultas dari database untuk dropdown
$sql = "SELECT id_fakultas, nama_fakultas FROM fakultas";
$result = $conn->query($sql);
$fakultas = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $fakultas[] = $row;
    }
}

// Proses jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_jurusan = trim($_POST['nama_jurusan']);
    $id_fakultas = trim($_POST['id_fakultas']);

    if (!empty($nama_jurusan) && !empty($id_fakultas)) {
        // Query untuk menambahkan data jurusan
        $sql = "INSERT INTO jurusan (nama_jurusan, id_fakultas) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $nama_jurusan, $id_fakultas);

        if ($stmt->execute()) {
            // Berhasil, kembali ke halaman daftar jurusan
            $_SESSION['message'] = "Jurusan berhasil ditambahkan!";
            header("Location: daftar_jurusan.php?success=true");
            exit();
        } else {
            $error = "Gagal menambahkan jurusan. Silakan coba lagi.";
        }

        $stmt->close();
    } else {
        $error = "Semua data wajib diisi!";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Jurusan</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Konten -->
    <div class="container mt-4">
        <h3>Tambah Jurusan</h3>

        <!-- Pesan Error -->
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <!-- Form Tambah Jurusan -->
        <form method="POST" action="">
            <div class="mb-3">
                <label for="nama_jurusan" class="form-label">Nama Jurusan</label>
                <input type="text" class="form-control" id="nama_jurusan" name="nama_jurusan" placeholder="Masukkan nama jurusan" required>
            </div>
            <div class="mb-3">
                <label for="id_fakultas" class="form-label">Fakultas</label>
                <select class="form-control" id="id_fakultas" name="id_fakultas" required>
                    <option value="">Pilih Fakultas</option>
                    <?php foreach ($fakultas as $f): ?>
                        <option value="<?= htmlspecialchars($f['id_fakultas']); ?>"><?= htmlspecialchars($f['nama_fakultas']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Tambah</button>
            <a href="daftar_jurusan.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
