<?php
session_start();
require_once 'HR_db.php';
require_once 'db.php';

/* Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}*/

// Handle approval or rejection via AJAX for HR accounts
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ajax']) && $_POST['type'] == 'HR') {
    $userId = $_POST['user_id'];
    $action = $_POST['action'];

    if ($action == 'approve') {
        $status = 'approved';
    } elseif ($action == 'reject') {
        $status = 'rejected';
    }

    // Establish connection to HR database
    $connHR = new mysqli($servername, $username, $password, 'HR_database');
    if ($connHR->connect_error) {
        die("Connection failed: " . $connHR->connect_error);
    }

    $query = "UPDATE users SET status = ? WHERE id = ?";
    $stmt = $connHR->prepare($query);
    $stmt->bind_param("si", $status, $userId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'status' => $status]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
    $connHR->close();
    exit();
}

// Handle approval or rejection via AJAX for Brilliant Researchers Africa accounts
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ajax']) && $_POST['type'] == 'BRA') {
    $userId = $_POST['user_id'];
    $action = $_POST['action'];
    $userRole = isset($_POST['user_role']) ? $_POST['user_role'] : null;

    if ($action == 'approve') {
        $status = 'approved';
    } elseif ($action == 'reject') {
        $status = 'rejected';
    }

    $query = "UPDATE users SET status = ?, user_role = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssi", $status, $userRole, $userId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'status' => $status, 'user_role' => $userRole]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
    exit();
}

// Fetch all pending HR users from HR_database
$connHR = new mysqli($servername, $username, $password, 'HR_database');
if ($connHR->connect_error) {
    die("Connection failed: " . $connHR->connect_error);
}
$queryHR = "SELECT * FROM users WHERE status = 'pending'";
$resultHR = $connHR->query($queryHR);

// Fetch all pending Brilliant Researchers Africa users from secure_login_system
$queryBRA = "SELECT * FROM users WHERE status = 'pending' AND user_role != 'HR'";
$resultBRA = $conn->query($queryBRA);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard | Brilliant Researchers Africa</title>
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
      margin-bottom: 20px;
    }
    table, th, td {
      border: 1px solid black;
    }
    th, td {
      padding: 10px;
      text-align: left;
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
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
        <li><a href="admindashb.php" class="active">Home</a></li>
        <li><a href="stock.php">Store & Requisition</a></li>
        <li><a href="search.php">Search</a></li>
        <li><a href="leavepermission_request.php">Leave and Permission Request</a></li>
        <li><a href="approve_pending_account.php">Approve Pending Account Creation</a></li>
        <li><a href="edit_profile.php">Edit Profile</a></li>
      </ul>
    </nav>
    <main class="content">
      <h2>Pending HR Accounts</h2>
      <table border="1" id="pendingHRAccountsTable">
        <tr>
          <th>ID</th>
          <th>First Name</th>
          <th>Last Name</th>
          <th>Email or Phone</th>
          <th>Action</th>
        </tr>
        <?php while ($user = $resultHR->fetch_assoc()): ?>
        <tr id="user-<?php echo $user['id']; ?>">
          <td><?php echo $user['id']; ?></td>
          <td><?php echo $user['first_name']; ?></td>
          <td><?php echo $user['last_name']; ?></td>
          <td><?php echo $user['email_or_phone']; ?></td>
          <td>
            <button onclick="updateHRUserStatus(<?php echo $user['id']; ?>, 'approve')">Approve</button>
            <button onclick="updateHRUserStatus(<?php echo $user['id']; ?>, 'reject')">Reject</button>
          </td>
        </tr>
        <?php endwhile; ?>
      </table>

      <h2>Pending Brilliant Researchers Africa Accounts</h2>
      <table border="1" id="pendingBRAAccountsTable">
        <tr>
          <th>ID</th>
          <th>First Name</th>
          <th>Last Name</th>
          <th>Email or Phone</th>
          <th>Action</th>
        </tr>
        <?php while ($user = $resultBRA->fetch_assoc()): ?>
        <tr id="user-<?php echo $user['id']; ?>">
          <td><?php echo $user['id']; ?></td>
          <td><?php echo $user['first_name']; ?></td>
          <td><?php echo $user['last_name']; ?></td>
          <td><?php echo $user['email_or_phone']; ?></td>
          <td>
            <select id="user-role-<?php echo $user['id']; ?>">
              <option value="User">User</option>
              <option value="Admin">Admin</option>
            </select>
            <button onclick="updateBRAUserStatus(<?php echo $user['id']; ?>, 'approve')">Approve</button>
            <button onclick="updateBRAUserStatus(<?php echo $user['id']; ?>, 'reject')">Reject</button>
          </td>
        </tr>
        <?php endwhile; ?>
      </table>
    </main>
  </div>
  <script>
    function updateHRUserStatus(userId, action) {
      $.ajax({
        url: 'approve_pending_account.php',
        type: 'POST',
        data: {
          user_id: userId,
          action: action,
          type: 'HR',
          ajax: true
        },
        success: function(response) {
          var data = JSON.parse(response);
          if (data.success) {
            $('#user-' + userId).remove();
            if (data.status === 'approved') {
              alert('User approved and can now log in.');
            } else if (data.status === 'rejected') {
              alert('User rejected.');
            }
          } else {
            alert('Error: ' + data.error);
          }
        }
      });
    }

    function updateBRAUserStatus(userId, action) {
      var userRole = $('#user-role-' + userId).val();
      $.ajax({
        url: 'approve_pending_account.php',
        type: 'POST',
        data: {
          user_id: userId,
          action: action,
          user_role: userRole,
          type: 'BRA',
          ajax: true
        },
        success: function(response) {
          var data = JSON.parse(response);
          if (data.success) {
            $('#user-' + userId).remove();
            if (data.status === 'approved') {
              alert('User approved and can now log in.');
            } else if (data.status === 'rejected') {
              alert('User rejected.');
            }
          } else {
            alert('Error: ' + data.error);
          }
        }
      });
    }

    function confirmLogout() {
      const confirmation = confirm("Are you sure you want to log out?");
      if (confirmation) {
        window.location.href = "index.php";
      }
    }
  </script>
</body>
</html>

<?php
$conn->close();
$connHR->close();
?>