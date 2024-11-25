<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <!-- Navbar -->
    <?php
    include_once "views/components/admin-navbar.php";
    ?>

    <!-- Main Content -->
    <div class="container mx-auto py-10 px-6">
        <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">Edit Booking</h1>
        <form action="/booking-update?id=<?php echo $booking['id'] ?>" method="POST" enctype="multipart/form-data">
            <!-- Users -->
            <div class="mb-4">
                <label for="user" class="block text-sm font-medium text-gray-700">Users</label>
                <select name="user_id" id="user_id"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    required>
                    <option value="">Select User</option>
                    <?php
                    foreach ($users as $user) {
                        echo "<option value=\"{$user['id']}\" " . (($user['id'] == $booking['user_id']) ? 'selected' : '') . ">{$user['name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <!-- Events -->
            <div class="mb-4">
                <label for="user" class="block text-sm font-medium text-gray-700">Events</label>
                <select name="event_id" id="event_id" onchange="retrieveEventData(this.value)"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    required>
                    <option value="">Select Event</option>
                    <?php
                    foreach ($events as $event) {
                        echo "<option value=\"{$event['id']}\"" . (($event['id'] == $booking['event_id']) ? 'selected' : '') . ">{$event['name']}</option>"; // Assuming 'id' and 'name' are the keys
                    }
                    ?>
                </select>
            </div>
            <!-- Seat Type -->
            <div class="mb-4">
                <label for="seat_type" class="block text-sm font-medium text-gray-700">Seat Type</label>
                <p class="text-gray-500 mb-2 text-sm font-medium" id="seat_type_old_value" style="display: block;">
                    <?php echo $booking['seat_type'] . ' - $' . $booking['total_price'] / $booking['quantity'] ?></p>
                <select name="seat_type" id="seat_type" onchange="updateTotalPrice(this.value)"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    required style="display: none;">
                </select>
            </div>

            <!-- Quantity -->
            <div class="mb-6">
                <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                <input type="number" id="quantity" name="quantity" placeholder="Enter your quantity"
                    value="<?php echo $booking['quantity'] ?>"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    required min="1" max="8" oninput="updateTotalPriceWithQuantity()">
            </div>


            <!-- Photo -->
            <div class="mb-6">
                <label for="photo" class="block text-sm font-medium text-gray-700">Photo</label>
                <img src="<?php echo $booking['adult_photo']; ?>" alt=""
                    class="mb-2 w-20 h-20 object-cover border border-gray-200 rounded-md" />
                <input type="file" id="photo" name="photo" placeholder="Enter your photo" accept="image/*"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    required>
            </div>

            <!--  Total Price -->
            <div class="mb-6">
                <label for="total_price" class="block text-sm font-medium text-gray-700">Total</label>
                <input type="text" id="total_price" name="total_price" placeholder="Enter price with total price"
                    value="$<?php echo $booking['total_price'] ?>"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    readonly required>
            </div>
            <!--  Booking Date -->
            <div class="mb-6">
                <label for="booking_date" class="block text-sm font-medium text-gray-700">Booking Date</label>
                <input type="date" id="booking_date" name="booking_date" placeholder="Enter Booking Date"
                    value="<?php echo (new DateTime($booking['booking_date']))->format('Y-m-d'); ?>"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    required>
            </div>
            <!-- Submit Button -->
            <div>
                <button type="submit"
                    class="w-full bg-blue-500 text-white font-bold py-2 px-4 rounded-md hover:bg-blue-600 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none">
                    Create
                </button>
            </div>
        </form>
    </div>
    <script>
        let prices = {}; // Global variable to store prices

        function retrieveEventData(id) {
            if (id) {
                const xhr = new XMLHttpRequest();
                xhr.open("GET", `/get-seat-type?id=${id}`, true);
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        prices = { // Store prices in the global variable
                            with_table: response.price_with_table,
                            without_table: response.price_without_table
                        };

                        console.log(prices); // Log prices to verify they are set correctly

                        // Update the UI with the seat type and prices
                        const seatTypeSelect = document.getElementById('seat_type');
                        const seatTypeOldValue = document.getElementById('seat_type_old_value');
                        seatTypeSelect.innerHTML = ""; // Clear existing options
                        seatTypeSelect.add(new Option("Select Seat Type", "")); // Add default option
                        seatTypeSelect.add(new Option(`With Table - $${prices.with_table}`, "with_table"));
                        seatTypeSelect.add(new Option(`Without Table - $${prices.without_table}`, "without_table"));

                        // Show seatTypeSelect and hide seat_type_old_value
                        seatTypeSelect.style.display = "block";
                        seatTypeOldValue.style.display = "none";
                    } else {
                        console.error("Error fetching seat type");
                    }
                };
                xhr.send();
            } else {
                document.getElementById('seat_type').value = ""; // Clear the seat type
            }
        }
        // Retrieve seat type value
        function updateTotalPrice(seatType) {
            console.log("Selected seat type:", seatType, "Price:", prices[
                seatType]); // Log the selected seat type and its price
            const totalPriceInput = document.getElementById('total_price');

            // Update the total price input based on the selected seat type
            if (prices[seatType]) {
                totalPriceInput.value = `$${prices[seatType]}`; // Set the total price with a dollar sign
            } else {
                totalPriceInput.value = ""; // Clear if no valid seat type is selected
            }
        }

        function updateTotalPriceWithQuantity() {
            const quantityInput = document.getElementById('quantity'); // Get the quantity input element
            let quantity = quantityInput.value; // Get the quantity value
            const seatType = document.getElementById('seat_type').value; // Get the selected seat type
            const totalPriceInput = document.getElementById('total_price');

            // Check if quantity exceeds 8
            if (quantity > 8) {
                alert("Quantity cannot exceed 8. Setting quantity to 8."); // Show alert message
                quantity = 8; // Set quantity to 8
                quantityInput.value = quantity; // Update the input field
            }

            // Calculate the total price based on the selected seat type and quantity
            if (prices[seatType] && quantity) {
                const totalPrice = prices[seatType] * quantity; // Calculate total price
                totalPriceInput.value = `$${totalPrice.toFixed(2)}`; // Set the total price with a dollar sign
            } else {
                totalPriceInput.value = ""; // Clear if no valid seat type or quantity is selected
            }
        }
    </script>
</body>

</html>