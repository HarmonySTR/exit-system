<?php
// Include the database connection file
require_once "db_connect.php";

// Check if the item ID is provided in the AJAX request
if (isset($_POST['itemId'])) {
    // Retrieve the item ID
    $itemId = $_POST['itemId'];

    // Prepare and execute the query to fetch item details
    $stmt = $conn->prepare("SELECT * FROM items_list WHERE item_id = ?");
    $stmt->bind_param("i", $itemId);
    $stmt->execute();

    // Fetch the item details
    $result = $stmt->get_result();
    $item = $result->fetch_assoc();

    // Close the prepared statement and database connection
    $stmt->close();
    $conn->close();

    // Return the item details as JSON response
    echo json_encode($item);
} else {
    // Handle case when item ID is not provided
    echo "Item ID not provided.";
    echo "<script>alert('Item ID not provided.'); window.location.href='../items-list.php';</script>";
}
?>


