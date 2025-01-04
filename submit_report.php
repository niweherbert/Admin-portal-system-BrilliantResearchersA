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
    $name = $_POST['name']; // Retrieve the name from the form
    $date = $_POST['date'];
    $timeArrived = $_POST['time_arrived'];
    $timeLeft = $_POST['time_left'];
    $transportFee = isset($_POST['transport_fee']) ? 1 : 0;
    $transportAmount = $_POST['transport_amount'] ?? NULL;
    $tasksPerformed = $_POST['tasks_performed'];
    $supervisorName = $_POST['supervisor_name'];
    $filePath = "";

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
    $sql = "INSERT INTO daily_reports (name, date, time_arrived, time_left, transport_fee, transport_amount, tasks_performed, file_upload, supervisor_name) 
            VALUES ('$name', '$date', '$timeArrived', '$timeLeft', $transportFee, " . ($transportAmount !== NULL ? "'$transportAmount'" : "NULL") . ", '$tasksPerformed', '$filePath', '$supervisorName')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>