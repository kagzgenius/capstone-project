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
  
-->
<!-- Developer: [Your Name],
     Changes made: [Description],
     Date: [YYYY-MM-DD]
     
     /**
 * login.php
 * 
 * This script handles the user login process. It uses `database.php` to establish a database connection 
 * and performs a SELECT statement on the user and profile tables.
 * 
 * Functionality includes:
 * 
 * 1. Receiving Form Data:
 *    There is a form on the login page that sends the username and password to this script using the POST method 
 *    when the user hits the submit button.
 * 
 * 2. Validating User Credentials:
 *    The script checks if the username and password exist in the user table. If the credentials are valid:
 *    - It creates session variables that hold `user_id`, `username`, `profile_pic`, and `is_admin`. These variables are used by various pages.
 *    - It redirects the user to `index.php`.
 * 
 * 3. Handling Invalid Credentials:
 *    If the username or password is incorrect, the script returns an error message indicating that the credentials are invalid.
 * 
 * 4. Additional Options:
 *    - Forgot Password: Users can click on the "Forgot your password" link, which redirects to `request_reset_form.php` to reset the password.
 *    - Register: Users without an account can click on the "Register" link, which redirects to `register.php`.
 */

     -->

     <?php
// Load the database and the session
session_start();
include 'DatabaseConnection.php';
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT users.id, users.password, users.is_active, profile.profile_pic, profile.cover_pic, users.is_admin 
                            FROM users 
                            JOIN profile ON users.id = profile.user_id 
                            WHERE users.username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id, $hashed_password, $is_active, $profile_pic, $cover_pic,$is_admin);
    $stmt->fetch();

    if ($stmt->num_rows > 0) {
        if (!$is_active) {
 
            $error = "Your account is no longer active.";
        } else {
            if (password_verify($password, $hashed_password)) {
                // Set session variables
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $username;
                $_SESSION['profile_pic'] = $profile_pic;
                $_SESSION['cover_pic'] = $cover_pic;
                $_SESSION['is_admin'] = $is_admin;
                header('Location: index.php');
                exit();
            } else {
                $error = "Invalid username or password.";
            }
        }
    } else {
        $error = "Invalid username or password.";
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
    <title>Login</title>
   
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
    <!--Form to ask username and password and if both are correct and exist in the profile table and user table then data is loaded.-->
    <div class="container">
        <h2>Login</h2>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="login.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Login</button>
        </form>
        <p><a href="request_reset_form.php">Forgot your password?</a>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>