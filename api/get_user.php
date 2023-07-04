<?php
// Include the database connection file
require_once "db_connect.php";

// Check if the user ID is provided in the AJAX request
if (isset($_POST['userId'])) {
    // Retrieve the user ID
    $userId = $_POST['userId'];

    // Prepare and execute the query to fetch user details
    $stmt = $conn->prepare("SELECT * FROM users_list WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();

    // Fetch the user details
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Close the prepared statement and database connection
    $stmt->close();
    $conn->close();

    // Return the user details as JSON response
    echo json_encode($user);
} else {
    // Handle case when user ID is not provided
    echo "User ID not provided.";
    echo "<script>alert('User ID not provided.'); window.location.href='../users-list.php';</script>";
}
?>


