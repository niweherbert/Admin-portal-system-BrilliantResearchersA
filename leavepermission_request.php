<?php
session_start();
/*if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}*/

require_once 'db.php';

// Handle approval/rejection
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_id = $_POST['request_id'];
    $status = $_POST['status'];

    // Debugging statements
    error_log("Request ID: $request_id");
    error_log("Status: $status");

    // Update the request status in the database
    $stmt = $conn->prepare("UPDATE leave_permission SET status = ? WHERE id = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("si", $status, $request_id);
    $stmt->execute();
    $stmt->close();

    // Set a success message in the session
    $_SESSION['message'] = "Request has been " . ($status === 'approved' ? 'approved' : 'rejected') . " successfully.";

    // Redirect to leave_permission.php with a success message
    header("Location: leavepermission_request.php");
    exit();
}

// Fetch leave and permission requests
$stmt = $conn->prepare("SELECT * FROM leave_permission ORDER BY created_at DESC");
if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}
$stmt->execute();
$result = $stmt->get_result();
$requests = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave & Permission Requests | Brilliant Researchers Africa</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
        }
        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #003366;
            color: white;
            padding: 10px 20px;
        }
        header h1 {
            font-size: 1.2rem;
            margin: 0;
        }
        .logout-icon {
            display: flex;
            align-items: center;
            cursor: pointer;
        }
        .logout-icon img {
            width: 24px;
            height: 24px;
            margin-right: 8px;
        }
        .logout-icon span {
            font-size: 0.9rem;
        }
        .container {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 250px;
            background-color: #002147;
            color: #fff;
            padding-top: 20px;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar ul li {
            margin: 10px 0;
        }
        .sidebar ul li a {
            color: #fff;
            text-decoration: none;
            padding: 10px;
            display: block;
        }
        .sidebar ul li a:hover,
        .sidebar ul li a.active {
            background-color: #001f3f;
        }
        .content {
            flex: 1;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        .approve-btn, .reject-btn {
            padding: 5px 10px;
            margin: 2px;
            cursor: pointer;
        }
       

        .logo-container {
    display: flex;
    align-items: center;
  }
  .logo {
    height: 50px;
    margin-right: 10px;
  }

    </style>
</head>
<body>
    <header>
        <div class="logo-container">
            <img src="BRA.png" alt="Company Logo" class="logo">
            <h1>Brilliant Researchers Africa</h1>
        </div>
        <div class="logout-icon" onclick="confirmLogout()">
            <img src="https://img.icons8.com/ios-glyphs/30/ffffff/logout-rounded.png" alt="Logout Icon">
            <span>Logout</span>
        </div>
    </header>
    <div class="container">
        <nav class="sidebar">
            <ul>
                <li><a href="admindashb.php">Home</a></li>
                <li><a href="stock.php">Store & Requisition</a></li>
                <li><a href="search.php">Search</a></li>
                <li><a href="leavepermission_request.php" class="active">Leave and Permission Request</a></li>
                <li><a href="edit_profile.php">Edit Profile</a></li>
            </ul>
        </nav>
</nav>
        <main class="content">
            <h2>Leave & Permission Requests</h2>
            <?php if (isset($_SESSION['message'])): ?>
                <div class="message">
                    <?php 
                    echo $_SESSION['message']; 
                    unset($_SESSION['message']);
                    ?>
                </div>
            <?php endif; ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Request Type</th>
                        <th>Reason</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Leave Period (Days)</th>
                        <th>Attachment</th>
                        <th>Comment</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($requests as $request): ?>
                        <?php if ($request['status'] !== 'approved' && $request['status'] !== 'rejected'): ?>
                            <tr>
                                <td><?php echo $request['id']; ?></td>
                                <td><?php echo $request['first_name'] . ' ' . $request['last_name']; ?></td>
                                <td><?php echo ucfirst($request['request_type']); ?></td>
                                <td><?php echo $request['reason']; ?></td>
                                <td><?php echo $request['start_date']; ?></td>
                                <td><?php echo $request['end_date']; ?></td>
                                <td>
                                    <?php
                                    $start_date = new DateTime($request['start_date']);
                                    $end_date = new DateTime($request['end_date']);
                                    $interval = $start_date->diff($end_date);
                                    echo $interval->days;
                                    ?>
                                </td>
                                <td>
                                    <?php if ($request['attachment']): ?>
                                        <a href="<?php echo $request['attachment']; ?>" target="_blank">View Attachment</a>
                                    <?php else: ?>
                                        No Attachment
                                    <?php endif; ?>
                                </td>
                                <td>
    <form method="post" action="submit_comment.php">
        <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
        <textarea name="comment" rows="2" cols="20"><?php echo isset($request['comment']) ? $request['comment'] : ''; ?></textarea>
        <button type="submit" name="action" value="comment">Submit Comment</button>
    </form>
</td>
                                <td>
                                    <form method="post" action="process_request.php">
                                        <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                                        <button type="submit" name="action" value="approve">Approve</button>
                                        <button type="submit" name="action" value="reject">Reject</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>
    <script>
        function confirmLogout() {
            const confirmation = confirm("Are you sure you want to log out?");
            if (confirmation) {
                window.location.href = "index.php";
            }
        }
    </script>
</body>
</html>