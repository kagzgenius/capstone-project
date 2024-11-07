<?php
/**
 * load-contact-list.php
 * This script retrieves a list of user contacts from the database to populate 
 * the contact list for the messaging system. It is typically called when the 
 * chat interface is loaded.
 * 
 * Functionality includes:
 * 
 * 1. Database connection:
 *    The script establishes a connection to the database using the `database.php` 
 *    file to access the users and profiles tables.
 * 
 * 2. Querying the database:
 *    It constructs and executes an SQL SELECT statement to fetch relevant user 
 *    data (e.g., user IDs, names, profile pictures) for users who can be contacted.
 * 
 * 3. Returning the contact list:
 *    The retrieved user contacts are typically formatted as JSON and returned 
 *    to the client for display in the chat interface.
 * 
 * 4. (Optional: Error handling can be described here if applicable)
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'database.php';

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

