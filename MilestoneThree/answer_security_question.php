<!--
 Creator: Zach Fordahl
 * Date: 11/12/2024
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

/**
 * answer_security_question.php
 * 
 * This script handles the verification of the user's security question answer.
 * 
 * Functionality includes:
 * 
 * 1. User Registration:
 *    During user registration, the user is asked a security question and submits an answer. 
 *    This answer is stored in the users table.
 * 
 * 2. Retrieving the Stored Answer:
 *    The script performs a SELECT statement to retrieve the stored answer from the users table 
 *    based on the user's input.
 * 
 * 3. Checking the Submitted Answer:
 *    It compares the retrieved answer with the answer provided by the user in the form submission.
 * 
 * 4. Handling Correct Answer:
 *    If the provided answer matches the stored answer, the script redirects the user to reset_password.php 
 *    to proceed with resetting their password.
 * 
 * 5. Handling Incorrect Answer:
 *    If the provided answer does not match the stored answer, the script throws an error message indicating 
 *    that the security answer is incorrect and repopulates the form for the user to try again.
 */
 -->
<?php
session_start();
include 'DatabaseConnection.php';
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $security_answer = $_POST['security_answer'];

    // Fetch and verify security answer
    $stmt = $conn->prepare("SELECT security_answer FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($hashed_security_answer);
    $stmt->fetch();

    if (password_verify($security_answer, $hashed_security_answer)) {
        $_SESSION['reset_token'] = bin2hex(random_bytes(50)); // Generate a unique token
        $_SESSION['reset_token_expiration'] = time() + 1800; // Token valid for 30 minutes
        header('Location: reset_password.php');
        exit();
    } else {
        $_SESSION['error'] = "Incorrect security answer.";
        header('Location: answer_security_question.php'); 
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>


<!-- Answer Security Question Form -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Answer Security Question</title>
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Answer Security Question</h2>
        <form action="answer_security_question.php" method="post">
            <p><?php echo htmlspecialchars($_SESSION['security_question']); ?></p>
            <?php  if (isset($_SESSION['error'])) { echo '<p style="color: red;">' . $_SESSION['error'] . '</p>'; unset($_SESSION['error']); } ?>
            <label for="security_answer">Answer:</label>
            <input type="text" id="security_answer" name="security_answer" required>
            <button type="submit">Verify Answer</button>
        </form>
    </div>
</body>
</html>
