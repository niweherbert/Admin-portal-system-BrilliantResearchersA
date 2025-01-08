<?php
include 'searchdb.php';

$type = $_GET['type'];
$value = $_GET['value'];
$response = ['success' => false];

if ($type === 'name') {
    $sql = "SELECT * FROM daily_reports WHERE name = ?";
} elseif ($type === 'date') {
    $sql = "SELECT * FROM daily_reports WHERE date = ?";
} else {
    echo json_encode($response);
    exit;
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $value);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();

    $response['success'] = true;
    $response['data'] = $data;
} else {
    error_log("No results found for $type: $value");
}

echo json_encode($response);

$stmt->close();
$conn->close();
?>