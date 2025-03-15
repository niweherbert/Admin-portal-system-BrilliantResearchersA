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
    <title>Search and Sort Researchers</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f4f4f9;
        }
        .sidebar {
            height: 100%;
            width: 200px;
            position: fixed;
            top: 0;
            left: 0;
            background-color:rgb(160, 175, 0);
            padding-top: 20px;
        }
        .sidebar a {
            padding: 10px 15px;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: block;
            transition: background-color 0.3s;
        }
        .sidebar a:hover {
            background-color: #575757;
        }
        .icon-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color:rgb(164, 208, 3);
            color: white;
            padding: 10px 15px;
            margin-left: 200px; /* To account for sidebar */
        }
        .icon-bar i {
            font-size: 24px;
            cursor: pointer;
        }
        .content {
            margin-left: 220px;
            padding: 20px;
        }
        .dropdown {
            position: relative;
            display: inline-block;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }
        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            transition: background-color 0.3s;
        }
        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }
        .dropdown:hover .dropdown-content {
            display: block;
        }
        #results div {
            padding: 10px;
            background-color: white;
            margin: 10px 0;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: background-color 0.3s;
            cursor: pointer;
        }
        #results div:hover {
            background-color: #f1f1f1;
        }
        .hidden {
            display: none;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color:rgb(202, 196, 5);
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ddd;
        }

        .sidebar {
            height: 100%;
            width: 200px;
            position: fixed;
            top: 0;
            left: 0;
            background-color:rgb(171, 183, 2);
            padding-top: 20px;
            transition: width 0.3s ease, transform 0.3s ease;
        }

        .sidebar.hidden {
            transform: translateX(-200px); /* Slide out the sidebar */
        }

        .icon-bar {
            margin-left: 200px;
            transition: margin-left 0.3s ease;
        }

        .icon-bar.expanded {
            margin-left: 0; /* Adjust for hidden sidebar */
        }

        .content {
            margin-left: 220px;
            padding: 20px;
            transition: margin-left 0.3s ease;
        }

        .content.expanded {
            margin-left: 20px; /* Adjust for hidden sidebar */
        }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0; /* Align to the right edge of the container */
            background-color: #f9f9f9;
            min-width: 120px; /* Adjust width as needed */
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
            border-radius: 5px;
            padding: 5px 0; /* Spacing inside the dropdown */
        }

        .dropdown-content a {
            color: black;
            padding: 10px 16px;
            text-decoration: none;
            display: block;
            transition: background-color 0.3s;
            text-align: left;
            font-size: 14px;
        }

        .dropdown-content a:hover {
            background-color: #ddd;
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

        .nested-dropdown {
            position: relative;
        }

        .nested-dropdown-content {
            display: none;
            position: absolute;
            left: 100%;
            top: 0;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .nested-dropdown:hover .nested-dropdown-content {
            display: block;
        }

        .nested-dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .nested-dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .sidebar {
            list-style-type: none;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <li><a href="HR_dashb.php" class="active">Home</a></li>
        <li><a href="HR_stock.php">Store & Requisition</a></li>
        <li><a href="HR_compensation.php">Compensation & Benefit</a></li>
        <li><a href="HR_srch.php">Search</a></li>
    </div>

    <!-- Top Bar with Icons -->
    <div class="icon-bar">
        <i class="fa-solid fa-bars" onclick="toggleSidebar()"></i> <!-- Hamburger menu icon -->
        <div class="dropdown">
            <i class="fa-solid fa-filter"></i> <!-- Filter/Sort icon -->
            <div class="dropdown-content">
                <a href="#" onclick="selectSort('name')">Sort by Name</a>
                <a href="#" onclick="selectSort('date')">Sort by Date</a>
                <div class="nested-dropdown">
                    <a href="#">Sort by Role</a>
                    <div class="nested-dropdown-content">
                        <a href="#" onclick="fetchAllData('specialization')">All Specializations</a>
                        <a href="#" onclick="fetchAllData('final_role')">All Final Roles</a>
                    </div>
                </div>
            </div>
        </div>
        <i class="fa-solid fa-magnifying-glass hidden" id="search-icon" onclick="searchData()"></i> <!-- Search icon -->
       
        <div class="logout-icon" onclick="confirmLogout()">
            <img src="https://img.icons8.com/ios-glyphs/30/ffffff/logout-rounded.png" alt="Logout Icon">
            <span>Logout</span>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content">
        <h1>Daily Records. </h1>
        <div id="results"></div>
        <div id="details"></div> <!-- Section to display additional details -->
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const iconBar = document.querySelector('.icon-bar');
            const content = document.querySelector('.content');

            sidebar.classList.toggle('hidden');
            iconBar.classList.toggle('expanded');
            content.classList.toggle('expanded');
        }

        let selectedSort = "";

        function selectSort(sortType) {
            selectedSort = sortType;
            const searchIcon = document.getElementById('search-icon');
            searchIcon.classList.remove('hidden'); 
        }

        function searchData() {
            if (!selectedSort) {
                alert("Please select a sorting option first!");
                return;
            }
            fetchData(selectedSort);
        }

        function fetchData(sort) {
            fetch('search_intern.php?sort=' + sort)
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('results');
                    container.innerHTML = ''; 
                    if (data.error) {
                        container.innerHTML = `<p>${data.error}</p>`;
                    } else {
                        data.forEach(item => {
                            const div = document.createElement('div');
                            div.textContent = item[sort];
                            div.onclick = function() {
                                fetchDetails(sort, item[sort]);
                            };
                            container.appendChild(div);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        }

        function fetchDetails(type, value) {
            fetch('details_intern.php?type=' + type + '&value=' + encodeURIComponent(value))
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('details');
                    container.innerHTML = ''; 
                    if (data.error) {
                        container.innerHTML = `<p>${data.error}</p>`;
                    } else {
                        const table = document.createElement('table');
                        const thead = document.createElement('thead');
                        const tbody = document.createElement('tbody');

                        const headers = Object.keys(data[0]).filter(header => !['time_left', 'time_arrived', 'tasks_performed', 'file_upload', 'supervisor_name'].includes(header));
                        const headerRow = document.createElement('tr');
                        headers.forEach(header => {
                            const th = document.createElement('th');
                            th.textContent = header;
                            headerRow.appendChild(th);
                        });
                        thead.appendChild(headerRow);

                        data.forEach(detail => {
                            const row = document.createElement('tr');
                            headers.forEach(header => {
                                const td = document.createElement('td');
                                td.textContent = detail[header];
                                row.appendChild(td);
                            });
                            tbody.appendChild(row);
                        });

                        table.appendChild(thead);
                        table.appendChild(tbody);
                        container.appendChild(table);
                    }
                })
                .catch(error => {
                    console.error('Error fetching details:', error);
                });
        }

        function fetchAllData(column) {
            fetch(`fetch_all_data.php?column=${column}`)
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('results');
                    container.innerHTML = ''; 
                    if (data.error) {
                        container.innerHTML = `<p>${data.error}</p>`;
                    } else {
                        data.forEach(item => {
                            const div = document.createElement('div');
                            div.textContent = item[column];
                            div.onclick = function() {
                                fetchNames(column, item[column]);
                            };
                            container.appendChild(div);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        }

        function fetchNames(column, value) {
            fetch(`fetch_names_by_role.php?column=${column}&value=${encodeURIComponent(value)}`)
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('results');
                    container.innerHTML = ''; 
                    if (data.error) {
                        container.innerHTML = `<p>${data.error}</p>`;
                    } else {
                        data.forEach(item => {
                            const div = document.createElement('div');
                            div.textContent = item.name;
                            div.onclick = function() {
                                fetchDetails('name', item.name);
                            };
                            container.appendChild(div);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching names:', error);
                });
        }
    </script>
</body>
</html>