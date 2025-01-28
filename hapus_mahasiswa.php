<?php
include 'db.php'; // Menghubungkan ke database
session_start(); // Memulai session

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Jika belum login, arahkan ke login
    exit();
}

// Cek apakah ada parameter id yang dikirimkan
if (isset($_GET['id'])) {
    $id_mahasiswa = $_GET['id'];

    // Hapus data dari tabel BayaranMahasiswa yang terkait dengan mahasiswa
    $sql_hapus_bayaran = "DELETE FROM bayaran_mahasiswa WHERE id_mahasiswa = $id_mahasiswa";
    if ($conn->query($sql_hapus_bayaran) === TRUE) {
        // Hapus data mahasiswa setelah data pembayaran berhasil dihapus
        $sql_hapus_mahasiswa = "DELETE FROM mahasiswa WHERE id_mahasiswa = $id_mahasiswa";
        if ($conn->query($sql_hapus_mahasiswa) === TRUE) {
            // Alihkan ke halaman daftar mahasiswa setelah berhasil menghapus data
            header("Location: mahasiswa.php");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    // Jika tidak ada id yang dikirimkan, alihkan ke halaman daftar mahasiswa
    header("Location: mahasiswa.php");
    exit();
}

// Menutup koneksi
$conn->close();
?>
