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
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $firstName = $_POST['first_name']; // Retrieve the first name from the form
    $lastName = $_POST['last_name']; // Retrieve the last name from the form
    $name = $firstName . ' ' . $lastName; // Concatenate first name and last name
    $date = $_POST['date'];
    $timeArrived = $_POST['time_arrived'];
    $timeLeft = $_POST['time_left'];
    $transportFee = isset($_POST['transport_fee']) ? 1 : 0;
    $transportAmount = $_POST['transport_amount'] ?? NULL;
    $tasksPerformed = $_POST['tasks_performed'];
    $supervisorName = $_POST['supervisor_name'];
    $filePath = "";
    $lunch = isset($_POST['lunch']) && $_POST['lunch'] === 'Yes' ? 1 : 0; // Convert lunch to integer (1 for Yes, 0 for No)

// Ensure the uploads directory exists
$targetDir = "uploads/";
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

// File upload handling
if (isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] === UPLOAD_ERR_OK) {
    $filePath = $targetDir . basename($_FILES["file_upload"]["name"]);
    move_uploaded_file($_FILES["file_upload"]["tmp_name"], $filePath);
}

// Prepare SQL statement
$stmt = $conn->prepare("INSERT INTO daily_reports (name, date, time_arrived, time_left, transport_fee, transport_amount, tasks_performed, file_upload, supervisor_name, lunch) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssidssss", $name, $date, $timeArrived, $timeLeft, $transportFee, $transportAmount, $tasksPerformed, $filePath, $supervisorName, $lunch);

if ($stmt->execute()) {
    echo "New record created successfully";
} else {
    echo "Error: " . $stmt->error;
}
$stmt->close();
}

$conn->close();
?>