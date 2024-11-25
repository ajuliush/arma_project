<?php
require_once 'models/User.php';
require_once 'models/Event.php';
require_once 'models/Booking.php';

#[AllowDynamicProperties]
class CustomerController
{
    public  function __construct()
    {
        $this->user = new User();
        $this->event = new Event();
        $this->booking = new Booking();
    }
    public function index(): void
    {
        $user = $this->user->getAllUsers();
        $events = $this->event->getAllEvents();
        include 'views/customer/index.php';
    }
    public function profile(): void
    {
        $user = $this->user->getAllUsers();
        include 'views/customer/profile.php';
    }

    public function profileUpdate(int $id): void // Added parameter for user ID
    {
        // var_dump($_SESSION['id']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name            = trim($_POST['name'] ?? '');
            $email           = trim($_POST['email'] ?? '');
            $phone           = trim($_POST['phone'] ?? '');
            $password        = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $photo           = $_FILES['photo'] ?? null;

            // Validation
            if (empty($name) || empty($email) || empty($phone)) {
                echo "All fields are required!";
                return;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "Invalid email address!";
                return;
            }

            if (!preg_match('/^[0-9]{10,15}$/', $phone)) {
                echo "Invalid phone number!";
                return;
            }

            // Password validation (optional)
            if (!empty($password) && $password !== $confirmPassword) {
                echo "Passwords do not match!";
                return;
            }

            // File Upload (if a new photo is uploaded)
            $uploadDir = 'storage/photos/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if ($photo && $photo['error'] === UPLOAD_ERR_OK) {
                $uniqueFileName = uniqid('photo_', true) . '.' . pathinfo($photo['name'], PATHINFO_EXTENSION);
                $photoPath      = $uploadDir . $uniqueFileName;

                if (!move_uploaded_file($photo['tmp_name'], $photoPath)) {
                    echo "Failed to upload photo!";
                    return;
                }
            } else {
                // If no new photo, keep the existing one
                $photoPath = $this->user->profile_photo; // Assuming you fetch the existing user first
            }

            // Update user
            $this->user->id             = $id; // Assuming you have an ID property
            $this->user->name           = $name;
            $this->user->email          = $email;
            $this->user->phone          = $phone;
            if (!empty($password)) {
                $this->user->password     = password_hash($password, PASSWORD_DEFAULT);
            }
            $this->user->profile_photo   = $photoPath;
            $this->user->updated_at     = date('Y-m-d H:i:s');

            if ($this->user->update()) { // Assuming you have an update method
                session_start();
                $_SESSION['success_message'] = "Profile updated successfully!";
                header('Location: /profile');
                exit();
            } else {
                echo "Failed to update user!";
            }
        } else {
            http_response_code(405);
            echo "Method Not Allowed";
        }
    }
    public function myTickets(): void
    {
        $bookings  = $this->booking->getAllBookings();
        // print_r($bookings);
        // exit();
        include 'views/customer/my-tickets.php';
    }
}