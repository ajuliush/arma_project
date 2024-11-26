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
    include_once "views/components/user-navbar.php";
    ?>
    <?php
    include_once "views/components/message.php";
    ?>
    <!-- Modal -->
    <div id="eventModal"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 transition-opacity duration-300 ease-in-out opacity-0">
        <div
            class="relative top-20 mx-auto p-8 border w-3/4 max-w-3xl shadow-2xl rounded-lg bg-white transition-transform duration-300 ease-in-out transform scale-95">
            <!-- Close button at top -->
            <button onclick="closeModal()"
                class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 transition-colors duration-200">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>

            <div class="mt-3">
                <h3 class="text-2xl font-bold text-gray-900 mb-6" id="modal-title"></h3>

                <div class="bg-slate-800 rounded-lg overflow-hidden">
                    <table class="w-full">
                        <tbody class="divide-y divide-slate-700">
                            <tr>
                                <td class="px-6 py-4 text-slate-300 font-medium w-1/3">Description</td>
                                <td class="px-6 py-4 text-slate-300" id="modal-description"></td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 text-slate-300 font-medium">Date</td>
                                <td class="px-6 py-4 text-slate-300" id="modal-date"></td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 text-slate-300 font-medium">Time</td>
                                <td class="px-6 py-4 text-slate-300" id="modal-time"></td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 text-slate-300 font-medium">Price with table</td>
                                <td class="px-6 py-4 text-slate-300">$<span id="modal-price-with"></span></td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 text-slate-300 font-medium">Price without table</td>
                                <td class="px-6 py-4 text-slate-300">$<span id="modal-price-without"></span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-8 flex justify-end">
                    <button onclick="closeModal()"
                        class="bg-slate-800 text-white py-2 px-6 rounded-lg hover:bg-slate-700 transition-colors duration-200">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Main Content -->
    <div class="container mx-auto py-10 px-6">
        <a href="/" class="inline-block bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 mb-4">
            Make Booking
        </a>
        <div
            class="relative flex flex-col w-full h-full overflow-scroll text-slate-300 bg-slate-800 shadow-md rounded-lg bg-clip-border">
            <table class="w-full text-left table-auto min-w-max">
                <thead>
                    <tr>
                        <th class="p-4 border-b border-slate-600 bg-slate-700">
                            <p class="text-sm font-normal leading-none text-slate-300">
                                S/N
                            </p>
                        </th>
                        <th class="p-4 border-b border-slate-600 bg-slate-700">
                            <p class="text-sm font-normal leading-none text-slate-300">
                                Event Name
                            </p>
                        </th>
                        <th class="p-4 border-b border-slate-600 bg-slate-700">
                            <p class="text-sm font-normal leading-none text-slate-300">
                                Description
                            </p>
                        </th>
                        <th class="p-4 border-b border-slate-600 bg-slate-700">
                            <p class="text-sm font-normal leading-none text-slate-300">
                                Date
                            </p>
                        </th>
                        <th class="p-4 border-b border-slate-600 bg-slate-700">
                            <p class="text-sm font-normal leading-none text-slate-300">
                                Time
                            </p>
                        </th>
                        <th class="p-4 border-b border-slate-600 bg-slate-700">
                            <p class="text-sm font-normal leading-none text-slate-300">
                                Price with table
                            </p>
                        </th>
                        <th class="p-4 border-b border-slate-600 bg-slate-700">
                            <p class="text-sm font-normal leading-none text-slate-300">
                                Price without table
                            </p>
                        </th>
                        <th class="p-4 border-b border-slate-600 bg-slate-700">
                            <p class="text-sm font-normal leading-none text-slate-300">
                                Actions
                            </p>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sn = 1;
                    foreach ($events as $event):
                    ?>
                    <tr class="hover:bg-slate-700">
                        <td class="p-4 w-1 border-b border-slate-700 bg-slate-900">
                            <p class="text-sm text-slate-100 font-semibold">
                                <?php echo htmlspecialchars($sn); ?>
                            </p>
                        </td>
                        <td class="p-4 border-b border-slate-700 bg-slate-800">
                            <p class="text-sm text-slate-300">
                                <?php echo htmlspecialchars($event['name']); ?>
                            </p>
                        </td>
                        <td class="p-4 border-b border-slate-700 bg-slate-900">
                            <p class="text-sm text-slate-300">
                                <?php echo htmlspecialchars($event['description']); ?>
                            </p>
                        </td>
                        <td class="p-4 border-b border-slate-700 bg-slate-800">
                            <p class="text-sm text-slate-300">
                                <?php echo htmlspecialchars($event['date']); ?>
                            </p>
                        </td>
                        <td class="p-4 border-b border-slate-700 bg-slate-800">
                            <p class="text-sm text-slate-300">
                                <?php echo htmlspecialchars($event['time']); ?>
                            </p>
                        </td>
                        <td class="p-4 border-b border-slate-700 bg-slate-800">
                            <p class="text-sm text-slate-300">
                                <?php echo htmlspecialchars($event['price_with_table']); ?>
                            </p>
                        </td>
                        <td class="p-4 border-b border-slate-700 bg-slate-800">
                            <p class="text-sm text-slate-300">
                                <?php echo htmlspecialchars($event['price_without_table']); ?>
                            </p>
                        </td>
                        <td class="p-4 border-b border-slate-700 bg-slate-900">
                            <button onclick='showModal(<?php echo json_encode($event); ?>)'
                                class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">
                                View
                            </button>
                        </td>
                    </tr>
                    <?php
                        $sn++;
                    endforeach;
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Add JavaScript at the bottom of the body -->
    <script>
    function showModal(event) {
        const modal = document.getElementById('eventModal');
        const modalContent = modal.children[0];

        // First make the modal visible but transparent
        modal.classList.remove('hidden');

        // Force a reflow to enable the transition
        void modal.offsetWidth;

        // Make it visible with transition
        modal.classList.remove('opacity-0');
        modalContent.classList.remove('scale-95');
        modalContent.classList.add('scale-100');

        // Set the content
        document.getElementById('modal-title').textContent = event.name;
        document.getElementById('modal-description').textContent = event.description;
        document.getElementById('modal-date').textContent = event.date;
        document.getElementById('modal-time').textContent = event.time;
        document.getElementById('modal-price-with').textContent = event.price_with_table;
        document.getElementById('modal-price-without').textContent = event.price_without_table;
    }

    function closeModal() {
        const modal = document.getElementById('eventModal');
        const modalContent = modal.children[0];

        // Start the fade out animation
        modal.classList.add('opacity-0');
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');

        // Wait for animation to finish before hiding
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    // Close modal when clicking outside
    document.getElementById('eventModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
    </script>
</body>

</html>