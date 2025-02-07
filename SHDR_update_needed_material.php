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

$id = $_POST['id'];
$name = $_POST['name'];
$quantity = $_POST['quantity'];

$sql = "UPDATE SHDR_materials SET name='$name', quantity='$quantity' WHERE id=$id";
if ($conn->query($sql) === TRUE) {
    echo json_encode(["success" => true, "message" => "Material updated successfully"]);
} else {
    echo json_encode(["success" => false, "message" => $conn->error]);
}

$conn->close();
?>