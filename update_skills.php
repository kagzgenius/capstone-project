

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
 update skils works with the profile.php page. The user has a button on the profile.php that says update. The click on that button and it sends data to this table to populate
 a form and styled with bootstrap that updates the skills table. 
-->

<!-- Developer: [Your Name], 
     Changes made: [Description], 
     Date: [YYYY-MM-DD] -->

<?php
session_start();
include 'database.php';
//gets connection from the database 
//check to see if the userId passed is here and if so selects all the data from the skills table from that id
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM `skills` WHERE id ='$id'";
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

// If update_skills is sent the data is updated in the skills table for that user. 
/** Basically in the profile table there will be a table with all the data in the table associated with this user and that id. The user then clicks on the field they want to update.
 * The data is then populated into the form. The user then can edit the changes they want and when they hit subit it updates the skills table. 
 */
if (isset($_POST['update_skill'])) {
    $id = $_POST['id'];
    $user_id = $_POST['user_id'];
    $skill_name = $_POST['skill_name'];
    $query = "UPDATE `skills` SET `skill_name`='$skill_name' WHERE `id`='$id' AND `user_id`='$user_id'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Query Failed: " . mysqli_error($conn));
    } else {
        header('Location: profile.php?message=Skill updated successfully');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Skill</title>
    <link rel="stylesheet" href="profileStyles.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
    <?php include('navbar.php'); ?>
<!--Form styled with bootstrap and allows a user to update the skills table. As the user clicks on the skill they want to update in the profile.php table that data gets sent to this form and the skill name that needs to changes
gets populated in this table. The user can then make changes and subit the changes that gets updated into the skills table  -->
    <div class="container">
        <h2>Update Skill</h2>
        <form action="update_skills.php?id=<?php echo $id; ?>" method="post">
            <div class="form-group">
                <label for="skill_name">Skill</label>
                <input type="text" name="skill_name" class="form-control" value="<?php echo isset($row['skill_name']) ? $row['skill_name'] : ''; ?>">
            </div>
            <!-- Hidden input field for ID -->
            <input type="hidden" name="id" value="<?php echo isset($row['id']) ? $row['id'] : ''; ?>">
            <!-- Hidden input field for User ID -->
            <input type="hidden" name="user_id" value="<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>">
            <div class="form-group">
                <input type="submit" class="btn btn-success" name="update_skill" value="UPDATE">
            </div>
        </form>
    </div>
</body>
</html>
