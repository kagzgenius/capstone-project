<?php


/**
 * DatabaseConnection.php
 * This script is responsible for creating the database and all the necessary tables 
 * required for the system to function correctly.
 * 
 * Functionality includes:
 * 
 * 1. Establishing a connection:
 *    The script first establishes a connection to the MySQL server using credentials 
 *    defined within the script or included from another configuration file.
 * 
 * 2. Database creation:
 *    It checks if the database already exists; if not, it creates the database.
 * 
 * 3. Table creation:
 *    After creating the database, the script creates all required tables (e.g., user, 
 *    profile, messages) with the appropriate schema (columns, data types, constraints).
 * 
 * 4. Error handling:
 *    The script includes error handling to manage any issues that arise during the 
 *    database or table creation process.
 * 
 * 5. (Optional: Confirmation messages can be described here if applicable)
 */

$servername = "localhost";
$username = "root";
$password = "mysql"; // Replace with your actual password
$dbname = "mywebsite"; //name the database

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

// Create database tables
$tables = [
    "users" => "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        password VARCHAR(255) NOT NULL,
        security_question VARCHAR(255) NOT NULL,
        security_answer VARCHAR(255) NOT NULL,
        is_admin TINYINT(1) DEFAULT 0,
        is_active TINYINT(1) DEFAULT 1  
    )",
    "profile" => "CREATE TABLE IF NOT EXISTS profile (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        first_name VARCHAR(100) NOT NULL,
        last_name VARCHAR(100) NOT NULL,
        bio TEXT NOT NULL,
        job_title VARCHAR(100),
        address VARCHAR(255),
        profile_pic VARCHAR(255),
        cover_pic VARCHAR(255),
        phone_number VARCHAR(15),
        FOREIGN KEY (user_id) REFERENCES users(id)
    )",
    "experience" => "CREATE TABLE IF NOT EXISTS experience (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        job_name VARCHAR(100) NOT NULL,
        job_title VARCHAR(100) NOT NULL,
        years_worked VARCHAR(50) NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id)
    )",
    "skills" => "CREATE TABLE IF NOT EXISTS skills (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        skill_name VARCHAR(100) NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id)
    )",
    "interests" => "CREATE TABLE IF NOT EXISTS interests (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        interest_name VARCHAR(100) NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id)
    )",
    "education" => "CREATE TABLE IF NOT EXISTS education (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        school_name VARCHAR(100) NOT NULL,
        degree_obtained VARCHAR(100) NOT NULL,
        years_attended VARCHAR(50) NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id)
    )",
    
     "messages" => "CREATE TABLE IF NOT EXISTS messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        sender VARCHAR(50),
       receiver VARCHAR(50),
        message TEXT,
        timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
     
    

];

// Create tables in the correct order
foreach ($tables as $table => $sql) {
    if ($conn->query($sql) === TRUE) {
        error_log("Table $table created successfully or already exists.");
    } else {
        error_log("Error creating table $table: " . $conn->error);
        echo "Error creating table $table: " . $conn->error . "<br>";
    }
}

$conn->close();
