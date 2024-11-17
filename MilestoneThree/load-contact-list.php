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

 * /**
 * load-contact-list.php
 * 
 * This script is called by the `loadUserContactList` function in `chat.js`.
 * It manages the retrieval of user contact information and returns it to the JavaScript function for display.
 * 
 * Functionality includes:
 * 
 * 1. getUsersProfile:
 *    This function, which is part of the load-contact-list.php script, performs a SELECT statement on the user and profile tables to retrieve the necessary user data.
 *    It fetches the user ID, first name, and last name from the database.
 * 
 * 2. Sending Data to loadUserContactList:
 *    The retrieved data is sent back to the `loadUserContactList` function in `chat.js`.
 *    This function then produces a clickable box with the user ID, first name, and last name.
 * 
 * 3. Handling User Selection:
 *    When a user is selected by clicking on the box, the user ID is grabbed and the data is sent to the `loadContactDetails` function.
 *    `loadContactDetails` then loads the messages for the selected user, enabling the user to view and interact with the contact's messages.
 */


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'database.php';


/**getUserProfile does select statement and returns results to loadContactList that shows all user logged into so you can send message to the user by clicking on in the chat.js
 *
 */
function getUserProfiles($conn) {
   
    $query = $conn->prepare("SELECT users.id AS user_id, users.username, profile.first_name, profile.last_name, profile.profile_pic FROM `users` JOIN `profile` ON users.id = profile.user_id");
    if (!$query->execute()) {
        error_log("Query Error: " . $query->error);
        return null; // Return null on error
    }
    
    $result = $query->get_result();
    $profiles = [];
    
    while ($row = $result->fetch_assoc()) {
        $profiles[] = $row;
    }
    return $profiles;
}

$userProfiles = getUserProfiles($conn);

header('Content-Type: application/json');
if ($userProfiles) {
    echo json_encode($userProfiles);
} else {
    echo json_encode(['error' => 'No profiles found.']);
}

