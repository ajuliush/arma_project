<?php
require_once 'config/connection.php';

class Sales
{
    public ?int $id = null;
    public string $event_id;
    public string $ticket_id;
    public string $total_tickets_sold;
    public string $total_revenue;
    public string $updated_at;


    public function save(): bool
    {
        global $conn;
        $stmt = $conn->prepare("INSERT INTO sales (ticket_id,event_id, total_tickets_sold, total_revenue, updated_at) VALUES (?,?,?,?,?)");
        if ($stmt === false) {
            return false;
        }
        $stmt->bind_param("sssss", $this->ticket_id, $this->event_id, $this->total_tickets_sold, $this->total_revenue, $this->updated_at);
        $result = $stmt->execute();

        if ($result) {
            $this->id = $conn->insert_id;
        }

        $stmt->close();

        return $result;
    }

    public function update(): bool
    {
        global $conn;
        $stmt = $conn->prepare("UPDATE sales SET ticket_id =?, event_id =?, total_tickets_sold =?, total_revenue =?, updated_at =? WHERE id =?");
        if ($stmt === false) {
            return false;
        }
        $stmt->bind_param("sssssi", $this->ticket_id, $this->event_id, $this->total_tickets_sold, $this->total_revenue, $this->updated_at, $this->id);
        $result = $stmt->execute();

        $stmt->close();

        return $result;
    }

    public function getSalesByTicketId($ticketId)
    {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM sales WHERE ticket_id = ?");
        if ($stmt === false) {
            return false;
        }

        $stmt->bind_param("i", $ticketId);
        $stmt->execute();

        $result = $stmt->get_result();
        $sales = $result->fetch_assoc();

        $stmt->close();

        return $sales;
    }

    public function getBookingById(int $id): ?array
    {
        global $conn;

        $stmt = $conn->prepare("SELECT * FROM sales WHERE id = ?");
        $stmt->bind_param("i", $id);

        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Fetch the user data as an associative array
        $user = $result->fetch_assoc();

        $stmt->close();

        return $user ?: null; // Return the user data or null if not found
    }
    public function delete(): bool
    {
        global $conn;

        // Prepare the SQL statement for deleting the user
        $stmt = $conn->prepare("DELETE FROM sales WHERE id = ?");

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
    public function countAllRevenue(): float
    {
        global $conn;
        $stmt = $conn->prepare("SELECT SUM(total_revenue) as total FROM sales");

        if ($stmt->execute()) {
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return (float) $row['total'] ?? 0; // Return 0 if NULL
            }

            return 0;
        }

        $stmt->close();
        return 0;
    }
}
