<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "SHDR_task";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from the SHDR_daily_reports table
$sql = "SELECT id, date,tasks_performed, file_upload, supervisor_name, name FROM SHDR_daily_reports";
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