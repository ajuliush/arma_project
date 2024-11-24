<?php

global $conn;
require_once "config/connection.php";

$sql = "CREATE TABLE tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    event_id INT NOT NULL,
    seat_type ENUM('with_table', 'without_table') NOT NULL,
    quantity INT NOT NULL,
    adult_photo VARCHAR(255),
    total_price DECIMAL(10, 2) NOT NULL,
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE)";

// Execute the query
if ($conn->query($sql) !== TRUE) {
    echo "Error creating table: " . $conn->error . "\n";
}