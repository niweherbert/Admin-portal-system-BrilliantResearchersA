<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "secure_login_system";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email_or_phone = $_POST['email_or_phone'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $user_role = $_POST['user_role'];
    $name = $first_name . ' ' . $last_name; // Combine first and last name to create a full name

    // Check for duplicate email
    $sql = "SELECT * FROM users WHERE email_or_phone = '$email_or_phone'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "An account with this email or phone already exists.";
        $conn->close();
        exit();
    }

    // Check for existing admin account
    if ($user_role === 'Admin') {
        $sql = "SELECT * FROM users WHERE user_role = 'Admin'";
        $result = $conn->query($sql);

        if ($result->num_rows >= 2) {
            echo "You are not allowed to create more than 2 admin accounts.";
            $conn->close();
            exit();
        }
    }

    // Insert new user
    $sql = "INSERT INTO users (first_name, last_name, email_or_phone, password, user_role, name) VALUES ('$first_name', '$last_name', '$email_or_phone', '$password', '$user_role', '$name')";
    if ($conn->query($sql) === TRUE) {
        echo "Account created successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>