<?php
include 'searchdb.php';

$type = $_GET['type'];
$response = ['success' => false, 'list' => []];

if ($type === 'name') {
    $sql = "SELECT DISTINCT name FROM daily_reports";
} elseif ($type === 'date') {
    $sql = "SELECT DISTINCT date FROM daily_reports";
} elseif ($type === 'role') {
    $sql = "SELECT DISTINCT role FROM daily_reports";
} else {
    echo json_encode($response);
    exit;
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $response['list'][] = $row[$type];
    }
    $response['success'] = true;
}

echo json_encode($response);

$conn->close();
?>