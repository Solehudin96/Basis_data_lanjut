<?php
// Konfigurasi database
$host = "localhost";
$user = "root";  // Ganti dengan username database Anda
$password = "";  // Ganti dengan password database Anda
$database = "pembayaran_mahasiswa";  // Nama database Anda

// Membuat koneksi
$conn = new mysqli($host, $user, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>