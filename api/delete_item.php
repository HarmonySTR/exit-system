<?php
// Include the database connection file
require_once "db_connect.php";

// Check if the item ID is provided in the POST request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['item_id'])) {
    // Retrieve the item ID
    $itemId = $_POST['item_id'];

    
    // Prepare and execute the query to delete the item
    $stmt = $conn->prepare("DELETE FROM items_list WHERE item_id = ?");
    $stmt->bind_param("i", $itemId);

    if ($stmt->execute()) {
        // Item deleted successfully
        echo "Item deleted successfully.";
        echo "<script>alert('Item successfully deleted.'); window.location.href='../items-list.php';</script>";
    } else {
        // Error deleting item
        echo "Error deleting item: " . $stmt->error;
        echo "<script>alert('Error in deleting data'); window.location.href='../items-list.php';</script>";
    }

    // Close the prepared statement and database connection
    $stmt->close();
    $conn->close();
} else {
    // Handle case when item ID is not provided
    echo "Item ID not provided.";
}
?>
