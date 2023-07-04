<?php
// Include the database connection file
require_once "db_connect.php";

// Check if the user ID is provided in the POST request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'])) {
    // Retrieve the user ID
    $userId = $_POST['user_id'];

    // Check if the deleted user ID is the same as the session user ID
    session_start();
    $sessionUserId = $_SESSION['user_id'];
    
    // Prepare and execute the query to delete the user
    $stmt = $conn->prepare("DELETE FROM users_list WHERE user_id = ?");
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        // User deleted successfully
        echo "User deleted successfully.";
        
        // Check if the deleted user is the same as the session user
        if ($userId == $sessionUserId) {
            // Logout the user
            session_unset();
            session_destroy();
            echo "<script>alert('You have been logged out.'); window.location.href='../login.php';</script>";
        }

        echo "<script>alert('User successfully deleted.'); window.location.href='../users-list.php';</script>";
    } else {
        // Error deleting user
        echo "Error deleting user: " . $stmt->error;
        echo "<script>alert('Error in deleting data'); window.location.href='../users-list.php';</script>";
    }

    // Close the prepared statement and database connection
    $stmt->close();
    $conn->close();
} else {
    // Handle case when user ID is not provided
    echo "User ID not provided.";
}
?>
