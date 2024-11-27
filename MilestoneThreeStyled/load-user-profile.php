<?php


/**
 * 
 * <!--
 * Creator: Zach Fordahl
 * Date: 10/16/2024
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

 * load-user-profile.php
 * This script retrieves and displays a user's profile information from the database. 
 * It is called when a user views another user's profile or their own.
 * 
 * Functionality includes:
 * 
 * 1. Retrieving parameters:
 *    The script receives the `user_id` via GET or POST request parameters to identify 
 *    the specific user's profile to fetch.
 * 
 * 2. Database connection:
 *    It establishes a connection to the database using the `database.php` file to 
 *    access the user and profile tables.
 * 
 * 3. Querying the database:
 *    The script constructs and executes an SQL SELECT statement to fetch the user's 
 *    profile data, including fields such as name, job title, interests, and 
 *    experience.
 * 
 * 4. Returning the profile data:
 *    The retrieved profile data is typically formatted as JSON and returned to the 
 *    client for display on the profile page.
 * 
 * 5. (Optional: Error handling can be described here if applicable)
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'database.php';

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    function getUserProfile($userId, $conn) {
        $query = $conn->prepare("SELECT `username`, `job_title`, `first_name`, `last_name` FROM `users` JOIN `profile` ON users.id = profile.user_id WHERE users.id = ?");
        $query->bind_param("i", $userId);
        $query->execute();
        $result = $query->get_result();

        if ($result) {
            return $result->fetch_assoc();
        } else {
            error_log("Query Error: " . $conn->error); // Log SQL errors
            return null;
        }
    }

    $userProfile = getUserProfile($userId, $conn);

    if ($userProfile) {
        header('Content-Type: application/json');
        echo json_encode($userProfile);
    } else {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'User profile not found.']);
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'User not authenticated']);
}

