<?php
require_once 'models/User.php';
require_once 'models/Event.php';
require_once 'models/Booking.php';
require_once 'models/Sales.php';

#[AllowDynamicProperties]
class CustomerController
{
    public  function __construct()
    {
        $this->user = new User();
        $this->event = new Event();
        $this->booking = new Booking();
        $this->sales = new Sales();
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
    public function editTicket($id): void
    {
        $booking = $this->booking->getBookingById($id);
        // print_r($booking);
        $users = $this->user->getAllUsers();
        $events = $this->event->getAllEvents();
        include 'views/customer/edit-ticket.php';
    }
    public function updateTicket(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user_id         = $_POST['user_id'] ?? null;
            $event_id        = $_POST['event_id'] ?? null;
            $seat_type       = $_POST['seat_type'] ?? null;
            $quantity        = $_POST['quantity'] ?? null;
            $total_price     = isset($_POST['total_price']) ? floatval(str_replace('$', '', $_POST['total_price'])) : null;
            $booking_date    = $_POST['booking_date'] ?? null;
            $photo           = $_FILES['photo'] ?? null;

            //validations
            $errors = [];
            if (empty($user_id)) {
                $errors['user_id'] = 'User is required';
            }
            if (empty($event_id)) {
                $errors['event_id'] = 'Event is required';
            }
            if (empty($seat_type)) {
                $errors['seat_type'] = 'Seat type is required';
            }
            if (empty($quantity)) {
                $errors['quantity'] = 'Quantity is required';
            }
            if (empty($total_price)) {
                $errors['total_price'] = 'Total price is required';
            }
            if (empty($booking_date)) {
                $errors['booking_date'] = 'Booking date is required';
            }
            if (empty($photo)) {
                $errors['photo'] = 'Photo is required';
            }
            if (!empty($errors)) {
                echo 'Missing fields: ' . implode(', ', $errors);
                return;
            }
            //upload photo
            $uploadDir = 'storage/adult_photo/';
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
                $photoPath = $this->booking->adult_photo; // Assuming you fetch the existing user first
            }
            //Update Booking
            $this->booking->id = $id;
            $this->booking->user_id = $user_id;
            $this->booking->event_id = $event_id;
            $this->booking->seat_type = $seat_type;
            $this->booking->quantity = $quantity;
            $this->booking->total_price = $total_price;
            $this->booking->booking_date = $booking_date;
            $this->booking->adult_photo = $photoPath;
            $this->booking->created_at    = date('Y-m-d H:i:s');
            $this->booking->updated_at    = date('Y-m-d H:i:s');

            if ($this->booking->update()) {
                // Fetch existing sales record for this booking
                $existingSales = $this->sales->getSalesByTicketId($id);

                if ($existingSales) {
                    // Update existing sales record
                    $this->sales->id = $existingSales['id']; // Set the sales ID
                    $this->sales->ticket_id = $id;
                    $this->sales->event_id = $event_id;
                    $this->sales->total_tickets_sold = $quantity;
                    $this->sales->total_revenue = $total_price;
                    $this->sales->updated_at = date('Y-m-d H:i:s');

                    if (!$this->sales->update()) {
                        echo "Failed to update sales record!";
                        return;
                    }
                } else {
                    // Create new sales record if none exists
                    $this->sales->ticket_id = $id;
                    $this->sales->event_id = $event_id;
                    $this->sales->total_tickets_sold = $quantity;
                    $this->sales->total_revenue = $total_price;
                    $this->sales->updated_at = date('Y-m-d H:i:s');

                    if (!$this->sales->save()) {
                        echo "Failed to create sales record!";
                        return;
                    }
                }

                session_start();
                $_SESSION['success_message'] = "Booking updated successfully!";
                header('Location: /my-tickets');
                exit();
            } else {
                echo "Failed to update booking!";
            }
        } else {
            http_response_code(405);
            echo "Method Not Allowed";
        }
    }
    public function deleteTicket(int $id): void
    {
        // Fetch the event to get the profile photo path
        $booking = $this->booking->getTicketById($id);
        // var_dump($booking->adult_photo);
        // exit;
        if ($booking) {
            // Delete the booking from the database
            if ($this->booking->delete($id)) { // Assuming you have a delete method
                // Remove the user's profile photo from the server
                if (file_exists($booking->adult_photo)) {
                    unlink($booking->adult_photo);
                }
                session_start();
                $_SESSION['error_message'] = "Booking Deleted successfully!";
                header('Location: /my-tickets');
                exit();
            } else {
                echo "Failed to delete booking!";
            }
        } else {
            echo "Booking not found!";
        }
    }
    public function myEvents(): void
    {
        // echo $_SESSION['id'];
        // exit();
        $events = $this->booking->getMyEvents();
        // print_r($events);
        // exit();
        include 'views/customer/my-events.php';
    }
}