<?php
include 'searchdb.php';

$data = json_decode(file_get_contents('php://input'), true);
$name = $data['name'];

$response = ['success' => false];

$sql = "SELECT * FROM daily_reports WHERE name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $name);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $report = $result->fetch_assoc();
    $response['success'] = true;
    $response['tasks'] = [
        ['name' => 'Task 1', 'file' => 'file1.pdf'],
        ['name' => 'Task 2', 'file' => 'file2.pdf']
    ];
    $response['weeklyParticipation'] = $report['weeklyParticipation'];
    $response['transportFee'] = $report['transportFee'];
    $response['supervisorName'] = $report['supervisorName'];
} else {
    $response['message'] = 'Person not found in the database.';
}

echo json_encode($response);

$stmt->close();
$conn->close();
?>