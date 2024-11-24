<?php
// session_start();
if (isset($_SESSION['success_message'])) {
    echo "<div class='success-message text-center text-white bg-green-500 p-2 rounded-md'>" . $_SESSION['success_message'] . "</div>";
    unset($_SESSION['success_message']);
}

if (isset($_SESSION['error_message'])) {
    echo "<div class='success-message text-center text-white bg-red-500 p-2 rounded-md'>" . $_SESSION['error_message'] . "</div>";
    unset($_SESSION['error_message']);
}