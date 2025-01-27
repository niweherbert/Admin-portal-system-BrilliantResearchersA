<?php
include 'db.php';

header('Content-Type: application/json');

if (isset($_GET['column']) && isset($_GET['value'])) {
    $column = $_GET['column'];
    $value = $_GET['value'];

    // Debugging: Print the column and value
    error_log("Column: $column, Value: $value");

    $sql = "SELECT name FROM users WHERE $column = ? ORDER BY name";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        error_log("Prepare failed: " . $conn->error);
        echo json_encode(array("error" => "SQL prepare failed"));
        exit;
    }
    $stmt->bind_param("s", $value);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = array();
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
    } else {
        echo json_encode(array("error" => "No names found for this role"));
    }
    $stmt->close();
} else {
    echo json_encode(array("error" => "Invalid parameters"));
}

$conn->close();
?>