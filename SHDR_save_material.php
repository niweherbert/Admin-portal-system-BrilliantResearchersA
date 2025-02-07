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

// Get the JSON data from the request
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['name']) && isset($data['quantity'])) {
    $name = $data['name'];
    $quantity = $data['quantity'];

    // Prepare SQL statement
    $sql = "INSERT INTO SHDR_materials (name, quantity) VALUES ('$name', '$quantity')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid input']);
}

$conn->close();
?>