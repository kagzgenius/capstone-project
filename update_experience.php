

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
 update_experience gets used by the profile.php to update the experience. The user will see a table that has all the data associated with the userid
 from the experience table. The then has a button next to each row of update. If you click on that button the id gets set in a get varible. This  table 
 grabe that id and slect the data from the table. It then gets pushed to the form. The user then can edit what they want and it gets updated in the experience table

-->

<!-- Developer: [Your Name], 
     Changes made: [Description], 
     Date: [YYYY-MM-DD] -->


<?php
session_start();
include 'database.php';

// gets the id from the profile.php to validate what data it needs. Each row has an id. Based on that id that data gets pushed to the form
//The user is then allowed to make changes and when hitting submit it updates the table. 

if (isset($_GET['id'])) {
    $Id = $_GET['id'];
    $query = "SELECT * FROM `experience` WHERE id ='$Id'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Query Failed: " . mysqli_error($conn));
    } else {
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
        } else {
            die("No record found with ID: $Id");
        }
    }
} else {
    die("ID parameter missing in the URL.");
}

//when it sees update_experience from the form it updates the data with the changes and updates the experience table. 
if (isset($_POST['update_experience'])) {
    $data_id = $_POST['data_id'];
    $data_userId = $_POST['data_userId'];
    $job_name = $_POST['job_name'];
    $job_title = $_POST['job_title'];
    $years_worked = $_POST['years_worked'];
    $query = "UPDATE `experience` SET `job_name`='$job_name', `job_title`='$job_title', `years_worked`='$years_worked' WHERE `id`='$data_id' AND `user_id`='$data_userId'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Query Failed: " . mysqli_error($conn));
    } else {
        header('Location: profile.php?message=Experience updated successfully');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Experience</title>
    <link rel="stylesheet" href="profileStyles.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
    <?php include('navbar.php'); ?>
    <div class="container">
        <h2>Update Experience</h2>
<!-- Data gets sent to this from based on what the user clicks on to update. The data then populates and the user makes the changes to the data in this table
 When they hit submit it gets posted and updated to the exp table. -->
        <form action="update_experience.php" method="post">
            <div class="form-group">
                <label for="job_name">Job Name</label>
                <input type="text" name="job_name" class="form-control" value="<?php echo isset($row['job_name']) ? $row['job_name'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="job_title">Job Title</label>
                <input type="text" name="job_title" class="form-control" value="<?php echo isset($row['job_title']) ? $row['job_title'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="years_worked">Years Worked</label>
                <input type="text" name="years_worked" class="form-control" value="<?php echo isset($row['years_worked']) ? $row['years_worked'] : ''; ?>">
            </div>
            <!-- Hidden input field for Data ID -->
            <input type="hidden" name="data_id" value="<?php echo isset($row['id']) ? $row['id'] : ''; ?>">
            <!-- Hidden input field for User ID -->
            <input type="hidden" name="data_userId" value="<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>">
            <div class="form-group">
                <input type="submit" class="btn btn-success" name="update_experience" value="UPDATE">
            </div>
        </form>
    </div>
</body>
</html>
