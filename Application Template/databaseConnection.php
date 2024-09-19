<?php
$servername = "localhost";
$username = "root";
$password = "mysql"; // Replace with your actual password
$dbname = "mywebsite";

// Start output buffering
ob_start();

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error);
    die("Connection failed. Please try again later.");
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    error_log("Database created successfully or already exists.");
} else {
    error_log("Error creating database: " . $conn->error);
}

// Select the database
$conn->select_db($dbname);

// SQL to create users table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL
)";
if ($conn->query($sql) === TRUE) {
    error_log("Table users created successfully or already exists.");
} else {
    error_log("Error creating table: " . $conn->error);
}

// SQL to create profile table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS profile (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    bio TEXT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
)";
if ($conn->query($sql) === TRUE) {
    error_log("Table profile created successfully or already exists.");
} else {
    error_log("Error creating table: " . $conn->error);
}

// End output buffering and discard the output
ob_end_clean();

