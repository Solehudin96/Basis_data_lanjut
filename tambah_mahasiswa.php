<?php
include 'db.php';

// Periksa koneksi database
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil daftar jurusan dari database
$sql = "SELECT id_jurusan, nama_jurusan FROM Jurusan";
$result = $conn->query($sql);

$jurusan = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $jurusan[] = $row;
    }
}

// Tutup koneksi database
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container mt-4">
        <h3>Tambah Mahasiswa</h3>
        <form action="create_mahasiswa.php" method="post">
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Mahasiswa</label>
                <input type="text" id="nama" name="nama" class="form-control" placeholder="Masukkan nama mahasiswa" required>
                <input type="hidden" id="id_user" name="id_user">
            </div>

            <div class="mb-3">
                <label for="nim" class="form-label">NIM</label>
                <input type="text" id="nim" name="nim" class="form-control" placeholder="Masukkan NIM" required>
            </div>

            <div class="mb-3">
                <label for="jurusan" class="form-label">Jurusan</label>
                <select id="jurusan" name="id_jurusan" class="form-control" required>
                    <option value="">Pilih Jurusan</option>
                    <?php foreach ($jurusan as $j): ?>
                        <option value="<?= htmlspecialchars($j['id_jurusan']); ?>"><?= htmlspecialchars($j['nama_jurusan']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="total_tagihan" class="form-label">Total Tagihan</label>
                <input type="text" id="total_tagihan" name="total_tagihan" class="form-control" placeholder="Masukkan Total Tagihan "required>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="mahasiswa.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#nama').typeahead({
                source: function (query, process) {
                    return $.get('autocomplete_user.php', { query: query }, function (data) {
                        return process(data.map(function (item) {
                            return { id: item.id_user, name: item.nama };
                        }));
                    });
                },
                updater: function (item) {
                    $('#id_user').val(item.id);
                    return item.name;
                },
                displayText: function (item) {
                    return item.name;
                },
                autoSelect: true,
                minLength: 1
            });
        });
    </script>
</body>
</html>
