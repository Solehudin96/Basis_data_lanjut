<?php
session_start();
session_destroy(); // Menghancurkan session
header("Location: login.php"); // Arahkan pengguna kembali ke halaman login
exit();
?>
