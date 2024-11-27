<?php
require_once 'config/connection.php';
class Event
{
    public ?int $id = null;
    public string $name;
    public string $description;
    public string $date;
    public string $time;
    public string $price_with_table;
    public string $price_without_table;
    public string $requires_adult;
    public string $seat_limit;
    public string $created_at;
    public string $updated_at;

    public function getAllEvents(): array
    {
        global $conn;
        $stmt = $conn->prepare("SELECT id, name, description, date, time, price_with_table, price_without_table, requires_adult, seat_limit, created_at, updated_at FROM events");

        if ($stmt->execute()) {
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                return $result->fetch_all(MYSQLI_ASSOC);
            }

            return [];
        }

        $stmt->close();
        return [];
    }
    public function save(): bool
    {
        global $conn;
        $stmt = $conn->prepare("INSERT INTO events (name, description, date, time, price_with_table, price_without_table, requires_adult, seat_limit, created_at, updated_at) VALUES (?,?,?,?,?,?,?,?,?,?)");
        if ($stmt === false) {
            return false;
        }
        $stmt->bind_param("ssssssssss", $this->name, $this->description, $this->date, $this->time, $this->price_with_table, $this->price_without_table, $this->requires_adult, $this->seat_limit, $this->created_at, $this->updated_at);
        $result = $stmt->execute();
        if ($result) {
            $this->id = $conn->insert_id;
        }
        $stmt->close();
        return $result;
    }
    public function getEventById($id): ?array
    {
        global $conn;
        $stmt = $conn->prepare("SELECT id, name, description, date, time, price_with_table, price_without_table, requires_adult, seat_limit, created_at, updated_at FROM events WHERE id = ?");
        $stmt->bind_param("i", $id);
        //Execute the statement
        $stmt->execute();
        //Get the result
        $result = $stmt->get_result();
        //Fetch the event data as an associative array
        $event = $result->fetch_assoc();
        //Close the statement and return the event data
        $stmt->close();
        return $event ?: null;
    }
    public function update(): bool
    {
        global $conn;
        $stmt = $conn->prepare("UPDATE events SET name =?, description =?, date =?, time =?, price_with_table =?, price_without_table =?, requires_adult =?, seat_limit =?, updated_at =? WHERE id = ?");
        if ($stmt === false) {
            return false;
        }
        $stmt->bind_param("sssssssssi", $this->name, $this->description, $this->date, $this->time, $this->price_with_table, $this->price_without_table, $this->requires_adult, $this->seat_limit, $this->updated_at, $this->id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    public function delete(int $id): bool
    {
        global $conn;
        $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
        if ($stmt === false) {
            return false;
        }
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function countAllEvents(): int
    {
        global $conn;
        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM events");

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
    public function getSeatTypesById($id): ?array
    {
        global $conn;
        $stmt = $conn->prepare("SELECT id, price_with_table, price_without_table, seat_limit, created_at, updated_at FROM events WHERE id = ?");
        $stmt->bind_param("i", $id);
        //Execute the statement
        $stmt->execute();
        //Get the result
        $result = $stmt->get_result();
        //Fetch the event data as an associative array
        $event_seat_types = $result->fetch_assoc();
        //Close the statement and return the event data
        $stmt->close();
        return $event_seat_types ?: null;
    }
}