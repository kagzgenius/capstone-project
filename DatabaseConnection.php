


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
 databaseConnection table is what creates the database and the tables that the application uses. The tables are being useds are the user,
 profile, message, skills, education, experience, and interests. 
  
-->
<!-- Developer: [Your Name],
     Changes made: [Description],
     Date: [YYYY-MM-DD] -->

<?php

/**
 * Database connection details
 */
$servername = "localhost";
$username = "root";
$password = "mysql"; // Replace with your actual password
$dbname = "mywebsite";//name the database

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


//create database tables

$tables = [
    "users"=>  "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL
)" ,
    "profile"=>"CREATE TABLE IF NOT EXISTS profile (
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
    "message"=> "CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender VARCHAR(50),
    receiver VARCHAR(50),
    message TEXT,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)"
];

//push the table array to the database to create the tables for education,skills, interest, experience
foreach ($tables as $table => $sql) {
    if ($conn->query($sql) === TRUE) {
        error_log("Table $table created successfully or already exists.");
    } else {
        error_log("Error creating table $table: " . $conn->error);
    }
}