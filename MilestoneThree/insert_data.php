<?php

/**
 * insert_data.php
 * This script handles the insertion of new records into various database tables 
 * based on data submitted from forms. It is a general-purpose insert handler.
 * 
 * Functionality includes:
 * 
 * 1. Retrieving parameters:
 *    The script receives data via POST request parameters, which may include fields 
 *    relevant to various tables (e.g., skills, interests, education, experience).
 * 
 * 2. Database connection:
 *    It establishes a connection to the database using the `database.php` file to 
 *    perform the insert operation.
 * 
 * 3. Inserting the data:
 *    The script constructs and executes an SQL INSERT statement based on the type 
 *    of data received, ensuring it matches the appropriate table structure.
 * 

 */

include 'database.php';

$response = [];

if (isset($_POST['insert_skill'])) {
    $skill_name = $_POST['skill_name'];
    $skill_UserID = $_POST['Skill_userId'];

    if (empty($skill_name)) {
        $response = ['success' => false, 'error' => 'You need to add the skill'];
    } else {
        // Prepare the statement to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO skills (user_id, skill_name) VALUES (?, ?)");
        $stmt->bind_param("is", $skill_UserID, $skill_name);

        if ($stmt->execute()) {
            $response = ['success' => true, 'message' => 'Data has been inserted into Skills'];
        } else {
            $response = ['success' => false, 'error' => 'Query Failed: ' . $stmt->error];
        }

        $stmt->close();
    }
}
if (isset($_POST['insert_experience'])) {
    $job_name = $_POST['job_name'];
    $job_title = $_POST['job_title'];
    $years_worked = $_POST['years_worked'];
    $experience_UserID = $_POST['experience_userId'];
    if ($job_name == "" || empty($job_name)) {
        $response = ['error' => 'You need to add the job name'];
    } else {
        $query = "INSERT INTO experience (user_id, job_name, job_title, years_worked) VALUES ('$experience_UserID', '$job_name', '$job_title', '$years_worked')";
        $result = mysqli_query($conn, $query);
        if (!$result) {
            $response = ['error' => 'Query Failed: ' . mysqli_error($conn)];
        } else {
            $response = ['success' => true, 'message' => 'Data has been inserted into Experience'];
        }
    }
}


if (isset($_POST['insert_education'])) {
    $school_name = $_POST['school_name'];
    $degree_obtained = $_POST['degree_obtained'];
    $years_attended = $_POST['years_attended'];
    $education_UserID = $_POST['Education_userId'];
    if ($school_name == "" || empty($school_name)) {
        $response = ['error' => 'You need to add the school name'];
    } else {
        $query = "INSERT INTO education (user_id, school_name, degree_obtained, years_attended) VALUES ('$education_UserID', '$school_name', '$degree_obtained', '$years_attended')";
        $result = mysqli_query($conn, $query);
        if (!$result) {
            $response = ['error' => 'Query Failed: ' . mysqli_error($conn)];
        } else {
            $response = ['success' => true, 'message' => 'Data has been inserted into Education'];
        }
    }
}



if (isset($_POST['insert_interest'])) {
    $interest_name = $_POST['interest_name'];
    $interest_UserID = $_POST['Interest_userId'];
    if ($interest_name == "" || empty($interest_name)) {
        $response = ['error' => 'You need to add the interest'];
    } else {
        $query = "INSERT INTO interests (user_id, interest_name) VALUES ('$interest_UserID', '$interest_name')";
        $result = mysqli_query($conn, $query);
        if (!$result) {
            $response = ['error' => 'Query Failed: ' . mysqli_error($conn)];
        } else {
            $response = ['success' => true, 'message' => 'Data has been inserted into Interests'];
        }
    }
}

header('Content-Type: application/json');
echo json_encode($response);
exit();

