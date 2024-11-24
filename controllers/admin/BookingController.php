<?php
require_once 'models/Booking.php';
require_once 'models/User.php';
require_once 'models/Event.php';
require_once 'models/Sales.php';

#[AllowDynamicProperties]

class BookingController
{
    public function __construct()
    {
        $this->booking = new Booking();
        $this->user = new User();
        $this->event = new Event();
        $this->sales = new Sales();
    }
    public function index(): void
    {
        $bookings = $this->booking->getAllBookings();
        // print_r($bookings);
        // exit();
        include 'views/admin/booking/bookings.php';
    }
    public function create(): void
    {
        $users = $this->user->getAllUsers();
        $events = $this->event->getAllEvents();
        // print_r($users);
        // print_r($events);    
        include 'views/admin/booking/create.php';
    }
    public function store(): void
    {
        // print_r($_POST);
        // exit();
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

            $uniqueFileName = uniqid('photo_', true) . '.' . pathinfo($photo['name'], PATHINFO_EXTENSION);
            $photoPath      = $uploadDir . $uniqueFileName;

            if (!move_uploaded_file($photo['tmp_name'], $photoPath)) {
                echo "Failed to upload photo!";
                return;
            }
            //Save Booking
            $this->booking->user_id = $user_id;
            $this->booking->event_id = $event_id;
            $this->booking->seat_type = $seat_type;
            $this->booking->quantity = $quantity;
            $this->booking->total_price = $total_price;
            $this->booking->booking_date = $booking_date;
            $this->booking->adult_photo = $photoPath;
            $this->booking->created_at    = date('Y-m-d H:i:s');
            $this->booking->updated_at    = date('Y-m-d H:i:s');

            //Save Sales
            $this->sales->event_id = $event_id;
            $this->sales->total_tickets_sold = $quantity;
            $this->sales->total_revenue = $total_price;
            $this->sales->updated_at    = date('Y-m-d H:i:s');

            if ($this->booking->save() && $this->sales->save()) {
                session_start();
                $_SESSION['success_message'] = "Booking Created successfully!";
                header('Location: /bookings');
                exit();
            } else {
                echo "Failed to  booking!";
            }
        } else {
            http_response_code(405);
            echo "Method Not Allowed";
        }
    }
}