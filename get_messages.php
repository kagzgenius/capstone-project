

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
 Get messages is used in the message table to show all the messages associated between the two users the sendid and receiverid. This allow two users
 to communicate where they left off and show the messages they have had together. 
-->

<!-- Developer: [Your Name], 
     Changes made: [Description], 
     Date: [YYYY-MM-DD] -->


<?php
session_start();

//get connection from the database. 
include 'database.php';

// Enable error logging and set the log file path
//Logging is not needed but added because it was not working originally and added it for debugging. 
ini_set('log_errors', 1);
ini_set('error_log', 'C:\\Program Files\\Ampps\\www\\AJAX_CRUD\\php-error.log'); // Replace with the path to your error log file

// Fetch sender and receiver from GET parameters
$sender = $_SESSION['user_id']; // Use session user ID for the sender
$receiver = $_GET['receiver']; // Use GET parameter for the receiver

// Log the sender and receiver IDs
error_log("Fetching messages for sender: $sender and receiver: $receiver");

// Log the SQL query for debugging
//returns all of the data for the sender and reciever on the senderid and receiverid based on session variable for the sender and a getvariable on the reciever that is set in the message.php table
$sql = "SELECT sender, receiver, message, timestamp FROM messages WHERE (sender = '$sender' AND receiver = '$receiver') OR (sender = '$receiver' AND receiver = '$sender') ORDER BY timestamp ASC";
error_log("SQL Query: $sql");

$result = $conn->query($sql);

$messages = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
} else {
    error_log("No messages found for sender: $sender and receiver: $receiver");
}

// Log the fetched messages
error_log("Fetched messages: " . json_encode($messages));

echo json_encode($messages);

$conn->close();
