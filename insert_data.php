<?php
include 'database.php';

if (isset($_POST['insert_skill'])) {
    $skill_name = $_POST['skill_name'];
    $skill_UserID = $_POST['Skill_userId'];

    if ($skill_name == "" || empty($skill_name)) {
        header('location:profile.php?message=You need to add the skill');
    } else {
        $query = "INSERT INTO `skills`(`user_id`, `skill_name`) VALUES ('$skill_UserID', '$skill_name')";
        $result = mysqli_query($conn, $query);

        if (!$result) {
            die("Query Failed: " . mysqli_error($conn));
        } else {
             header('location:profile.php?insert_msg=Data has been inserted into Skills');
        }
    }
}

if (isset($_POST['insert_experience'])) {
    $job_name = $_POST['job_name'];
    $job_title = $_POST['job_title'];
    $years_worked = $_POST['years_worked'];
    $experience_UserID = $_POST['Experience_userId'];

    if ($job_name == "" || empty($job_name)) {
        header('location:profile.php?message=You need to add the job name');
    } else {
        $query = "INSERT INTO `experience`(`user_id`, `job_name`, `job_title`, `years_worked`) VALUES ('$experience_UserID', '$job_name', '$job_title', '$years_worked')";
        $result = mysqli_query($conn, $query);

        if (!$result) {
            die("Query Failed: " . mysqli_error($conn));
        } else {
             header('location:profile.php?insert_msg=Data has been inserted into Experience');
        }
    }
}

if (isset($_POST['insert_education'])) {
    $school_name = $_POST['school_name'];
    $degree_obtained = $_POST['degree_obtained'];
    $years_attended = $_POST['years_attended'];
    $education_UserID = $_POST['Education_userId'];

    if ($school_name == "" || empty($school_name)) {
        header('location:profile.php?message=You need to add the school name');
    } else {
        $query = "INSERT INTO `education`(`user_id`, `school_name`, `degree_obtained`, `years_attended`) VALUES ('$education_UserID', '$school_name', '$degree_obtained', '$years_attended')";
        $result = mysqli_query($conn, $query);

        if (!$result) {
            die("Query Failed: " . mysqli_error($conn));
        } else {
             header('location:profile.php?insert_msg=Data has been inserted into Education');
        }
    }
}

if (isset($_POST['insert_interest'])) {
    $interest_name = $_POST['interest_name'];
    $interest_UserID = $_POST['Interest_userId'];

    if ($interest_name == "" || empty($interest_name)) {
        header('location:profile.php?message=You need to add the interest');
    } else {
        $query = "INSERT INTO `interests`(`user_id`, `interest_name`) VALUES ('$interest_UserID', '$interest_name')";
        $result = mysqli_query($conn, $query);

        if (!$result) {
            die("Query Failed: " . mysqli_error($conn));
        } else {
             header('location:profile.php?insert_msg=Data has been inserted into Interests');
        }
    }
}

