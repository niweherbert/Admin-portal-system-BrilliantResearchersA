<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

require_once 'db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $request_type = $_POST['request_type'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $reason = $_POST['reason'];

    // Handle file upload
    $attachment_path = null;
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == 0) {
        $upload_dir = 'uploads/';
        $attachment_name = uniqid() . '_' . $_FILES['attachment']['name'];
        $attachment_path = $upload_dir . $attachment_name;

        if (!move_uploaded_file($_FILES['attachment']['tmp_name'], $attachment_path)) {
            die("Failed to upload file.");
        }
    }

    // Insert data into the database
    $stmt = $conn->prepare("INSERT INTO leave_permission (user_id, first_name, last_name, request_type, start_date, end_date, reason, attachment) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssss", $user_id, $first_name, $last_name, $request_type, $start_date, $end_date, $reason, $attachment_path);

    if ($stmt->execute()) {
        $message = "Request submitted successfully.";
        // Clear form fields
        $_POST = array();
    } else {
        $message = "Error submitting request: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
// Output the message
if ($message) {
    echo "<p>$message</p>";
}
?>