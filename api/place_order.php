<?php
require_once 'db_connect.php'; // Include the db_connect.php file to establish the database connection

// Retrieve the temp_order data from the database or wherever it is stored
$temp_order_query = "SELECT * FROM temp_order";
$temp_order_result = mysqli_query($conn, $temp_order_query);

// Check if there are items in the temp_order table
if (mysqli_num_rows($temp_order_result) > 0) {
    
    // Create the order_data string and calculate the total price
    $order_data = '';
    $total_price = 0;

    while ($row = mysqli_fetch_assoc($temp_order_result)) {
        $item_id = $row['item_id'];
        $item_name = $row['item_name'];
        $quantity = $row['quantity'];
        $item_price = $row['price'];

        // Add the item to the order_data string
        $order_data .= $quantity . 'x ' . $item_name . ', ';

        // Update the total price
        $total_price += $item_price;

        // Insert the item into the orders_statistics table
        $order_statistics_query = "INSERT INTO orders_statistics (order_id, item_id, item_name, quantity, date_ordered, earnings)
                                   VALUES (NULL, '$item_id', '$item_name', '$quantity', NOW(), '$item_price')";
        mysqli_query($conn, $order_statistics_query);
    }

    $order_data = rtrim($order_data, ', '); // Remove the trailing comma and space

    // Insert the order data into the orders_list table
    $orders_list_query = "INSERT INTO orders_list (order_id, order_date, order_data, total_price, status)
                          VALUES (NULL, NOW(), '$order_data', '$total_price', 'Pending')";
    $insert_result = mysqli_query($conn, $orders_list_query);

    // Check if the insertion was successful
    if ($insert_result) {
        // Truncate the temp_order table
        $truncate_query = "TRUNCATE TABLE temp_order";
        mysqli_query($conn, $truncate_query);

        // Order placed successfully
        echo "<script>window.location.href = document.referrer;</script>";
        exit();
    } else {
        // Error inserting the order data
        echo "<script>alert('Error placing order. Please try again.');</script>";
        echo "<script>window.location.href = document.referrer;</script>";
        exit();
    }
} else {
    // No items in the temp_order table
    echo "<script>alert('No order data found.');</script>";
    echo "<script>window.location.href = document.referrer;</script>";
    exit();
}

// Close the database connection
mysqli_close($conn);


?>
