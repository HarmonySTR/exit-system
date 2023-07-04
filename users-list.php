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
            background-image: url('food.jpeg');
            background-size: cover;
            background-position: center center;
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
    <div id="navbar" class="flex items-center justify-between bg-[#7C9070] py-2 px-16 absolute w-full">
        <a href="index.php">
            <div class="flex items-center">
                <h1 class="text-white font-bold text-xl uppercase">Bahay kainan</h1>
            </div>
        </a>
        <div class="flex">
            <div class="relative mx-2">
                <button id="menuButton" class="text-white font-bold uppercase text-xl focus:outline-none">
                    <span>Menu &#9776;</span>
                </button>
                <div id="menuDropdown" class="absolute z-50 right-0 mt-2 bg-white rounded-lg shadow-lg hidden w-36">
                    <a href="index.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Home</a>
                    <a href="orders.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Orders</a>
                    <a href="items-list.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Items</a>
                    <a href="statistics.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">History</a>
                    <a href="users-list.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Users</a>
                    <a href="api/logout.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->

    <div class="min-h-screen flex flex-col items-start justify-center py-12">
        <div id="addbtn" class="ml-14 mb-1">
            <a href="#">
                <p class="btn text-white bg-[#2c6e35] px-6 py-3 rounded-full font-bold uppercase">Add user</p>
            </a>
        </div>
        <div id="main-content" class="rounded-2xl w-11/12 bg-[#7C9070] drop-shadow-xl p-8 mx-auto">
            <table id="users-table" class="table bg-[#7C9070]" style="width:100%">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Username</th>
                        <th>User Type</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Table rows will be populated dynamically using JavaScript -->
                </tbody>
                <tfoot>
                    <tr>
                        <th>User ID</th>
                        <th>Username</th>
                        <th>User Type</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>


    <!-- Add User Modal -->
    <div id="add-user-modal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
        <div class="fixed inset-0 bg-gray-800 bg-opacity-75" onclick="closeModal('add-user-modal')"></div>
        <div class="bg-[#4d68a1] rounded-lg p-8 relative drop-shadow-xl">
            <h2 class="text-2xl text-white font-bold mb-4">Add User</h2>
            <form id="add-user-form" action="api/adduser.php" method="POST">
                <div class="mb-4">
                    <label for="username" class="text-white block text-lg font-semibold mb-1">Username:</label>
                    <input type="text" id="username" name="username"
                        class="w-full border-gray-300 border rounded px-4 py-2 focus:outline-none focus:border-indigo-500"
                        required>
                </div>
                <div class="mb-4">
                    <label for="password" class="text-white block text-lg font-semibold mb-1">Password:</label>
                    <input type="password" id="password" name="password"
                        class="w-full border-gray-300 border rounded px-4 py-2 focus:outline-none focus:border-indigo-500"
                        required>
                </div>
                <div class="mb-4">
                    <label for="usertype" class="text-white block text-lg font-semibold mb-1">User Type:</label>
                    <select id="usertype" name="usertype"
                        class="w-full border-gray-300 border rounded px-4 py-2 focus:outline-none focus:border-indigo-500"
                        required>
                        <option value="Admin">Admin</option>
                        <option value="Front Desk">Front Desk Staff</option>
                        <option value="Kitchen Staff">Kitchen Staff</option>
                    </select>
                </div>
                <div class="flex justify-end">
                    <button type="submit" id="submit-add-user"
                        class="btn rounded-full bg-[#4e4485] py-1 drop-shadow-lg px-3 mr-3 text-white">Submit</button>
                    <button id="cancel-add-user" type="button"
                        class="btn rounded-full bg-gray-400 py-1 px-3 drop-shadow-lg font-bold mr-2">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div id="edit-user-modal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
        <div class="fixed inset-0 bg-gray-800 bg-opacity-75" onclick="closeModal('edit-user-modal')"></div>
        <div class="bg-white rounded-lg p-8 relative">
            <h2 class="text-2xl font-bold mb-4">Edit User</h2>
            <form id="edit-user-form" action="api/update_user.php" method="POST">
                <input type="hidden" name="user_id" id="edit-user-id">
                <div class="mb-4">
                    <label for="edit-username" class="block text-lg font-semibold mb-1">Username:</label>
                    <input type="text" id="edit-username" name="username"
                        class="w-full border-gray-300 border rounded px-4 py-2 focus:outline-none focus:border-indigo-500"
                        required>
                </div>
                <div class="mb-4">
                    <label for="edit-password" class="block text-lg font-semibold mb-1">Password:</label>
                    <input type="text" id="edit-password" name="password"
                        class="w-full border-gray-300 border rounded px-4 py-2 focus:outline-none focus:border-indigo-500"
                        required>
                </div>
                <div class="mb-4">
                    <label for="edit-usertype" class="block text-lg font-semibold mb-1">User Type:</label>
                    <select id="edit-usertype" name="usertype"
                        class="w-full border-gray-300 border rounded px-4 py-2 focus:outline-none focus:border-indigo-500"
                        required>
                        <option value="Admin">Admin</option>
                        <option value="Front Desk">Front Desk Staff</option>
                        <option value="Kitchen Staff">Kitchen Staff</option>
                    </select>
                </div>
                <div class="flex justify-end">
                    <button id="submit-edit-user" type="submit"
                        class="btn rounded-full bg-[#4e4485] py-1 px-3 mr-3 text-white">Submit</button>
                    <button id="cancel-edit-user" type="button"
                        class="btn rounded-full bg-gray-400 py-1 px-3 text-white mr-2">Cancel</button>
                </div>
            </form>
        </div>
    </div>


    <!-- Delete User Modal -->
    <div id="delete-user-modal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
        <div class="fixed inset-0 bg-gray-800 bg-opacity-75" onclick="closeModal('delete-user-modal')"></div>
        <div class="bg-white rounded-lg p-8 relative">
            <h2 class="text-2xl font-bold mb-4">Are you sure you want to delete user?</h2>
            <form id="delete-user-form" action="api/delete_user.php" method="POST">
                <input type="hidden" name="user_id" id="delete-user-id">
                <div class="mb-4">
                    <label for="delete-username" class="block text-lg font-semibold mb-1">Username:</label>
                    <input type="text" id="delete-username" name="username"
                        class="w-full border-gray-300 border rounded px-4 py-2 focus:outline-none focus:border-indigo-500"
                        readonly>
                </div>
                <div class="mb-4">
                    <label for="delete-usertype" class="block text-lg font-semibold mb-1">User Type:</label>
                    <input type="text" id="delete-usertype" name="usertype"
                        class="w-full border-gray-300 border rounded px-4 py-2 focus:outline-none focus:border-indigo-500"
                        readonly>
                </div>
                <div class="flex justify-end">
                    <button id="submit-delete-user" type="submit"
                        class="btn rounded-full bg-[#4e4485] py-1 px-3 mr-3 text-white">Submit</button>
                    <button id="cancel-delete-user" type="button"
                        class="btn rounded-full bg-gray-400 py-1 px-3 text-white mr-2">Cancel</button>
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

            // Open Add User Modal
            $('#addbtn a').click(function (e) {
                e.preventDefault();
                $('#add-user-modal').removeClass('hidden');
            });

            // Close Add User Modal
            $('#cancel-add-user').click(function () {
                $('#add-user-modal').addClass('hidden');
            });

            // Close Edit User Modal
            $('#cancel-edit-user').click(function () {
                $('#edit-user-modal').addClass('hidden');
            });

            // Close Edit User Modal
            $('#cancel-delete-user').click(function () {
                $('#delete-user-modal').addClass('hidden');
            });

            // Initialize DataTable
            $('#users-table').DataTable({
                ajax: {
                    url: 'api/users.php',
                    dataSrc: 'data' // Use 'data' as the property to retrieve the JSON data
                },
                columns: [
                    { data: '0', className: 'text-center bg-[#7C9070]' }, // Use the index to access the 'USER ID' column
                    { data: '1', className: 'text-center bg-[#7C9070]' }, // Use the index to access the 'USERNAME' column
                    { data: '2', className: 'text-center bg-[#7C9070]' }, // Use the index to access the 'USERTYPE' column
                    {
                        data: '3', // Use the index to access the 'OPTIONS' column
                        className: 'text-center bg-[#7C9070]',
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

        function openEditModal(userId) {
            // Show the edit user modal
            document.getElementById("edit-user-modal").classList.remove("hidden");

            // Make an AJAX request to fetch user details
            $.ajax({
                url: "api/edit_user.php",
                type: "GET",
                data: { user_id: userId },
                success: function (response) {
                    // Parse the JSON response
                    var user = JSON.parse(response);

                    // Populate the form fields with user details
                    $("#edit-user-id").val(user.user_id);
                    $("#edit-password").val(user.password);
                    $("#edit-username").val(user.username);
                    $("#edit-usertype").val(user.usertype);

                    // Show the Edit User modal
                    showModal("edit-user-modal");
                },
                error: function (xhr, status, error) {
                    // Handle the error if the request fails
                    console.log(error);
                }
            });
        }


        // for deleting user
        function openDeleteModal(userId) {
            // Show the delete user modal
            document.getElementById("delete-user-modal").classList.remove("hidden");

            // Perform AJAX request to retrieve user details
            $.ajax({
                url: 'api/get_user.php',
                type: 'POST',
                data: { userId: userId },
                success: function (response) {
                    // Parse the JSON response
                    var user = JSON.parse(response);

                    // Update the delete-user-modal form with the retrieved values
                    $('#delete-user-id').val(user.user_id);
                    $('#delete-username').val(user.username);
                    $('#delete-usertype').val(user.usertype);

                    // Open the delete-user-modal
                    $('#delete-user-modal').removeClass('hidden');
                },
                error: function () {
                    // Handle error case
                    console.log('Error occurred while retrieving user details.');
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