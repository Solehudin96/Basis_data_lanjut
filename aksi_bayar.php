<?php
// Konfigurasi database
include 'db.php';

// Pastikan parameter id_mahasiswa diterima
if (isset($_GET['id'])) {
    $id_mahasiswa = $_GET['id'];
    
    // Pastikan jumlah pembayaran diterima melalui POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $jumlah_bayar = isset($_POST['jumlah_bayar']) ? (int)$_POST['jumlah_bayar'] : 0;

        // Pastikan jumlah pembayaran valid
        if ($jumlah_bayar > 0) {
            // Simpan data pembayaran ke tabel pembayaran_mahasiswa
            $insert_query = "INSERT INTO bayaran_mahasiswa (id_mahasiswa, jumlah_bayaran, tanggal_pembayaran) VALUES (?, ?, NOW())";
            $insert_stmt = $conn->prepare($insert_query);
            $insert_stmt->bind_param("ii", $id_mahasiswa, $jumlah_bayar);

            if ($insert_stmt->execute()) {
                // Pembayaran berhasil dicatat
                header("Location: mahasiswa.php?status=success");
                exit;
            } else {
                $error_message = "Gagal menyimpan data pembayaran.";
            }
        } else {
            $error_message = "Jumlah pembayaran tidak valid.";
        }
    }
} else {
    $error_message = "ID mahasiswa tidak valid.";
}

// Jika terjadi kesalahan, tampilkan pesan
if (isset($error_message)) {
    echo "<p>Error: $error_message</p>";
    echo "<a href='mahasiswa.php'>Kembali ke daftar mahasiswa</a>";
}
?>
