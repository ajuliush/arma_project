<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mellodian || Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-100">
    <!-- Navigation Bar -->

    <?php
    include_once 'views/components/user-navbar.php';
    ?>
    <?php
    include_once "views/components/message.php";
    ?>
    <!-- Main Content -->
    <main class="container mx-auto py-8 px-6">
        <!-- Welcome Message -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Welcome, <?php echo $user[0]->name; ?>
            </h2>
            <p class="text-gray-600">Browse and book tickets for exciting Christmas events.</p>
        </div>

        <!-- Events Section -->
        <section>
            <h3 class="text-xl font-bold text-gray-800 mb-4">Upcoming Events</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Event Card 1 -->
                <?php foreach ($events as $event) : ?>
                <div
                    class="bg-white shadow-lg rounded-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="p-6">
                        <h4 class="text-xl font-bold text-gray-800 mb-3"><?php echo $event['name']; ?></h4>
                        <p class="text-gray-600 mb-4"><?php echo $event['description']; ?></p>

                        <div class="space-y-3 border-t border-gray-100 pt-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">With table:</span>
                                <span
                                    class="text-lg font-semibold text-blue-600">$<?php echo $event['price_with_table']; ?></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Without table:</span>
                                <span
                                    class="text-lg font-semibold text-blue-600">$<?php echo $event['price_without_table']; ?></span>
                            </div>
                        </div>

                        <button onclick="openModal(<?php echo htmlspecialchars(json_encode($event)); ?>)"
                            class="mt-6 w-full bg-blue-500 text-white py-3 px-4 rounded-lg font-medium hover:bg-blue-600 transform hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                            Book Now
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
                <!-- Event Card 2 -->
            </div>
        </section>
    </main>

    <!-- Modal -->
    <div id="bookingModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden"
        x-data="{ open: false }" x-show="open" x-on:open-modal.window="open = true"
        x-on:close-modal.window="open = false" x-on:click="if ($event.target.id === 'bookingModal') closeModal()"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

        <div class="relative top-20 mx-auto p-8 border w-full max-w-2xl shadow-lg rounded-md bg-white"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 transform scale-95 translate-y-4">
            <!-- Add close button -->
            <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>

            <form action="booking-store" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
                <div class="mt-3">
                    <h3 class="text-2xl font-bold text-gray-900 mb-8" id="modal-title"></h3>

                    <!-- Main Form Content -->
                    <div class="space-y-8">
                        <!-- Ticket Details Section -->
                        <div class="bg-gray-50 p-6 rounded-xl space-y-6">
                            <h4 class="text-lg font-semibold text-gray-800">Ticket Details</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Ticket
                                        Type</label>
                                    <select id="ticketType" name="seat_type"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-3 px-4"
                                        required>
                                        <option value="">-- Select Ticket Type --</option>
                                        <option value="with_table">With Table</option>
                                        <option value="without_table">Without Table</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Number of
                                        Tickets</label>
                                    <input type="number" id="ticketQuantity" min="1" max="8" value="1" name="quantity"
                                        onchange="validateTicketQuantity(this)"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-3 px-4"
                                        required>
                                    <p class="mt-1 text-sm text-gray-500">Maximum 8 tickets per booking</p>
                                </div>
                            </div>
                        </div>

                        <!-- Personal Details Section -->
                        <div class="bg-gray-50 p-6 rounded-xl space-y-6">
                            <h4 class="text-lg font-semibold text-gray-800">Personal Details</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Booking Date</label>
                                    <input type="date" name="booking_date" id="booking_date"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-3 px-4"
                                        required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Adult Photo ID</label>
                                    <div class="flex items-center justify-center w-full">
                                        <label
                                            class="w-full flex flex-col items-center px-4 py-3 bg-white text-gray-700 rounded-lg shadow-sm border border-gray-300 cursor-pointer hover:bg-gray-50">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span class="mt-2 text-sm">Select a photo</span>
                                            <input type="file" name="photo" id="photo" accept="image/*" class="hidden">
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Price Summary Section -->
                        <div class="bg-blue-50 p-6 rounded-xl">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-800">Total Amount</h4>
                                    <p class="text-sm text-gray-600 mt-1">Including all taxes and fees</p>
                                </div>
                                <div class="text-right">
                                    <span id="totalPrice" class="text-3xl font-bold text-blue-600">$0.00</span>
                                </div>
                            </div>

                            <!-- Hidden inputs -->
                            <input type="hidden" name="user_id" id="user_id">
                            <input type="hidden" name="event_id" id="event_id">
                            <input type="hidden" name="total_price" id="totalPriceHidden">
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end gap-4 mt-8">
                        <button type="button" onclick="closeModal()"
                            class="px-6 py-3 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors duration-200">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition-colors duration-200">
                            Confirm Booking
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Add JavaScript for modal handling -->
    <script>
    let currentEvent = null;
    const currentUserId = <?php echo json_encode($_SESSION['id'] ?? null); ?>;

    function openModal(event) {
        currentEvent = event;
        document.getElementById('modal-title').textContent = 'Book for ' + event.name;
        document.getElementById('user_id').value = currentUserId;
        document.getElementById('event_id').value = event.id;
        document.getElementById('bookingModal').classList.remove('hidden');
        window.dispatchEvent(new CustomEvent('open-modal'));
        updateTotalPrice();
    }

    function closeModal() {
        document.getElementById('bookingModal').classList.add('hidden');
        window.dispatchEvent(new CustomEvent('close-modal'));
        currentEvent = null;
    }

    function updateTotalPrice() {
        if (!currentEvent) return;

        let quantity = parseInt(document.getElementById('ticketQuantity').value);
        // Ensure quantity doesn't exceed 8
        quantity = Math.min(quantity, 8);
        document.getElementById('ticketQuantity').value = quantity;

        const ticketType = document.getElementById('ticketType').value;
        const price = ticketType === 'with_table' ?
            currentEvent.price_with_table :
            currentEvent.price_without_table;

        const total = price * quantity;
        document.getElementById('totalPrice').textContent = `$${total}`;
        document.getElementById('totalPriceHidden').value = `$${total}`;
    }

    function submitBooking() {
        // Add your booking submission logic here
        const ticketType = document.getElementById('ticketType').value;
        const quantity = document.getElementById('ticketQuantity').value;

        // Example: You might want to send this to your server
        console.log('Booking submitted:', {
            eventId: currentEvent.id,
            ticketType,
            quantity
        });

        closeModal();
    }

    // Add event listeners for price updates
    document.getElementById('ticketType').addEventListener('change', updateTotalPrice);
    document.getElementById('ticketQuantity').addEventListener('input', updateTotalPrice);

    function validateTicketQuantity(input) {
        if (input.value > 8) {
            input.value = 8;
        }
        updateTotalPrice();
    }

    function validateForm() {
        const ticketType = document.getElementById('ticketType');
        const ticketQuantity = document.getElementById('ticketQuantity');
        const bookingDate = document.getElementById('booking_date');
        const photo = document.getElementById('photo');
        let isValid = true;

        // Reset previous error styles
        ticketType.classList.remove('border-red-500');
        ticketQuantity.classList.remove('border-red-500');
        bookingDate.classList.remove('border-red-500');
        photo.parentElement.classList.remove('border-red-500'); // Reset photo field error

        // Validate Ticket Type
        if (!ticketType.value) {
            ticketType.classList.add('border-red-500');
            isValid = false;
        }

        // Validate Ticket Quantity
        if (ticketQuantity.value < 1 || ticketQuantity.value > 8) {
            ticketQuantity.classList.add('border-red-500');
            isValid = false;
        }

        // Validate Booking Date
        if (!bookingDate.value) {
            bookingDate.classList.add('border-red-500');
            isValid = false;
        }

        // Validate Photo ID
        if (!photo.files.length) {
            photo.parentElement.classList.add('border-red-500'); // Highlight the label instead
            isValid = false;
        }

        if (!isValid) {
            alert('Please correct the highlighted fields.');
        }

        return isValid; // Return the overall validity
    }
    </script>

    <style>
    .border-red-500 {
        border-color: red !important;
        /* Ensure the red border is applied */
    }
    </style>
</body>

</html>