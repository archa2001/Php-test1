<?php
$host = "localhost";
$username = "root";
$password = "dummy";//dummmy password
$database = "weather_db";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// API details
$apiKey = "101f940530bf3f97b7d3cb6a33c6ee37"; 

// Define an array of cities
$cities = ["Mumbai", "London", "Tokyo", "Sydney", "Paris"];

foreach ($cities as $city) {
    $url = "https://api.openweathermap.org/data/2.5/weather?q=$city&appid=$apiKey&units=metric";

    // Fetch data from API
    $response = file_get_contents($url);
    $weatherData = json_decode($response, true);

    if (isset($weatherData['main'])) {
        $temperature = $weatherData['main']['temp'];
        $humidity = $weatherData['main']['humidity'];
        $description = $weatherData['weather'][0]['description'];

        $stmt = $conn->prepare("INSERT INTO weather_reports (city, temperature, humidity, description) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sdis", $city, $temperature, $humidity, $description);
        $stmt->execute();

        echo "Weather data for $city inserted successfully.\n";
    } else {
        echo "Failed to fetch weather data for $city.\n";
    }
}

$conn->close();
?>
