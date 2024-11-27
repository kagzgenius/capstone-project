
<!--
/**Creator: Zach Fordahl
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
     Date: [YYYY-MM-DD]
     
 * upload_profile_pic.php
 * 
 * This script handles the upload and update of a user's profile picture.
 * 
 * Functionality includes:
 * 
 * 1. Checking for File Existence:
 *    The script checks if a profile picture file exists using $_FILES['profile_pic'].
 * 
 * 2. Renaming the Picture:
 *    If the file exists, it changes the name of the picture to include the user ID, a timestamp, 
 *    and the directory "uploads".
 * 
 * 3. Verifying File Size:
 *    The script verifies that the file size is within the acceptable limit of 2MB.
 * 
 * 4. Verifying File Type:
 *    It checks that the file type is one of the allowed formats: jpg, jpeg, png, gif. 
 *    If the file type is not allowed, it throws an error.
 * 
 * 5. Uploading the Picture:
 *    If the file passes all the prechecks, it is uploaded to the specified directory.
 * 
 * 6. Updating the Profile Table:
 *    The script then performs an UPDATE statement to save the new picture path in the profile table.
 * 
 * 7. Error Handling:
 *    The script checks for any errors that occurred during the upload process and handles them accordingly.
 */
-->
<?php
session_start();
include 'database.php';

$response = [];

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_FILES['profile_pic'])) {
    $target_dir = "uploads/";
    $imageFileType = strtolower(pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION));
    $uniqueFileName = $target_dir . $_SESSION['user_id'] . '_' . time() . '.' . $imageFileType;

    $uploadOk = 1;

    // Check if image file is an actual image or fake image
    $check = getimagesize($_FILES['profile_pic']['tmp_name']);
    if ($check === false) {
        $response = ['error' => 'File is not an image.'];
        $uploadOk = 0;
    }

    // Check file size (e.g., limit to 2MB)
    if ($_FILES['profile_pic']['size'] > 2000000) {
        $response = ['error' => 'Sorry, your file is too large.'];
        $uploadOk = 0;
    }

    // Allow certain file formats
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowedTypes)) {
        $response = ['error' => 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.'];
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $response = ['error' => 'Sorry, your file was not uploaded.'];
    } else {
        if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $uniqueFileName)) {
            // Update the user's profile picture in the database
            $query = "UPDATE profile SET profile_pic = ? WHERE id = ?";
            $stmt = $conn->prepare($query);

            if ($stmt === false) {
                die("Error preparing query: " . htmlspecialchars($conn->error));
            }

            $stmt->bind_param("si", $uniqueFileName, $_SESSION['user_id']);
            if ($stmt->execute()) {
                $response = ['success' => true, 'fileName' => basename($uniqueFileName), 'message' => 'Profile picture updated successfully'];
                // Update the session variable with the new profile picture path
                $_SESSION['profile_pic'] = $uniqueFileName;
            } else {
                $response = ['error' => 'Error updating profile picture: ' . $stmt->error];
            }

            $stmt->close();
        } else {
            $response = ['error' => 'Sorry, there was an error uploading your file.'];
        }
    }
} else {
    $response = ['error' => 'No file was uploaded.'];
}

// Ensure the response is always JSON
header('Content-Type: application/json');
echo json_encode($response);

$conn->close();



header("Location: index.php"); 
exit();

