<?php
$conn = new mysqli("localhost", "root", "", "brilliant_researchers");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);
    $query = "SELECT * FROM daily_reports WHERE id = $id";
    $result = $conn->query($query);
    $data = $result->fetch_assoc();
    if ($data) {
        echo "<h1>Summary of " . htmlspecialchars($data['name']) . "</h1>";
        echo "<ul>";
        foreach ($data as $key => $value) {
            echo "<li><strong>" . htmlspecialchars($key) . ":</strong> " . htmlspecialchars($value) . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No report found with ID: " . htmlspecialchars($id) . "</p>";
    }
} else {
    echo "<p>ID not set</p>";
}

$conn->close();
?>