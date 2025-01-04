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
    $name = $_POST['name'];
    $id = $_POST['id'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $time_arrived = $_POST['time_arrived'];
    $time_left = $_POST['time_left'];
    $lunch_issued = isset($_POST['lunch_issued']) ? 1 : 0;
    $weekly_transport = $_POST['weekly_transport'];
    $supervisor = $_POST['supervisor'];
    $task_files = $_POST['task_files'];

    $sql = "INSERT INTO researchers (name, id, email, role, time_arrived, time_left, lunch_issued, weekly_transport, supervisor, task_files)
            VALUES ('$name', '$id', '$email', '$role', '$time_arrived', '$time_left', $lunch_issued, '$weekly_transport', '$supervisor', '$task_files')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>