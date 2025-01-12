<?php
// Database configuration
$host = "localhost";
$username = "root"; // Replace with your database username
$password = "";     // Replace with your database password
$database = "abtech"; // Replace with your database name

// Create a connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Database connection failed."]));
}

// Retrieve data from POST request
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['username']) || !isset($data['password'])) {
    echo json_encode(["success" => false, "message" => "Invalid input."]);
    exit();
}

$username = $conn->real_escape_string($data['username']);
$password = $conn->real_escape_string($data['password']);

// Query to validate username and password
$stmt = $conn->prepare("SELECT id, role FROM users WHERE name = ? AND password = ?");
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode([
        "success" => true,
        "message" => "Login successful.",
        "user_id" => $row['id'],
        "role" => $row['role']
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Invalid username or password."]);
}
$stmt->close();

