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
    $old = $_SESSION['old'] ?? [];

    // Clear the session data after retrieving
    unset($_SESSION['errors']);
    unset($_SESSION['old']);
    ?>
    <!-- Main Content -->
    <div class="container mx-auto py-10 px-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <a href="/events"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 text-white font-semibold rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                            clip-rule="evenodd" />
                    </svg>
                    Back to Events List
                </a>
            </div>

            <h1 class="text-2xl font-bold text-gray-800">Edit <?php echo $event['name']; ?></h1>

            <!-- Added empty div for balanced spacing -->
            <div class="w-[140px]"></div>
        </div>
        <form action="/event-update?id=<?php echo $event['id']; ?>" method="POST" enctype="multipart/form-data">
            <!-- Name -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" id="name" name="name" placeholder="Enter your name"
                    value="<?php echo $event['name']; ?>"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                <?php if (isset($errors['name'])): ?>
                    <div class="mb-4 p-4 rounded-md bg-red-50 border border-red-400">
                        <p class="text-red-700 text-sm"><?php echo htmlspecialchars($errors['name']); ?></p>
                    </div>
                    <?php unset($errors['name']); ?>
                <?php endif; ?>
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    cols="30" rows="10"> <?php echo $event['description']; ?></textarea>
                <?php if (isset($errors['description'])): ?>
                    <div class="mb-4 p-4 rounded-md bg-red-50 border border-red-400">
                        <p class="text-red-700 text-sm"><?php echo htmlspecialchars($errors['description']); ?></p>
                    </div>
                    <?php unset($errors['description']); ?>
                <?php endif; ?>
            </div>

            <!-- Date -->
            <div class="mb-6">
                <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                <input type="date" id="date" name="date" placeholder="Enter your date"
                    value="<?php echo $event['date']; ?>"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                <?php if (isset($errors['date'])): ?>
                    <div class="mb-4 p-4 rounded-md bg-red-50 border border-red-400">
                        <p class="text-red-700 text-sm"><?php echo htmlspecialchars($errors['date']); ?></p>
                    </div>
                    <?php unset($errors['date']); ?>
                <?php endif; ?>
            </div>


            <!-- Time -->
            <div class="mb-6">
                <label for="time" class="block text-sm font-medium text-gray-700">Time</label>
                <input type="time" id="time" name="time" placeholder="Enter your time"
                    value="<?php echo $event['time']; ?>"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                <?php if (isset($errors['time'])): ?>
                    <div class="mb-4 p-4 rounded-md bg-red-50 border border-red-400">
                        <p class="text-red-700 text-sm"><?php echo htmlspecialchars($errors['time']); ?></p>
                    </div>
                    <?php unset($errors['time']); ?>
                <?php endif; ?>
            </div>

            <!--  Price with table -->
            <div class="mb-6">
                <label for="price_with_table" class="block text-sm font-medium text-gray-700">Price with table</label>
                <input type="number" id="price_with_table" name="price_with_table" placeholder="Enter price with table"
                    value="<?php echo $event['price_with_table']; ?>"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                <?php if (isset($errors['price_with_table'])): ?>
                    <div class="mb-4 p-4 rounded-md bg-red-50 border border-red-400">
                        <p class="text-red-700 text-sm"><?php echo htmlspecialchars($errors['price_with_table']); ?></p>
                    </div>
                    <?php unset($errors['price_with_table']); ?>
                <?php endif; ?>
            </div>
            <!--  Price without table -->
            <div class="mb-6">
                <label for="price_without_table" class="block text-sm font-medium text-gray-700">Price without
                    table</label>
                <input type="number" id="price_without_table" name="price_without_table"
                    placeholder="Enter price without table" value="<?php echo $event['price_without_table']; ?>"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                <?php if (isset($errors['price_without_table'])): ?>
                    <div class="mb-4 p-4 rounded-md bg-red-50 border border-red-400">
                        <p class="text-red-700 text-sm"><?php echo htmlspecialchars($errors['price_without_table']); ?></p>
                    </div>
                    <?php unset($errors['price_without_table']); ?>
                <?php endif; ?>
            </div>
            <!--  Requires Adult -->
            <div class="mb-6">
                <label for="requires_adult" class="block text-sm font-medium text-gray-700">Requires Adult
                </label>
                <select name="requires_adult" id="requires_adult"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="1" <?php if ($event['requires_adult'] == 1) echo "selected"; ?>>Yes</option>
                    <option value="0" <?php if ($event['requires_adult'] == 0) echo "selected"; ?>>No</option>
                </select>
                <?php if (isset($errors['requires_adult'])): ?>
                    <div class="mb-4 p-4 rounded-md bg-red-50 border border-red-400">
                        <p class="text-red-700 text-sm"><?php echo htmlspecialchars($errors['requires_adult']); ?></p>
                    </div>
                    <?php unset($errors['requires_adult']); ?>
                <?php endif; ?>
            </div>
            <!--  Seat limit -->
            <div class="mb-6">
                <label for="seat_limit" class="block text-sm font-medium text-gray-700">Seat limit</label>
                <input type="number" id="seat_limit"" name=" seat_limit"" placeholder="Enter seat limit"
                    value="<?php echo $event['seat_limit']; ?>"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                <?php if (isset($errors['seat_limit'])): ?>
                    <div class="mb-4 p-4 rounded-md bg-red-50 border border-red-400">
                        <p class="text-red-700 text-sm"><?php echo htmlspecialchars($errors['seat_limit']); ?></p>
                    </div>
                    <?php unset($errors['seat_limit']); ?>
                <?php endif; ?>
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