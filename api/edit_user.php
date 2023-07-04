<?php
// Include the database connection file
require_once "db_connect.php";

// Check if the user ID is provided in the GET request
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['user_id'])) {
    // Retrieve the user ID
    $userId = $_GET['user_id'];

    // Prepare and execute the query to fetch the user details
    $stmt = $conn->prepare("SELECT * FROM users_list WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    
    if ($stmt->execute()) {
        // Retrieve the user details
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Convert the user details to JSON and return the response
        echo json_encode($user);
    } else {
        // Error retrieving user details
        echo "Error retrieving user details: " . $stmt->error;
        echo "<script>alert('Error retrieving user details'); window.location.href=document.referrer;</script>";
    }

    // Close the prepared statement and database connection
    $stmt->close();
    $conn->close();
} else {
    // Handle case when user ID is not provided
    echo "User ID not provided.";
    echo "<script>alert('User ID not provided'); window.location.href=document.referrer;</script>";
}
?>
