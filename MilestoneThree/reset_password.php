<!--
Creator: Zach Fordahl
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
 send_message is what the messaging.php uses to allow users to send the message to the message table. 
  
-->
<!-- Developer: [Your Name],
     Changes made: [Description],
     Date: [YYYY-MM-DD] 

If reset_password.php gets a token and a timed token from answer-security_question, along with a updated password the pass word is then reset
 in the users table of the database.-->
<?php

session_start();
include 'DatabaseConnection.php';
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reset_token = $_SESSION['reset_token'];
    $reset_token_expiration = $_SESSION['reset_token_expiration'];
    $newPassword = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

    if ($reset_token && $reset_token_expiration > time()) {
        $user_id = $_SESSION['user_id'];

        // Update the user's password
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $newPassword, $user_id);
        $stmt->execute();

        // Invalidate the token
        unset($_SESSION['reset_token']);
        unset($_SESSION['reset_token_expiration']);

        $_SESSION['message'] = "Your password has been reset successfully.";
        header('Location: login.php');
        exit(); // Important to stop further execution
    } else {
        $_SESSION['error'] = "Invalid or expired token.";
        header('Location: login.php');
        exit(); // Important to stop further execution
    }

    $stmt->close();
    $conn->close();
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <style>
     body { font-family: Arial, sans-serif; 
     background-color: #2c2c2c;
      margin: 0; padding: 0; 
      display: flex; justify-content: 
      center; align-items: 
      center; height: 100vh; }

      .container { 
        background-color: rgba(255, 255, 255, 0.1); 
        padding: 20px; border-radius: 8px; 
        box-shadow: 0 0 20px rgba(255, 255, 255, 0.5); 
        width: 400px; border: 2px solid rgba(255, 255, 255, 0.5); } 
        
        h2 { text-align: center; 
        margin-bottom: 20px; 
        color: #fff; } 
        form { 
            display: flex;
             flex-direction: column; 
             } 
        label { color: #fff; 
        margin-bottom: 5px; 
        } 
        input { padding: 8px; 
        margin-bottom: 10px;
         border: 1px solid #ccc; 
         border-radius: 4px; 
         background-color: rgba(255, 255, 255, 0.2); 
         color: #fff; } 
         button { 
            padding: 10px; 
            background-color: rgba(228, 0, 70, 0.8); 
            color: #fff; 
            border: 2px solid #e40046; 
            border-radius: 4px;
             cursor: pointer; 
             transition: background-color 0.3s, 
             box-shadow 0.3s; } 
        button:hover { 
            background-color: rgba(228, 0, 70, 1); 
            box-shadow: 0 0 10px #e40046; } 
            p { text-align: center; color: #fff; } </style>
</head>
<body>
<div>
<!-- Reset Password Form -->
 <div class ="container">
<form action="reset_password.php" method="post">
    <p>Reset Password</p>
    <label for="new_password">New Password:</label>
    <input type="password" id="new_password" name="new_password" required>
    <button type="submit">Reset Password</button>
</form>

</div>
</body>
</html>