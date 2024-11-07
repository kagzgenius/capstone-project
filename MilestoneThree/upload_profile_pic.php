<?php
/**
 * upload_profile_pic.php
 * This script handles the upload of a user's profile picture. It establishes a 
 * connection to the database using `database.php` and updates the profile table 
 * with the selected profile picture.
 * 
 * Functionality includes:
 * 
 * 1. Establishing a database connection:
 *    The script includes `database.php` to connect to the database, allowing access 
 *    to the profile table.
 * 
 * 2. Validating the uploaded file:
 *    The script checks if the uploaded file exists, verifies that it is located in the 
 *    correct directory, and ensures that it is of the correct file type (e.g., JPEG, PNG) 
 *    and size (e.g., not exceeding a predefined limit).
 * 
 * 3. Updating the profile picture:
 *    If all validations pass, the script updates the appropriate field in the profile 
 *    table with the file path of the uploaded profile picture.
 */
session_start();
include 'database.php';

$response = [];

if (isset($_FILES['profile_pic'])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES['profile_pic']['name']);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
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
        if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target_file)) {
            // Update the user's profile picture in the database
            $query = "UPDATE users SET profile_pic = ? WHERE user_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("si", $target_file, $_SESSION['user_id']);
            if ($stmt->execute()) {
                $response = ['success' => true, 'fileName' => basename($target_file), 'message' => 'Profile picture updated successfully'];
            } else {
                $response = ['error' => 'Error updating profile picture: ' . $stmt->error];
            }
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

