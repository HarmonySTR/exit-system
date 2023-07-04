<?php
// Include the database connection file
require_once "db_connect.php";

// Check if the item ID is provided in the GET request
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['item_id'])) {
    // Retrieve the item ID
    $itemId = $_GET['item_id'];

    // Prepare and execute the query to fetch the item details
    $stmt = $conn->prepare("SELECT * FROM items_list WHERE item_id = ?");
    $stmt->bind_param("i", $itemId);
    
    if ($stmt->execute()) {
        // Retrieve the item details
        $result = $stmt->get_result();
        $item = $result->fetch_assoc();

        // Convert the item details to JSON and return the response
        echo json_encode($item);
    } else {
        // Error retrieving item details
        echo "Error retrieving item details: " . $stmt->error;
        echo "<script>alert('Error in deleting data'); window.location.href=document.referrer;</script>";
    }

    // Close the prepared statement and database connection
    $stmt->close();
    $conn->close();
} else {
    // Handle case when item ID is not provided
    echo "Item ID not provided.";
    echo "<script>alert('Item ID not provided'); window.location.href=document.referrer;</script>";
    
}
?>
