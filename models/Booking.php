<?php
require_once 'config/connection.php';
class Booking
{
    public ?int $id = null;
    public string $user_id;
    public string $event_id;
    public string $seat_type;
    public string $quantity;
    public string $adult_photo;
    public string $total_price;
    public string $booking_date;
    public string $created_at;
    public string $updated_at;


    public function getAllBookings(): array
    {
        if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
            global $conn;
            $stmt = $conn->prepare("SELECT 
                    tickets.id AS ticket_id,
                    users.name AS user_name,
                    events.name AS event_name,
                    tickets.seat_type,
                    tickets.quantity,
                    tickets.adult_photo,
                    tickets.total_price,
                    tickets.booking_date
                FROM tickets
                JOIN users ON tickets.user_id = users.id
                JOIN events ON tickets.event_id = events.id;
                                          ");

            if ($stmt->execute()) {
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    return $result->fetch_all(MYSQLI_ASSOC);
                }

                return [];
            }

            $stmt->close();
            return [];
        } elseif (isset($_SESSION['role']) && $_SESSION['role'] == 'user') {
            global $conn;
            $stmt = $conn->prepare("SELECT 
                            tickets.id AS ticket_id,
                            users.name AS name,
                            events.mane AS name,
                            tickets.seat_type,
                            tickets.quantity,
                            tickets.total_price,
                            tickets.booking_date
                        FROM tickets
                        JOIN users ON tickets.user_id = users.id
                        JOIN events ON tickets.event_id = events.id
                        WHERE tickets.user_id = ?;");

            $stmt->bind_param("i", $_SESSION['id']);

            if ($stmt->execute()) {
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $bookings = [];
                    while ($user = $result->fetch_object()) {
                        $bookings[] = $user; // Collect each user object
                    }
                    return $bookings; // Return the array of user objects
                }

                return [];
            }

            $stmt->close();
            return [];
        }
    }
    public function save(): bool
    {
        global $conn;
        $stmt = $conn->prepare("INSERT INTO tickets (user_id, event_id, seat_type, quantity, adult_photo, total_price, booking_date, created_at, updated_at) VALUES (?,?,?,?,?,?,?,?,?)");
        if ($stmt === false) {
            return false;
        }
        $stmt->bind_param(
            "sssssssss",
            $this->user_id,
            $this->event_id,
            $this->seat_type,
            $this->quantity,
            $this->adult_photo,
            $this->total_price,
            $this->booking_date,
            $this->created_at,
            $this->updated_at
        );
        $result = $stmt->execute();
        if ($result) {
            $this->id = $conn->insert_id;
        }
        $stmt->close();
        return $result;
    }
}