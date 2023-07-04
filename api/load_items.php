<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection file
require_once "db_connect.php";

// Perform the query to fetch items from the table
$query = "SELECT * FROM items_list";
$result = $conn->query($query);

// Check if the query was successful
if (!$result) {
    // Error adding data
    mysqli_error($conn);
    echo "<script>alert('Error in loading data'); window.location.href='../index.php';</script>";
}

// Fetch the items and store them in an array
$items = array();
while ($row = $result->fetch_assoc()) {
    $items[] = $row;
}

// Close the database connection
$conn->close();

// Set the response content type to JSON
header('Content-Type: application/json');

// Send the items array as JSON response
echo json_encode($items);
?>