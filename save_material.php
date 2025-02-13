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

// Get the JSON data from the request
$data = json_decode(file_get_contents('php://input'), true);

// Debugging: Check if data is received correctly
if ($data === null) {
    echo json_encode(['success' => false, 'error' => 'No JSON data received']);
    $conn->close();
    exit();
}

// Debugging: Log received data
error_log("Received data: " . print_r($data, true));

if (isset($data['name']) && isset($data['quantity']) && isset($data['type'])) {
    $name = $data['name'];
    $quantity = $data['quantity'];
    $type = $data['type'];

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO materials (name, quantity, type) VALUES (?, ?, ?)");
    if ($stmt === false) {
        echo json_encode(['success' => false, 'error' => 'Prepare failed: ' . $conn->error]);
        $conn->close();
        exit();
    }

    $stmt->bind_param("sis", $name, $quantity, $type);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid input']);
}

$conn->close();
?>