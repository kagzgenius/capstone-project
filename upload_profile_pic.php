
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
   Upload_profile_pic checks file type, file size, if the file is correct to be an image and if all are correct it will update the profile table in the database
   to store the image. The image is also stored into the upload folder where it gets checked. 
-->
<!-- Developer: [Your Name],
     Changes made: [Description],
     Date: [YYYY-MM-DD] -->


<?php
session_start();
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $target_dir = "uploads/";

    /**This if checks to see if the file is uploaded or if there arent any errors on upload. It then will assign the file information to the profile_pic
     * and the target path of the file. If the file system is uploaded ok. It will also change the file extention to lowercase. That way no matter what is loaded it will 
     * work because of consistency. 
     */
    if (isset($_FILES["profile_pic"]) && $_FILES["profile_pic"]["error"] == 0) {
        $profile_pic = $_FILES["profile_pic"];
        $profile_target_file = $target_dir . basename($profile_pic["name"]);
        $profile_uploadOk = 1;
        $profile_imageFileType = strtolower(pathinfo($profile_target_file, PATHINFO_EXTENSION));
        /**
         * Checks to see if the file is an image and if so it will change the profile_uploadOk to 1 and if not image to zero. 
         */
        $check_profile = getimagesize($profile_pic["tmp_name"]);
        if ($check_profile !== false) {
            $profile_uploadOk = 1;
        } else {
            echo "Profile file is not an image.";
            $profile_uploadOk = 0;
        }

        /** Check if the file exist */
        if (file_exists($profile_target_file)) {
            $profile_target_file = $target_dir . time() . "_" . basename($profile_pic["name"]);
        }
        /** Checks if the file size is ok and not to large */
        if ($profile_pic["size"] > 500000) {
            echo "Sorry, your profile file is too large.";
            $profile_uploadOk = 0;
        }
        //Check if the file type is consistent with an image. 
        $allowed_file_types = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($profile_imageFileType, $allowed_file_types)) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed for profile picture.";
            $profile_uploadOk = 0;
        }
       /** If all of those paramaters from above are correct it will then Update the profile page with the current image for the profile page.  */
        if ($profile_uploadOk == 0) {
            echo "Sorry, your profile file was not uploaded.";
        } else {
            if (move_uploaded_file($profile_pic["tmp_name"], $profile_target_file)) {
                $_SESSION['profile_pic'] = basename($profile_target_file);
                $user_id = $_SESSION['user_id'];
                $profile_pic_name = $_SESSION['profile_pic'];
                $sql = "UPDATE profile SET profile_pic = '$profile_pic_name' WHERE user_id = $user_id";
                if (mysqli_query($conn, $sql)) {
                    echo "Profile picture uploaded.";
                    header("Location: profile.php");
                    exit();
                } else {
                    echo "Error updating record: " . mysqli_error($conn);
                }
            } else {
                echo "Sorry, there was an error uploading your profile file.";
            }
        }
    }
}


