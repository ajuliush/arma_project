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
                            users.name AS user_name,
                            events.name AS event_name,
                            tickets.id,
                            tickets.seat_type,
                            tickets.quantity,
                            tickets.adult_photo,
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
    public function getBookingById(int $id): array
    {
        global $conn;
        $stmt = $conn->prepare("
        SELECT 
        id, 
        user_id, 
        event_id, 
        seat_type, 
        quantity, 
        adult_photo, 
        total_price, 
        total_price, 
        booking_date, 
        created_at, 
        updated_at 
        FROM tickets 
        WHERE id = ?");
        $stmt->bind_param("i", $id);
        //Execute the statement
        $stmt->execute();
        //Get the result
        $result = $stmt->get_result();
        //Fetch the event data as an associative array
        $booking = $result->fetch_assoc();
        //Close the statement and return the event data
        $stmt->close();
        return $booking ?: null;
    }
    public function getTotalQuantityByEventId(int $eventId): int
    {
        global $conn;
        $stmt = $conn->prepare("
        SELECT SUM(quantity) AS total_quantity
        FROM tickets
        WHERE event_id = ?
    ");
        $stmt->bind_param("i", $eventId);
        // Execute the statement
        $stmt->execute();
        // Get the result
        $result = $stmt->get_result();
        // Fetch the total quantity
        $data = $result->fetch_assoc();
        // Close the statement
        $stmt->close();
        // Return the total quantity (0 if null)
        return $data['total_quantity'] ?? 0;
    }

    public function getTotalsSeatByEventId(int $eventId): int
    {
        global $conn;
        $stmt = $conn->prepare("
        SELECT  id,
        seat_limit
        FROM events
        WHERE id = ?
    ");
        $stmt->bind_param("i", $eventId);
        // Execute the statement
        $stmt->execute();
        // Get the result
        $result = $stmt->get_result();
        // Fetch the total quantity
        $data = $result->fetch_assoc();
        // Close the statement
        $stmt->close();
        // Return the total quantity (0 if null)
        return $data['seat_limit'] ?? 0;
    }

    public function update(): bool
    {
        global $conn;
        // Fetch the current user data to get the existing profile photo
        $currentTicket = $this->getTicketById($this->id);
        $oldPhotoPath = $currentTicket->adult_photo;

        // Prepare the SQL statement for updating user data
        $stmt = $conn->prepare("UPDATE tickets SET user_id =?, event_id =?, seat_type =?, quantity =?, adult_photo =?, total_price =?, booking_date =?, created_at =?, updated_at =? WHERE id = ?");
        if ($stmt === false) {
            return false;
        }
        if ($this->adult_photo != $oldPhotoPath) {
            if (file_exists($oldPhotoPath)) {
                unlink($oldPhotoPath); // Delete the old photo from the server
            }
        }
        //bind parameters
        $stmt->bind_param(
            "issssissss",
            $this->user_id,
            $this->event_id,
            $this->seat_type,
            $this->quantity,
            $this->adult_photo,
            $this->total_price,
            $this->booking_date,
            $this->created_at,
            $this->updated_at,
            $this->id //Bind the id of the user to update
        );
        //Execute the statement
        $result = $stmt->execute();
        //Close the statement
        $stmt->close();
        return $result;
    }
    public function getTicketById(int $id)
    {
        global $conn;

        $stmt = $conn->prepare("SELECT * FROM tickets WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_object(); // Assuming you want to return an object

        $stmt->close();

        return $user;
    }
    public function delete(int $id): bool
    {
        global $conn;

        // Prepare the SQL statement for deleting the user
        $stmt = $conn->prepare("DELETE FROM tickets WHERE id = ?");

        if ($stmt === false) {
            return false;
        }

        // Bind the user ID for the WHERE clause
        $stmt->bind_param("i", $id);

        // Execute the statement
        $result = $stmt->execute();

        // Close the statement
        $stmt->close();

        return $result;
    }
    public function countAllBookings()
    {
        global $conn;
        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM tickets");

        if ($stmt->execute()) {
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return (int) $row['total'];
            }

            return 0;
        }

        $stmt->close();
        return 0;
    }
    public function getMyEvents()
    {
        global $conn;
        $stmt = $conn->prepare("SELECT events.* 
                FROM tickets
                INNER JOIN events ON tickets.event_id = events.id
                WHERE tickets.user_id = ?
                ");
        $stmt->bind_param("i", $_SESSION['id']);
        if ($stmt->execute()) {
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $events = [];
                while ($row = $result->fetch_assoc()) {
                    $events[] = $row;
                }
                return $events;
            }

            return [];
        }

        $stmt->close();
        return [];
    }
}