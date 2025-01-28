<?php  
include 'db.php';

// Cek apakah pengguna sudah login
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Ambil data pencarian jika ada
$search = '';
if (isset($_POST['search'])) {
    $search = $_POST['search'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Pembayaran Mahasiswa</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Konten -->
    <div class="container mt-4">
        <h3>Laporan Pembayaran Mahasiswa</h3>

        <!-- Tabel Log Pembayaran -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Deskripsi Laporan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Query untuk mengambil data log pembayaran
                $sql_log = "
                    SELECT id_log, deskripsi_laporan, tanggal 
                    FROM log 
                    WHERE deskripsi_laporan LIKE ? OR tanggal LIKE ?
                    ORDER BY tanggal DESC
                ";

                // Menyiapkan query untuk menghindari SQL Injection
                $stmt_log = $conn->prepare($sql_log);
                $searchParam = "%" . $search . "%";
                $stmt_log->bind_param("ss", $searchParam, $searchParam);

                // Menjalankan query
                $stmt_log->execute();
                $result_log = $stmt_log->get_result();

                if ($result_log && $result_log->num_rows > 0) {
                    $no = 1;
                    while ($row_log = $result_log->fetch_assoc()) {
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($row_log['deskripsi_laporan']); ?></td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="3" class="text-center">Tidak ada data log pembayaran yang ditemukan.</td>
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
