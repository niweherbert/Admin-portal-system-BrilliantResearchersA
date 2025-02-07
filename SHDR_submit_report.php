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
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $firstName = $_POST['first_name']; // Retrieve the first name from the form
    $lastName = $_POST['last_name']; // Retrieve the last name from the form
    $name = $firstName . ' ' . $lastName; // Concatenate first name and last name
    $date = $_POST['date'];
    $tasksPerformed = $_POST['tasks_performed'];
    $supervisorName = $_POST['supervisor_name'];
    $filePath = "";

    // Ensure the uploads directory exists
    $targetDir = "SHDR_uploads/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // File upload handling
    if (isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] === UPLOAD_ERR_OK) {
        $filePath = $targetDir . basename($_FILES["file_upload"]["name"]);
        move_uploaded_file($_FILES["file_upload"]["tmp_name"], $filePath);
    }

    // Prepare SQL statement
    $sql = "INSERT INTO SHDR_daily_reports (name, date, tasks_performed, file_upload, supervisor_name) 
            VALUES ('$name', '$date', '$tasksPerformed', '$filePath', '$supervisorName')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>