<?php
include 'SHDR_db.php';

$sql = "SELECT DISTINCT user_role FROM SHDR_users WHERE user_role IS NOT NULL AND user_role != '' ORDER BY user_role";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $data = array();
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data);
} else {
    echo json_encode(array("error" => "No roles found"));
}

$conn->close();
?>