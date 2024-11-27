<?php

/**
 * <!--
 * Creator: Zach Fordahl
 * Date: 11/08/2024
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
 * update_education.php
 * 
 * This script manages the retrieval and updating of user education data.
 * It includes the following functionalities:
 * 
 * 1. `fetchDegrees`: 
 *    Fetches degrees related to the provided user ID from the education table.
 * 
 * 2. `fetchUsersWithDegree`: 
 *    Fetches users who have obtained the specified degree and are not the logged-in user.
 * 
 * 3. `fetchColleges`: 
 *    Fetches colleges related to the provided user ID from the education table.
 * 
 * 4. `fetchUsersWithCollege`: 
 *    Fetches users who attended the specified college and are not the logged-in user.
 * 
 * 5. `fetchEducation`: 
 *    Fetches detailed education information related to the provided user ID from the education table.
 * 
 * 6. `updateEducation`: 
 *    Updates the education data for the specified user ID and education ID in the education table.
 * 
 * The script performs the following steps:
 * - Checks if `user_id`, `degree_name`, or `college_name` is provided in the GET request.
 * - Based on the provided parameters, it calls the appropriate function to fetch the required data.
 * - If the POST request includes education update data, it calls the `updateEducation` function to update the education information.
 * - Returns the fetched or updated data in JSON format.
 * 
 * home.js uses this script to fetch and update the education data on the home page.
 * 
 * education.js also uses this script to update the education information in the profile tab on index.php.
 */



ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include 'database.php';

header('Content-Type: application/json');


/**
 * fetchDegrees
 * 
 * This function takes the userId that was sent by index.php.
 * It executes a SELECT statement to fetch the id, degree_obtained, and user_id 
 * from the education table for the specified userId.
 * 
 * The fetched data is passed to home.js, where the viewSameDegrees function 
 * uses it to display all of the degrees associated with the logged-in user.
 */

/**
 * fetchUsersWithDegree
 * 
 * This function takes the degreeName and userId that are passed from home.js FetchUsersWithDegree.
 * It executes a SELECT statement to fetch the user data when a user clicks on view profile.
 * 
 * The function retrieves all users who have obtained the specified degree and are not the logged-in user.
 * It builds the data for these users, including their username, degree, and id.
 * 
 * The fetched data is used to display the users with the selected degree.
 */

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

/**
 * fetchColleges
 * 
 * This function takes the userId that was sent by index.php.
 * It executes a SELECT statement to fetch the id, school_name, and user_id 
 * from the education table for the specified userId.
 * 
 * The fetched data is passed to home.js, where the viewSameColleges function 
 * uses it to display all of the colleges associated with the logged-in user.
 */

/**
 * fetchUsersWithCollege
 * 
 * This function takes the collegeName and userId that are passed from home.js FetchUsersWithCollege.
 * It executes a SELECT statement to fetch the user data when a user clicks on view profile.
 * 
 * The function retrieves all users who attended the specified college and are not the logged-in user.
 * It builds the data for these users, including their username, college name, and id.
 * 
 * The fetched data is used to display the users with the selected college.
 */


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

/**
 * fetchEducation
 * 
 * This function takes the userId that was sent by index.php.
 * It executes a SELECT statement to fetch the id, school_name, degree_obtained, years_attended, and user_id 
 * from the education table for the specified userId.
 * 
 * The fetched data is used by education.js to create a form that allows the user to update their education information.
 * The updateEducation function in education.js uses this data to display the user's current education details.
 */

/**
 * updateEducation
 * 
 * This function takes the id, userId, schoolName, degreeObtained, and yearsAttended 
 * and updates the corresponding entry in the education table.
 * 
 * The function is used by education.js to update the education information for the specified user.
 * It is also used in conjunction with the updateDeleteEducation function in education.js 
 * to create a table with update and delete buttons that push the data to updateEducation.
 */


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

