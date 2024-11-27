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
*Username name gets passed to the request_reset.php. Request_reset.php queries the user table on that username and grabs the security question and answer
*
-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Password Reset</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #2c2c2c;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.5);
            width: 400px;
            border: 2px solid rgba(255, 255, 255, 0.5);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #fff;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            color: #fff;
            margin-bottom: 5px;
        }
        input {
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: rgba(255, 255, 255, 0.2);
            color: #fff;
        }
        button {
            padding: 10px;
            background-color: rgba(228, 0, 70, 0.8);
            color: #fff;
            border: 2px solid #e40046;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s, box-shadow 0.3s;
        }
        button:hover {
            background-color: rgba(228, 0, 70, 1);
            box-shadow: 0 0 10px #e40046;
        }
        p {
            text-align: center;
            color: #fff;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container"> <h2>Request Password Reset</h2> <?php session_start(); if (isset($_SESSION['error'])) { echo '<p style="color: red;">' . $_SESSION['error'] . '</p>'; unset($_SESSION['error']); } ?>
        <form action="request_reset.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <button type="submit">Request Password Reset</button>
        </form>
    </div>
</body>
</html>
