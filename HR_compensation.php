<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: HR_index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Dashboard</title>
    <style>
       body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
    }
    header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background-color: rgb(166, 195, 1);
        color: white;
        padding: 10px 20px;
        position: fixed;
        width: 100%;
        top: 0;
        z-index: 1000;
    }
    header h1 {
        font-size: 1.2rem;
        margin: 0;
    }
    .logo-container {
        display: flex;
        align-items: center;
    }
    .logo {
        height: 50px;
        margin-right: 10px;
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
        margin-top: 60px; /* Adjust based on header height */
    }
    .sidebar {
        width: 250px;
        background-color: rgb(161, 173, 0);
        color: #fff;
        padding-top: 20px;
        position: fixed;
        top: 60px; /* Adjust based on header height */
        height: calc(100% - 60px); /* Adjust based on header height */
        overflow-y: auto;
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
        background-color: rgb(67, 72, 0);
    }
    .content {
        flex-grow: 1;
        padding: 20px;
        margin-left: 250px; /* Adjust based on sidebar width */
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }
    th {
        background-color: #f2f2f2;
    }
    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    .add-btn {
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 10px 20px;
        font-size: 16px;
        cursor: pointer;
        border-radius: 5px;
    }
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(114, 122, 5, 0.4);
    }
    .modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 500px;
    }
    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }
    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }
    #addDataForm {
        display: flex;
        flex-direction: column;
    }
    #addDataForm input,
    #addDataForm select {
        margin-bottom: 10px;
        padding: 5px;
    }
    #addDataForm button {
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 10px;
        cursor: pointer;
    }
    .edit-btn, .delete-btn {
        padding: 5px 10px;
        margin: 0 5px;
        cursor: pointer;
    }
    .edit-btn {
        background-color: #2196F3;
        color: white;
        border: none;
    }
    .delete-btn {
        background-color: #f44336;
        color: white;
        border: none;
    }
    </style>
