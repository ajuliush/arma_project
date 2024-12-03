<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mellodian || Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- <link rel="stylesheet" href="../../storage/assets/css/tailwind.css"> -->
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md">
        <?php
        include_once "views/components/message.php";
        ?>
        <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">Login</h1>
        <?php if (isset($_SESSION['error'])): ?>
        <div class="mb-4 p-4 rounded-md bg-red-50 border border-red-400">
            <p class="text-red-700 text-sm"><?php echo htmlspecialchars($_SESSION['error']); ?></p>
        </div>
        <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        <form action="/login" method="POST">
            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    required>
            </div>

            <!-- Password -->
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    required>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit"
                    class="w-full bg-blue-500 text-white font-bold py-2 px-4 rounded-md hover:bg-blue-600 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none">
                    Login
                </button>
            </div>
        </form>

        <!-- Registration Link -->
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Don't have an account?
                <a href="/register" class="font-medium text-blue-500 hover:text-blue-600 hover:underline">
                    Create account here
                </a>
            </p>
        </div>
    </div>
</body>

</html>