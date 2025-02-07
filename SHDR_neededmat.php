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
    <title>Needed Materials / SUSTAINABLE HOMES DESIGN RWANDA LTD </title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
        }
        .sidebar {
            width: 250px;
            background-color:rgb(0, 71, 30);
            color: #fff;
            height: 100vh; /* Full height */
            position: fixed; /* Fixed position */
            top: 0;
            left: 0;
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
            background-color:rgb(16, 81, 2);
        }
        .content {
            margin-left: 250px; /* Same as the width of the sidebar */
            padding: 20px;
            width: calc(100% - 250px); /* Adjust content width */
        }
        .add-item {
            margin: 20px 0;
        }
        .table-container {
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
</style>
</head>
<body> 
    <nav class="sidebar">
        <ul>
            <li><a href="SHDR_userdashboard.php">Home</a></li>
            <li><a href="SHDR_feedback.php">Feedback</a></li>
            <li><a href="SHDR_neededmat.php" class="active">Needed Materials</a></li>
            <li><a href="SHDR_edit-profile.php">Edit Profile</a></li>
        </ul>
    </nav>
    <div class="content">
        <h1>Needed Materials</h1>
        <div class="add-item">
            <input type="text" id="item-name" placeholder="Enter item name">
            <input type="text" id="item-quantity" placeholder="Enter quantity (e.g., 5Kg)">
            <button onclick="addItem()">Add Item</button>
        </div>
        <div class="table-container">
            <table id="materials-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Items</th>
                        <th>Quantity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Dynamic rows will be added here -->
                </tbody>
            </table>
        </div>
    </div>
    <script>
        let SHDR_materials = JSON.parse(localStorage.getItem('SHDR_materials')) || [];

function addItem() {
    const itemName = document.getElementById('item-name').value;
    const itemQuantity = document.getElementById('item-quantity').value;

    if (itemName && itemQuantity) {
        const newItem = { name: itemName, quantity: itemQuantity };

        // Send data to the server
        fetch('SHDR_save_material.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(newItem)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Add the new item to the table
                SHDR_materials.push(newItem);
                localStorage.setItem('SHDR_materials', JSON.stringify(SHDR_materials));
                renderTable();

                // Clear the input fields
                document.getElementById('item-name').value = '';
                document.getElementById('item-quantity').value = ''; 
            } else {
                alert('Failed to save item');
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

function renderTable() {
    const tableBody = document.getElementById('materials-table').getElementsByTagName('tbody')[0];
    tableBody.innerHTML = '';

    const SHDR_materials = JSON.parse(localStorage.getItem('SHDR_materials')) || []; // Fetch from new key

    SHDR_materials.forEach((material, index) => {
        const row = tableBody.insertRow();
        row.insertCell(0).innerText = index + 1;
        row.insertCell(1).innerText = material.name;
        row.insertCell(2).innerText = material.quantity;
        row.insertCell(3).innerHTML = `<button onclick="deleteItem(${index})">Delete</button>`;
    });
}

function deleteItem(index) {
    SHDR_materials.splice(index, 1);
    localStorage.setItem('SHDR_materials', JSON.stringify(SHDR_materials));
    renderTable();
}
    </script>
</body>
</html>