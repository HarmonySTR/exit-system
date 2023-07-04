<?php
$name = 'dateTime';
$value = date('Y-m-d H:i:s');

// Set the cookie
setcookie($name, $value);
session_start();
if ($_SESSION['usertype'] != 'Admin') {
    if (!isset($_SESSION['usertype'])) {
        // Redirect to the login page
        echo "<script>alert('You need to login!'); window.location.href='login.php';</script>";
        exit();
    } elseif ($_SESSION['usertype'] == 'Kitchen Staff') {
        echo "<script>alert('Only admins have access to this page');</script>";
        header("Location: orders.php");
        exit();
    } else {
        echo "<script>alert('Only admins have access to this page');</script>";
        header("Location: index.php");
        exit();
    }
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
    <div class="min-h-screen flex flex-col items-start justify-center py-12  items-center">
        <div id="addbtn" class="ml-14 mb-1">
            <a href="#">
                <p class="btn text-white bg-blue-500 px-6 py-3 font-bold uppercase">Add item</p>
            </a>
        </div>
        <div id="main-content" class="w-9/12 bg-white p-8 mx-auto border border-black">
            <table id="items-table" class="table bg-white" style="width:100%">
                <thead>
                    <tr>
                        <th>Item ID</th>
                        <th>Item Name</th>
                        <th>Item Price</th>
                        <th>Item Type</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Table rows will be populated dynamically using JavaScript -->
                </tbody>
                <tfoot>
                    <tr>
                        <th>Item ID</th>
                        <th>Item Name</th>
                        <th>Item Price</th>
                        <th>Item Type</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>


    <!-- Add item Modal -->
    <div id="add-item-modal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
        <div class="fixed inset-0 bg-gray-800 bg-opacity-75 " onclick="closeModal('add-item-modal')"></div>
        <div class="bg-white  p-8 relative drop-shadow-xl">
            <h2 class="text-2xl text-black font-bold mb-4">Add Item</h2>
            <form id="add-item-form" action="api/additem.php" method="POST">
                <div class="mb-4">
                    <label for="item_name" class="text-black block text-lg font-semibold mb-1">Item Name:</label>
                    <input type="text" id="item_name" name="item_name"
                        class="w-full border-gray-300 border  px-4 py-2 focus:outline-none focus:border-black"
                        required>
                </div>
                <div class="mb-4">
                    <label for="item_price" class="text-black block text-lg font-semibold mb-1">Item Price:</label>
                    <input type="item_price" id="item_price" name="item_price"
                        class="w-full border-gray-300 border  px-4 py-2 focus:outline-none focus:border-black"
                        required>
                </div>
                <div class="mb-4">
                    <label for="item_type" class="text-black block text-lg font-semibold mb-1">Item Type:</label>
                    <select id="item_type" name="item_type"
                        class="w-full border-gray-300 border  px-4 py-2 focus:outline-none focus:border-black"
                        required>
                        <option value="Beverage">Beverage</option>
                        <option value="Meal">Meal</option>
                        <option value="Snack">Snack</option>
                        <option value="Add-on">Add-on</option>
                    </select>
                </div>
                <div class="flex justify-end">
                    <button type="submit" id="submit-add-item"
                        class="btn  bg-blue-500 py-1  px-3 mr-3 text-white">Add</button>
                    <button id="cancel-add-item" type="button"
                        class="btn  bg-red-500 py-1 px-3 mr-2 text-white">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit item Modal -->
    <div id="edit-item-modal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
        <div class="fixed inset-0 bg-gray-800 bg-opacity-75" onclick="closeModal('edit-item-modal')"></div>
        <div class="bg-white  p-8 relative">
            <h2 class="text-2xl font-bold mb-4">Edit item</h2>
            <form id="edit-item-form" action="api/update_item.php" method="POST">
                <input type="hidden" name="item_id" id="edit-item-id">
                <div class="mb-4">
                    <label for="edit-item_name" class="block text-lg font-semibold mb-1">Item Name:</label>
                    <input type="text" id="edit-item_name" name="item_name"
                        class="w-full border-gray-300 border  px-4 py-2 focus:outline-none focus:border-black"
                        required>
                </div>
                <div class="mb-4">
                    <label for="edit-item_price" class="block text-lg font-semibold mb-1">Item Price:</label>
                    <input type="text" id="edit-item_price" name="item_price"
                        class="w-full border-gray-300 border  px-4 py-2 focus:outline-none focus:border-black"
                        required>
                </div>
                <div class="mb-4">
                    <label for="edit-item_type" class="block text-lg font-semibold mb-1">Item Type:</label>
                    <select id="edit-item_type" name="item_type"
                        class="w-full border-gray-300 border  px-4 py-2 focus:outline-none focus:border-black"
                        required>
                        <option value="Beverage">Beverage</option>
                        <option value="Meal">Meal</option>
                        <option value="Snack">Snack</option>
                        <option value="Add-on">Add-on</option>
                    </select>
                </div>
                <div class="flex justify-end">
                    <button id="submit-edit-item" type="submit"
                        class="btn  bg-blue-500 cancel-add-itemitempy-1 px-3 mr-3 text-white">Add</button>
                    <button id="cancel-edit-item" type="button"
                        class="btn  bg-red-500 py-1 px-3 text-white mr-2">Cancel</button>
                </div>
            </form>
        </div>
    </div>


    <!-- Delete item Modal -->
    <div id="delete-item-modal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
        <div class="fixed inset-0 bg-gray-800 bg-opacity-75" onclick="closeModal('delete-item-modal')"></div>
        <div class="bg-white p-8 relative">
            <h2 class="text-2xl font-bold mb-4">Are you sure you want to delete item?</h2>
            <form id="delete-item-form" action="api/delete_item.php" method="POST">
                <input type="hidden" name="item_id" id="delete-item-id">
                <div class="mb-4">
                    <label for="delete-item_name" class="block text-lg font-semibold mb-1">Item Name:</label>
                    <input type="text" id="delete-item_name" name="item_name"
                        class="w-full border-gray-300 border rounded px-4 py-2 focus:outline-none focus:border-indigo-500"
                        readonly>
                </div>
                <div class="mb-4">
                    <label for="delete-item_type" class="block text-lg font-semibold mb-1">Item Type:</label>
                    <input type="text" id="delete-item_type" name="item_type"
                        class="w-full border-gray-300 border rounded px-4 py-2 focus:outline-none focus:border-indigo-500"
                        readonly>
                </div>
                <div class="flex justify-end">
                    <button id="submit-delete-item" type="submit"
                        class="btn bg-red-500 py-1 px-3 mr-3 text-white">Submit</button>
                    <button id="cancel-delete-item" type="button"
                        class="btn bg-black py-1 px-3 text-white mr-2">Cancel</button>
                </div>
            </form>
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

            // Initialize DataTable
            $('#items-table').DataTable({
                ajax: {
                    url: 'api/items.php',
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