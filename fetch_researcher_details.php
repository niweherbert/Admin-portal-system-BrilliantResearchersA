<?php
include 'db.php';

if (isset($_GET['name'])) {
    $name = $_GET['name'];
    
    $sql = "SELECT * FROM daily_reports WHERE name = ? ORDER BY date DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = array();
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
    } else {
        echo json_encode(array("error" => "No data found for this researcher"));
    }
} else {
    echo json_encode(array("error" => "Invalid parameters"));
}

$conn->close();
?>