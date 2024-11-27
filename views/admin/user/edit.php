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
        <!-- Back button -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <a href="/users"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 text-white font-semibold rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                            clip-rule="evenodd" />
                    </svg>
                    Back to Users List
                </a>
            </div>

            <h1 class="text-2xl font-bold text-gray-800">Edit user</h1>

            <!-- Added empty div for balanced spacing -->
            <div class="w-[140px]"></div>
        </div>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="mb-4 p-4 rounded-md bg-red-50 border border-red-400">
                <p class="text-red-700 text-sm"><?php echo htmlspecialchars($_SESSION['error']); ?></p>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        <form action="/user-update?id=<?php echo $user['id'] ?>" method="POST" enctype="multipart/form-data">
            <!-- Name -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" id="name" name="name" placeholder="Enter your name"
                    value="<?php echo $user['name'] ?>"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email"
                    value="<?php echo $user['email'] ?>"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                <?php if (isset($_SESSION['error_email'])): ?>
                    <div class="mb-4 p-4 rounded-md bg-red-50 border border-red-400">
                        <p class="text-red-700 text-sm"><?php echo htmlspecialchars($_SESSION['error_email']); ?></p>
                    </div>
                    <?php unset($_SESSION['error_email']); ?>
                <?php endif; ?>
            </div>

            <!-- Phone -->
            <div class="mb-6">
                <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                <input type="tel" id="phone" name="phone" placeholder="Enter your phone number"
                    value="<?php echo $user['phone'] ?>"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                <?php if (isset($_SESSION['error_phone'])): ?>
                    <div class="mb-4 p-4 rounded-md bg-red-50 border border-red-400">
                        <p class="text-red-700 text-sm"><?php echo htmlspecialchars($_SESSION['error_phone']); ?></p>
                    </div>
                    <?php unset($_SESSION['error_phone']); ?>
                <?php endif; ?>
            </div>

            <!-- Photo -->
            <div class="mb-4">
                <label for="photo" class="block text-sm font-medium text-gray-700">Photo</label>
                <img src="<?php echo $user['profile_photo'] ?>" alt="" class="mb-2 rounded-md w-32 h-32 object-cover ">
                <input type="file" id="photo" name="photo" accept="image/*"
                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border file:border-gray-300 file:text-gray-700 file:bg-gray-50 file:hover:bg-gray-100">
                <?php if (isset($_SESSION['error_image'])): ?>
                    <div class="mb-4 p-4 rounded-md bg-red-50 border border-red-400">
                        <p class="text-red-700 text-sm"><?php echo htmlspecialchars($_SESSION['error_image']); ?></p>
                    </div>
                    <?php unset($_SESSION['error_image']); ?>
                <?php endif; ?>
            </div>

            <!-- Password -->
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                <?php if (isset($_SESSION['error_password'])): ?>
                    <div class="mb-4 p-4 rounded-md bg-red-50 border border-red-400">
                        <p class="text-red-700 text-sm"><?php echo htmlspecialchars($_SESSION['error_password']); ?></p>
                    </div>
                    <?php unset($_SESSION['error_password']); ?>
                <?php endif; ?>
                <?php if (isset($_SESSION['error_password_confirm'])): ?>
                    <div class="mb-4 p-4 rounded-md bg-red-50 border border-red-400">
                        <p class="text-red-700 text-sm"><?php echo htmlspecialchars($_SESSION['error_password_confirm']); ?>
                        </p>
                    </div>
                    <?php unset($_SESSION['error_password_confirm']); ?>
                <?php endif; ?>
            </div>

            <!-- Confirm Password -->
            <div class="mb-6">
                <label for="confirm_password" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password"
                    placeholder="Enter your confirm password"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit"
                    class="w-full bg-blue-500 text-white font-bold py-2 px-4 rounded-md hover:bg-blue-600 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none">
                    Update
                </button>
            </div>
        </form>
    </div>
</body>

</html>