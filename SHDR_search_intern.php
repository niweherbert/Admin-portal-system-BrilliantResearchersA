<?php
$conn = new mysqli("localhost", "root", "", "SHDR_task");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$valid_columns = ['id', 'name', 'date']; // Add valid columns here

if (isset($_GET['sort']) && in_array($_GET['sort'], $valid_columns)) {
    $sort = $conn->real_escape_string($_GET['sort']);
    $query = "SELECT id, $sort FROM SHDR_daily_reports ORDER BY $sort";
    
    // Debugging statement to check the query
    error_log("Executing query: $query");
    
    $result = $conn->query($query);
    
    if ($result) {
        // Debugging statement to check the number of rows returned
        error_log("Number of rows returned: " . $result->num_rows);
        
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
    } else {
        // Debugging statement to check query error
        error_log("Query error: " . $conn->error);
        echo json_encode(['error' => 'Query failed']);
    }
} else {
    echo json_encode(['error' => 'Invalid sort column']);
}

$conn->close();
?>