</head>
<body>
    <header>
        <div class="logo-container">
            <img src="BRA.png" alt="Company Logo" class="logo">
            <img src="SHDR.png.png" alt="Company Logo" class="logo">
            <h1>BRILLIANT RESEARCHERS AFRICA & SUSTAINABLE HOMES DESIGNS RWANDA LTD</h1>
        </div>
        <div class="logout-icon" onclick="confirmLogout()">
            <img src="https://img.icons8.com/ios-glyphs/30/ffffff/logout-rounded.png" alt="Logout Icon">
            <span>Logout......</span>
        </div>
    </header>
    <div class="container">
        <nav class="sidebar">
            <ul>
                <li><a href="HR_dashb.php">Home</a></li>
                <li><a href="HR_stock.php">Store & Requisition</a></li>
                <li><a href="HR_compensation.php" class="active">Compensation & Benefit</a></li>
                <li><a href="HR_srch.php">Search</a></li>
            </ul>
        </nav>
        <main class="content">
            <h2>HR Dashboard</h2>
            <div class="table-header">
                <h3>Employee Data</h3>
                <button id="addDataBtn" class="add-btn">+</button>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Department</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="employeeTableBody">
                    <!-- Table data will be dynamically inserted here -->
                </tbody>
            </table>
        </main>
    </div>

    <!-- Add Data Modal -->
    <div id="addDataModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Add New Employee Data</h2>
            <form id="addDataForm">
                <input type="text" name="name" placeholder="Name" required>
                <input type="text" name="role" placeholder="Role" required>
                <input type="text" name="department" placeholder="Department" required>
                <input type="text" name="description" placeholder="Description" required>
                <input type="number" name="amount" placeholder="Amount" required>
                <select name="status" required>
                    <option value="">Select Status</option>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
                <button type="submit">Add Data</button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addDataBtn = document.getElementById('addDataBtn');
            const modal = document.getElementById('addDataModal');
            const closeBtn = modal.querySelector('.close');
            const addDataForm = document.getElementById('addDataForm');
            const employeeTableBody = document.getElementById('employeeTableBody');

            // Open modal
            addDataBtn.onclick = function() {
                modal.style.display = "block";
            }

            // Close modal
            closeBtn.onclick = function() {
                modal.style.display = "none";
            }

            // Close modal if clicked outside
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }

            // Handle form submission
            addDataForm.onsubmit = function(e) {
                e.preventDefault();
                const formData = new FormData(addDataForm);
                const newRow = document.createElement('tr');
                
                for (let pair of formData.entries()) {
                    const cell = document.createElement('td');
                    cell.textContent = pair[1];
                    newRow.appendChild(cell);
                }

                // Add action buttons
                const actionCell = document.createElement('td');
                actionCell.innerHTML = '<button class="edit-btn">Edit</button> <button class="delete-btn">Delete</button>';
                newRow.appendChild(actionCell);

                employeeTableBody.appendChild(newRow);
                modal.style.display = "none";
                addDataForm.reset();
            }
        });

        function confirmLogout() {
            const confirmation = confirm("Are you sure you want to log out?");
            if (confirmation) {
                window.location.href = "HR_index.php";
            }
        }

















        document.addEventListener('DOMContentLoaded', function() {
        const addDataBtn = document.getElementById('addDataBtn');
        const modal = document.getElementById('addDataModal');
        const closeBtn = modal.querySelector('.close');
        const addDataForm = document.getElementById('addDataForm');
        const employeeTableBody = document.getElementById('employeeTableBody');
        let editIndex = null;

        // Load data from local storage
        loadTableData();

        // Open modal
        addDataBtn.onclick = function() {
            modal.style.display = "block";
        }

        // Close modal
        closeBtn.onclick = function() {
            modal.style.display = "none";
        }

        // Close modal if clicked outside
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        // Handle form submission
        addDataForm.onsubmit = function(e) {
            e.preventDefault();
            const formData = new FormData(addDataForm);
            const rowData = {};

            for (let pair of formData.entries()) {
                rowData[pair[0]] = pair[1];
            }

            if (editIndex !== null) {
                updateTableRow(editIndex, rowData);
                editIndex = null;
            } else {
                addTableRow(rowData);
            }

            saveTableData();
            modal.style.display = "none";
            addDataForm.reset();
        }

        function addTableRow(rowData) {
            const newRow = document.createElement('tr');

            for (let key in rowData) {
                const cell = document.createElement('td');
                cell.textContent = rowData[key];
                newRow.appendChild(cell);
            }

            // Add action buttons
            const actionCell = document.createElement('td');
            actionCell.innerHTML = '<button class="edit-btn">Edit</button> <button class="delete-btn">Delete</button>';
            newRow.appendChild(actionCell);

            employeeTableBody.appendChild(newRow);
            addRowEventListeners(newRow);
        }

        function updateTableRow(index, rowData) {
            const rows = employeeTableBody.getElementsByTagName('tr');
            const row = rows[index];

            let cellIndex = 0;
            for (let key in rowData) {
                row.cells[cellIndex].textContent = rowData[key];
                cellIndex++;
            }
        }

        function addRowEventListeners(row) {
            const editBtn = row.querySelector('.edit-btn');
            const deleteBtn = row.querySelector('.delete-btn');

            editBtn.onclick = function() {
                editIndex = Array.from(employeeTableBody.children).indexOf(row);
                const cells = row.getElementsByTagName('td');
                addDataForm.name.value = cells[0].textContent;
                addDataForm.role.value = cells[1].textContent;
                addDataForm.department.value = cells[2].textContent;
                addDataForm.description.value = cells[3].textContent;
                addDataForm.amount.value = cells[4].textContent;
                addDataForm.status.value = cells[5].textContent;
                modal.style.display = "block";
            }

            deleteBtn.onclick = function() {
                row.remove();
                saveTableData();
            }
        }

        function saveTableData() {
            const rows = employeeTableBody.getElementsByTagName('tr');
            const tableData = [];

            for (let row of rows) {
                const rowData = {};
                const cells = row.getElementsByTagName('td');

                rowData.name = cells[0].textContent;
                rowData.role = cells[1].textContent;
                rowData.department = cells[2].textContent;
                rowData.description = cells[3].textContent;
                rowData.amount = cells[4].textContent;
                rowData.status = cells[5].textContent;

                tableData.push(rowData);
            }

            localStorage.setItem('employeeTableData', JSON.stringify(tableData));
        }

        function loadTableData() {
            const tableData = JSON.parse(localStorage.getItem('employeeTableData')) || [];
            tableData.forEach(rowData => {
                addTableRow(rowData);
            });
        }
    });

    function confirmLogout() {
        const confirmation = confirm("Are you sure you want to log out?");
        if (confirmation) {
            window.location.href = "HR_index.php";
        }
    }


    </script>
</body>
</html>