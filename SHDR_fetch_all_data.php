<?php
require_once 'SHDR_db.php';

header('Content-Type: application/json');

$valid_columns = ['specialization', 'role']; // Update with correct column names

if (isset($_GET['column']) && in_array($_GET['column'], $valid_columns)) {
    $column = $_GET['column'];
    
    $sql = "SELECT DISTINCT $column FROM SHDR_users WHERE $column IS NOT NULL AND $column != '' ORDER BY $column";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $data = array();
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
    } else {
        echo json_encode(array("error" => "No data found"));
    }
} else {
    echo json_encode(array("error" => "Invalid column specified"));
}

$conn->close();
?>