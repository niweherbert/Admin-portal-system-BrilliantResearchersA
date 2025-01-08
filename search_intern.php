<?php
$conn = new mysqli("localhost", "root", "", "brilliant_researchers");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$valid_columns = ['id', 'name', 'date']; // Add valid columns here

if (isset($_GET['sort']) && in_array($_GET['sort'], $valid_columns)) {
    $sort = $conn->real_escape_string($_GET['sort']);
    $query = "SELECT id, $sort FROM daily_reports ORDER BY $sort";
    $result = $conn->query($query);
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data);
} else {
    echo json_encode(['error' => 'Invalid sort column']);
}

$conn->close();
?>