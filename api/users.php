<?php
require_once 'db_connect.php';

$output = array('data' => array());
$sql = "SELECT * FROM users_list";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $editButton = "<button class='edit-btn bg-[#2c6e35] hover:bg-[#3e8248] text-white font-bold py-2 px-4 rounded-lg col-span-8 mb-1' data-userid='" . $row['user_id'] . "' onclick='openEditModal(" . $row['user_id'] . ")'>Edit</button>";
        $deleteButton = "<button class='delete-btn bg-[#2c6e35] hover:bg-[#3e8248] text-white font-bold py-2 px-4 rounded-lg col-span-8 mb-1' data-userid='" . $row['user_id'] . "' onclick='openDeleteModal(" . $row['user_id'] . ")'>Delete</button>";
        $options = $editButton . " " . $deleteButton;

        $output['data'][] = array(
            $row['user_id'],
            $row['username'],
            $row['usertype'],
            $options
        );
    }
}
mysqli_close($conn);

header('Content-Type: application/json');
echo json_encode($output);
?>
