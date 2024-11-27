

<?php

/**
 * Creator: Zach Fordahl
 * Date: 10/26/2024
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

 * get_messages.php
 * This script retrieves messages between two users from the database. It is called 
 * when loading the chat window to display previous conversations.
 * 
 * Functionality includes:
 * 
 * 
 * get_message.php
 * 
 * This script is used by the `loadContactDetails` function in `chat.js` and the `loadHomeMessages` function in `home.js`
 * to retrieve all the messages between the sender and receiver from the message table.
 * 
 * Functionality includes:
 * 
 * 1. Retrieving Parameters:
 *    The script receives the `sender_id` and `receiver_id` via GET or POST request parameters.
 * 
 * 2. Performing SELECT Statement:
 *    It performs a SELECT statement to retrieve the messages between the sender and receiver.
 *    The script uses the profile table and joins it with the message table to get the necessary data.
 * 
 * 3. Data Retrieval:
 *    The script fetches the messages along with the sender's and receiver's profile information, such as names and timestamps.
 * 
 * 4. Returning Data:
 *    The retrieved data is returned to the calling JavaScript functions (`loadContactDetails` in `chat.js` and `loadHomeMessages` in `home.js`) 
 *    to populate a scrollable message window showing the conversation between the sender and receiver, including names and timestamps.
 */

 


session_start();

//get connection from the database. 
include 'database.php';

// Enable error logging and set the log file path

ini_set('log_errors', 1);
ini_set('error_log', 'C:\\Program Files\\Ampps\\www\\Project template test\php-error.log'); // Replace with the path to your error log file

// Fetch sender and receiver from GET parameters

$sender = $_SESSION['user_id']; // Use session user ID for the sender
$receiver = $_GET['receiver']; // Use GET parameter for the receiver

// Log the sender and receiver IDs
error_log("Fetching messages for sender: $sender and receiver: $receiver");

// Log the SQL query for debugging
//returns all of the data for the sender and reciever on the senderid and receiverid based on session variable for the sender and a getvariable on the reciever that is set in the message.php table
$sql = "SELECT 
    m.sender,
    m.receiver,
    m.message,
    senderProfile.first_name AS sender_first_name,
    senderProfile.last_name AS sender_last_name,
    receiverProfile.first_name AS receiver_first_name,
    receiverProfile.last_name AS receiver_last_name
FROM 
    messages m
JOIN 
    profile senderProfile ON m.sender = senderProfile.user_id
JOIN 
    profile receiverProfile ON m.receiver = receiverProfile.user_id
WHERE 
    (m.sender = $sender AND m.receiver = $receiver) OR (m.sender = $receiver AND m.receiver = $sender)
ORDER BY 
    m.timestamp ASC;
";
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
