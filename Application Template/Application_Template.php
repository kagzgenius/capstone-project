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
     Date: [YYYY-MM-DD] -->

<?php include('databaseConnection.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class = "wrapper">
    <header>
        <div class = "logo">
        <img src="Logo.png" alt="My Website Logo">
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="messaging.php">Messaging</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="admin.php">Admin</a></li>
                <li><a href="signout.php">Sign Out</a></li>
            </ul>
        </nav>
    </header>
    
    <main>
        <!-- Page-specific content goes here -->
        <?php echo $pageContent; ?>
    </main>
    
    <footer>
        <p>&copy; 2024 My Website. All rights reserved.</p>
    </footer>
</div>
</body>
</html>
