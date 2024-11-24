<?php

global $conn;
require_once "config/connection.php";

$sql = "CREATE TABLE sales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    total_tickets_sold INT DEFAULT 0,
    total_revenue DECIMAL(10, 2) DEFAULT 0.00,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE)";

// Execute the query
if ($conn->query($sql) !== TRUE) {
    echo "Error creating table: " . $conn->error . "\n";
}