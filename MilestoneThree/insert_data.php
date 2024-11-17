<?php

/**
 * <!--
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

 * /**
 * insert_data.php
 * 
 * This script handles the insertion of new data into the skills, education, interest, and experience tables.
 * It is called by various JavaScript functions in education.js, experience.js, skills.js, and interest.js.
 * 
 * Functionality includes:
 * 
 * 1. Receiving Data:
 *    The script receives data via POST request parameters from the following functions:
 *    - `insertIntoEducation` from education.js
 *    - `insertIntoExperience` from experience.js
 *    - `insertIntoSkills` from skills.js
 *    - `insertIntoInterest` from interest.js
 * 
 * 2. Identifying the Operation:
 *    The script identifies which table to insert the data into based on the POST request parameters.
 *    Each form submission includes a hidden field indicating the type of data being inserted.
 * 
 * 3. Database Connection:
 *    It establishes a connection to the database using the `database.php` file to perform the insert operation.
 * 
 * 4. Performing the Insert:
 *    The script constructs an SQL INSERT statement to add the new data to the appropriate table:
 *    - If `$_POST['insert_education']` is set, it inserts data into the education table.
 *    - If `$_POST['insert_experience']` is set, it inserts data into the experience table.
 *    - If `$_POST['insert_skill']` is set, it inserts data into the skills table.
 *    - If `$_POST['insert_interest']` is set, it inserts data into the interest table.
 * 
 * 5. Error Handling and Confirmation:
 *    The script handles any errors that occur during the insert operation and provides confirmation messages upon successful completion of the insertion.
 */


 

include 'database.php';

$response = [];

//gets data from skill.js
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
//gets data from experience.js
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

//gets data from education.js
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


//gets data from interest.js
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

