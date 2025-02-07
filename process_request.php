<?php
require_once 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_id = $_POST['request_id'];
    $action = $_POST['action'];

    // Determine the new status based on the action
    $new_status = ($action === 'approve') ? 'approved' : 'rejected';

    // Update the status in the database
    $stmt = $conn->prepare("UPDATE leave_permission SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $request_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Request has been " . $new_status . ".";
    } else {
        $_SESSION['message'] = "Error updating request: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    // Redirect back to the leave permission request page with a message
    header("Location: leavepermission_request.php");
    exit();
}
?>