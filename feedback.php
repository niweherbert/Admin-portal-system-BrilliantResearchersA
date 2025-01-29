<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to the login page
    header("Location: login.php");
    exit();
}

// Get the user's username from the session
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'User';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Feedback | Brilliant Researchers Africa</title>
  <style>
    /* Internal CSS */
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
    .container {
      display: flex;
      min-height: 100vh;
    }
    .sidebar {
      width: 250px;
      background-color: #002244;
      color: white;
      padding: 20px 0;
    }
    .sidebar ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }
    .sidebar ul li {
      padding: 15px 20px;
    }
    .sidebar ul li a {
      color: white;
      text-decoration: none;
      display: block;
      font-size: 1rem;
    }
    .sidebar ul li a.active,
    .sidebar ul li a:hover {
      background-color: #0056b3;
      border-radius: 5px;
    }
    .content {
      flex: 1;
      padding: 20px;
      background-color: white;
      box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
      margin: 20px;
      border-radius: 5px;
    }
    .content h2 {
      margin-top: 0;
    }
    textarea {
      width: 100%;
      resize: none;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 0.9rem;
      height: 100px;
      background-color: #f4f4f4;
      color: #666;
    }
    button {
      padding: 10px;
      border: none;
      background-color: #003366;
      color: white;
      font-size: 1rem;
      border-radius: 5px;
      cursor: pointer;
    }
    button:hover {
      background-color: #0056b3;
    }
    p {
      font-size: 1rem;
      margin-top: 20px;
    }
  </style>
</head>
<body>
  <header>
    <h1>Feedback</h1>
  </header>
  <div class="container">
    <nav class="sidebar">
      <ul>
        <li><a href="dashboard.php">Home</a></li>
        <li><a href="feedback.php" class="active">Feedback</a></li>
        <li><a href="neededmat.php">Needed Materials</a></li>
        <li><a href="edit-profile.php">Edit Profile</a></li>
      </ul>
    </nav>
    <main class="content">
      <h2>Feedback and Info Section</h2>
      <textarea id="displayFeedback" placeholder="Feedback will appear here..." readonly></textarea>
      
    </main>
  </div>
  <script>
     document.addEventListener('DOMContentLoaded', function() {
        loadFeedback();
    });

    function loadFeedback() {
        fetch('get_user_feedback.php', {
            method: 'GET',
            credentials: 'same-origin' // This line ensures that cookies (including session ID) are sent with the request
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
            } else {
                document.getElementById('displayFeedback').value = data.map(item => item.feedback).join('\n');
            }
        })
        .catch(error => {
            console.error('Error fetching feedback:', error);
        });
    }
  </script>
</body>
</html>
