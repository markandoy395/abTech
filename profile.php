<?php
// Database configuration
$host = 'localhost';
$dbname = 'abtech';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query to fetch profiles and corresponding project images
    $query = $pdo->prepare("
        SELECT 
            p.id, 
            p.avatar_image, 
            p.name, 
            p.address, 
            p.rating, 
            p.contact_number,
            pr.photo_path AS project_image
        FROM profiles p
        LEFT JOIN projects pr ON p.id = pr.user_id
    ");
    $query->execute();
    $profiles = $query->fetchAll(PDO::FETCH_ASSOC);

    // Return the profiles as JSON
    echo json_encode($profiles);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
