<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $feedback = $_POST['feedback'];

    $sql = "INSERT INTO feedback (name, feedback) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $name, $feedback);

    if ($stmt->execute()) {
        echo json_encode(array("success" => "Feedback submitted successfully"));
    } else {
        echo json_encode(array("error" => "Failed to submit feedback"));
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(array("error" => "Invalid request method"));
}
?>