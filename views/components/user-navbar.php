<header class="bg-blue-600 text-white">
    <div class="container mx-auto flex justify-between items-center py-4 px-6">
        <h1 class="text-xl font-bold">Mellodian Community Park</h1>
        <nav>
            <ul class="flex space-x-4">
                <li><a href="#" class="hover:text-blue-300">Events</a></li>
                <li><a href="/profile" class="hover:text-blue-300">Profile</a></li>
                <!-- <li><a href="/logout" class="hover:text-blue-300">Logout</a> -->
                <form action="/logout" method="post">
                    <button type="submit" class="hover:underline text-white bg-transparent border-0">
                        Logout
                    </button>
                </form>
                </li>
            </ul>
        </nav>
    </div>
</header>