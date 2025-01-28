<?php
include 'db.php';

// Periksa apakah form telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $id_mahasiswa = intval($_POST['id_mahasiswa']);
    $nim = $conn->real_escape_string($_POST['nim']);
    $id_jurusan = intval($_POST['id_jurusan']);

    // Validasi input tidak boleh kosong
    if (empty($nim) || empty($id_jurusan)) {
        echo "Semua field harus diisi!";
        exit;
    }

    // Periksa apakah NIM sudah digunakan oleh mahasiswa lain
    $sql_check = "SELECT id_mahasiswa FROM Mahasiswa WHERE nim = ? AND id_mahasiswa != ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("si", $nim, $id_mahasiswa);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        echo "NIM sudah terdaftar oleh mahasiswa lain.";
        exit;
    }

    // Update data mahasiswa di database
    $sql_update = "UPDATE Mahasiswa SET nim = ?, id_jurusan = ? WHERE id_mahasiswa = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("sii", $nim, $id_jurusan, $id_mahasiswa);

    if ($stmt_update->execute()) {
        echo "Data mahasiswa berhasil diperbarui.";
        header("Location: mahasiswa.php");
        exit;
    } else {
        echo "Gagal memperbarui data mahasiswa: " . $conn->error;
    }

    // Tutup statement
    $stmt_update->close();
} else {
    echo "Akses tidak diizinkan.";
}

$conn->close();
?>
