

<?php

/**
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
 send_message is what the messaging.php uses to allow users to send the message to the message table. 
  
-->
<!-- Developer: [Your Name],
     Changes made: [Description],
     Date: [YYYY-MM-DD] -->
     
 * database.php
 * This script establishes a connection to the database and is included in various 
 * PHP files that require database access.
 * 
 * Functionality includes:
 * 
 * 1. Database connection parameters:
 *    The script defines the necessary parameters for connecting to the database, 
 *    including the database name, username, and password.
 * 
 * 2. Establishing a connection:
 *    It uses these parameters to create a connection to the database. If the connection 
 *    fails, it handles the error appropriately (e.g., by displaying an error message).
 * 
 * 3. Return connection resource:
 *    The script typically returns the connection resource, allowing other PHP files 
 *    to execute SQL queries against the database.
 */

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

// Select the database
if (!mysqli_select_db($conn, $dbname)) {
    error_log("Database selection failed: " . mysqli_error($conn));
    die("Database selection failed. Please try again later.");
}

