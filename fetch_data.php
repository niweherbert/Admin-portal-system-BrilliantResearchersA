<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "brilliant_researchers";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from the daily_reports table
$sql = "SELECT id, date, time_arrived, time_left, transport_fee, transport_amount, tasks_performed, file_upload, supervisor_name, name FROM daily_reports";
$result = $conn->query($sql);

$data = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data);

$conn->close();
?>