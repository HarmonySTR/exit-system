<?php
require_once 'db_connect.php';
session_start();
$usertype = $_SESSION['usertype'];

$output = array('data' => array());
$sql = "SELECT * FROM orders_list WHERE status != 'Completed'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $editButton = "<button class='edit-btn bg-[#2c6e35] hover:bg-[#3e8248] text-white font-bold py-2 px-4 rounded-lg col-span-8 mb-1' data-userid='" . $row['order_id'] . "' onclick='window.location.href = \"api/updateOrderStatus.php?order_id=" . $row['order_id'] . "\"'>Complete</button>";
        $readyButton = "<button class='edit-btn bg-[#2c6e35] hover:bg-[#3e8248] text-white font-bold py-2 px-4 rounded-lg col-span-8 mb-1' data-userid='" . $row['order_id'] . "' onclick='window.location.href = \"api/updateOrderStatus.php?order_id=" . $row['order_id'] . "\"'>Ready</button>";
        $deleteButton = "<button class='edit-btn bg-red-500 hover:bg-[#3e8248] text-white font-bold py-2 px-4 rounded-lg col-span-8 mb-1' data-userid='" . $row['order_id'] . "' onclick='window.location.href = \"api/deleteOrderStatus.php?order_id=" . $row['order_id'] . "\"'>Delete</button>";
        
        if (($row['status'] == 'Ready')&&($usertype == 'Front Desk')||($usertype == 'Admin')) {
            $options = $editButton." " .$deleteButton;
        } elseif (($row['status'] == 'Pending')&&($usertype == 'Kitchen Staff')) {
            $options = $readyButton;
        } else {
            $options = '';
        }

        $output['data'][] = [
            $row['order_id'],
            $row['order_data'],
            $row['total_price'],
            $row['status'],
            $options
        ];
    }
}

mysqli_close($conn);

header('Content-Type: application/json');
echo json_encode($output);
?>
