<?php
// Koneksi ke database
$host = "localhost";
$username = "root";
$password = "";
$dbname = "pembayaran_mahasiswa";
$conn = new mysqli($host, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk mendapatkan laporan tunggakan mahasiswa
$sql = "
SELECT 
    mahasiswa.id_mahasiswa, 
    users.nama AS nama_mahasiswa, 
    mahasiswa.total_tagihan, 
    IFNULL(SUM(bayaran_mahasiswa.jumlah_bayaran), 0) AS total_pembayaran, 
    mahasiswa.total_tagihan - IFNULL(SUM(bayaran_mahasiswa.jumlah_bayaran), 0) AS sisa_tunggakan
FROM mahasiswa
LEFT JOIN bayaran_mahasiswa ON mahasiswa.id_mahasiswa = bayaran_mahasiswa.id_mahasiswa
LEFT JOIN users ON mahasiswa.id_user = users.id_user
GROUP BY mahasiswa.id_mahasiswa
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pembayaran</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* CSS khusus untuk tampilan cetak */
        @media print {
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 20px;
            }

            .container {
                width: 100%;
                max-width: none;
                margin: 0;
            }

            .no-print {
                display: none;
            }

            .navbar {
                display: none;
            }

            .btn {
                display: none;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th, td {
                padding: 8px;
                text-align: left;
                border: 1px solid #ddd;
            }

            h3 {
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar (bisa diganti sesuai kebutuhan) -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Laporan Pembayaran</a>
    </nav>

    <div class="container mt-4">
        <h3>Riwayat Pembayaran</h3>
        
        <!-- Tabel Laporan -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Mahasiswa</th>
                    <th>Total Tagihan</th>
                    <th>Total Pembayaran</th>
                    <th>Sisa Tunggakan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Menampilkan data laporan
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        ?>
                        <tr>
                            <td><?= $row['nama_mahasiswa']; ?></td>
                            <td>Rp <?= number_format($row['total_tagihan'], 2, ',', '.'); ?></td>
                            <td>Rp <?= number_format($row['total_pembayaran'], 2, ',', '.'); ?></td>
                            <td>Rp <?= number_format($row['sisa_tunggakan'], 2, ',', '.'); ?></td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada data tunggakan.</td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
<script type="text/javascript">
        window.print();

</script>
</html>

<?php
// Tutup koneksi database
$conn->close();
?>
