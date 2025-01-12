<?php
// Database configuration
$host = 'localhost';
$dbname = 'abtech';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get the user_id from the query parameter
    $user_id = $_GET['user_id'];

    // Query the projects table to get the project_info and photo_path based on the user_id
    $query = $pdo->prepare("SELECT project_info, photo_path FROM projects WHERE user_id = :user_id");
    $query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $query->execute();

    // Fetch all projects
    $projects = $query->fetchAll(PDO::FETCH_ASSOC);

    if ($projects) {
        echo json_encode(['success' => true, 'projects' => $projects]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No projects found for this user']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
