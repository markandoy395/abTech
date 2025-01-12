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

    // Get the user ID from the query string
    $userId = $_GET['user_id'] ?? null;

    if (!$userId) {
        echo json_encode(['error' => 'User ID is required']);
        exit;
    }

    // Fetch profile data and status based on user ID
    $query = "
        SELECT 
            p.name, 
            p.address, 
            r.status 
        FROM 
            profiles p 
        JOIN 
            requests r 
        ON 
            p.id = r.id 
        WHERE 
            r.user_id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $userId);
    $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($results) {
        echo json_encode(['profiles' => $results]);
    } else {
        echo json_encode(['error' => 'No data found']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
