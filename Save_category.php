<?php
// Database connection settings
$host = 'localhost';
$dbname = 'abtech';
$username = 'root';
$password = '';

try {
    // Create database connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database connection failed: " . $e->getMessage()]);
    exit;
}

// Check if data was sent via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['user_id']) && isset($_POST['category']) && isset($_POST['service'])) {
        $user_id = $_POST['user_id'];
        $category = $_POST['category'];
        $service = $_POST['service'];

        // For debugging purposes, print the values
        error_log("User ID: " . $user_id);
        error_log("Category: " . $category);
        error_log("Service: " . $service);

        try {
            // Save category and service to the database
            $stmt = $pdo->prepare("INSERT INTO user_services (user_id, category, service) VALUES (:user_id, :category, :service)");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':category', $category);
            $stmt->bindParam(':service', $service);  // Insert the custom service
            $stmt->execute();

            echo json_encode(["success" => true, "message" => "Category and service saved successfully!"]);
        } catch (PDOException $e) {
            echo json_encode(["success" => false, "message" => "Error saving category or service: " . $e->getMessage()]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Missing required parameters."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
}
?>
