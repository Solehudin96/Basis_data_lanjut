<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_user = $_POST['id_user'];
    $nim = $_POST['nim'];
    $id_jurusan = $_POST['id_jurusan'];
    $total_tagihan = $_POST['total_tagihan'];

    $sql = "INSERT INTO Mahasiswa (id_user, nim, id_jurusan, total_tagihan) VALUES ('$id_user', '$nim', '$id_jurusan','$total_tagihan')";
    if ($conn->query($sql) === TRUE) {
        header("Location: mahasiswa.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
