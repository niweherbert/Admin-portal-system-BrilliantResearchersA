<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email_or_phone = $_POST['email_or_phone'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email_or_phone = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email_or_phone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_role'] = $user['role'];

            if ($user['user_role'] === 'Admin') {
                header("Location: admindashb.php");
            } else {
                header("Location: dashboard.php");
            }
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with the provided credentials.";
    }
}
?>