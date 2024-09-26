<!--
 * Creator: Zach Fordahl
 * Date: 9/26/2024
 * Class: CSC450
 * Instructor: James Tucker
 * Group: 3
 * 
 * Project requirements:
 * - Desktop or Web application (with optional Android app)
 * - User authentication and user management
 * - Messaging (not external email)
 * - User Profile
 * - Dashboard
 * - Some form of transaction between users
 * - Use a database with at least five tables
 * 
 * Description: We are creating a professional application similar to Reddit where working professionals can connect with each other.
-->

<!-- Developer: [Your Name], 
     Changes made: [Description], 
     Date: [YYYY-MM-DD] -->


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

//Create experience table
$sql = "CREATE TABLE IF NOT EXISTS experience (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    job_name VARCHAR(100) NOT NULL,
    job_title VARCHAR(100) NOT NULL,
    years_worked VARCHAR(50) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);";
if ($conn->query($sql) === TRUE) {
    error_log("Table profile created successfully or already exists.");
} else {
    error_log("Error creating table: " . $conn->error);
}
// Create skills table
$sql = "CREATE TABLE IF NOT EXISTS skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    skill_name VARCHAR(100) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);";
if ($conn->query($sql) === TRUE) {
    error_log("Table profile created successfully or already exists.");
} else {
    error_log("Error creating table: " . $conn->error);
}

// Create interests table
$sql = "CREATE TABLE IF NOT EXISTS interests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    interest_name VARCHAR(100) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);";
if ($conn->query($sql) === TRUE) {
    error_log("Table profile created successfully or already exists.");
} else {
    error_log("Error creating table: " . $conn->error);
}

// Create education table
$sql = "CREATE TABLE IF NOT EXISTS education (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    school_name VARCHAR(100) NOT NULL,
    degree_obtained VARCHAR(100) NOT NULL,
    years_attended VARCHAR(50) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);";
if ($conn->query($sql) === TRUE) {
    error_log("Table profile created successfully or already exists.");
} else {
    error_log("Error creating table: " . $conn->error);
}

// End output buffering and discard the output
ob_end_clean();

