

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
 get_contacts grabs the all id, first,last names and profile pictures from the profile page and is used on the messaging system to create a contact list for each user

  
-->
<!-- Developer: [Your Name],
     Changes made: [Description],
     Date: [YYYY-MM-DD] -->

<?php
//Put this in place to give errors for debugging. The error_reporting is not needed but used to debugging on why ajax was not populating correctly when I first
//tried to get the contact data. 
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//get connection from the database
include 'database.php';
//selects all the users from the database in the profile table to be used for the contact list. 
$sql = "SELECT id, user_id, first_name, last_name,profile_pic FROM profile";
$result = $conn->query($sql);

$users = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($users);

$conn->close();