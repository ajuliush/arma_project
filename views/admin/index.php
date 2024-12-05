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
    include_once "views/components/message.php";
    ?>
    <!-- Main Content -->
    <div class="container mx-auto py-10 px-6">
        <!-- Overview Section -->
        <section class="mb-10">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Overview</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Statistic Cards -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-gray-700 font-bold text-lg">Total Events</h3>
                    <p class="text-4xl font-semibold text-blue-600 mt-4"><?php echo $totalEvents; ?></p>
                </div>
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-gray-700 font-bold text-lg">Total Bookings</h3>
                    <p class="text-4xl font-semibold text-blue-600 mt-4"><?php echo $totalBooking; ?></p>
                </div>
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-gray-700 font-bold text-lg">Total Revenue</h3>
                    <p class="text-4xl font-semibold text-blue-600 mt-4"><?php echo $totalRevenue; ?></p>
                </div>
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-gray-700 font-bold text-lg">Total Users</h3>
                    <p class="text-4xl font-semibold text-blue-600 mt-4"><?php echo $totalUsers; ?></p>
                </div>
            </div>
        </section>

        <!-- Management Section -->
        <section>
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Manage</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- User Management -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-gray-700 font-bold text-lg">User Management</h3>
                    <p class="text-gray-600 mt-2">
                        View and manage all registered users.
                    </p>
                    <a href="users"
                        class="mt-4 inline-block bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600">
                        Manage Users
                    </a>
                </div>

                <!-- Event Management -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-gray-700 font-bold text-lg">Event Management</h3>
                    <p class="text-gray-600 mt-2">Create, update, or delete events.</p>
                    <a href="/events"
                        class="mt-4 inline-block bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600">
                        Manage Events
                    </a>
                </div>

                <!-- Booking Management -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-gray-700 font-bold text-lg">Booking Management</h3>
                    <p class="text-gray-600 mt-2">View and manage ticket bookings.</p>
                    <a href="/bookings"
                        class="mt-4 inline-block bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600">
                        Manage Bookings
                    </a>
                </div>
            </div>
        </section>
    </div>
</body>

</html>