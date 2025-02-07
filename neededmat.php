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
    <title>Needed Materials</title>
    <style>








body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
        }
        .sidebar {
            width: 250px;
            background-color: #002147;
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
            background-color: #001f3f;
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
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
        }
        .sidebar {
            width: 250px;
            background-color: #002147;
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
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <ul>
            <li><a href="dashboard.php">Home</a></li>
            <li><a href="neededmat.php" class="active">Needed Materials</a></li>
            <li><a href="leave_Permission.php"> Leave & Permission</a></li>
            <li><a href="edit-profile.php">Edit Profile</a></li>
        </ul>
    </div>
    <div class="content">
        <h1>Needed Materials</h1>
        <div class="add-item">
            <input type="text" id="item-name" placeholder="Enter item name">
            <input type="text" id="item-quantity" placeholder="Enter quantity (e.g., 5Kg)">
            <select id="item-type">
                <option value="">Select Type</option>
                <option value="Chemical">Chemical</option>
                <option value="Equipment">Equipment</option>
            </select>
            <button onclick="addItem()">Add Item</button>
        </div>
        <div class="table-container">
            <h2>Chemicals</h2>
            <table id="chemical-table">
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
            <h2>Equipment</h2>
            <table id="equipment-table">
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
        let materials = JSON.parse(localStorage.getItem('materials')) || [];
        let editingIndex = null;

        document.addEventListener('DOMContentLoaded', renderTable);

        function addItem() {
            const itemName = document.getElementById('item-name').value;
            const itemQuantity = document.getElementById('item-quantity').value;
            const itemType = document.getElementById('item-type').value;

            if (itemName && itemQuantity && itemType) {
                const newItem = { name: itemName, quantity: itemQuantity, type: itemType };

                // Send data to the server
                fetch('save_material.php', {
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
                        materials.push(newItem);
                        localStorage.setItem('materials', JSON.stringify(materials));
                        renderTable();

                        // Clear the input fields
                        document.getElementById('item-name').value = '';
                        document.getElementById('item-quantity').value = '';
                        document.getElementById('item-type').value = '';
                    } else {
                        alert('Failed to save item');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }

        function renderTable() {
            const chemicalTableBody = document.getElementById('chemical-table').getElementsByTagName('tbody')[0];
            const equipmentTableBody = document.getElementById('equipment-table').getElementsByTagName('tbody')[0];
            chemicalTableBody.innerHTML = '';
            equipmentTableBody.innerHTML = '';

            materials.forEach((material, index) => {
                const row = document.createElement('tr');
                row.insertCell(0).innerText = index + 1;
                row.insertCell(1).innerText = material.name;
                row.insertCell(2).innerText = material.quantity;
                row.insertCell(3).innerHTML = `<button onclick="deleteItem(${index})">Delete</button>`;

                if (material.type === 'Chemical') {
                    chemicalTableBody.appendChild(row);
                } else if (material.type === 'Equipment') {
                    equipmentTableBody.appendChild(row);
                }
            });
        }

        function deleteItem(index) {
            materials.splice(index, 1);
            localStorage.setItem('materials', JSON.stringify(materials));
            renderTable();
        }
    </script>
</body>
</html>