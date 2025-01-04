<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email_or_phone = $_POST['email_or_phone'];

    // Check if the user exists in the database
    $sql = "SELECT * FROM users WHERE email_or_phone = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email_or_phone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Send reset link (this is a placeholder, implement actual email sending)
        echo "Reset link sent to " . $email_or_phone;
    } else {
        echo "No account found with that email/phone";
    }

    $stmt->close();
    $conn->close();
}
?>