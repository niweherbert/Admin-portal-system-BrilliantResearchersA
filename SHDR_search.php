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
            background-color:rgb(0, 71, 18);
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
            background-color:rgb(0, 71, 21);
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
            background-color:rgb(3, 107, 18);
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
    background-color:rgb(0, 71, 26);
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







    .feedback-container {
            margin-top: 20px;
        }

        .feedback-container input, .feedback-container textarea {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .feedback-container button {
            margin-top: 10px;
            padding: 10px 15px;
            background-color:rgb(4, 189, 66);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .feedback-container button:hover {
            background-color:rgb(0, 179, 81);
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


    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <a href="SHDR_admindashb.php">Home</a>
        <a href="SHDR_search.php">Search</a>
        <a href="SHDR_stock.php">Store & Requisition</a>
        <a href="SHDR_edit_profile.php">Edit Profile</a>
    </div>

    <!-- Top Bar with Icons -->
    <div class="icon-bar">
        <i class="fa-solid fa-bars" onclick="toggleSidebar()"></i> <!-- Hamburger menu icon -->
        <div class="dropdown">
            <i class="fa-solid fa-filter"></i> <!-- Filter/Sort icon -->
            <div class="dropdown-content">
                <a href="#" onclick="selectSort('name')">Sort by Name</a>
                <a href="#" onclick="selectSort('date')">Sort by Date</a>
                <a href="#" onclick="fetchAllRoles()">Sort by Role</a>
                   
            </div>
        </div>
        <i class="fa-solid fa-magnifying-glass hidden" id="search-icon" onclick="searchData()"></i> <!-- Search icon -->
       
        <div class="logout-icon" onclick="confirmLogout()">
            <img src="https://img.icons8.com/ios-glyphs/30/ffffff/logout-rounded.png" alt="Logout Icon">
            <span>Logout</span>
        </div>

        </div>

    </div>

    <!-- Main Content -->
    <div class="content">
        <h1>SUSTAINABLE HOMES DESIGNS RWANDA LTD</h1>
        <div id="results"></div>
        <div id="details"></div> <!-- Section to display additional details -->
    </div>

    <script>




function fetchAllRoles() {
    fetch('SHDR_fetch_all_roles.php')
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('results');
            container.innerHTML = ''; 
            if (data.error) {
                container.innerHTML = `<p>${data.error}</p>`;
            } else {
                data.forEach(item => {
                    const div = document.createElement('div');
                    div.textContent = item.user_role;
                    div.onclick = function() {
                        fetchUsersByRole(item.user_role);
                    };
                    container.appendChild(div);
                });
            }
        })
        .catch(error => {
            console.error('Error fetching roles:', error);
        });
}

function fetchUsersByRole(role) {
    fetch(`SHDR_fetch_users_by_role.php?role=${encodeURIComponent(role)}`)
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

                const headers = Object.keys(data[0]);
                const headerRow = document.createElement('tr');
                headers.forEach(header => {
                    const th = document.createElement('th');
                    th.textContent = header;
                    headerRow.appendChild(th);
                });
                thead.appendChild(headerRow);

                data.forEach(user => {
                    const row = document.createElement('tr');
                    headers.forEach(header => {
                        const td = document.createElement('td');
                        td.textContent = user[header];
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
            console.error('Error fetching users:', error);
        });
}



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
        fetch('SHDR_search_intern.php?sort=' + sort)
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
        fetch('SHDR_details_intern.php?type=' + type + '&value=' + encodeURIComponent(value))
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

                    const headers = Object.keys(data[0]);
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

                    // Display feedback form
                    displayFeedbackForm(value);
                }
            })
            .catch(error => {
                console.error('Error fetching details:', error);
            });
    }

    function displayFeedbackForm(name) {
        const container = document.getElementById('details');
        const feedbackContainer = document.createElement('div');
        feedbackContainer.classList.add('feedback-container');

        const feedbackTitle = document.createElement('h3');
        feedbackTitle.textContent = `Leave feedback for ${name}`;
        feedbackContainer.appendChild(feedbackTitle);

        const textarea = document.createElement('textarea');
        textarea.placeholder = 'Write your feedback here...';
        feedbackContainer.appendChild(textarea);

        const button = document.createElement('button');
        button.textContent = 'Submit Feedback';
        button.onclick = function() {
            submitFeedback(name, textarea.value);
        };
        feedbackContainer.appendChild(button);

        container.appendChild(feedbackContainer);
    }

    function submitFeedback(name, feedback) {
        if (feedback.trim() === '') {
            alert('Please write some feedback before submitting!');
            return;
        }

        fetch('SHDR_submit_feedback.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `name=${encodeURIComponent(name)}&feedback=${encodeURIComponent(feedback)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.success);
            } else {
                alert(data.error);
            }
        })
        .catch(error => {
            console.error('Error submitting feedback:', error);
        });
    }

    function fetchAllData(column) {
        fetch(`SHDR_fetch_all_data.php?column=${column}`)
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
        fetch(`SHDR_fetch_names_by_role.php?column=${column}&value=${encodeURIComponent(value)}`)
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
</body>
</html>