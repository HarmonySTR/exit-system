<?php
require_once 'db_connect.php';

// Get today's date
$today = date('Y-m-d');
// Get the start date of the current week (Sunday)
$startDate = date('Y-m-d', strtotime('last Sunday'));
// Calculate the start date of the previous week (Sunday to Sunday)
$startDate1 = date('Y-m-d', strtotime('last Sunday -1 week'));

// Get the start date of the current month (first day of the month)
$startDateMonth = date('Y-m-01');
// Get the start date of the previous month (first day of the month)
$startDateMonth1 = date('Y-m-01', strtotime('last month'));


// Initialize the response array
$response = array(
    'today' => array(
        'totalEarnings' => 0,
        'topSelling' => '',
        'soldQuantity' => 0,
        'leastSoldItem' => ''
    ),
    'overall' => array(
        'overallEarnings' => 0,
        'overallQuantity' => 0
    ),
    'itemSummary' => array()
);


// Query to get today's total earnings
$todayTotalEarningsQuery = "SELECT COALESCE(SUM(earnings), 0) AS totalEarnings
    FROM orders_statistics
    WHERE DATE(date_ordered) = '$today'";
$todayTotalEarningsResult = mysqli_query($conn, $todayTotalEarningsQuery);
if ($todayTotalEarningsResult && mysqli_num_rows($todayTotalEarningsResult) > 0) {
    $todayTotalEarningsData = mysqli_fetch_assoc($todayTotalEarningsResult);
    $response['today']['totalEarnings'] = $todayTotalEarningsData['totalEarnings'];
}

// Query to get the top-selling item and sold quantity for today
$todayTopSellingQuery = "SELECT items_list.item_name AS topSelling, SUM(orders_statistics.quantity) AS soldQuantity
    FROM orders_statistics
    JOIN items_list ON orders_statistics.item_id = items_list.item_id
    WHERE DATE(orders_statistics.date_ordered) = '$today'
    GROUP BY orders_statistics.item_id
    ORDER BY soldQuantity DESC
    LIMIT 1";
$todayTopSellingResult = mysqli_query($conn, $todayTopSellingQuery);
if ($todayTopSellingResult && mysqli_num_rows($todayTopSellingResult) > 0) {
    $todayTopSellingData = mysqli_fetch_assoc($todayTopSellingResult);
    $response['today']['topSelling'] = $todayTopSellingData['topSelling'];
    $response['today']['soldQuantity'] = (int) $todayTopSellingData['soldQuantity']; // Convert to integer
}

// Query to get the least sold item for today
$todayLeastSoldQuery = "SELECT items_list.item_name AS leastSoldItem, SUM(orders_statistics.quantity) AS totalQuantity
    FROM orders_statistics
    JOIN items_list ON orders_statistics.item_id = items_list.item_id
    WHERE DATE(orders_statistics.date_ordered) = '$today'
    GROUP BY orders_statistics.item_id
    HAVING totalQuantity = (
        SELECT MIN(subquery.totalQuantity)
        FROM (
            SELECT SUM(orders_statistics.quantity) AS totalQuantity
            FROM orders_statistics
            WHERE DATE(orders_statistics.date_ordered) = '$today'
            GROUP BY orders_statistics.item_id
        ) AS subquery
    )
    LIMIT 1";
$todayLeastSoldResult = mysqli_query($conn, $todayLeastSoldQuery);
if ($todayLeastSoldResult && mysqli_num_rows($todayLeastSoldResult) > 0) {
    $todayLeastSoldData = mysqli_fetch_assoc($todayLeastSoldResult);
    $response['today']['leastSoldItem'] = $todayLeastSoldData['leastSoldItem'];
}

//===================================================================================================================
// Query to get the summary for each item
// Get today's date
$today = date('Y-m-d');

// Query to get the summary for each item for today's date
$itemSummaryQuery = "SELECT items_list.item_name, COALESCE(SUM(orders_statistics.quantity), 0) AS soldQuantity, COALESCE(SUM(orders_statistics.earnings), 0) AS totalEarnings
    FROM items_list
    LEFT JOIN orders_statistics ON items_list.item_id = orders_statistics.item_id
    WHERE DATE(orders_statistics.date_ordered) = '$today'
    GROUP BY items_list.item_id";

$itemSummaryResult = mysqli_query($conn, $itemSummaryQuery);
if ($itemSummaryResult && mysqli_num_rows($itemSummaryResult) > 0) {
    while ($itemSummaryData = mysqli_fetch_assoc($itemSummaryResult)) {
        $response['itemSummary'][] = array(
            'itemName' => $itemSummaryData['item_name'],
            'soldQuantity' => (int) $itemSummaryData['soldQuantity'], // Convert to integer
            'totalEarnings' => $itemSummaryData['totalEarnings']
        );
    }
}


// Convert the response array to JSON
$jsonResponse = json_encode($response, JSON_PRETTY_PRINT);

// Print the JSON response
echo $jsonResponse;

?>
