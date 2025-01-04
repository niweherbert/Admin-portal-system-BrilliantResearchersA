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

$name = $_POST['name'];
$quantity = $_POST['quantity'];

// Validate quantity to ensure it is not empty
if (empty($quantity)) {
    echo json_encode(["success" => false, "message" => "Quantity cannot be empty"]);
    $conn->close();
    exit();
}

$sql = "INSERT INTO stock (name, quantity) VALUES ('$name', '$quantity')";
if ($conn->query($sql) === TRUE) {
    echo json_encode(["success" => true, "message" => "Stock added successfully"]);
} else {
    echo json_encode(["success" => false, "message" => $conn->error]);
}

$conn->close();
?>