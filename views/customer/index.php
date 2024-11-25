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

        <div class="relative top-20 mx-auto p-8 border w-full max-w-2xl shadow-lg rounded-md bg-white">
            <!-- Add close button -->
            <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>

            <form action="booking-store" method="post" enctype="multipart/form-data">
                <div class="mt-3">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6" id="modal-title"></h3>
                    <div class="mt-2 space-y-6">
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Select Ticket Type</label>
                                <select id="ticketType" name="seat_type"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2.5">
                                    <option value="with_table">With Table</option>
                                    <option value="without_table">Without Table</option>
                                </select>
                            </div>
                            <div>
                                <label for="" class="block text-sm font-medium text-gray-700 mb-2">Adult Photo</label>
                                <input type="file" name="photo" id="photo" accept="image/*"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2.5">
                            </div>
                            <div>
                                <label for="" class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                                <input type="date" name="booking_date" id="booking_date"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2.5">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Number of Tickets</label>
                                <input type="number" id="ticketQuantity" min="1" max="8" value="1" name="quantity"
                                    onchange="validateTicketQuantity(this)"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2">
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-lg text-gray-700">Total Price: <span id="totalPrice"
                                        class="font-bold text-blue-600 text-xl"></span></p>
                                <input type="hidden"
                                    class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2"
                                    name="user_id" id="user_id">
                                <input type="hidden"
                                    class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2"
                                    name="event_id" id="event_id">

                                <input type="hidden"
                                    class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2"
                                    name="total_price" id="totalPriceHidden">
                            </div>
                        </div>

                    </div>
                    <div class="flex justify-end gap-4 mt-8">
                        <a href="#" onclick="closeModal()"
                            class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 font-medium">
                            Cancel
                        </a>
                        <button type="submit"
                            class="px-6 py-2.5 bg-blue-500 text-white rounded-md hover:bg-blue-600 font-medium">
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
    </script>
</body>

</html>