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
  <title>Admin Dashboard | SUSTAINABLE HOMES DESIGNS RWANDA LTD</title>
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
      background-color:rgb(0, 102, 34);
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
      background-color:rgb(0, 71, 8);
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
      background-color:rgb(0, 63, 7);
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
  <header> <div class="logo-container">
      <img src="SHDR.png.png" alt="Company Logo" class="logo">
   
    <h1>SUSTAINABLE HOMES DESIGNS RWANDA LTD</h1> </div>
    <div class="logout-icon" onclick="confirmLogout()">
      <img src="https://img.icons8.com/ios-glyphs/30/ffffff/logout-rounded.png" alt="Logout Icon">
      <span>Logout</span>
    </div>
  </header>
  <div class="container">
    <nav class="sidebar">
      <ul>
        <li><a href="SHDR_admindashb.php" class="active">Home</a></li>
        <li><a href="SHDR_stock.php">Store & Requisition</a></li>
        <li><a href="SHDR_search.php">Search</a></li>
        <li><a href="SHDR_edit_profile.php">Edit Profile</a></li>
      </ul>
    </nav>
    <main class="content">
       <h2>Admin Dashboard</h2>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Date</th>
            <th>Tasks Performed</th>
            <th>File Upload</th>
            <th>Supervisor Name</th>
          </tr>
        </thead>
        <tbody id="researchers-table-body">
          <!-- Dynamic rows will be added here -->
        </tbody>
      </table>
    </main>
  </div>
  <script>
    document.addEventListener('DOMContentLoaded', fetchData);

    function fetchData() {
        fetch('SHDR_fetch_data.php')
            .then(response => response.json())
            .then(data => {
                const tableBody = document.getElementById('researchers-table-body');
                tableBody.innerHTML = '';

                data.forEach((report, index) => {
                    const row = document.createElement('tr');

                    // Format the ID with leading zeros
                    const formattedId = String(report.id).padStart(3, '0');

                    row.innerHTML = `
                        <td>${formattedId}</td>
                        <td>${report.name}</td>
                        <td>${report.date}</td>
                        <td>${report.tasks_performed}</td>
                        <td><a href="${report.file_upload}">View</a></td>
                        <td>${report.supervisor_name}</td>
                    `;
                    tableBody.appendChild(row);
                });
            })
            .catch(error => console.error('Error fetching data:', error));
    }

    function confirmLogout() {
      const confirmation = confirm("Are you sure you want to log out?");
      if (confirmation) {
        window.location.href = "SHDR.php";
      }
    }
  </script>
</body>
</html>
