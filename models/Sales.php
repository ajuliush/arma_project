<?php
require_once 'config/connection.php';

class Sales
{
    public ?int $id = null;
    public string $event_id;
    public string $total_tickets_sold;
    public string $total_revenue;
    public string $updated_at;


    public function save(): bool
    {
        global $conn;
        $stmt = $conn->prepare("INSERT INTO sales (event_id, total_tickets_sold, total_revenue, updated_at) VALUES (?,?,?,?)");
        if ($stmt === false) {
            return false;
        }
        $stmt->bind_param("ssis", $this->event_id, $this->total_tickets_sold, $this->total_revenue, $this->updated_at);
        $result = $stmt->execute();

        if ($result) {
            $this->id = $conn->insert_id;
        }

        $stmt->close();

        return $result;
    }
}