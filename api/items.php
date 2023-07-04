<?php
require_once 'db_connect.php';

$output = array('data' => array());
$sql = "SELECT * FROM items_list";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $editButton = "<button class='edit-btn bg-blue-500 hover:bg-blue-300 text-white font-bold py-2 px-4 col-span-8 mb-1' data-itemid='" . $row['item_id'] . "' onclick='openEditModal(" . $row['item_id'] . ")'>Edit</button>";
        $deleteButton = "<button class='delete-btn bg-red-500 hover:bg-red-300 text-white font-bold py-2 px-4 col-span-8 mb-1' data-itemid='" . $row['item_id'] . "' onclick='openDeleteModal(" . $row['item_id'] . ")'>Delete</button>";
        $options = $editButton . " " . $deleteButton;

        $output['data'][] = array(
            $row['item_id'],
            $row['item_name'],
            $row['item_price'],
            $row['item_type'],
            $options
        );
    }
}
mysqli_close($conn);

header('Content-Type: application/json');
echo json_encode($output);
?>
