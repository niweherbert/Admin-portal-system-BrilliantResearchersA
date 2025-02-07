<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: SHDR.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Profile | SUSTAINABLE HOMES DESIGNS RWANDA LTD</title>
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
      background-color:rgb(3, 102, 0);
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
      background-color:rgb(0, 68, 23);
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
      background-color:rgb(2, 111, 16);
      border-radius: 5px;
    }
    .content {
      flex: 1;
      padding: 20px;
      background-color: white;
      box-shadow: 0 0 5px rgba(9, 34, 1, 0.1);
      margin: 20px;
      border-radius: 5px;
    }
    .content h2 {
      margin-top: 0;
    }
    form {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }
    form label {
      display: flex;
      flex-direction: column;
      font-size: 0.9rem;
      position: relative;
    }
    form input {
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 0.9rem;
    }
    form input[disabled] {
      background-color: #f0f0f0;
      color: #aaa;
      cursor: not-allowed;
    }
    form button {
      padding: 10px;
      border: none;
      background-color:rgb(0, 102, 10);
      color: white;
      border-radius: 5px;
      cursor: pointer;
    }
    form button:hover {
      background-color:rgb(3, 65, 1);
    }
    .edit-icon {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      font-size: 1.2rem;
      color: #333;
    }
  </style>
</head>
<body>
  <header>
    <h1>Edit Profile</h1>
  </header>
  <div class="container">
    <nav class="sidebar">
      <ul>
        <li><a href="SHDR_userdashboard.php">Home</a></li>
        <li><a href="SHDR_feedback.php">Feedback</a></li>
        <li><a href="SHDR_neededmat.php">Needed Materials</a></li>
        <li><a href="SHDR_edit-profile.php" class="active">Edit Profile</a></li>
      </ul>
    </nav>
    <main class="content">
      <h2>Edit Profile</h2>
      <form id="profile-form">
        <label>Update Picture:
          <input type="file" />
        </label>
        <label>
          Name:
          <input type="text" id="name" />
          <span class="edit-icon" onclick="editField(this)">✏️</span>
        </label>
        <label>
          Email:
          <input type="email" id="email" disabled />
          <span class="edit-icon" onclick="editField(this)"></span>
        </label>
        <label>
          Phone:
          <input type="tel" id="phone" />
          <span class="edit-icon" onclick="editField(this)">✏️</span>
        </label>
        <button type="button" onclick="saveProfile()">Save Changes</button>
      </form>
    </main>
  </div>
  <script>
    // Load saved data from LocalStorage
    document.addEventListener("DOMContentLoaded", () => {
      fetch('get_user_profile.php')
        .then(response => response.json())
        .then(data => {
          document.getElementById("name").value = data.name;
          document.getElementById("email").value = data.email;
          document.getElementById("phone").value = data.phone;
        })
        .catch(error => console.error('Error fetching profile:', error));
    });

    // Enable editing of input fields
    function editField(icon) {
      const inputField = icon.previousElementSibling;
      if (inputField.disabled) {
        inputField.disabled = false;
        inputField.focus();
      } else {
        alert("You are already editing this field.");
      }
    }

    // Save profile data to the server
    function saveProfile() {
      const name = document.getElementById("name").value;
      const email = document.getElementById("email").value;
      const phone = document.getElementById("phone").value;

      fetch('update_user_profile.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ name, email, phone })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert("Profile saved successfully!");
        } else {
          alert("Error saving profile: " + data.error);
        }
      })
      .catch(error => console.error('Error saving profile:', error));
    }
  </script>
</body>
</html>