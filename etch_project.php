<?php
// Suppress warnings and errors
error_reporting(0);
ini_set('display_errors', 0);

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "abtech";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]));
}

// Get user_id from the query parameter
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;

// If user_id is not provided or invalid, return an error
if (!$user_id) {
    echo json_encode(['success' => false, 'message' => 'User ID is missing or invalid.']);
    exit;
}

// Fetch user profile information from the profiles table
$sqlProfile = "SELECT name, address, rating FROM profiles WHERE user_id = ?";
$stmtProfile = $conn->prepare($sqlProfile);
$stmtProfile->bind_param("i", $user_id); // Bind the user_id as an integer parameter
$stmtProfile->execute();
$resultProfile = $stmtProfile->get_result();

// Fetch the profile data
$profile = $resultProfile->fetch_assoc();

// Check if profile is found
if ($profile) {
    // Fetch projects related to the user from the projects table
    $sqlProjects = "SELECT project_info, photo_path FROM projects WHERE user_id = ?";
    $stmtProjects = $conn->prepare($sqlProjects);
    $stmtProjects->bind_param("i", $user_id); // Bind the user_id as an integer parameter
    $stmtProjects->execute();
    $resultProjects = $stmtProjects->get_result();

    // Check if projects are found
    if ($resultProjects->num_rows > 0) {
        $projects = [];
        while ($row = $resultProjects->fetch_assoc()) {
            $projects[] = [
                'project_info' => $row['project_info'],
                'photo_path' => $row['photo_path']
            ];
        }
        echo json_encode(['success' => true, 'profile' => $profile, 'projects' => $projects]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No projects found for this user.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Profile not found for this user.']);
}

$stmtProfile->close();
$stmtProjects->close();
$conn->close();
?>
