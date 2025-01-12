<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "abtech";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the user_id from the POST data
if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
} else {
    echo "User ID not found.";
    exit;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize and get project info
    $projectInfo = $conn->real_escape_string($_POST['project_info']);
    $photoPath = "";

    // Handle file upload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = "uploads/";
        $fileName = basename($_FILES['photo']['name']);
        $targetFilePath = $uploadDir . time() . "_" . $fileName;

        // Create upload directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Move the file to the target directory
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFilePath)) {
            $photoPath = $targetFilePath;
        } else {
            echo "Error uploading photo.";
            exit;
        }
    }

    // Insert project into the database
    $sql = "INSERT INTO projects (project_info, photo_path, user_id) 
            VALUES ('$projectInfo', '$photoPath', '$user_id')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Project posted successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
