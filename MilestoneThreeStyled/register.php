<!--Creator: Zach Fordahl
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

/**
 * register.php
 *
 * This script handles the user registration process. It presents a form for the user to input their details
 * and inserts the data into the appropriate tables in the database.
 *
 * Functionality includes:
 *
 * 1. Registration Form:
 *    The registration page has a form that asks the user for the following details:
 *    - First Name
 *    - Last Name
 *    - Username
 *    - Password
 *    - Address
 *    - Phone Number
 *    - Job Title
 *    - Email
 *    - Bio
 *    - Security Questions and Answers
 *
 * 2. Link to Login Page:
 *    The registration page includes a hyperlink to `login.php` in case the user already has an account.
 *
 * 3. Database Connection:
 *    It establishes a connection to the database using the `database.php` file to perform the insert operations.
 *
 * 4. Inserting Data into Tables:
 *    - The script receives the form data via POST request.
 *    - It inserts the username, email, and password into the user table.
 *    - It inserts the first name, last name, bio, job title, address, and phone number into the profile table.
 *
 * 5. Creating Session Variables:
 *    Upon successful registration, the script creates session variables that hold `user_id` and `username`.
 *    These variables are used by various pages.
 *
 * 6. Redirecting to index.php:
 *    If no errors occur during the registration process, the script redirects the user to `index.php`.
 *
 * 7. Error Handling:
 *    If any errors occur during the registration process, the script displays an error message.
 */


-->

     <?php
session_start();
include('DatabaseConnection.php');
include 'database.php';

// Get data from the form and post to the database
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $security_question = $_POST['security_question'];
    $security_answer = password_hash($_POST['security_answer'], PASSWORD_BCRYPT);

    // Insert username, email and password into the user table
    $stmt = $conn->prepare("INSERT INTO users (username, email, password,security_question, security_answer) VALUES (?, ?, ?,?,?)");
    if ($stmt === false) {
        die("Prepare failed for users table: " . $conn->error);
    }
    $stmt->bind_param("sssss", $username, $email, $password,$security_question,$security_answer);
    if ($stmt->execute() === TRUE) {
        $user_id = $stmt->insert_id; // Get the ID of the newly created user
        $stmt->close();

        // As the user clicks on the form the post variables get set and the variables below get set and inserted into the profile table
        $firstName = $_POST['first_name'];
        $lastName = $_POST['last_name'];
        $bio = $_POST['bio'];
        $jobTitle = $_POST['job_title'];
        $address = $_POST['address'];
        $phoneNumber = $_POST['phone_number'];
        //insert new into profile table
        $stmt = $conn->prepare("INSERT INTO profile (user_id, first_name, last_name, bio, job_title, address, phone_number) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if ($stmt === false) {
            die("Prepare failed for profile table: " . $conn->error);
        }
        $stmt->bind_param("issssss", $user_id, $firstName, $lastName, $bio, $jobTitle, $address, $phoneNumber);
       //if everything with the prepare statment goes through sets the username and iserid in a session variable to be used from other tables throughout the program
        if ($stmt->execute() === TRUE) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            header('Location: index.php');
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Error: " . $stmt->error;
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
    <title>Register</title>

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
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.6);
            width: 600px;
            border: 2px solid rgba(0, 0, 0, 0.6);;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #fff;
        }
        form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        label {
            color: #fff;
            margin-bottom: 5px;
        }
        input, textarea {
            width: calc(100% - 20px); /* Adjust width to create space on the right */
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: rgba(255, 255, 255, 0.2);
            color: #fff;
        }
        textarea {
            grid-column: span 2;
        }
        button {
            grid-column: span 2;
            padding: 10px;
            background-color: #3269ba;
            color: #fff;
            border: 1px solid black;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s, box-shadow 0.3s;
        }
        button:hover {
            /*background-color: rgba(228, 0, 70, 1);*/
            box-shadow: 0 0 10px black;
        }
        p {
            text-align: center;
            grid-column: span 2;
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
    <!-- Create a form to allow ne users to add data into the user table and the profile table-->
    <div class="container">
        <h2>Register</h2>
        <form action="register.php" method="post">
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" required>
            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" required>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required>
            <label for="phone_number">Phone Number:</label>
            <input type="text" id="phone_number" name="phone_number" required>
            <label for="job_title">Job Title:</label>
            <input type="text" id="job_title" name="job_title" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="bio">Bio:</label>
            <textarea id="bio" name="bio" required></textarea>
            <label for="security_question">Security Question:</label>
            <select id="security_question" name="security_question" required>
            <option value="mother_maiden_name">What is your mother's maiden name?</option>
            <option value="first_pet">What was the name of your first pet?</option>
            <option value="favorite_teacher">Who was your favorite teacher?</option>
            </select>
            <label for="security_answer">Answer:</label>
            <input type="text" id="security_answer" name="security_answer" required>
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>
