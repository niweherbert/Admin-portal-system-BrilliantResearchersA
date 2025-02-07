<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "SHDR_needed_materials_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM SHDR_stock";
$result = $conn->query($sql);

$stock = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $stock[] = $row;
    }
}

echo json_encode($stock);

$conn->close();
?>