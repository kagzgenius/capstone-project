

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
 As a user looks at the skills, interest, education and experience in the profile table the user has the option to delete the record. This table gets
 called and removes the record based on the id that the user select. The php table will have a loop that loops through all the data in a table and one of the hidden values
 in the table is the id of that row. If the user clicks on delete that id gets sent here
-->

<!-- Developer: [Your Name], 
     Changes made: [Description], 
     Date: [YYYY-MM-DD] -->


<?php
include 'database.php';
//validates what id of the row that being asked to delete. If valid it then will delete from the table. 
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $table = $_GET['table'];
}

$query = "DELETE FROM `$table` WHERE id='$id'";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
} else {
    header('Location: profile.php?message=Data Deleted successfully');
}
