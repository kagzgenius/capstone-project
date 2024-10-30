

<?php

/**
 * get_messages.php
 * This script retrieves messages between two users from the database. It is called 
 * when loading the chat window to display previous conversations.
 * 
 * Functionality includes:
 * 
 * 1. Retrieving parameters:
 *    The script receives the `sender_id` and `receiver_id` via GET or POST request 
 *    parameters to identify the specific conversation to fetch.
 * 
 * 2. Database connection:
 *    It establishes a connection to the database using the `database.php` file to 
 *    access the messages table.
 * 
 * 3. Querying the database:
 *    The script constructs and executes an SQL SELECT statement to fetch messages 
 *    where the sender and receiver match the provided IDs. It ensures that messages 
 *    are returned in the correct order (e.g., by timestamp).
 * 
 * 4. Returning messages:
 *    The retrieved messages are typically formatted as JSON and returned to the client 
 *    for display in the chat window.
 * 
 * 5. (Optional: Error handling can be described here if applicable)
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
