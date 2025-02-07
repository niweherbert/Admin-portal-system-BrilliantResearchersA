<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

require_once 'db.php';

// Fetch user's leave and permission requests
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM leave_permission WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
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
    <title>Leave & Permission | Brilliant Researchers Africa</title>
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
    form label {
      display: block;
      margin-bottom: 10px;
    }
    form input,
    form textarea {
      width: 100%;
      padding: 8px;
      margin-top: 5px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    form button {
      padding: 10px 15px;
      background-color: #003366;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    form button:hover {
      background-color: #002147;
    }



/* Responsive styles */
@media screen and (max-width: 768px) {
      .container {
        flex-direction: column;
      }

      .sidebar {
        width: 100%;
        padding-top: 10px;
      }

      .content {
        padding: 10px;
      }

      table {
        font-size: 14px;
      }
    }

    @media screen and (max-width: 480px) {
      header h1 {
        font-size: 1rem;
      }

      .logout-icon span {
        display: none;
      }

      .sidebar ul li a {
        font-size: 0.9rem;
      }

      form input,
      form textarea,
      form button {
        font-size: 14px;
      }
    }



/* styles to handle to toggle */

@media screen and (max-width: 768px) {
  .sidebar {
    position: fixed;
    left: -250px;
    top: 0;
    height: 100%;
    transition: left 0.3s ease;
  }

  .sidebar.active {
    left: 0;
  }

  #sidebarToggle {
    display: block;
  }
}

@media screen and (min-width: 769px) {
  #sidebarToggle {
    display: none;
  }
}

.logo-container {
    display: flex;
    align-items: center;
  }
  .logo {
    height: 50px;
    margin-right: 10px;
  }



        /* Copy the styles from dashboard.php and add any additional styles here */
        .request-status {
            margin-top: 20px;
        }
        .request-status table {
            width: 100%;
            border-collapse: collapse;
        }
        .request-status th, .request-status td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .request-status th {
            background-color: #f2f2f2;
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
                <li><a href="dashboard.php">Home</a></li>
                <li><a href="feedback.php">Feedback</a></li>
                <li><a href="neededmat.php">Needed Materials</a></li>
                <li><a href="leave_Permission.php" class="active">Leave & Permission</a></li>
                <li><a href="edit-profile.php">Edit Profile</a></li>
            </ul>
        </nav>
        <main class="content">
            <h2>Leave & Permission Request</h2>
            <form id="leave-permission-form" action="submit_leave_permission.php" method="POST" enctype="multipart/form-data">
                <label>First Name: <input type="text" name="first_name" placeholder="Enter your first name" required /></label>
                <label>Last Name: <input type="text" name="last_name" placeholder="Enter your last name" required /></label>
                <label>Request Type:
                    <select name="request_type" required>
                        <option value="">Select request type</option>
                        <option value="leave">Leave</option>
                        <option value="permission">Permission</option>
                    </select>
                </label>

                <label>Start Date: <input type="date" name="start_date" required /></label>
<label>End Date: <input type="date" name="end_date" required /></label>
                <label>Reason:
                    <textarea name="reason" placeholder="Enter the reason for your request" required></textarea>
    </label>
                <label>Attach Document (optional):
                    <input type="file" name="attachment" accept=".pdf,.doc,.docx">
                </label>
                <button type="submit">Submit Request</button>
            </form>

            <div class="request-status">
            <h3>Your Request Status</h3>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Request Type</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Reason</th>
                <th>Comment</th>
                <th>Status</th>
                
            </tr>
        </thead>
        <tbody>
            <?php foreach ($requests as $request): ?>
                <tr>
                    <td><?php echo $request['first_name'] . ' ' . $request['last_name']; ?></td>
                    <td><?php echo ucfirst($request['request_type']); ?></td>
                    <td><?php echo $request['start_date']; ?></td>
                    <td><?php echo $request['end_date']; ?></td>
                    <td><?php echo $request['reason']; ?></td>
                    <td><?php echo isset($request['comment']) ? $request['comment'] : 'No Comment'; ?></td>
                    <td><?php echo ucfirst($request['status']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
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