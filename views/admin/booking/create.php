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
    <?php
    // session_start();
    $errors = $_SESSION['errors'] ?? [];

    // Clear the session data after retrieving
    unset($_SESSION['errors']);
    ?>
    <!-- Main Content -->
    <div class="container mx-auto py-10 px-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <a href="/bookings"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 text-white font-semibold rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                            clip-rule="evenodd" />
                    </svg>
                    Back to Bookings List
                </a>
            </div>

            <h1 class="text-2xl font-bold text-gray-800">Create new Booking</h1>

            <!-- Added empty div for balanced spacing -->
            <div class="w-[140px]"></div>
        </div>
        <form action="/booking-store" method="POST" enctype="multipart/form-data"
            class="bg-white shadow-md rounded-lg p-6">
            <!-- Users -->
            <div class="mb-4">
                <label for="user" class="block text-sm font-medium text-gray-700">Users</label>
                <select name="user_id" id="user_id"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="">Select User</option>
                    <?php
                    foreach ($users as $user) {
                        echo "<option value=\"{$user['id']}\">{$user['name']}</option>"; // Assuming 'id' and 'name' are the keys
                    }
                    ?>
                </select>
                <?php if (isset($errors['user_id'])): ?>
                <div class="mt-2 p-2 rounded-md bg-red-50 border border-red-400">
                    <p class="text-red-700 text-sm"><?php echo htmlspecialchars($errors['user_id']); ?></p>
                </div>
                <?php unset($errors['user_id']); ?>
                <?php endif; ?>
            </div>
            <!-- Events -->
            <div class="mb-4">
                <label for="event_id" class="block text-sm font-medium text-gray-700">Events</label>
                <select name="event_id" id="event_id" onchange="retrieveEventData(this.value)"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="">Select Event</option>
                    <?php
                    foreach ($events as $event) {
                        echo "<option value=\"{$event['id']}\">{$event['name']}</option>"; // Assuming 'id' and 'name' are the keys
                    }
                    ?>
                </select>
                <?php if (isset($errors['event_id'])): ?>
                <div class="mt-2 p-2 rounded-md bg-red-50 border border-red-400">
                    <p class="text-red-700 text-sm"><?php echo htmlspecialchars($errors['event_id']); ?></p>
                </div>
                <?php unset($errors['event_id']); ?>
                <?php endif; ?>
            </div>
            <!-- Seat Type -->
            <div class="mb-4">
                <label for="seat_type" class="block text-sm font-medium text-gray-700">Seat Type</label>
                <select name="seat_type" id="seat_type" onchange="updateTotalPrice(this.value)"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </select>
                <?php if (isset($errors['seat_type'])): ?>
                <div class="mt-2 p-2 rounded-md bg-red-50 border border-red-400">
                    <p class="text-red-700 text-sm"><?php echo htmlspecialchars($errors['seat_type']); ?></p>
                </div>
                <?php unset($errors['seat_type']); ?>
                <?php endif; ?>
            </div>
            <!-- Seat Limit -->
            <div class="mb-4">
                <label for="seat_limit" class="block text-sm font-medium text-gray-700">Seat Limit</label>
                <input type="text" id="seat_limit" name="seat_limit" readonly
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm bg-gray-100">
            </div>
            <!-- Book Seat -->
            <div class="mb-4">
                <label for="book_seat" class="block text-sm font-medium text-gray-700">Book Seat</label>
                <input type="text" id="book_seat" name="book_seat" readonly
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm bg-gray-100">
            </div>
            <!-- Available Seat -->
            <div class="mb-4">
                <label for="available_seat" class="block text-sm font-medium text-gray-700">Available Seat</label>
                <input type="text" id="available_seat" name="available_seat" readonly
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm bg-gray-100">
            </div>
            <!-- Quantity -->
            <div class="mb-6">
                <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                <input type="number" id="quantity" name="quantity" placeholder="Enter your quantity" value="1"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    min="1" max="8" oninput="updateTotalPriceWithQuantity()">
                <?php if (isset($errors['quantity'])): ?>
                <div class="mt-2 p-2 rounded-md bg-red-50 border border-red-400">
                    <p class="text-red-700 text-sm"><?php echo htmlspecialchars($errors['quantity']); ?></p>
                </div>
                <?php unset($errors['quantity']); ?>
                <?php endif; ?>
            </div>
            <!-- Photo -->
            <div class="mb-6">
                <label for="photo" class="block text-sm font-medium text-gray-700">Photo</label>
                <input type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/gif"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                <p class="mt-1 text-sm text-gray-500">Accepted formats: JPG, PNG, GIF (Max: 2MB)</p>
                <?php if (isset($errors['photo'])): ?>
                <div class="mt-2 p-2 rounded-md bg-red-50 border border-red-400">
                    <p class="text-red-700 text-sm"><?php echo htmlspecialchars($errors['photo']); ?></p>
                </div>
                <?php unset($errors['photo']); ?>
                <?php endif; ?>
            </div>
            <!-- Total Price -->
            <div class="mb-6">
                <label for="total_price" class="block text-sm font-medium text-gray-700">Total</label>
                <input type="text" id="total_price" name="total_price" placeholder="Enter price with total price"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    readonly>
            </div>
            <!-- Booking Date -->
            <div class="mb-6">
                <label for="booking_date" class="block text-sm font-medium text-gray-700">Booking Date</label>
                <input type="date" id="booking_date" name="booking_date" placeholder="Enter Booking Date"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                <?php if (isset($errors['booking_date'])): ?>
                <div class="mt-2 p-2 rounded-md bg-red-50 border border-red-400">
                    <p class="text-red-700 text-sm"><?php echo htmlspecialchars($errors['booking_date']); ?></p>
                </div>
                <?php unset($errors['booking_date']); ?>
                <?php endif; ?>
            </div>
            <!-- Submit Button -->
            <div>
                <button type="submit" id="if_seat_availability"
                    class="w-full bg-blue-500 text-white font-bold py-2 px-4 rounded-md hover:bg-blue-600 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none">
                    Create
                </button>
                <p id="if__not_seat_availability" style="display: none;"
                    class="w-full bg-red-500 text-white font-bold py-2 px-4 rounded-md hover:bg-red-600 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:outline-none text-center">
                    No Seats are available, please select another event!
                </p>
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

                    console.log(response.total_quantity); // Log prices to verify they are set correctly
                    seat_limit.value = response.seat_limit;
                    book_seat.value = response.total_quantity;
                    available_seat.value = response.seat_limit - response.total_quantity;
                    // Update the UI with the seat type and prices
                    const seatTypeSelect = document.getElementById('seat_type');
                    seatTypeSelect.innerHTML = ""; // Clear existing options
                    seatTypeSelect.add(new Option("Select Seat Type", "")); // Add default option
                    seatTypeSelect.add(new Option(`With Table - $${prices.with_table}`, "with_table"));
                    seatTypeSelect.add(new Option(`Without Table - $${prices.without_table}`, "without_table"));

                    if (response.seat_limit - response.total_quantity === 0) {
                        alert('No seats are available, Select another event!');
                        document.getElementById('if_seat_availability').style.display = "none";
                        document.getElementById('if__not_seat_availability').style.display = "block";
                    } else {
                        document.getElementById('if_seat_availability').style.display = "block";
                        document.getElementById('if__not_seat_availability').style.display = "none";
                    }
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
        const availableSeats = parseInt(document.getElementById('available_seat').value); // Get available seats

        // Check if quantity exceeds available seats or 8
        if (quantity > availableSeats) {
            alert(
                `Quantity cannot exceed available seats (${availableSeats}). Setting quantity to ${availableSeats}.`
            ); // Show alert message
            quantity = availableSeats; // Set quantity to available seats
        }
        if (quantity > 8) {
            alert("Quantity cannot exceed 8. Setting quantity to 8."); // Show alert message
            quantity = 8; // Set quantity to 8
        }

        quantityInput.value = quantity; // Update the input field

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