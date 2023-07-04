<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection file
require_once "db_connect.php";

// Check if the order ID is provided in the URL
if (isset($_GET['order_id'])) {
    $orderId = $_GET['order_id'];

    // Check the current order status from the database
    $statusQuery = "SELECT status FROM orders_list WHERE order_id = ?";
    $statusStmt = $conn->prepare($statusQuery);
    $statusStmt->bind_param("i", $orderId);
    $statusStmt->execute();
    $statusResult = $statusStmt->get_result();

    if ($statusResult->num_rows > 0) {
        $statusRow = $statusResult->fetch_assoc();
        $currentStatus = $statusRow['status'];

        // Determine the new status based on the current status
        $newStatus = '';
        if ($currentStatus == 'Pending') {
            $newStatus = 'Ready';
        } elseif ($currentStatus == 'Ready') {
            $newStatus = 'Completed';
        }

        // Update the order status in the database
        if (!empty($newStatus)) {
            $updateQuery = "UPDATE orders_list SET status = ? WHERE order_id = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("si", $newStatus, $orderId);

            if ($updateStmt->execute()) {
                // Order status updated successfully
                echo "<script>alert('Order status updated successfully.');</script>";
                echo "<script>window.location.href = document.referrer;</script>";
                exit();
            } else {
                // Failed to update the order status
                $errorMessage = "Failed to update the order status. Error: " . mysqli_error($conn);
                echo "<script>alert('$errorMessage');</script>";
                echo "<script>window.location.href = document.referrer;</script>";
                exit();
            }
        } else {
            // Invalid current status or new status is not defined
            echo "<script>alert('Invalid order status or new status is not defined.');</script>";
            echo "<script>window.location.href = document.referrer;</script>";
            exit();
        }
    } else {
        // Order not found
        echo "<script>alert('Order not found.');</script>";
        header("Location: orders.php");
        exit();
    }

    // Close the statement and the connection
    $statusStmt->close();
    $updateStmt->close();
    $conn->close();
} else {
    // Order ID not provided
    echo "<script>alert('Order ID not provided.');</script>";
    header("Location: orders.php");
    exit();
}
?>
