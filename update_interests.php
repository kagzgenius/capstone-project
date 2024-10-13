

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
 Update_interests is used by the profile table to allow users to update the data they have in the interest table associated with the userid.
-->

<!-- Developer: [Your Name], 
     Changes made: [Description], 
     Date: [YYYY-MM-DD] -->


<?php
//look to the database to get a connection
session_start();
include 'database.php';
//if the user hits the update in the profile.php it sends a Userid and gets checked here to validate. If it is there it then selects all the data for that user id 
//in the interest gable. 
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM `interests` WHERE id ='$id'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Query Failed: " . mysqli_error($conn));
    } else {
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
        } else {
            die("No record found with ID: $id");
        }
    }
} else {
    die("ID parameter missing in the URL.");
}

//looks to the form in the profile table for the paramater update_interest and if sent it then updates the table with the changes made. 
if (isset($_POST['update_interest'])) {
    $id = $_POST['id'];
    $user_id = $_POST['user_id'];
    $interest_name = $_POST['interest_name'];
    $query = "UPDATE `interests` SET `interest_name`='$interest_name' WHERE `id`='$id' AND `user_id`='$user_id'";
    $result = mysqli_query($conn, $query);
    //sends back to the profile table if user data has been updated with a message that gets populated below the form of successful
    if (!$result) {
        die("Query Failed: " . mysqli_error($conn));
    } else {
        header('Location: profile.php?message=Interest updated successfully');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Interest</title>
    <link rel="stylesheet" href="profileStyles.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
    <?php include('navbar.php'); ?>
    <div class="container">
        <h2>Update Interest</h2>
        <form action="update_interests.php?id=<?php echo $id; ?>" method="post">
            <div class="form-group">
                <label for="interest_name">Interest</label>
                <input type="text" name="interest_name" class="form-control" value="<?php echo isset($row['interest_name']) ? $row['interest_name'] : ''; ?>">
            </div>
            <!-- Hidden input field for ID -->
            <input type="hidden" name="id" value="<?php echo isset($row['id']) ? $row['id'] : ''; ?>">
            <!-- Hidden input field for User ID -->
            <input type="hidden" name="user_id" value="<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>">
            <div class="form-group">
                <input type="submit" class="btn btn-success" name="update_interest" value="UPDATE">
            </div>
        </form>
    </div>
</body>
</html>
