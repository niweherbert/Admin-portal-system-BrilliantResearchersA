<?php
session_start();
include 'db.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email_or_phone = $_POST['email_or_phone'];
    $password = $_POST['password'];

    // Check if the user exists in the database
    $sql = "SELECT * FROM users WHERE email_or_phone = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email_or_phone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Password is correct, start a session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['user_role'];
            $_SESSION['session_token'] = bin2hex(random_bytes(32)); // Generate a unique session token

            // Redirect based on user role
            if ($user['user_role'] == 'Admin') {
                header("Location: admindashb.html");
            } else {
                header("Location: dashboard.html");
            }
            exit();
        } else {
            echo "Invalid password";
        }
    } else {
        echo "Invalid email/phone or password";
    }

    $stmt->close();
    $conn->close();
}
?>