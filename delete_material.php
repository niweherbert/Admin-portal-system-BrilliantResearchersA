<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "needed_materials_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_POST['id'];

$sql = "DELETE FROM materials WHERE id=$id";
if ($conn->query($sql) === TRUE) {
    echo json_encode(["success" => true, "message" => "Material deleted successfully"]);
} else {
    echo json_encode(["success" => false, "message" => $conn->error]);
}

$conn->close();
?>
