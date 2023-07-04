<?php
// Include the database connection file
require_once "db_connect.php";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $userId = $_POST['user_id'];
    $newUsername = $_POST['username'];
    $newPassword = $_POST['password'];
    $newUserType = $_POST['usertype'];

    // Check if the new username already exists
    $stmt = $conn->prepare("SELECT * FROM users_list WHERE username = ? AND user_id != ?");
    $stmt->bind_param("si", $newUsername, $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Username already exists
        echo "Error: Username already exists.";
        echo "<script>alert('Username already exists.'); window.location.href='../users-list.php';</script>";
        exit();
    }

    // Prepare and execute the query to update the user
    $stmt = $conn->prepare("UPDATE users_list SET username = ?, password = ?, usertype = ? WHERE user_id = ?");
    $stmt->bind_param("sssi", $newUsername, $newPassword, $newUserType, $userId);

    if ($stmt->execute()) {
        // User updated successfully
        echo "User updated successfully.";
        echo "<script>alert('User successfully updated.'); window.location.href='../users-list.php';</script>";
    } else {
        // Error updating user
        echo "Error updating user: " . $stmt->error;
        echo "<script>alert('Error in updating data'); window.location.href='../users-list.php';</script>";
    }

    // Close the prepared statement and database connection
    $stmt->close();
    $conn->close();
} else {
    // Handle case when form is not submitted
    echo "Invalid request.";
    echo "<script>alert('Invalid request.'); window.location.href='../users-list.php';</script>";
}
?>
