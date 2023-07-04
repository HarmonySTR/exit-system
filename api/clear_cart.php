<?php
require_once 'db_connect.php';

// Truncate the temp_order table
$truncateQuery = "TRUNCATE TABLE temp_order";
if (mysqli_query($conn, $truncateQuery)) {
    // Table truncated successfully
    echo "<script>alert('Cart cleared successfully.');</script>";
    echo "<script>window.location.href = document.referrer;</script>";
    exit();
} else {
    // Failed to truncate the table
    $errorMessage = "Failed to clear the cart. Error: " . mysqli_error($conn);
    echo "<script>alert('$errorMessage');</script>";
    echo "<script>window.location.href = document.referrer;</script>";
    exit();
}

mysqli_close($conn);
?>
