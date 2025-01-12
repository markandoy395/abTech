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

    // Get data from the POST request
    $data = json_decode(file_get_contents('php://input'), true);
    $requestId = $data['request_id'] ?? null;
    $newStatus = $data['status'] ?? null;

    if (!$requestId || !$newStatus) {
        echo json_encode(['success' => false, 'error' => 'Invalid request data']);
        exit;
    }

    // Update the status in the database
    $query = "UPDATE requests SET status = :status WHERE id = :request_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':status', $newStatus);
    $stmt->bindParam(':request_id', $requestId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to update status']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
