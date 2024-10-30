<?php

/**
 * update_interest.php
 * This script processes updates to a user's interests. It is called when the 
 * `updateInterest` function in `interests.js` is executed.
 * 
 * Functionality includes:
 * 
 * 1. Retrieving parameters:
 *    The script receives the `interest_id`, `user_id`, and the updated interest 
 *    data via GET or POST request parameters to identify the specific interest 
 *    record to be updated.
 * 
 * 2. Database connection:
 *    It establishes a connection to the database using the `database.php` file 
 *    to perform the update operation.
 * 
 * 3. Updating the record:
 *    The script constructs an SQL UPDATE statement to modify the record in the 
 *    interests table based on the provided `interest_id`, applying the new interest 
 *    data while ensuring it corresponds to the specified `user_id`.
 * 
 * 4. (Optional: Error handling and confirmation messages can be described here if applicable)
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include 'database.php';

// Ensure the database connection is established
if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]));
}

// Function to fetch interests
function fetchInterests($userId, $conn) {
    $query = "SELECT id, interest_name, user_id FROM interests WHERE user_id = '$userId'";
    error_log("Executing query: $query"); // Log the query
    $result = mysqli_query($conn, $query);
    if (!$result) {
        return ['error' => 'Query Failed: ' . mysqli_error($conn)];
    }
    $interests = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $interests[] = $row;
    }
    return ['interests' => $interests];
}

// Function to update interest
function updateInterest($interestId, $userId, $interestName, $conn) {
    $query = "UPDATE interests SET interest_name='$interestName' WHERE id='$interestId' AND user_id='$userId'";
    error_log("Executing update query: $query"); // Log the query
    $result = mysqli_query($conn, $query);
    if (!$result) {
        error_log("Update query failed: " . mysqli_error($conn)); // Log the error
        return ['error' => 'Query Failed: ' . mysqli_error($conn)];
    }
    return ['success' => true, 'message' => 'Interest updated successfully'];
}

$response = [];
if (isset($_GET['user_id'])) {
    $userId = intval($_GET['user_id']);
    if ($userId === 0) {
        $response = ['error' => 'Invalid user ID'];
    } else {
        error_log("Fetching interests for user ID: $userId"); // Log the user ID being fetched
        $response = fetchInterests($userId, $conn);
    }
}

if (isset($_POST['interest_id']) && isset($_POST['user_id']) && isset($_POST['interest_name'])) {
    $interestId = intval($_POST['interest_id']);
    $userId = intval($_POST['user_id']);
    $interestName = trim($_POST['interest_name']);
    if ($interestId === 0 || $userId === 0 || empty($interestName)) {
        $response = ['error' => 'Invalid input data'];
    } else {
        error_log("Updating interest ID: $interestId for user ID: $userId with name: $interestName"); // Log the update details
        $response = updateInterest($interestId, $userId, $interestName, $conn);
    }
}

echo json_encode($response);

