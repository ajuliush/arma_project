<header class="bg-blue-600 text-white">
    <div class="container mx-auto flex justify-between items-center py-4 px-6">
        <h1 class="text-xl font-bold">
            <a href="/" class="hover:text-blue-300 transition duration-200">Mellodian Community Park</a>
        </h1>
        <nav>
            <ul class="flex items-center space-x-6">
                <li><a href="/" class="hover:text-blue-300 transition duration-200">Events</a></li>
                <li><a href="/profile" class="hover:text-blue-300 transition duration-200">Profile</a></li>
                <li><a href="/my-tickets" class="hover:text-blue-300 transition duration-200">My Tickets</a></li>
                <li><a href="/my-events" class="hover:text-blue-300 transition duration-200">My Events</a></li>
                <li>
                    <form action="/logout" method="post">
                        <button type="submit"
                            class="hover:text-blue-300 transition duration-200 flex items-center gap-2">
                            <span>Logout</span>
                            <span class="text-blue-300">(<?php echo htmlspecialchars($_SESSION['name']); ?>)</span>
                        </button>
                    </form>
                </li>
            </ul>
        </nav>
    </div>
</header>