<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_id = $_POST['request_id'];
    $comment = $_POST['comment'];

    // Update the comment in the database
    $stmt = $conn->prepare("UPDATE leave_permission SET comment = ? WHERE id = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("si", $comment, $request_id);
    $stmt->execute();
    $stmt->close();

    // Set a success message in the session
    $_SESSION['message'] = "Comment has been submitted successfully.";

    // Redirect back to leavepermission_request.php
    header("Location: leavepermission_request.php");
    exit();
} else {
    // If not a POST request, redirect to leavepermission_request.php
    header("Location: leavepermission_request.php");
    exit();
}
?>