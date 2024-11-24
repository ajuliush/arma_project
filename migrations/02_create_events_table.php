<?php

global $conn;
require_once "config/connection.php";

$sql = "CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    date DATE NOT NULL,
    time TIME NOT NULL,
    price_with_table DECIMAL(10, 2) NOT NULL,
    price_without_table DECIMAL(10, 2) NOT NULL,
    requires_adult BOOLEAN DEFAULT FALSE,
    seat_limit INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)";

// Execute the query
if ($conn->query($sql) !== TRUE) {
    echo "Error creating table: " . $conn->error . "\n";
}