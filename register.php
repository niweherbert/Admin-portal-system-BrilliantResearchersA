<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email_or_phone = $_POST['email_or_phone'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password
    $name = $_POST['name'];
    $user_role = $_POST['user_role'];
    $specialization = $_POST['specialization'];
    $final_role = isset($_POST['final_role']) ? $_POST['final_role'] : '';

    if (empty($name)) {
        echo "Name is required.";
        exit();
    }

    // Check if the email or phone already exists
    $check_sql = "SELECT * FROM users WHERE email_or_phone = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $email_or_phone);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        echo "Email or phone already exists.";
    } else {
        // Insert data into the database
        $sql = "INSERT INTO users (email_or_phone, password, name, user_role, specialization, final_role) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $email_or_phone, $password, $name, $user_role, $specialization, $final_role);

        if ($stmt->execute()) {
            echo "Registration successful.";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    $check_stmt->close();
    $conn->close();
}
?>