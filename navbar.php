
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
The navbar links provide a consistent look and feel for the logo, header, and navigation buttons across the site, reducing redundancy and ensuring a seamless user experience. 
  
-->
<!-- Developer: [Your Name],
     Changes made: [Description],
     Date: [YYYY-MM-DD] -->

<?php
/** checks to see if session is started and if not starts */
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style>
        /* General styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            height: 100%;
        }

        .navbar {
            margin-bottom: 0;
            border-radius: 0;
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
        }

        .navbar-header {
            display: flex;
            align-items: center;
        }

        .logo img {
            height: 75px;
            width: 150px;
            background-color: #444;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
        }

        .logo img:hover {
            transform: translateY(-5px);
        }

        .navbar-nav {
            float: right;
        }

        .navbar-nav li a {
            color: #fff !important;
            padding: 15px 20px;
            text-decoration: none;
            border-radius: 8px;
            background-color: #444;
            position: relative;
            margin: 0 10px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .navbar-nav li a:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-bottom: 4px solid red;
        }

        .footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            width: 100%;
            bottom: 0;
        }


        /* Media Queries for Responsiveness */
        @media (max-width: 768px) {
            .navbar-header, .navbar-nav {
                float: none;
                text-align: center;
            }

            .navbar-nav li {
                display: inline-block;
                float: none;
            }

            .navbar-nav li a {
                padding: 10px 20px;
                margin: 2%;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <div class="logo">
                    <img src="Logo.png" alt="My Website Logo">
                </div>
                
            </div>
    <!-- link to home,messaging,profile,admin and logout. -->
            <ul class="nav navbar-nav">
                <li><a href="index.php">Home</a></li>
                <li><a href="messaging.php">Messaging</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="admin.php">Admin</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>
    <footer class="footer">
        © 2024 Your Company
    </footer>
</body>
</html>
