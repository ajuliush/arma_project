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
        <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">Create Event</h1>
        <form action="/event-store" method="POST" enctype="multipart/form-data">
            <!-- Name -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" id="name" name="name" placeholder="Enter your name"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    required>
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    required cols="30" rows="10"></textarea>
            </div>

            <!-- Date -->
            <div class="mb-6">
                <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                <input type="date" id="date" name="date" placeholder="Enter your date"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    required>
            </div>


            <!-- Time -->
            <div class="mb-6">
                <label for="time" class="block text-sm font-medium text-gray-700">Time</label>
                <input type="time" id="time" name="time" placeholder="Enter your time"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    required>
            </div>

            <!--  Price with table -->
            <div class="mb-6">
                <label for="price_with_table" class="block text-sm font-medium text-gray-700">Price with table</label>
                <input type="number" id="price_with_table" name="price_with_table" placeholder="Enter price with table"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    required>
            </div>
            <!--  Price without table -->
            <div class="mb-6">
                <label for="price_without_table" class="block text-sm font-medium text-gray-700">Price without
                    table</label>
                <input type="number" id="price_without_table" name="price_without_table"
                    placeholder="Enter price without table"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    required>
            </div>
            <!--  Requires Adult -->
            <div class="mb-6">
                <label for="requires_adult" class="block text-sm font-medium text-gray-700">Requires Adult
                </label>
                <select name="requires_adult" id="requires_adult"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
            <!--  Seat limit -->
            <div class="mb-6">
                <label for="seat_limit" class="block text-sm font-medium text-gray-700">Seat limit</label>
                <input type="number" id="seat_limit"" name=" seat_limit"" placeholder="Enter seat limit"
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
</body>

</html>