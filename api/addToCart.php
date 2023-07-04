<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'db_connect.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the form data
    $quantity = $_POST['quantity'];
    $itemId = $_POST['quantity_id'];

    // Validate the data (perform additional validation if necessary)

    // Retrieve item details from items_list table
    $itemStmt = $conn->prepare("SELECT item_name, item_price FROM items_list WHERE item_id = ?");
    $itemStmt->bind_param("i", $itemId);
    $itemStmt->execute();
    $itemResult = $itemStmt->get_result();
    $itemRow = $itemResult->fetch_assoc();

    // Extract item details
    $itemName = $itemRow['item_name'];
    $itemPrice = $itemRow['item_price'];

    // Calculate the total price
    $totalPrice = $itemPrice * $quantity;

    // Check if the item already exists in temp_order table
    $existingItemStmt = $conn->prepare("SELECT quantity, price FROM temp_order WHERE item_id = ?");
    $existingItemStmt->bind_param("i", $itemId);
    $existingItemStmt->execute();
    $existingItemResult = $existingItemStmt->get_result();

    if ($existingItemResult->num_rows > 0) {
        // Item already exists, update the quantity and price
        $existingItemRow = $existingItemResult->fetch_assoc();
        $existingQuantity = $existingItemRow['quantity'];
        $existingPrice = $existingItemRow['price'];

        $quantity += $existingQuantity;
        $totalPrice += $existingPrice;

        // Update the quantity and price in temp_order table
        $updateStmt = $conn->prepare("UPDATE temp_order SET quantity = ?, price = ? WHERE item_id = ?");
        $updateStmt->bind_param("isi", $quantity, $totalPrice, $itemId);

        // Execute the update statement
        if ($updateStmt->execute()) {
            // Quantity and price updated successfully
            echo "<script>alert('Item quantity updated in the cart.');</script>";
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            // Failed to update the quantity and price
            $errorMessage = "Failed to update the item quantity. Error: " . mysqli_error($conn);
            echo "<script>alert('$errorMessage');</script>";
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }
    } else {
        // Item does not exist, insert into temp_order table
        $sql = "INSERT INTO temp_order (item_id, item_name, quantity, price, date_ordered) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isis", $itemId, $itemName, $quantity, $totalPrice);

        // Execute the insert statement
        if ($stmt->execute()) {
            // Item added successfully
            echo "<script>alert('Item added to the cart successfully.');</script>";
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            // Failed to add the item
            $errorMessage = "Failed to add the item to the cart. Error: " . mysqli_error($conn);
            echo "<script>alert('$errorMessage');</script>";
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }
    }

    // Close the statements and the connection
    $itemStmt->close();
    $existingItemStmt->close();
    $stmt->close();
    $conn->close();
}
?>
