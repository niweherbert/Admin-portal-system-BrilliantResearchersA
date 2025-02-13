<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <button id="sidebarToggle">☰</button>
  <title>Dashboard | Brilliant Researchers Africa</title>
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








  .sidebar ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

.sidebar ul li {
    padding: 10px;
}

.sidebar ul li a {
    color: #fff;
    text-decoration: none;
    display: block;
}

.sidebar ul li a:hover,
.sidebar ul li a.active {
    background-color:rgba(1, 8, 46, 0.41);
}











  </style>
</head>
<body>
  <header>  <div class="logo-container">
      <img src="BRA.png" alt="Company Logo" class="logo">
     
    <h1>Brilliant Researchers Africa</h1> </div>
    <div class="logout-icon" onclick="confirmLogout()">
      <img src="https://img.icons8.com/ios-glyphs/30/ffffff/logout-rounded.png" alt="Logout Icon">
      <span>Logout</span>
    </div>
  </header>
  <div class="container">
    <nav class="sidebar">
      <ul>
        <li><a href="dashboard.php" class="active">Home</a></li>
        <li><a href="neededmat.php">Needed Materials</a></li>
        <li><a href="leave_Permission.php">Leave & Permission</a></li> 
        <li><a href="feedback.php">Feedback</a></li>
        <li><a href="edit-profile.php">Edit Profile</a></li>
      </ul>
    </nav>
    <main class="content">
      <h2>Daily Report</h2>
      <form id="work-log-form" action="submit_report.php" method="POST" enctype="multipart/form-data">
        <label>First Name: <input type="text" id="first-name" name="first_name" placeholder="Enter your first name" required /></label>
        <label>Last Name: <input type="text" id="last-name" name="last_name" placeholder="Enter your last name" required /></label>
        <label>Date: <input type="date" name="date" required /></label>
        <label>Time Arrived: <input type="time" name="time_arrived" required /></label>
        <label>Time Left: <input type="time" name="time_left" required /></label>
        <label>
          Weekly Transport Fee:
          <input type="checkbox" name="transport_fee" id="transport-fee">
          <input type="number" name="transport_amount" id="transport-amount" placeholder="Amount" disabled>
        </label>


        <label for="lunch">Lunch Issued: </label>
<select name="lunch" id="lunch">
    <option value="">Select Type</option>
    <option value="No">No</option>
    <option value="Yes">Yes</option>
</select><br><br>





        <label>Task Performed/Skill Gained:<textarea name="tasks_performed" placeholder="Type here..." required></textarea></label>
        <label>Upload File:<input type="file" name="file_upload" /></label>
        <label>Supervisor Name:<input type="text" name="supervisor_name" placeholder="Enter supervisor's name" required /></label>
        <button type="submit">Submit</button>
      </form>
    </main>
  </div>
  <script>

document.getElementById('sidebarToggle').addEventListener('click', function() {
    document.querySelector('.sidebar').classList.toggle('active');
  });

    const transportFeeCheckbox = document.getElementById('transport-fee');
    const transportAmountInput = document.getElementById('transport-amount');
    transportFeeCheckbox.addEventListener('change', () => {
      transportAmountInput.disabled = !transportFeeCheckbox.checked;
    });

    function confirmLogout() {
      const confirmation = confirm("Are you sure you want to log out?");
      if (confirmation) {
        window.location.href = "index.php";
      }
    }

    async function addItem() {
        const name = document.getElementById('item-name').value.trim();
        const quantity = document.getElementById('item-quantity').value.trim();

        console.log('Name:', name);
        console.log('Quantity:', quantity);

        if (!name || !quantity) {
            alert('Please enter both item name and quantity.');
            return;
        }

        const formData = new FormData();
        formData.append('name', name);
        formData.append('quantity', quantity);

        const response = await fetch('add_material.php', {
            method: 'POST',
            body: formData,
        });

        const result = await response.json();
        if (result.success) {
            fetchMaterials();
            document.getElementById('item-name').value = '';
            document.getElementById('item-quantity').value = '';
        } else {
            alert(result.message);
        }
    }

    async function removeItem(index) {
        const id = materials[index].id;

        const formData = new FormData();
        formData.append('id', id);

        const response = await fetch('delete_material.php', {
            method: 'POST',
            body: formData,
        });

        const result = await response.json();
        if (result.success) {
            fetchMaterials();
        } else {
            alert(result.message);
        }
    }

    async function fetchMaterials() {
        const response = await fetch('fetch_materials.php');
        materials = await response.json();
        renderTable();
    }

    function renderTable() {
        const tableBody = document.getElementById('materials-table').querySelector('tbody');
        tableBody.innerHTML = ''; // Clear the table

        materials.forEach((material, index) => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${index + 1}</td>
                <td>${material.name}</td>
                <td>${material.quantity}</td>
                <td class="action-buttons">
                    <button class="remove-btn" onclick="removeItem(${index})">Remove</button>
                </td>
            `;
            tableBody.appendChild(row);
        });
    }

    document.addEventListener('DOMContentLoaded', fetchMaterials);

    document.getElementById('work-log-form').addEventListener('submit', function(event) {
        var lunch = document.getElementById('lunch').value;
        if (lunch !== 'Yes' && lunch !== 'No') {
            alert('Please select a valid option for Lunch Issued.');
            event.preventDefault();
        }
    });
  </script>
</body>
</html>