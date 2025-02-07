<?php
include 'SHDR_db.php';

if (isset($_GET['role'])) {
    $role = $conn->real_escape_string($_GET['role']);
    
    $sql = "SELECT u.name, u.user_role, t.date, t.task_description 
            FROM SHDR_users u 
            LEFT JOIN SHDR_task t ON u.name = t.name 
            WHERE u.user_role = ?
            ORDER BY u.name, t.date DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $role);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = array();
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
    } else {
        echo json_encode(array("error" => "No users found for this role"));
    }
    $stmt->close();
} else {
    echo json_encode(array("error" => "No role specified"));
}

$conn->close();
?>