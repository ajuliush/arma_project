<?php

global $conn;
require_once "config/connection.php";

$sql = "CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone VARCHAR(15) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL DEFAULT 'user',
    profile_photo VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)"; // <- Closing parenthesis added here

// Execute the query
if ($conn->query($sql) !== TRUE) {
    echo "Error creating table: " . $conn->error . "\n";
} else {
    echo "Table 'users' created successfully.\n";
}
