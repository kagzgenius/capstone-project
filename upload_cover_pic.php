
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
 Checks to make sure that the cover picture is an image, it is the correct, size, type and there are no errors. It then will update the profile
 table in the database to store the image and when the user logs it it gets loaded and is used in the profile and messaging page to show th users cover. 
  
-->
<!-- Developer: [Your Name],
     Changes made: [Description],
     Date: [YYYY-MM-DD] -->

<?php
session_start();
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $target_dir = "uploads/";
// makes sure the cover picture exists and there isnt any errors. If both are true it loads the path and details of the photo.
    if (isset($_FILES["cover_pic"]) && $_FILES["cover_pic"]["error"] == 0) {
        $cover_pic = $_FILES["cover_pic"];
        $cover_target_file = $target_dir . basename($cover_pic["name"]);
        $cover_uploadOk = 1;
        $cover_imageFileType = strtolower(pathinfo($cover_target_file, PATHINFO_EXTENSION));

        $check_cover = getimagesize($cover_pic["tmp_name"]);
        if ($check_cover !== false) {
            $cover_uploadOk = 1;
        } else {
            echo "Cover file is not an image.";
            $cover_uploadOk = 0;
        }
         //checks to see if the cover page exist
        if (file_exists($cover_target_file)) {
            $cover_target_file = $target_dir . time() . "_" . basename($cover_pic["name"]);
        }
        //checks to make sure the file isnt to large for the system to handle. 
        if ($cover_pic["size"] > 500000) {
            echo "Sorry, your cover file is too large.";
            $cover_uploadOk = 0;
        }
        //checks the file type of the image
        $allowed_file_types = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($cover_imageFileType, $allowed_file_types)) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed for cover picture.";
            $cover_uploadOk = 0;
        }
        //if all are correct it then updates the profile table with the path and name of the folder to be used when the program is loaded. 
        if ($cover_uploadOk == 0) {
            echo "Sorry, your cover file was not uploaded.";
        } else {
            if (move_uploaded_file($cover_pic["tmp_name"], $cover_target_file)) {
                $_SESSION['cover_pic'] = basename($cover_target_file);
                $user_id = $_SESSION['user_id'];
                $cover_pic_name = $_SESSION['cover_pic'];
                $sql = "UPDATE profile SET cover_pic = '$cover_pic_name' WHERE user_id = $user_id";
                if (mysqli_query($conn, $sql)) {
                    echo "Cover picture uploaded.";
                    header("Location: profile.php");
                    exit();
                } else {
                    echo "Error updating record: " . mysqli_error($conn);
                }
            } else {
                echo "Sorry, there was an error uploading your cover file.";
            }
        }
    }
}

