<?php
session_start();
include('databaseConnection.php');

$response = ['success' => false];

if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['profileImage']['tmp_name'];
    $fileName = $_FILES['profileImage']['name'];
    $fileSize = $_FILES['profileImage']['size'];
    $fileType = $_FILES['profileImage']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));

    $allowedfileExtensions = ['jpg', 'gif', 'png', 'jpeg'];
    if (in_array($fileExtension, $allowedfileExtensions)) {
        $uploadFileDir = './uploads/';
        $dest_path = $uploadFileDir . $fileName;

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $response['success'] = true;
            $response['filePath'] = $dest_path;
        }
    }
}

echo json_encode($response);

