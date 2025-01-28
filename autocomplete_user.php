<?php
include 'db.php';

$search = $_GET['query'] ?? '';
$search = $conn->real_escape_string($search);

$query = "
    SELECT id_user, nama 
    FROM Users 
    WHERE nama LIKE '%$search%' 
    LIMIT 10
";
$result = $conn->query($query);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = [
        'id_user' => $row['id_user'],
        'nama' => $row['nama']
    ];
}

header('Content-Type: application/json');
echo json_encode($data);
?>
