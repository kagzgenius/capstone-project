<?php

/**
 * delete_interest.php
 * This script is called when the `deleteInterest` function in `interests.js` 
 * is executed. It handles the deletion of a user's interest record from the database.
 * 
 * Functionality includes:
 * 
 * 1. Retrieving parameters:
 *    The script receives the `interest_id` and `user_id` via GET or POST request 
 *    parameters to identify the specific interest record to be deleted.
 * 
 * 2. Database connection:
 *    It establishes a connection to the database using the `database.php` file to 
 *    perform the deletion operation.
 * 
 * 3. Deleting the record:
 *    The script constructs an SQL DELETE statement to remove the record from the 
 *    interests table based on the provided `interest_id`, ensuring it corresponds 
 *    to the specified `user_id` to prevent accidental deletions.
 * 
 * 4. (Optional: Error handling and confirmation messages can be described here if applicable)
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include 'database.php';

$input = json_decode(file_get_contents('php://input'), true);

$interestId = isset($input['interest_id']) ? intval($input['interest_id']) : 0;
$userId = isset($input['user_id']) ? intval($input['user_id']) : 0;

if ($interestId === 0 || $userId === 0) {
    echo json_encode(['error' => 'Invalid input data']);
    exit();
}

$query = "DELETE FROM interests WHERE id='$interestId' AND user_id='$userId'";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo json_encode(['error' => 'Query Failed: ' . mysqli_error($conn)]);
    exit();
}

echo json_encode(['success' => true, 'message' => 'Interest deleted successfully']);

