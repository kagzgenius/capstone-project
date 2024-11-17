<?php
/**
 * 
 *  Creator: Zach Fordahl
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

 * updateProfile.php
 * This script processes updates to a user's profile information. It is called by 
 * the `editUserProfile` function in `profile.js`.
 * 
 * Functionality includes:
 * 
 * 1. Collecting form data:
 *    The script retrieves all data submitted from the form that the `editUserProfile` 
 *    function populates, ensuring that all relevant fields are included in the update.
 * 
 * 2. Updating the database:
 *    It updates both the user table and the profile table in the database, reflecting any 
 *    changes made to the user data. The script uses the `$_POST` variable to access the 
 *    submitted data and constructs the appropriate SQL statement for updating the records.
 * 
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include 'database.php';

$userId = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$firstName = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
$lastName = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
$bio = isset($_POST['bio']) ? trim($_POST['bio']) : '';
$jobTitle = isset($_POST['job_title']) ? trim($_POST['job_title']) : '';
$address = isset($_POST['address']) ? trim($_POST['address']) : '';
$phoneNumber = isset($_POST['phone_number']) ? trim($_POST['phone_number']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$admin = isset($_POST['admin']) ? trim($_POST['admin']) : '';
$active = isset($_POST['active']) ? trim($_POST['active']) : '';

error_log("Received input data: userId=$userId, username=$username, email=$email, firstName=$firstName, lastName=$lastName, bio=$bio, jobTitle=$jobTitle, address=$address, phoneNumber=$phoneNumber, password=$hashedPassword, admin=$admin, active=$active");

if ($userId === 0 || empty($username) || empty($email) || empty($firstName) || empty($lastName)) {
    echo json_encode(['error' => 'Invalid input data']);
    error_log("Invalid input data: userId=$userId, username=$username, email=$email, firstName=$firstName, lastName=$lastName");
    exit();
}

error_log("Updating profile for user ID: $userId");

// Update the profile and user tables
$queryUser = "UPDATE users SET username='$username', email='$email',`password` ='$hashedPassword',is_admin='$admin', is_active='$active' WHERE id='$userId'";
$queryProfile = "UPDATE profile SET first_name='$firstName', last_name='$lastName', bio='$bio', job_title='$jobTitle', address='$address', phone_number='$phoneNumber' WHERE user_id='$userId'";

error_log("Executing query for users table: $queryUser");
error_log("Executing query for profile table: $queryProfile");

$resultUser = mysqli_query($conn, $queryUser);
if (!$resultUser) {
    error_log("Query Error (users table): " . mysqli_error($conn));
    echo json_encode(['error' => 'Failed to update user']);
    exit();
}

$resultProfile = mysqli_query($conn, $queryProfile);
if (!$resultProfile) {
    error_log("Query Error (profile table): " . mysqli_error($conn));
    echo json_encode(['error' => 'Failed to update profile']);
    exit();
}

echo json_encode(['success' => true]);
error_log("Profile updated successfully for user ID: $userId");

