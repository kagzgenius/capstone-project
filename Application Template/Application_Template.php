


<?php include('databaseConnection.php'); ?>
<!DOCTYPE html>
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
<!--
 * Application Template
 * 
 * This template is utilized by the Home, Messaging, Profile, and Admin pages to ensure consistency across the application.
 * 
 * Structure:
 * - Header: Contains the application logo and a navigation bar with buttons for Home, Messaging, Profile, Admin, and Sign Out.
 * - Main Page: The central area where the content specific to each page will be displayed.
 * - Footer: A footer section to be included at the bottom of each page.
 * 
 * By using this template, we maintain a uniform look and feel throughout the application, enhancing the user experience.
-->

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="styles.css">
    <!--
  All the styles for the home page, messaging page, profile page, and admin page 
  are done in this styles.css file. However, you will need to style the contents 
  of each page you are working on in its own style page to ensure the template 
  layout remains consistent.
-->

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
    <!-- The $pageContent variable is used to maintain a consistent layout across different pages (e.g., home, messaging, profile, admin). 
         This allows each page to have its unique content while preserving the overall design and structure of the site.-->

        <?php echo $pageContent; ?>
    </main>
    
    <footer>
        <p>&copy; 2024 My Website. All rights reserved.</p>
    </footer>
</div>
</body>
</html>
