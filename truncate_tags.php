<?php
// Simple database connection
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'feedback_platform';

// Create connection
$conn = new mysqli($hostname, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Disable foreign key checks
$conn->query('SET FOREIGN_KEY_CHECKS = 0');

// Truncate the tags table
$result = $conn->query('TRUNCATE TABLE tags');

// Re-enable foreign key checks
$conn->query('SET FOREIGN_KEY_CHECKS = 1');

if ($result) {
    echo "Tags table truncated successfully.\n";
} else {
    echo "Error truncating table: " . $conn->error . "\n";
}

$conn->close();