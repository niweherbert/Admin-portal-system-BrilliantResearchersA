<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to the login page
    header("Location: SHDR.php");
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
  <title>Feedback | SUSTAINABLE HOMES DESIGNS RWANDA LTD</title>
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
      background-color:rgb(0, 102, 54);
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
      background-color:rgb(0, 71, 24);
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
      background-color:rgb(0, 63, 28);
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
      background-color:rgb(0, 102, 31);
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    form button:hover {
      background-color:rgb(0, 71, 15);
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

  </style>
</head>
<body>
  <header>
    <h1>Feedback</h1>
  </header>
  <div class="container">
    <nav class="sidebar">
      <ul>
        <li><a href="SHDR_userdashboard.php">Home</a></li>
        <li><a href="SHDR_feedback.php" class="active">Feedback</a></li>
        <li><a href="SHDR_neededmat.php">Needed Materials</a></li>
        <li><a href="SHDR_edit-profile.php">Edit Profile</a></li>
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
