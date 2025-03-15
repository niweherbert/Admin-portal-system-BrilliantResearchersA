<?php
session_start();
require_once 'HR_db.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $otherName = isset($_POST['other_name']) ? $_POST['other_name'] : null;
    $emailOrPhone = $_POST['email_or_phone'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Check if user already exists
    $query = "SELECT * FROM users WHERE email_or_phone = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $emailOrPhone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "User already exists.";
    } else {
        // Insert new user into the database with pending status
        $query = "INSERT INTO users (first_name, last_name, other_name, email_or_phone, password, status) VALUES (?, ?, ?, ?, ?, 'pending')";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssss", $firstName, $lastName, $otherName, $emailOrPhone, $password);

        if ($stmt->execute()) {
            echo "Registration successful. Please wait for admin approval.";
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    $stmt->close();
    $conn->close();
}
?>