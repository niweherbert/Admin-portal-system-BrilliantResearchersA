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
  <title>Stock Management | Brilliant Researchers Africa</title>
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
    form label {
      display: block;
      margin-bottom: 10px;
    }
    form input {
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
     
    <h1>Brilliant Researchers Africa</h1> </div>
    <div class="logout-icon" onclick="confirmLogout()">
    <img src="https://img.icons8.com/ios-glyphs/30/ffffff/logout-rounded.png" alt="Logout Icon">
      <span>Logout</span>
    </div>
  </header>
  <div class="container">
    <nav class="sidebar">
      <ul>
        <li><a href="admindashb.php">Home</a></li>
        <li><a href="stock.php" class="active">Store & Requisition</a></li>
        <li><a href="edit_profile.php">Edit Profile</a></li>
      </ul>
    </nav>
    <main class="content">
      <h2>Needed Materials</h2>
      
      <h3>Chemicals</h3>
      <table id="chemical-needed-table">
        <thead>
          <tr>
            <th>Name</th>
            <th>Quantity</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <!-- Dynamic rows will be added here -->
        </tbody>
      </table>

      <h3>Equipment</h3>
      <table id="equipment-needed-table">
        <thead>
          <tr>
            <th>Name</th>
            <th>Quantity</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <!-- Dynamic rows will be added here -->
        </tbody>
      </table>

      <div id="edit-form" style="display: none;">
        <h3>Edit Material</h3>
        <form>
          <label for="edit-name">Name:</label>
          <input type="text" id="edit-name" name="name" required>
          <label for="edit-quantity">Quantity:</label>
          <input type="text" id="edit-quantity" name="quantity" required>
          <input type="hidden" id="edit-id">
          <button type="button" onclick="saveEdit()">Save</button>
        </form>
      </div>

      <h2>Available Stock - Chemicals</h2>
      <table id="chemical-stock-table">
        <thead>
          <tr>
            <th>Name</th>
            <th>Quantity</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <!-- Dynamic rows will be added here -->
        </tbody>
      </table>

      <h2>Available Stock - Equipment</h2>
      <table id="equipment-stock-table">
        <thead>
          <tr>
            <th>Name</th>
            <th>Quantity</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <!-- Dynamic rows will be added here -->
        </tbody>
      </table>

      <div id="edit-form" style="display: none;">
        <h3>Edit Material</h3>
        <form>
          <label for="edit-name">Name:</label>
          <input type="text" id="edit-name" name="name" required>
          <label for="edit-quantity">Quantity:</label>
          <input type="text" id="edit-quantity" name="quantity" required>
          <input type="hidden" id="edit-id">
          <button type="button" onclick="saveEdit()">Save</button>
        </form>
      </div>

     





      <div id="stock-form">
        <h3>Add New Stock</h3>
        <form>
          <label for="stock-name">Name:</label>
          <input type="text" id="stock-name" name="name" required>
          <label for="stock-quantity">Quantity:</label>
          <input type="text" id="stock-quantity" name="quantity" required>
          <label for="stock-type">Type:</label>
          <select id="stock-type" name="type" required>
            <option value="Chemical">Chemicals</option>
            <option value="Equipment">Equipment</option>
          </select>
          <button type="button" onclick="addStock()">Add</button>
        </form>
      </div>

      <script>
        let stock = JSON.parse(localStorage.getItem('stock')) || [];

        function addStock() {
          const stockName = document.getElementById('stock-name').value;
          const stockQuantity = document.getElementById('stock-quantity').value;
          const stockType = document.getElementById('stock-type').value;

          if (stockName && stockQuantity && stockType) {
            const newStock = { name: stockName, quantity: stockQuantity, type: stockType };

            // Send data to the server
            fetch('save_stock.php', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json'
              },
              body: JSON.stringify(newStock)
            })
            .then(response => response.json())
            .then(data => {
              if (data.success) {
                // Add the new stock to the table
                stock.push(newStock);
                localStorage.setItem('stock', JSON.stringify(stock));
                renderStockTable();

                // Clear the input fields
                document.getElementById('stock-name').value = '';
                document.getElementById('stock-quantity').value = '';
                document.getElementById('stock-type').value = 'Chemical'; // Reset to default
              } else {
                alert('Failed to save stock');
              }
            })
            .catch(error => console.error('Error:', error));
          }
        }

        function renderStockTable() {
          const chemicalStockTableBody = document.getElementById('chemical-stock-table').querySelector('tbody');
          const equipmentStockTableBody = document.getElementById('equipment-stock-table').querySelector('tbody');
          chemicalStockTableBody.innerHTML = '';
          equipmentStockTableBody.innerHTML = '';

          stock.forEach((item, index) => {
            const row = document.createElement('tr');
            row.innerHTML = `
              <td>${item.name}</td>
              <td>${item.quantity}</td>
              <td class="action-buttons">
                <button class="edit-btn" onclick="editStock(${index})">Edit</button>
                <button class="remove-btn" onclick="removeStock(${index})">Remove</button>
              </td>
            `;
            if (item.type === 'Chemical') {
              chemicalStockTableBody.appendChild(row);
            } else if (item.type === 'Equipment') {
              equipmentStockTableBody.appendChild(row);
            }
          });
        }

        document.addEventListener('DOMContentLoaded', renderStockTable);




        async function fetchNeededMaterials() {
  try {
    const response = await fetch('fetch_needed_materials.php');
    const materials = await response.json();

    const chemicalTableBody = document.getElementById('chemical-needed-table').querySelector('tbody');
    const equipmentTableBody = document.getElementById('equipment-needed-table').querySelector('tbody');
    chemicalTableBody.innerHTML = '';
    equipmentTableBody.innerHTML = '';

    materials.forEach(material => {
      const row = document.createElement('tr');
      row.innerHTML = `
        <td>${material.name}</td>
        <td>${material.quantity}</td>
        <td>
          <button onclick="editItem(${material.id}, '${material.name}', '${material.quantity}')">Edit</button>
          <button onclick="deleteItem(${material.id})">Remove</button>
        </td>
      `;
      if (material.type === 'Chemical') {
        chemicalTableBody.appendChild(row);
      } else if (material.type === 'Equipment') {
        equipmentTableBody.appendChild(row);
      }
    });
  } catch (error) {
    console.error('Fetch error:', error);
  }
}

document.addEventListener('DOMContentLoaded', fetchNeededMaterials);
      </script>
    </main>
  </div>
</body>
</html>