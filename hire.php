<?php
// Database configuration
$host = 'localhost';
$dbname = 'abtech';
$username = 'root';
$password = '';

try {
    // Create a new PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get the input JSON data
    $data = json_decode(file_get_contents('php://input'), true);

    // Check if necessary data is provided
    if (!isset($data['user_id']) || !isset($data['provider_id']) || !isset($data['service_type'])) {
        echo json_encode(['error' => 'Missing data. Please provide user_id, provider_id, and service_type.']);
        exit;
    }

    // Extract the data
    $userId = $data['user_id'];
    $providerId = $data['provider_id'];
    $serviceType = $data['service_type'];

    // Insert the hire request into the database
    $query = "INSERT INTO requests (user_id, id, service_type, status, request_date, created_at) 
              VALUES (:user_id, :provider_id, :service_type, 'pending', NOW(), NOW())";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $userId);
    $stmt->bindParam(':provider_id', $providerId);
    $stmt->bindParam(':service_type', $serviceType);

    // Execute the query
    $stmt->execute();

    // Return a success response
    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    // Handle error if there is an issue with the database
    echo json_encode(['error' => $e->getMessage()]);
}
?>
