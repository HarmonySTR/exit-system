<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection file
require_once "db_connect.php";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the form data
    $item_name = $_POST["item_name"];
    $item_price = $_POST["item_price"];
    $item_type = $_POST["item_type"];

    // Check if the username already exists in table
    $checkUsernameQuery = "SELECT * FROM items_list WHERE item_name = '$item_name'";
    $checkUsernameResult = $conn->query($checkUsernameQuery);

    if ($checkUsernameResult !== false && $checkUsernameResult->num_rows > 0) {
        // Username already exists
        echo "Item already exists!";
        echo "<script>alert('Item already exists'); window.location.href='../items-list.php';</script>";
    } else {
        // Insert the data into the database
        $insertQuery = "INSERT INTO items_list (item_name, item_price, item_type) VALUES ('$item_name', '$item_price','$item_type')";

        if ($conn->query($insertQuery) === TRUE) {
            // Data added successfully
            echo "Data added successfully!";
            echo "<script>alert('Data successfully added.'); window.location.href='../items-list.php';</script>";
        } else {
            // Error adding data
            mysqli_error($conn);
            echo "<script>alert('Error in adding data'); window.location.href='../items-list.php';</script>";
        }
    }

    // Close the database connection
    $conn->close();
}
?>
