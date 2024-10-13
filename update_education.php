

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

 Update_education uses the profile.php. The profile.php will loop through all the data in the education table for the user who is logged in and next to the data will be an -
 update button. When the click on the update button the data that they selected gets pushed to the below form and the user can then edit and make changes to the data and hit submit that
 then gets pushed to an update statement and then updates the row associated to the changes made. 
-->

<!-- Developer: [Your Name], 
     Changes made: [Description], 
     Date: [YYYY-MM-DD] -->


<?php
session_start();
include 'database.php';

// Function to log messages to a file not needed but used because data was not populating correctly right away. 
function log_message($message) {
    $logfile = 'update_education.log';
    file_put_contents($logfile, date('Y-m-d H:i:s') . " - " . $message . PHP_EOL, FILE_APPEND);
}

log_message("Full URL: " . $_SERVER['REQUEST_URI']);

//make sure the id being passed is there and based on the user id select all the data from the education table for that id. 
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    log_message("ID: $id");

    $query = "SELECT * FROM `education` WHERE id ='$id'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        log_message("Query Failed: " . mysqli_error($conn));
        die("Query Failed: " . mysqli_error($conn));
    } else {
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            log_message("Record found: " . json_encode($row));
        } else {
            log_message("No record found with ID: $id");
            die("No record found with ID: $id");
        }
    }
} else {
    log_message("ID parameter missing in the URL.");
    die("ID parameter missing in the URL.");
}

//as update_education gets pushed the data gets set into variable to update the education table. 
if (isset($_POST['update_education'])) {
    log_message("Form submitted.");
    log_message("POST data: " . json_encode($_POST));
    $id = $_POST['id'];
    $user_id = $_POST['user_id'];
    $school_name = $_POST['school_name'];
    $degree_obtained = $_POST['degree_obtained'];
    $years_attended = $_POST['years_attended'];
    log_message("Updating data for Education, ID: $id, User ID: $user_id");

    $query = "UPDATE `education` SET `school_name`='$school_name', `degree_obtained`='$degree_obtained', `years_attended`='$years_attended' WHERE `id`='$id' AND `user_id`='$user_id'";
    log_message("Update Query: " . $query);
    $result = mysqli_query($conn, $query);

    if (!$result) {
        log_message("Query Failed: " . mysqli_error($conn));
        die("Query Failed: " . mysqli_error($conn));
    } else {
        log_message("Education data updated successfully for ID: $id");
        header('Location: profile.php?message=Education updated successfully');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Education</title>
    <link rel="stylesheet" href="profileStyles.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
    <?php include('navbar.php'); ?>
    <div class="container">
        <h2>Update Education</h2>
<!-- this table gets whatever data the user decides they want to update pushed to it. They then can make changes to the data and hit submit and it gets posted.
 The data then gets updated into the education table. -->
        <form action="update_education.php?id=<?php echo $id; ?>" method="post">
            <div class="form-group">
                <label for="school_name">School Name</label>
                <input type="text" name="school_name" class="form-control" value="<?php echo isset($row['school_name']) ? $row['school_name'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="degree_obtained">Degree Obtained</label>
                <input type="text" name="degree_obtained" class="form-control" value="<?php echo isset($row['degree_obtained']) ? $row['degree_obtained'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="years_attended">Years Attended</label>
                <input type="text" name="years_attended" class="form-control" value="<?php echo isset($row['years_attended']) ? $row['years_attended'] : ''; ?>">
            </div>
            <!-- Hidden input field for ID -->
            <input type="hidden" name="id" value="<?php echo isset($row['id']) ? $row['id'] : ''; ?>">
            <!-- Hidden input field for User ID -->
            <input type="hidden" name="user_id" value="<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>">
            <div class="form-group">
                <input type="submit" class="btn btn-success" name="update_education" value="UPDATE">
            </div>
        </form>
    </div>
</body>
</html>
