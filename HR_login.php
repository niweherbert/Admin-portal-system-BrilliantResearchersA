<?php
session_start();
include 'HR_db.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $emailOrPhone = $_POST['email_or_phone'];
    $password = $_POST['password'];

    // Check if user exists and is approved
    $query = "SELECT * FROM users WHERE email_or_phone = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $emailOrPhone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if ($user['status'] == 'approved') {
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['first_name'] = $user['first_name'];
                $_SESSION['last_name'] = $user['last_name'];

                // Redirect to dashboard
                header("Location: HR_dashb.php");
                exit();
            } else {
                echo "Invalid password.";
            }
        } elseif ($user['status'] == 'pending') {
            echo "Your account is still pending approval.";
        } else {
            echo "Your account has been rejected.";
        }
    } else {
        echo "User not found.";
    }

    $stmt->close();
    $conn->close();
}
?>