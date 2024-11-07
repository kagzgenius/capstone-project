<?php

/**
 * update_education.php
 * This script processes updates to a user's education records. It is called when the 
 * `updateEducation` function in `education.js` is executed.
 * 
 * Functionality includes:
 * 
 * 1. Retrieving parameters:
 *    The script receives the `education_id`, `user_id`, and the updated education 
 *    data via GET or POST request parameters to identify the specific education 
 *    record to be updated.
 * 
 * 2. Database connection:
 *    It establishes a connection to the database using the `database.php` file 
 *    to perform the update operation.
 * 
 * 3. Updating the record:
 *    The script constructs an SQL UPDATE statement to modify the record in the 
 *    education table based on the provided `education_id`, applying the new education 
 *    data while ensuring it corresponds to the specified `user_id`.
 * 
 * 4. (Optional: Error handling and confirmation messages can be described here if applicable)
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include 'database.php';

header('Content-Type: application/json');

function fetchDegrees($userId, $conn) {
    $query = "SELECT id, degree_obtained, user_id FROM education WHERE user_id = '$userId'";
    error_log("Executing fetchDegrees query for user ID: $userId"); // Log user ID
    $result = mysqli_query($conn, $query);

    if (!$result) {
        error_log("Query Error in fetchDegrees: " . mysqli_error($conn)); // Log query error
        return ['error' => 'Query Failed: ' . mysqli_error($conn)];
    }

    $degrees = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $degrees[] = $row;
    }
    error_log("Degrees fetched for user ID $userId: " . json_encode($degrees)); // Log fetched degrees
    return ['degrees' => $degrees]; // Ensure the key is 'degrees'
}




function fetchUsersWithDegree($degreeName, $userId, $conn) {
    error_log("Degree name received in fetchUsersWithDegree: $degreeName"); // Log the degree name received
    $query = "SELECT u.id, u.username, e.degree_obtained FROM users u JOIN education e ON u.id = e.user_id WHERE e.degree_obtained = '$degreeName' AND u.id != '$userId'";
    error_log("Executing fetchUsersWithDegree query for degree name: $degreeName"); // Log query execution
    $result = mysqli_query($conn, $query);

    if (!$result) {
        error_log("Query Error in fetchUsersWithDegree: " . mysqli_error($conn)); // Log query error
        return ['error' => 'Query Failed: ' . mysqli_error($conn)];
    }

    $users = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
    error_log("Users fetched with degree $degreeName: " . json_encode($users)); // Log fetched users
    return ['users' => $users];
}



function fetchColleges($userId, $conn) {
    $query = "SELECT id, school_name, user_id FROM education WHERE user_id = '$userId'";
    error_log("Executing fetchColleges query for user ID: $userId"); // Log user ID
    $result = mysqli_query($conn, $query);

    if (!$result) {
        error_log("Query Error in fetchColleges: " . mysqli_error($conn)); // Log query error
        return ['error' => 'Query Failed: ' . mysqli_error($conn)];
    }

    $colleges = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $colleges[] = $row;
    }
    error_log("Colleges fetched for user ID $userId: " . json_encode($colleges)); // Log fetched colleges
    return ['colleges' => $colleges]; // Return with the key 'colleges'
}


function fetchUsersWithCollege($collegeName, $userId, $conn) {
    error_log("College name received in fetchUsersWithCollege: $collegeName"); // Log the college name received
    $query = "SELECT u.id, u.username, e.school_name FROM users u JOIN education e ON u.id = e.user_id WHERE e.school_name = '$collegeName' AND u.id != '$userId'";
    error_log("Executing fetchUsersWithCollege query for college name: $collegeName"); // Log query execution
    $result = mysqli_query($conn, $query);

    if (!$result) {
        error_log("Query Error in fetchUsersWithCollege: " . mysqli_error($conn)); // Log query error
        return ['error' => 'Query Failed: ' . mysqli_error($conn)];
    }

    $users = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
    error_log("Users fetched with college $collegeName: " . json_encode($users)); // Log fetched users
    return ['users' => $users];
}



function fetchEducation($userId, $conn) {
    $query = "SELECT id, school_name, degree_obtained, years_attended, user_id FROM education WHERE user_id = '$userId'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        return ['error' => 'Query Failed: ' . mysqli_error($conn)];
    }

    $education = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $education[] = $row;
    }
    return ['education' => $education];
}

// Function to update education
function updateEducation($id, $userId, $schoolName, $degreeObtained, $yearsAttended, $conn) {
    $query = "UPDATE education SET school_name='$schoolName', degree_obtained='$degreeObtained', years_attended='$yearsAttended' WHERE id='$id' AND user_id='$userId'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        return ['error' => 'Query Failed: ' . mysqli_error($conn)];
    }
    return ['success' => true, 'message' => 'Education updated successfully'];
}


$response = [];
if (isset($_GET['user_id'])) {
    $userId = intval($_GET['user_id']);
    if ($userId === 0) {
        $response = ['error' => 'Invalid user ID'];
    } else {
        $response = fetchEducation($userId, $conn);
    }
} elseif (isset($_GET['degree_name'])) {
    $degreeName = $_GET['degree_name'];
    $userId = $_SESSION['user_id']; // Assuming the user ID is stored in the session
    $response = fetchUsersWithDegree($degreeName, $userId, $conn);
} elseif (isset($_GET['college_name'])) {
    $collegeName = $_GET['college_name'];
    error_log("College name in GET request: $collegeName"); // Log the college name in the GET request
    $userId = $_SESSION['user_id']; // Assuming the user ID is stored in the session
    error_log("User ID from session: $userId"); // Log the user ID
    $response = fetchUsersWithCollege($collegeName, $userId, $conn);
}


if (isset($_POST['education_id']) && isset($_POST['user_id']) && isset($_POST['school_name']) && isset($_POST['degree_obtained']) && isset($_POST['years_attended'])) {
    $id = intval($_POST['education_id']);
    $userId = intval($_POST['user_id']);
    $schoolName = trim($_POST['school_name']);
    $degreeObtained = trim($_POST['degree_obtained']);
    $yearsAttended = trim($_POST['years_attended']);
    if ($id === 0 || $userId === 0 || empty($schoolName) || empty($degreeObtained) || empty($yearsAttended)) {
        $response = ['error' => 'Invalid input data'];
    } else {
        $response = updateEducation($id, $userId, $schoolName, $degreeObtained, $yearsAttended, $conn);
    }
}

echo json_encode($response);

