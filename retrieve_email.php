<?php
// Database configuration
$host = 'localhost';
$dbname = 'abtech'; // Replace with your database name
$username = 'root';
$password = '';

header('Content-Type: application/json');

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if user_id is provided via GET request
    if (isset($_GET['user_id']) && is_numeric($_GET['user_id'])) {
        $userId = intval($_GET['user_id']);

        // Prepare and execute the query to retrieve email from users table
        $stmt = $pdo->prepare("SELECT email FROM users WHERE id = :user_id LIMIT 1");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        // Check if a row is returned
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Return email as JSON response
            echo json_encode(['success' => true, 'email' => $user['email']]);
        } else {
            echo json_encode(['success' => false, 'message' => 'User not found.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid or missing user_id.']);
    }
} catch (PDOException $e) {
    // Handle database errors
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
