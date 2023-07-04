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
    <script src="https://cdn.tailwindcss.com"></script>

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

        /* CSS for customizing the scrollbar */

        .item-display::-webkit-scrollbar {
            width: 8px;
        }

        .item-display::-webkit-scrollbar-track {
            background-color: #dddddd;
        }

        .item-display::-webkit-scrollbar-thumb {
            background-color: black;
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
    <div class="min-h-screen flex flex-col items-start justify-center py-8">
        <div id="main-content" class=" w-11/12 bg-black drop-shadow-xl p-1 mx-auto">
            <div class="flex">
                <!-- Cart -->
                <div class="w-full md:w-1/2 bg-white shadow-xl p-4 ml-1">
                    <h2 class="text-xl font-semibold mb-4 text-center">Order</h2>
                    <div class="cart-items">
                        <!-- Cart items will be populated dynamically -->
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th>Item Name</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                error_reporting(E_ALL);
                                ini_set('display_errors', 1);

                                require_once 'api/db_connect.php';

                                // Retrieve cart items from the temp_order table
                                $cartQuery = "SELECT * FROM temp_order";
                                $cartResult = mysqli_query($conn, $cartQuery);
                                $totalPrice = 0; // Initialize total price variable
                                
                                if ($cartResult && mysqli_num_rows($cartResult) > 0) {
                                    while ($row = mysqli_fetch_assoc($cartResult)) {
                                        $itemName = $row['item_name'];
                                        $quantity = $row['quantity'];
                                        $itemPrice = $row['price'];

                                        // Display each cart item in a table row
                                        echo "<tr class='text-center'>";
                                        echo "<td>$itemName</td>";
                                        echo "<td>$quantity</td>";
                                        echo "</tr>";

                                        // Compute the total price by adding the price of each item
                                        $totalPrice += $itemPrice;
                                    }


                                } else {
                                    // No items in the cart
                                    echo "<tr class='text-center'><td colspan='2'></td></tr>";
                                }

                                // Free the result set
                                mysqli_free_result($cartResult);
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-16 justify-center items-center">
                        <div><hr style="height:3px;color:black;background-color:black;border-width:0"></div>
                        <p class="font-semibold">Total Price:</p>
                            <?php
                            // Display the total price
                            echo "<p class='font-bold' id='total-price'>$" . number_format($totalPrice, 2) . "</p>";
                            ?>
                        <div class="mt-8">
                            <button class="checkout-btn bg-blue-700 hover:bg-blue-300 text-white font-bold py-2 px-4 rounded-lg mt-4">Checkout</button>
                            <button class="checkout-btn bg-blue-700 hover:bg-blue-300 text-white font-bold py-2 px-4 rounded-lg mt-4" onclick="window.location.href = 'orders.php';">Order</button>
                            <button class="Clear-btn bg-red-500 hover:bg-red-300 text-white font-bold py-2 px-4 rounded-lg mt-4" onclick="openClearModal('delete-modal')">Reset</button>                      
                        </div>
                    </div>

                </div>
                <!-- Item Display -->
                
                <div
                    class="w-full md:w-1/2 bg-white shadow-xl p-4 ml-4 item-display grid grid-cols-3 gap-4 h-96 overflow-y-auto">

                    <!-- Items will be populated dynamically -->
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center"
        id="QuantityModal">
        <div class="modal-content bg-white rounded-lg shadow-xl p-8 z-40">
            <h2 class="text-2xl font-semibold mb-4">Order Item</h2>
            <div id="item-details" class="mb-2"></div>
            <form id="order-form" method="POST" action="api/addToCart.php">

                <div class="mb-4 flex">
                    <label class="block text-lg font-semibold my-auto mr-2" for="quantity">Quantity: </label>
                    <input type="number" id="quantity_id" name="quantity_id" hidden>
                    <input class="w-24 px-4 py-2 border border-gray-300 rounded-lg" type="number" id="quantity"
                        name="quantity" required>
                </div>
                <button class="submit-btn bg-[#4c8554] hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg"
                    type="submit">Submit</button>
                <button id="closeQuantityModal"
                    class="close-btn bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg ml-4"
                    type="button">Close</button>
            </form>
        </div>
        <div class="fixed inset-0 bg-gray-800 bg-opacity-75" onclick="closeModal('QuantityModal')"></div>
    </div>

    <!-- Delete Modal -->
    <div id="delete-modal" class="fixed inset-0 flex items-center justify-center z-40 hidden">
    <div class="fixed inset-0 bg-gray-800 bg-opacity-75" onclick="closeModal('delete-modal')"></div>
    <div class="bg-white rounded-lg p-24 relative shadow-lg">
        <h2 class="text-3xl font-bold mb-6">Clear Order?</h2>
        <form id="delete-form" action="api/clear_cart.php" method="POST">
            <input type="hidden" name="delete-id" id="delete-id">
            <div class="flex justify-end">
                <button id="submit-delete" type="submit"
                    class="btn rounded-full bg-blue-700 py-2 px-4 mr-4 text-white">Clear</button>
                <button id="cancel-delete" type="button" onclick="closeModal('delete-modal')"
                    class="btn rounded-full bg-red-700 py-2 px-4 text-white">Cancel</button>
            </div>
        </form>
    </div>
</div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Retrieve items for a specific category from the server
        function loadItems(category) {
            // Simulate the AJAX request to fetch items from the server
            setTimeout(function () {
                // Make an AJAX request to load_items.php
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'api/load_items.php', true);
                xhr.onload = function () {
                    console.log('Response received:', xhr.status);
                    if (xhr.status === 200) {
                        // Parse the response JSON
                        var items = JSON.parse(xhr.responseText);


                        // Generate item cards HTML and append to the item display area
                        var itemDisplay = document.querySelector('.item-display');
                        itemDisplay.innerHTML = '';
                        items.forEach(function (item) {
                            var itemCard = document.createElement('div');
                            itemCard.classList.add('item-card');
                            itemCard.innerHTML = `
                                <h3 class="font-semibold mb-2">${item.item_name}</h3>
                                <p>${item.description}</p>
                                <p>Price: $${item.item_price}</p>
                                <button class="order-btn bg-blue-700 hover:bg-blue-300 text-white font-bold py-2 px-4 rounded-lg mt-4" 
                                onclick="openModal('${item.item_name}', ${item.item_id})">Order</button>
                            `;
                            itemDisplay.appendChild(itemCard);
                        });
                    } else {
                        console.error('Error loading items:', xhr.status);
                    }
                };
                xhr.send();
            }, 500);
        }



        // Submit the order form
        function submitOrderForm(e) {
            e.preventDefault();

            // Retrieve the quantity from the form
            var quantityInput = document.getElementById('quantity');
            var quantity = parseInt(quantityInput.value);

            // Validate the quantity
            if (isNaN(quantity) || quantity <= 0) {
                alert('Please enter a valid quantity.');
                return;
            }

            // Perform additional processing or submit the order
            // ...

            // Clear the form
            quantityInput.value = '';

            // Close the modal
            closeModal();
        }

        // Open the order modal
        function openModal(itemName, itemId) {
            var modal = document.querySelector('#QuantityModal');
            modal.classList.remove('hidden');

            var itemDetailsElement = document.querySelector('#item-details');
            itemDetailsElement.innerHTML = `
                    <p>Item: <span class='font-bold'>${itemName}</span></p>
                `;

            var quantityIdInput = document.querySelector('#quantity_id');
            quantityIdInput.value = itemId;
        }

        function openClearModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.remove('hidden');
        }

        // Close quantity item Modal
        $('#closeQuantityModal').click(function () {
            $('#QuantityModal').addClass('hidden');
        });
        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.add('hidden');
        }

        // Initialize the menu
        function initializeMenu() {
            // Load categories and items
            loadItems();

            // Add event listeners
            var orderBtns = document.querySelectorAll('.order-btn');
            var submitBtn = document.getElementById('submit-btn');
            var closeBtn = document.getElementById('close-btn');

            orderBtns.forEach(function (btn) {
                btn.addEventListener('click', openModal);
            });

            submitBtn.addEventListener('click', submitOrderForm);
            closeBtn.addEventListener('click', closeModal);
        }

        // Initialize the menu when the DOM is ready
        document.addEventListener('DOMContentLoaded', function () {
            initializeMenu();
        });
        // Add event listener to the Checkout button
        var checkoutBtn = document.querySelector('.checkout-btn');
        checkoutBtn.addEventListener('click', function () {
            // Redirect to api/place_order.php
            window.location.href = 'api/place_order.php';
        });

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