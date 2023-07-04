<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection file
require_once "db_connect.php";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the form data
    $username = $_POST["username"];
    $password = $_POST["password"];
    $usertype = $_POST["usertype"];

    // Check if the username already exists in table
    $checkUsernameQuery = "SELECT * FROM users_list WHERE username = '$username'";
    $checkUsernameResult = $conn->query($checkUsernameQuery);

    if ($checkUsernameResult !== false && $checkUsernameResult->num_rows > 0) {
        // Username already exists
        echo "Username already exists!";
        echo "<script>alert('Username already exists'); window.location.href='../users-list.php';</script>";
    } else {
        // Insert the data into the database
        $insertQuery = "INSERT INTO users_list (username, password, usertype) VALUES ('$username', '$password','$usertype')";

        if ($conn->query($insertQuery) === TRUE) {
            // Data added successfully
            echo "Data added successfully!";
            echo "<script>alert('Data successfully added.'); window.location.href='../users-list.php';</script>";
        } else {
            // Error adding data
            mysqli_error($conn);
            echo "<script>alert('Error in adding data'); window.location.href='../users-list.php';</script>";
        }
    }

    // Close the database connection
    $conn->close();
}
?>
