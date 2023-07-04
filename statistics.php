<?php
$name = 'dateTime';
$value = date('Y-m-d H:i:s');

// Set the cookie
setcookie($name, $value);
session_start();
    if (!isset($_SESSION['usertype'])){
        // Redirect to the login page
        echo "<script>alert('You need to login!'); window.location.href='login.php';</script>";
        exit();
    } elseif ($_SESSION['usertype']=='Kitchen Staff') {
        echo "<script>alert('Only admins have access to this page');</script>";
        header("Location: orders.php");
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
    <div class="flex justify-center items-center h-screen">
        <div class="container">
            <div class="flex justify-center items-center">
                <div class="w-full">
                    <div id="main-content" class="card bg-white p-5 floating-animation border border-black">
                        <div class="card-body mx-auto">

                            <h2 class="card-title text-center font-bold text-2xl uppercase font-black mb-3">
                                Sales Summary
                            </h2>

                            <div class="w-full mt-5">
                                <h3 class="text-xl font-bold mb-2">Summary of Sales</h3>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mx-auto">
                                    <div id="today-sales" class="border border-black bo p-4"></div>
                                    <div id="item-sales" class="border border-black p-4"></div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            // Apply floating animation to the main content after page load
            setTimeout(function () {
                $('#main-content').addClass('floating-animation');
            }, 1000);

            // Apply floating-down animation to the navbar after page load
            setTimeout(function () {
                $('#navbar').addClass('floating-animation-down');
            }, 0);

            // Apply floating-down animation to the add button after page load
            setTimeout(function () {
                $('#add-button').addClass('floating-animation-down');
            }, 150);

            $.ajax({
                url: "api/stats.php",
                method: "GET",
                dataType: "json",
                success: function (response) {
                    // Populate today's sales information
                    $("#today-sales").html(`
                        <h4 class="text-lg font-semibold mb-2">Sales History</h4>
                        <p>Total Earnings: ₱${response.today.totalEarnings}</p>
                    `);

                    // Populate item sales information
                    var itemSalesHtml = `<h4 class="text-lg font-semibold mb-2">Items Sold</h4>`;
                    for (var i = 0; i < response.itemSummary.length; i++) {
                        var item = response.itemSummary[i];
                        itemSalesHtml += `
                            <div>
                                <p>Item Name: ${item.itemName}</p>
                                <p>Sold Quantity: ${item.soldQuantity}</p>
                                <p>Total Earnings: ₱${item.totalEarnings}</p>
                            </div>
                            <hr class="my-4 border-black">
                        `;
                    }
                    $("#item-sales").html(itemSalesHtml);
                },
                error: function () {
                    console.log("Error occurred while fetching sales data.");
                }
            });

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