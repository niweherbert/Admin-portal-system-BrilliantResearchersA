<?php
require_once 'db.php';

$message = '';
$token = isset($_GET['token']) ? $_GET['token'] : '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $token = $_POST['token'];

    if ($new_password === $confirm_password) {
        // Verify token and update password
        $stmt = $conn->prepare("SELECT * FROM users WHERE reset_token = ? AND reset_token_expiry > NOW()");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE id = ?");
            $stmt->bind_param("si", $hashed_password, $user['id']);

            if ($stmt->execute()) {
                $message = "Password successfully reset. You can now <a href='index.html'>login</a> with your new password.";
            } else {
                $message = "Failed to reset password. Please try again.";
            }
        } else {
            $message = "Invalid or expired token.";
        }
    } else {
        $message = "Passwords do not match.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | Brilliant Researchers Africa</title>
    <style>
        /* Copy the styles from index.html */
    </style>
</head>
<body>
    <div class="container">
        <h1>Reset Password</h1>
        <?php if ($message): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>
        <?php if (!$message): ?>
            <form action="reset_password.php" method="POST">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" placeholder="Enter new password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm new password" required>
                </div>
                <div class="form-group">
                    <button type="submit">Reset Password</button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>