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
  <title>Stock Management | SUSTAINABLE HOMES DESIGNS RWANDA LTD</title>
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
      background-color:rgb(0, 102, 31);
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
      background-color:rgb(0, 63, 15);
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
      background-color:rgb(0, 102, 20);
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    form button:hover {
      background-color:rgb(0, 71, 25);
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
      
    <h1>SUSTAINABLE HOMES DESIGNS RWANDA LTD </h1></div>
    <div class="logout-icon" onclick="confirmLogout()">
      <img src="https://img.icons8.com/ios-glyphs/30/ffffff/logout-rounded.png" alt="Logout Icon">
      <span>Logout</span>
    </div>
  </header>
  <div class="container">
    <nav class="sidebar">
      <ul>
        <li><a href="SHDR_admindashb.php">Home</a></li>
        <li><a href="SHDR_stock.php" class="active">Store & Requisition</a></li>
        <li><a href="SHDR_edit_profile.php">Edit Profile</a></li>
      </ul>
    </nav>
    <main class="content">
      <h2>Needed Materials</h2>
      <table id="needed-table">
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

      <h2>Available Stock</h2>
      <table id="stock-table">
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

      <div id="stock-form">
        <h3>Add New Stock</h3>
        <form>
          <label for="stock-name">Name:</label>
          <input type="text" id="stock-name" name="name" required>
          <label for="stock-quantity">Quantity:</label>
          <input type="text" id="stock-quantity" name="quantity" required>
          <button type="button" onclick="addStock()">Add</button>
        </form>
      </div>

      <div id="edit-stock-form" style="display: none;">
        <h3>Edit Stock</h3>
        <form>
          <label for="edit-stock-name">Name:</label>
          <input type="text" id="edit-stock-name" name="name" required>
          <label for="edit-stock-quantity">Quantity:</label>
          <input type="text" id="edit-stock-quantity" name="quantity" required>
          <input type="hidden" id="edit-stock-id">
          <button type="button" onclick="saveStockEdit()">Save</button>
        </form>
      </div>

      <script>
        async function fetchData() {
          try {
            const neededResponse = await fetch('SHDR_fetch_needed_materials.php');
            if (!neededResponse.ok) {
              throw new Error('Network response was not ok');
            }
            const needed = await neededResponse.json();

            const neededTable = document.getElementById('needed-table').querySelector('tbody');
            neededTable.innerHTML = needed.map(item => `
              <tr>
                <td>${item.name}</td>
                <td>${item.quantity}</td>
                <td>
                  <button onclick="editItem(${item.id}, '${item.name}', '${item.quantity}')">Edit</button>
                  <button onclick="deleteItem(${item.id})">Remove</button>
                </td>
              </tr>
            `).join('');

            const stockResponse = await fetch('SHDR_fetch_stock.php');
            if (!stockResponse.ok) {
              throw new Error('Network response was not ok');
            }
            const stock = await stockResponse.json();

            const stockTable = document.getElementById('stock-table').querySelector('tbody');
            stockTable.innerHTML = stock.map(item => `
              <tr>
                <td>${item.name}</td>
                <td>${item.quantity}</td>
                <td>
                  <button onclick="editStock(${item.id}, '${item.name}', '${item.quantity}')">Edit</button>
                  <button onclick="deleteStock(${item.id})">Remove</button>
                </td>
              </tr>
            `).join('');
          } catch (error) {
            console.error('Fetch error:', error);
          }
        }

        async function deleteItem(id) {
          try {
            const formData = new FormData();
            formData.append('id', id);

            const response = await fetch('SHDR_delete_needed_material.php', {
              method: 'POST',
              body: formData,
            });

            const result = await response.json();
            if (result.success) {
              fetchData();
            } else {
              alert(result.message);
            }
          } catch (error) {
            console.error('Delete error:', error);
          }
        }

        function editItem(id, name, quantity) {
          document.getElementById('edit-id').value = id;
          document.getElementById('edit-name').value = name;
          document.getElementById('edit-quantity').value = quantity;
          document.getElementById('edit-form').style.display = 'block';
        }

        async function saveEdit() {
          const id = document.getElementById('edit-id').value;
          const name = document.getElementById('edit-name').value;
          const quantity = document.getElementById('edit-quantity').value;

          try {
            const formData = new FormData();
            formData.append('id', id);
            formData.append('name', name);
            formData.append('quantity', quantity);

            const response = await fetch('SHDR_update_needed_material.php', {
              method: 'POST',
              body: formData,
            });

            const result = await response.json();
            if (result.success) {
              fetchData();
              document.getElementById('edit-form').style.display = 'none';
            } else {
              alert(result.message);
            }
          } catch (error) {
            console.error('Update error:', error);
          }
        }

        async function addStock() {
          const name = document.getElementById('stock-name').value;
          const quantity = document.getElementById('stock-quantity').value;

          try {
            const formData = new FormData();
            formData.append('name', name);
            formData.append('quantity', quantity);

            const response = await fetch('SHDR_add_stock.php', {
              method: 'POST',
              body: formData,
            });

            const result = await response.json();
            if (result.success) {
              fetchData();
              document.getElementById('stock-name').value = '';
              document.getElementById('stock-quantity').value = '';
            } else {
              alert(result.message);
            }
          } catch (error) {
            console.error('Add stock error:', error);
          }
        }

        async function deleteStock(id) {
          try {
            const formData = new FormData();
            formData.append('id', id);

            const response = await fetch('SHDR_delete_stock.php', {
              method: 'POST',
              body: formData,
            });

            const result = await response.json();
            if (result.success) {
              fetchData();
            } else {
              alert(result.message);
            }
          } catch (error) {
            console.error('Delete stock error:', error);
          }
        }

        function editStock(id, name, quantity) {
          document.getElementById('edit-stock-id').value = id;
          document.getElementById('edit-stock-name').value = name;
          document.getElementById('edit-stock-quantity').value = quantity;
          document.getElementById('edit-stock-form').style.display = 'block';
        }

        async function saveStockEdit() {
          const id = document.getElementById('edit-stock-id').value;
          const name = document.getElementById('edit-stock-name').value;
          const quantity = document.getElementById('edit-stock-quantity').value;

          try {
            const formData = new FormData();
            formData.append('id', id);
            formData.append('name', name);
            formData.append('quantity', quantity);

            const response = await fetch('SHDR_update_stock.php', {
              method: 'POST',
              body: formData,
            });

            const result = await response.json();
            if (result.success) {
              fetchData();
              document.getElementById('edit-stock-form').style.display = 'none';
            } else {
              alert(result.message);
            }
          } catch (error) {
            console.error('Update stock error:', error);
          }
        }

        function confirmLogout() {
          const confirmation = confirm("Are you sure you want to log out?");
          if (confirmation) {
            window.location.href = "SHDR.php";
          }
        }

        fetchData();
      </script>
    </main>
  </div>
</body>
</html>