<?php
if (isset($_GET['type']) && isset($_GET['value'])) {
    $type = $_GET['type']; // e.g., 'date' or 'name'
    $value = $_GET['value']; // e.g., '2025-01-10' or 'John Doe'

    // Simulate database connection
    $db = new PDO("mysql:host=localhost;dbname=brilliant_researchers", "root", "");

    // Query the database for details
    $stmt = $db->prepare("SELECT * FROM daily_reports WHERE $type = :value");
    $stmt->bindParam(':value', $value);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($result) {
        echo json_encode($result);
    } else {
        echo json_encode(['error' => 'No details found for the selected value.']);
    }
} else {
    echo json_encode(['error' => 'Invalid request.']);
}
?>
