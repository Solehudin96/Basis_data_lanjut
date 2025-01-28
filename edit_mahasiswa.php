<?php
include 'db.php';

// Periksa apakah ID mahasiswa diberikan
if (isset($_GET['id'])) {
    $id_mahasiswa = intval($_GET['id']);

    // Ambil data mahasiswa berdasarkan ID
    $sql = "SELECT m.id_mahasiswa, u.nama, m.nim, m.id_jurusan 
            FROM Mahasiswa m
            JOIN Users u ON m.id_user = u.id_user
            WHERE m.id_mahasiswa = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_mahasiswa);
    $stmt->execute();
    $result = $stmt->get_result();
    $mahasiswa = $result->fetch_assoc();

    if (!$mahasiswa) {
        echo "Mahasiswa tidak ditemukan.";
        exit;
    }
} else {
    echo "ID mahasiswa tidak diberikan.";
    exit;
}

// Ambil daftar jurusan
$sql_jurusan = "SELECT id_jurusan, nama_jurusan FROM Jurusan";
$result_jurusan = $conn->query($sql_jurusan);

$jurusan = [];
if ($result_jurusan && $result_jurusan->num_rows > 0) {
    while ($row = $result_jurusan->fetch_assoc()) {
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
    <title>Edit Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container mt-4">
        <h3>Edit Mahasiswa</h3>
        <form action="update_mahasiswa.php" method="POST">
            <input type="hidden" name="id_mahasiswa" value="<?= htmlspecialchars($mahasiswa['id_mahasiswa']); ?>">

            <div class="mb-3">
                <label for="nama" class="form-label">Nama Mahasiswa</label>
                <input type="text" id="nama" name="nama" class="form-control" 
                       value="<?= htmlspecialchars($mahasiswa['nama']); ?>" readonly>
            </div>

            <div class="mb-3">
                <label for="nim" class="form-label">NIM</label>
                <input type="text" id="nim" name="nim" class="form-control" 
                       value="<?= htmlspecialchars($mahasiswa['nim']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="jurusan" class="form-label">Jurusan</label>
                <select id="jurusan" name="id_jurusan" class="form-control" required>
                    <option value="">Pilih Jurusan</option>
                    <?php foreach ($jurusan as $j): ?>
                        <option value="<?= $j['id_jurusan']; ?>" 
                                <?= ($j['id_jurusan'] == $mahasiswa['id_jurusan']) ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($j['nama_jurusan']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="mahasiswa.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>
</html>
