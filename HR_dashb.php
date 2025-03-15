<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname1 = "secure_login_system";
$dbname2 = "SHDR_users";

// Create connection to first database
$conn1 = new mysqli($servername, $username, $password, $dbname1);
if ($conn1->connect_error) {
    die("Connection failed: " . $conn1->connect_error);
}

// Create connection to second database
$conn2 = new mysqli($servername, $username, $password, $dbname2);
if ($conn2->connect_error) {
    die("Connection failed: " . $conn2->connect_error);
}

// Get total employees from first database
$totalEmployees1 = $conn1->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];

// Get total employees from second database
$totalEmployees2 = $conn2->query("SELECT COUNT(*) AS total FROM SHDR_users")->fetch_assoc()['total'];

// Sum total employees from both databases
$totalEmployees = $totalEmployees1 + $totalEmployees2;

// Get names of employees from first database
$employees1 = $conn1->query("SELECT name FROM users")->fetch_all(MYSQLI_ASSOC);

// Get names of employees from second database
$employees2 = $conn2->query("SELECT name FROM SHDR_users")->fetch_all(MYSQLI_ASSOC);

$conn1->close();
$conn2->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.4/main.min.css">

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color:rgb(255, 255, 255);
            color: #333;
        }
        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color:rgb(183, 206, 6);
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
            background-color: rgb(131, 147, 9);
            color: #fff;
            padding: 0px 10px 0px 0px; /* Adjusted padding values */
            margin: 0;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar ul li {
            margin: px 0;
        }
        .sidebar ul li a {
            color: #fff;
            text-decoration: none;
            padding: 10px;
            display: block;
        }
        .sidebar ul li a:hover,
        .sidebar ul li a.active {
            background-color:rgb(55, 63, 0);
        }
        .content {
            flex: 1;
            padding: 20px;
        }
        .calendar-day.today {
            background-color: #ffeb3b;
        }
        #departmentChart {
            max-width: 45%;
            height: 300px; /* Adjust the height as needed */
        }
        .chart-container {
            margin-top: 20px; /* Reduce the space above the chart */
        }
        .logo-container {
            display: flex;
            align-items: center;
        }
        .logo {
            height: 50px;
            margin-right: 10px;
        }
        .container {
            margin-left: 0;
            padding-left: 0;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo-container">
            <img src="BRA.png" alt="Company Logo" class="logo">
            <img src="SHDR.png.PNG" alt="Company Logo" class="logo">
            <h1>BRILLIANT RESEARCHERS AFRICA & SUSTAINABLE HOMES DESIGN RWANDA LTD</h1>
        </div>
        <div class="logout-icon" onclick="confirmLogout()">
            <img src="https://img.icons8.com/ios-glyphs/30/ffffff/logout-rounded.png" alt="Logout Icon">
            <span>Logout</span>
        </div>
    </header>
    <div class="container">
        <nav class="sidebar">
            <ul>
                <li><a href="HR_dashb.php" class="active">Home</a></li>
                <li><a href="HR_stock.php">Store & Requisition</a></li>
                <li><a href="HR_compensation.php">Compensation & Benefit</a></li>
                <li><a href="HR_srch.php">Search</a></li>
            </ul>
        </nav>
        <main class="content">
            <h2>HR Dashboard</h2>
            <div class="row text-center">
                <div class="col-md-4">
                    <a href="#" onclick="showTotalEmployees()" style="text-decoration: none; color: inherit;">
                        <div class="card p-3">
                            <h5>Total Employees</h5>
                            <h3><?php echo $totalEmployees; ?></h3>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="#" onclick="showSHDREmployees()" style="text-decoration: none; color: inherit;">
                        <div class="card p-3">
                            <h5>SHDR Employees</h5>
                            <h3><?php echo $totalEmployees2; ?></h3>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="#" onclick="showBRAEmployees()" style="text-decoration: none; color: inherit;">
                        <div class="card p-3">
                            <h5>BRA Employees</h5>
                            <h3><?php echo $totalEmployees1; ?></h3>
                        </div>
                    </a>
                </div>
            </div>

            <div class="row chart-container">
                <div class="col-md-12">
                    <canvas id="departmentChart"></canvas>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-12">
                    <div id="simpleCalendar"></div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal for adding activities -->
    <div class="modal fade" id="activityModal" tabindex="-1" aria-labelledby="activityModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="activityModalLabel">Add Activity</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="activityForm">
                        <div class="mb-3">
                            <label for="activityDate" class="form-label">Date</label>
                            <input type="text" class="form-control" id="activityDate" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="activityDescription" class="form-label">Activity</label>
                            <input type="text" class="form-control" id="activityDescription" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
   
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        var ctx = document.getElementById('departmentChart').getContext('2d');
        var departmentChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['SHDR', 'BRA'],
                datasets: [{
                    data: [60, 40],
                    backgroundColor: ['#4CAF50', '#FFCC00']
                }]
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('simpleCalendar');
            var today = new Date();
            var currentMonth = today.getMonth();
            var currentYear = today.getFullYear();

            function renderCalendar(month, year) {
                var firstDay = new Date(year, month, 1).getDay();
                var daysInMonth = new Date(year, month + 1, 0).getDate();

                var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
                var calendarHTML = '<div class="text-center mb-3"><button id="prevMonth" class="btn btn-secondary btn-sm me-2">&lt;</button><h4 class="d-inline">' + monthNames[month] + ' ' + year + '</h4><button id="nextMonth" class="btn btn-secondary btn-sm ms-2">&gt;</button></div>';
                calendarHTML += '<table class="table table-bordered">';
                calendarHTML += '<thead><tr><th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th></tr></thead>';
                calendarHTML += '<tbody><tr>';

                for (var i = 0; i < firstDay; i++) {
                    calendarHTML += '<td></td>';
                }

                for (var day = 1; day <= daysInMonth; day++) {
                    if ((day + firstDay - 1) % 7 === 0 && day !== 1) {
                        calendarHTML += '</tr><tr>';
                    }
                    var isToday = (day === today.getDate() && month === today.getMonth() && year === today.getFullYear()) ? 'today' : '';
                    calendarHTML += '<td class="calendar-day ' + isToday + '" data-date="' + year + '-' + (month + 1) + '-' + day + '">' + day + '</td>';
                }

                calendarHTML += '</tr></tbody></table>';
                calendarEl.innerHTML = calendarHTML;

                document.querySelectorAll('.calendar-day').forEach(function(day) {
                    day.addEventListener('click', function() {
                        var date = this.getAttribute('data-date');
                        document.getElementById('activityDate').value = date;
                        var activityModal = new bootstrap.Modal(document.getElementById('activityModal'));
                        activityModal.show();
                    });
                });

                document.getElementById('prevMonth').addEventListener('click', function() {
                    currentMonth--;
                    if (currentMonth < 0) {
                        currentMonth = 11;
                        currentYear--;
                    }
                    renderCalendar(currentMonth, currentYear);
                });

                document.getElementById('nextMonth').addEventListener('click', function() {
                    currentMonth++;
                    if (currentMonth > 11) {
                        currentMonth = 0;
                        currentYear++;
                    }
                    renderCalendar(currentMonth, currentYear);
                });
            }

            renderCalendar(currentMonth, currentYear);

            document.getElementById('activityForm').addEventListener('submit', function(event) {
                event.preventDefault();
                var date = document.getElementById('activityDate').value;
                var description = document.getElementById('activityDescription').value;
                alert('Activity on ' + date + ': ' + description);
                var activityModal = bootstrap.Modal.getInstance(document.getElementById('activityModal'));
                activityModal.hide();
            });
        });

        function confirmLogout() {
            const confirmation = confirm("Are you sure you want to log out?");
            if (confirmation) {
                window.location.href = "HR_index.php";
            }
        }

        function showTotalEmployees() {
            alert('Total Employees: <?php echo $totalEmployees; ?>');
        }

        function showSHDREmployees() {
            const employees = <?php echo json_encode(array_column($employees2, 'name')); ?>;
            alert('SHDR Employees:\n' + employees.join('\n'));
        }

        function showBRAEmployees() {
            const employees = <?php echo json_encode(array_column($employees1, 'name')); ?>;
            alert('BRA Employees:\n' + employees.join('\n'));
        }


        function showTotalEmployees() {
        alert('Total Employees: <?php echo $totalEmployees; ?>');
    }

    function showSHDREmployees() {
        const employees = <?php echo json_encode(array_column($employees2, 'name')); ?>;
        alert('SHDR Employees:\n' + employees.join('\n'));
    }

    function showBRAEmployees() {
        const employees = <?php echo json_encode(array_column($employees1, 'name')); ?>;
        alert('BRA Employees:\n' + employees.join('\n'));
    }

    </script>
</body>
</html>