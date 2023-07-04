<?php
// Include the database connection file
require_once "db_connect.php";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $item_id = $_POST['item_id'];
    $newItemName = $_POST['item_name'];
    $newPassword = $_POST['item_price'];
    $newUserType = $_POST['item_type'];

    // Check if the new item_name already exists
    $stmt = $conn->prepare("SELECT * FROM items_list WHERE item_name = ? AND item_id != ?");
    $stmt->bind_param("si", $newItemName, $item_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // ItemName already exists
        echo "Error: Item already exists.";
        echo "<script>alert('Item already exists.'); window.location.href='../items-list.php';</script>";
        exit();
    }

    // Prepare and execute the query to update the item
    $stmt = $conn->prepare("UPDATE items_list SET item_name = ?, item_price = ?, item_type = ? WHERE item_id = ?");
    $stmt->bind_param("sssi", $newItemName, $newPassword, $newUserType, $item_id);

    if ($stmt->execute()) {
        // User updated successfully
        echo "Item updated successfully.";
        echo "<script>alert('Item successfully updated.'); window.location.href='../items-list.php';</script>";
    } else {
        // Error updating item
        echo "Error updating item: " . $stmt->error;
        echo "<script>alert('Error in updating data'); window.location.href='../items-list.php';</script>";
    }

    // Close the prepared statement and database connection
    $stmt->close();
    $conn->close();
} else {
    // Handle case when form is not submitted
    echo "Invalid request.";
    echo "<script>alert('Invalid request.'); window.location.href='../items-list.php';</script>";
}
?>
