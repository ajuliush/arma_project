<?php
if (isset($_SESSION['success_message'])) {
    echo "<div class='success-message relative text-center text-white bg-green-500 p-2 rounded-md transition-all duration-300 opacity-100' style='overflow: hidden;'>
            <span>" . $_SESSION['success_message'] . "</span>
            <button class='absolute top-1 right-2 text-white' onclick='closeMessage(this)'>&times;</button>
          </div>";
    unset($_SESSION['success_message']);
}

if (isset($_SESSION['error_message'])) {
    echo "<div class='error-message relative text-center text-white bg-red-500 p-2 rounded-md transition-all duration-300 opacity-100' style='overflow: hidden;'>
            <span>" . $_SESSION['error_message'] . "</span>
            <button class='absolute top-1 right-2 text-white' onclick='closeMessage(this)'>&times;</button>
          </div>";
    unset($_SESSION['error_message']);
}
?>
<script>
function closeMessage(button) {
    const messageDiv = button.parentElement;
    messageDiv.style.opacity = '0'; // Fade out
    messageDiv.style.height = '0'; // Collapse height
    setTimeout(() => {
        messageDiv.style.display = 'none'; // Hide after transition
    }, 300); // Match the duration of the transition
}
</script>