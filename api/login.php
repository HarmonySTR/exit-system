<?php
// Include the db_connect.php file
require_once "db_connect.php";

// Start the session
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Retrieve the form data
  $username = $_POST["uname"];
  $password = $_POST["pass"];

  // Prepare and execute the SQL query to fetch the user from the database
  $query = "SELECT * FROM users_list WHERE username = ? AND password = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ss", $username, $password);
  $stmt->execute();
  $result = $stmt->get_result();

  // Check if the user exists
  if ($result->num_rows == 1) {
    // Fetch the user data from the result
    $row = $result->fetch_assoc();

    // Set session variables
    $_SESSION["user_id"] = $row["user_id"];
    $_SESSION["username"] = $row["username"];
    $_SESSION["password"] = $row["password"];
    $_SESSION["usertype"] = $row["usertype"];

    // Redirect to the desired page
    // Check the usertype and redirect accordingly
    if ($usertype == 'Kitchen Staff') {
      header("Location: ../orders.php");
      exit();
    }else{
      header("Location: ../index.php");
    }
    exit();
  } else {
    // User not found, display an error message
    echo "Invalid username or password.";
    echo "<script>alert('Invalid username or password.'); window.location.href='../login.php';</script>";
  }

  // Close the prepared statement and database connection
  $stmt->close();
  $conn->close();
}
?>