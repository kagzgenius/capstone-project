

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
 send_message is what the messaging.php uses to allow users to send the message to the message table. 
  
-->
<!-- Developer: [Your Name],
     Changes made: [Description],
     Date: [YYYY-MM-DD] -->

<?php

session_start();
include 'database.php';

// Enable error logging and set the log file path
//logging is not needed but used because i was having issues with ajax and getting the data to populate. It will log the connection with the send_message and can be used with debugging
ini_set('log_errors', 1);
ini_set('error_log', 'C:\\Program Files\\Ampps\\www\\AJAX_CRUD\\php-error.log'); // Replace with the path to your error log file

// Fetch sender, receiver, and message from POST parameters
$sender = $_SESSION['user_id'];
$receiver = $_POST['receiver'];
$message = $_POST['message'];

// Insert the new message into the database
$sql = "INSERT INTO messages (sender, receiver, message) VALUES ('$sender', '$receiver', '$message')";

if ($conn->query($sql) === TRUE) {
    error_log("Message sent successfully from $sender to $receiver: $message");
    echo "Message sent successfully";
} else {
    error_log("Error sending message: " . $conn->error);
    echo "Error sending message: " . $conn->error;
}

$conn->close();

