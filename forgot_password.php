<?php
require_once 'db.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email_or_phone = $_POST['email_or_phone'];

    // Check if email or phone exists in the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE email_or_phone = ?");
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("s", $email_or_phone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generate a unique token
        $token = bin2hex(random_bytes(50));

        // Store the token in the database
        $update_stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email_or_phone = ?");
        if ($update_stmt === false) {
            die("Error preparing update statement: " . $conn->error);
        }
        $update_stmt->bind_param("ss", $token, $email_or_phone);
        $update_stmt->execute();

        // Send email with reset link
        $reset_link = "https://https://brilliantresearchersafrica25.ct.ws/reset_password.php?token=" . $token;
        $to = $email_or_phone;
        $subject = "Password Reset Request";
        $message_body = "Click the following link to reset your password: " . $reset_link;
        $headers = "From: noreply@yourdomain.com";

        if (mail($to, $subject, $message_body, $headers)) {
            $message = "A password reset link has been sent to your email or phone.";
        } else {
            $message = "Failed to send reset email. Please try again.";
        }
    } else {
        $message = "Email or phone not found in our records.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | Brilliant Researchers Africa</title>
    <style>
       

       * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: Arial, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background: linear-gradient(to bottom right, #87CEEB, #1E90FF);
    }

    .container {
      width: 350px;
      padding: 20px;
      background: rgba(255, 255, 255, 0.9);
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      text-align: center;
    }

    .container h1 {
      font-size: 20px;
      margin-bottom: 15px;
    }

    .form-group {
      margin-bottom: 15px;
    }

    .form-group label {
      display: block;
      font-size: 14px;
      margin-bottom: 5px;
      text-align: left;
    }

    .form-group input, .form-group select {
      width: 100%;
      padding: 10px;
      font-size: 14px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    .form-group button {
      width: 100%;
      padding: 10px;
      background: #1E90FF;
      color: #fff;
      border: none;
      border-radius: 4px;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s;
    }

    .form-group button:hover {
      background: #104E8B;
    }

    .links {
      margin-top: 10px;
    }

    .links a {
      color: #1E90FF;
      text-decoration: none;
      font-size: 12px;
    }

    .links a:hover {
      text-decoration: underline;
    }

    </style>
</head>
<body>
    <div class="container">
        <h1>Forgot Password</h1>
        <?php if ($message): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>
        <form action="forgot_password.php" method="POST">
            <div class="form-group">
                <label for="email_or_phone">Email or Phone</label>
                <input type="text" id="email_or_phone" name="email_or_phone" placeholder="Enter your email or phone" required>
            </div>
            <div class="form-group">
                <button type="submit">Reset Password</button>
            </div>
        </form>
        <div class="links">
            <a href="index.html">Back to Login</a>
        </div>
    </div>
</body>
</html>