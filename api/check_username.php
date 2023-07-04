<?php
// Retrieve the submitted username from the AJAX request
$username = $_POST['username'];

// Perform the username check logic
if (usernameExists($username)) {
  // Username already exists
  echo 'duplicate';
} else {
  // Username is available
  echo 'available';
}

// Function to check if the username exists in the database
function usernameExists($username) {
    require_once "db_connect.php";
  
    // Prepare the query
    $stmt = $conn->prepare('SELECT username FROM users_list WHERE username = ?');
    $stmt->bind_param('s', $username);
  
    // Execute the query
    $stmt->execute();
  
    // Store the result
    $stmt->store_result();
  
    // Check if any rows are returned
    $count = $stmt->num_rows;
  
    // Close the statement and connection
    $stmt->close();
    $conn->close();
  
    // Return true if the username exists, false otherwise
    return $count > 0;
}
?>
