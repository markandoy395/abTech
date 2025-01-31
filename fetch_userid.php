<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set the content type to JSON
header('Content-Type: application/json');

// Step 1: Connect to the database
$servername = "localhost"; // Database server
$username = "root";        // Database username
$password = "";            // Database password
$dbname = "abtech";        // Database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Step 2: Get the user_id from the URL query parameter (optional if you want to filter by user_id)
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Step 3: Prepare SQL query to retrieve data for the given user_id
    $sql = "SELECT id, name, password, address, contact, email, role FROM users WHERE id = ?";
    
    // Prepare statement to prevent SQL injection
    if ($stmt = $conn->prepare($sql)) {
        // Bind the user_id parameter
        $stmt->bind_param("i", $user_id);
        
        // Execute the statement
        $stmt->execute();
        
        // Get the result
        $result = $stmt->get_result();
        
        // Check if the user was found
        if ($result->num_rows > 0) {
            // Fetch the data
            $user_data = $result->fetch_assoc();
            
            // Step 4: Return the data as a JSON response
            echo json_encode(array(
                'success' => true,
                'data' => $user_data
            ));
        } else {
            // If no user found, return an error
            echo json_encode(array(
                'success' => false,
                'message' => 'User not found'
            ));
        }
        
        // Close the statement
        $stmt->close();
    } else {
        echo json_encode(array(
            'success' => false,
            'message' => 'Error preparing statement'
        ));
    }
} else {
    // If user_id is not set, select all users
    $sql = "SELECT id, name, password, address, contact, email, role FROM users";
    
    if ($result = $conn->query($sql)) {
        // Check if any records were returned
        if ($result->num_rows > 0) {
            $users_data = $result->fetch_all(MYSQLI_ASSOC);
            
            // Step 4: Return the data as a JSON response
            echo json_encode(array(
                'success' => true,
                'data' => $users_data
            ));
        } else {
            // If no users found, return an error
            echo json_encode(array(
                'success' => false,
                'message' => 'No users found'
            ));
        }
    } else {
        echo json_encode(array(
            'success' => false,
            'message' => 'Error fetching data'
        ));
    }
}

// Step 5: Close the database connection
$conn->close();
?>
