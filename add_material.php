<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "brilliant_researchers";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$name = $_POST['name'];
$quantity = $_POST['quantity'];

$sql = "INSERT INTO materials (name, quantity) VALUES ('$name', '$quantity')";
if ($conn->query($sql) === TRUE) {
    echo json_encode(["success" => true, "message" => "Material added successfully"]);
} else {
    echo json_encode(["success" => false, "message" => $conn->error]);
}

$conn->close();
?>