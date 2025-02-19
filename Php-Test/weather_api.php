<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "Arun@2595", "weather_db");

if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed"]));
}

// Get city from URL
if (!isset($_GET['city'])) {
    echo json_encode(["error" => "City is required"]);
    exit;
}

$city = $_GET['city'];

// Fetch latest weather data for the given city
$stmt = $conn->prepare("SELECT * FROM weather_reports WHERE city = ? ORDER BY created_at DESC LIMIT 1");
$stmt->bind_param("s", $city);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode($result->fetch_assoc());
} else {
    echo json_encode(["error" => "No data found"]);
}

$conn->close();
?>
