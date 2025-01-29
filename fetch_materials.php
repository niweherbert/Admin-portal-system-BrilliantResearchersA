<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "needed_materials_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM materials";
$result = $conn->query($sql);

$materials = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $materials[] = $row;
    }
}

echo json_encode($materials);

$conn->close();
?>