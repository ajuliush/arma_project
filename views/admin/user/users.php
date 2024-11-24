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
        <a href="create-user"
            class="inline-block bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 mb-4">
            Create User
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
                                Name
                            </p>
                        </th>
                        <th class="p-4 border-b border-slate-600 bg-slate-700">
                            <p class="text-sm font-normal leading-none text-slate-300">
                                Email
                            </p>
                        </th>
                        <th class="p-4 border-b border-slate-600 bg-slate-700">
                            <p class="text-sm font-normal leading-none text-slate-300">
                                Phone
                            </p>
                        </th>
                        <th class="p-4 border-b border-slate-600 bg-slate-700">
                            <p class="text-sm font-normal leading-none text-slate-300">
                                Image
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
                    foreach ($users as $user):
                    ?>
                    <tr class="hover:bg-slate-700">
                        <td class="p-4 w-1 border-b border-slate-700 bg-slate-900">
                            <p class="text-sm text-slate-100 font-semibold">
                                <?php echo htmlspecialchars($sn); ?>
                            </p>
                        </td>
                        <td class="p-4 border-b border-slate-700 bg-slate-800">
                            <p class="text-sm text-slate-300">
                                <?php echo htmlspecialchars($user['name']); ?>
                            </p>
                        </td>
                        <td class="p-4 border-b border-slate-700 bg-slate-900">
                            <p class="text-sm text-slate-300">
                                <?php echo htmlspecialchars($user['email']); ?>
                            </p>
                        </td>
                        <td class="p-4 border-b border-slate-700 bg-slate-800">
                            <p class="text-sm text-slate-300">
                                <?php echo htmlspecialchars($user['phone']); ?>
                            </p>
                        </td>
                        <td class="p-4 border-b border-slate-700 bg-slate-800">
                            <p class="text-sm text-slate-300">
                                <img class="w-20 h-20 object-cover rounded-full"
                                    src="<?php echo htmlspecialchars($user['profile_photo']); ?>" alt="">
                            </p>
                        </td>
                        <td class="p-4 border-b border-slate-700 bg-slate-900">
                            <a href="edit-user?id=<?php echo $user['id']; ?>"
                                class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">Edit</a>
                            <?php
                                if ($user['role'] != 'admin') {
                                ?>
                            <a href="delete_user?id=<?php echo $user['id']; ?>"
                                onclick="return confirm('Are you sure?');"
                                class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600">Delete</a>
                            <?php
                                }
                                ?>
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
</body>

</html>