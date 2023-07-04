<?php
$name = 'dateTime';
$value = date('Y-m-d H:i:s');

// Set the cookie
setcookie($name, $value);
session_start();

if (!isset($_SESSION['usertype'])) {
    // Redirect to the login page
    echo "<script>alert('You need to login!'); window.location.href='login.php';</script>";
    exit();
}

$usertype = $_SESSION['usertype'];
$username = $_SESSION['username'];
$id = $_SESSION['user_id'];

?>

<!doctype html>
<html lang="en">

<head>
    <title>BAHAY KAINAN</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="assets/img/SG_Logo.png" type="image/png">
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="assets/datatables.min.css" />

    <script src="https://cdn.tailwindcss.com"></script>

    <!-- jQuery -->
    <script src="assets/jquery/jquery-3.6.4.min.js"></script>

    <!-- DataTables JavaScript -->
    <script src="assets/datatables/datatables.min.js"></script>

    <style>
        body {
            overflow-x: hidden;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100%;
            padding:0;
    margin:0;
        }

        .drop-shadow-lg {
            box-shadow: 1px 4px 4px 0 rgba(0, 0, 0, 0.50);
        }

        .drop-shadow-xl {
            box-shadow: 2px 4px 6px 0 rgba(0, 0, 0, 0.50);
        }
    </style>

</head>

<body>
    <!-- Navbar -->
    <div id="navbar" class="flex items-center bg-black py-2 px-5 absolute w-full">
    <a href="index.php">
        <div class="flex">
            <h1 class="text-white font-bold text-xl uppercase">Los Pollos Hermanos</h1>
        </div>
    </a>
    <a href="orders.php" class="text-white block px-4 py-2 text-gray-800 hover:bg-gray-100 font-bold ml-16 px-4">Orders</a>
    <div>|</div>
    <a href="items-list.php" class="text-white block px-4 py-2 text-gray-800 hover:bg-gray-100 font-bold px-4">Items</a>
    <div>|</div>
    <a href="statistics.php" class="text-white block px-4 py-2 text-gray-800 hover:bg-gray-100 font-bold px-4">History</a>
    <div>|</div>
    <a href="api/logout.php" class="text-white block px-4 py-2 text-gray-800 hover:bg-gray-100 font-bold px-4">Logout</a>
</div>

    <!-- Main Content -->
    <div class="min-h-screen flex flex-col items-start justify-center py-8 ">

<div id="main-content" class="w-1/2 h-full bg-white drop-shadow-xl p-8 mx-auto border border-black">
    <table id="items-table" class="table bg-white" style="width:100%">
        <thead>
            <tr>
                <th>Queue</th>
                <th>Items</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <!-- Table rows will be populated dynamically using JavaScript -->
        </tbody>
        <tfoot>
            <tr>
                <th>Queue</th>
                <th>Items</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </tfoot>
    </table>
</div>
</div>


    <script>
        $(document).ready(function () {
            // Apply floating animation to the main content after page load
            setTimeout(function () {
                $('#main-content').addClass('floating-animation');
            }, 0);

            // Apply floating-down animation to the navbar after page load
            setTimeout(function () {
                $('#navbar, #addbtn').addClass('floating-animation-down');
            }, 0);

            // Open Add item Modal
            $('#addbtn a').click(function (e) {
                e.preventDefault();
                $('#add-item-modal').removeClass('hidden');
            });

            // Close Add item Modal
            $('#cancel-add-item').click(function () {
                $('#add-item-modal').addClass('hidden');
            });

            // Close Edit item Modal
            $('#cancel-edit-item').click(function () {
                $('#edit-item-modal').addClass('hidden');
            });

            // Close Edit item Modal
            $('#cancel-delete-item').click(function () {
                $('#delete-item-modal').addClass('hidden');
            });

            function initializeDataTable() {
                // Initialize DataTable
                $('#items-table').DataTable({
                    ajax: {
                        url: 'api/orders.php',
                        dataSrc: 'data' // Use 'data' as the property to retrieve the JSON data
                    },
                    columns: [
                        { data: '0', className: 'text-center bg-white' }, // Use the index to access the 'ITEM ID' column
                        { data: '1', className: 'text-center bg-white' }, // Use the index to access the 'ITEM NAME' column
                        {
                            data: '2', className: 'text-center bg-white', render: function (data, type, row) {
                                return '₱ ' + data; // Append "Php" before the data
                            }
                        }, // Use the index to access the 'ITEM PRICE' column
                        { data: '3', className: 'text-center bg-white' }, // Use the index to access the 'ITEM TYPE' column
                        {
                            data: '4', // Use the index to access the 'OPTIONS' column
                            className: 'text-center bg-white',
                            render: function (data, type, row) {
                                return data;
                            }
                        }
                    ]
                });
            }
            // Call the function to initialize DataTable
            initializeDataTable();

            // Refresh DataTable every 10 seconds
            setInterval(function () {
                $('#items-table').DataTable().ajax.reload();
            }, 10000); // 10000 milliseconds = 10 seconds


        });

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.add('hidden');
        }

        function openEditModal(itemId) {
            // Show the edit item modal
            document.getElementById("edit-item-modal").classList.remove("hidden");

            // Make an AJAX request to fetch item details
            $.ajax({
                url: "api/edit_item.php",
                type: "GET",
                data: { item_id: itemId },
                success: function (response) {
                    // Parse the JSON response
                    var item = JSON.parse(response);

                    // Populate the form fields with item details
                    $("#edit-item-id").val(item.item_id);
                    $("#edit-item_price").val(item.item_price);
                    $("#edit-item_name").val(item.item_name);
                    $("#edit-item_type").val(item.item_type);

                    // Show the Edit item modal
                    showModal("edit-item-modal");
                },
                error: function (xhr, status, error) {
                    // Handle the error if the request fails
                    console.log(error);
                }
            });
        }


        // for deleting item
        function openDeleteModal(itemId) {
            // Show the delete item modal
            document.getElementById("delete-item-modal").classList.remove("hidden");

            // Perform AJAX request to retrieve item details
            $.ajax({
                url: 'api/get_item.php',
                type: 'POST',
                data: { itemId: itemId },
                success: function (response) {
                    // Parse the JSON response
                    var item = JSON.parse(response);

                    // Update the delete-item-modal form with the retrieved values
                    $('#delete-item-id').val(item.item_id);
                    $('#delete-item_name').val(item.item_name);
                    $('#delete-item_type').val(item.item_type);

                    // Open the delete-item-modal
                    $('#delete-item-modal').removeClass('hidden');
                },
                error: function () {
                    // Handle error case
                    console.log('Error occurred while retrieving item details.');
                }
            });
        }

        var menuButton = document.getElementById('menuButton');
        var menuDropdown = document.getElementById('menuDropdown');

        menuButton.addEventListener('click', function () {
            menuDropdown.classList.toggle('hidden');
        });

        // Close the dropdown when clicking outside
        document.addEventListener('click', function (event) {
            var targetElement = event.target;
            var isClickInside = menuButton.contains(targetElement) || menuDropdown.contains(targetElement);
            if (!isClickInside) {
                menuDropdown.classList.add('hidden');
            }
        });
    </script>


</body>

</html>