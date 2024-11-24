<header class="bg-blue-600 text-white p-4 shadow">
    <div class="container mx-auto flex justify-between items-center">
        <h1 class="text-xl font-bold">
            <a href="/">
                Admin Dashboard
            </a>
        </h1>
        <nav>
            <ul class="flex space-x-4">
                <li><a href="/users" class="hover:underline">Users</a></li>
                <!-- <li><a href="/logout" class="hover:underline">Logout</a></li> -->
                <form action="/logout" method="POST">
                    <button type="submit" class="hover:underline text-white bg-transparent border-0">
                        Logout
                    </button>
                </form>
            </ul>
        </nav>
    </div>
</header>