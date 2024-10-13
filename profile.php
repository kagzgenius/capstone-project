
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
 The profile page stores data from the profile table. This page allows user to upload the coverImage, profileImage, add, insert or delete skills,interests,
 education and experience
  
-->
<!-- Developer: [Your Name],
     Changes made: [Description],
     Date: [YYYY-MM-DD] -->


<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//database.php grabs connection from the database so sql statements can be executed on each page. 
include 'database.php';

// Fetch user data 
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM `users` WHERE id ='$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// Fetch profile data
$query2 = "SELECT * FROM `profile` WHERE user_id ='$user_id'";
$result2 = mysqli_query($conn, $query2);
$profile = mysqli_fetch_assoc($result2);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <title>Profile</title>
    
</head>

<style>
    .profileContainer{
        max-width: 1100px;/**width and height: min; */
            min-height: 600px;
            display: flex;
            margin: auto;
            border: 2px solid black;
            margin-top: 50px;
        }
        /**left side of profile Container */
        .profile-left{
            flex: 1;
            min-height: 300px;
            background-color: #2c3e50;
            color: #ecf0f1;
            padding: 20px;
            text-align: center;
        }
        .profile-left img{
            width: 70%;
            border: solid thin white;
            border-radius: 50%;
            margin: 20px;

        }
        .chat-username{
            font-size:2.5em;
            font-weight: bold;
        }

        .profile-left label {
            width: 100%;
            height: 30px;
            display: block;
            background-color: #404b56;
            margin: 9px;
            border-radius: 12px;
            transition: .4s;
        }

        .profile-left label:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-bottom: solid thin red;
        }

        .upload-profile {
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #f4f4f4;
    padding: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
}

.upload-profile form {
    display: flex;
    flex-direction: column;
    align-items: center;
    background-color: #fff;
    padding: 10px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.upload-profile .profile_pic-label {
    margin-bottom: 5px;
    font-size: 1.2em;
    
}

.upload-profile input[type="file"] {
    margin-bottom: 10px;
}

.upload-profile input[type="submit"] {
    background-color: #007bff;
    color: white;
    padding: 8px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.upload-profile input[type="submit"]:hover {
    background-color: #0056b3;
}

        /**Center between left and right */
        .radioLabel{
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
          

        }
  
        /**Right side of profile Container */
        .profile-right{
            flex: 4;
            min-height: 300px;
           
           margin-left: 10px;
           background-color: #404b56;
        }

        .profile-right-container{
            display: flex;
            flex-direction: column;
            min-height: 600px;
            background-color: #ffffff;
            flex: 1;
            margin-right: 20px;
            margin-left: 20px;
            margin-top: 50px;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .profile-right-container .cover-image{
            width: 100%;

        }

</style>
<body>
<?php include('navbar.php'); ?>

<div class = "profileContainer">
    
    <!-- allows a user to upload a profile picture and loads the profile picture on the screen after doing so. Uses upload_profile_pic to get the profile pic -->
    <div class="profile-left">   <img src="uploads/<?php echo isset($_SESSION['profile_pic']) ? htmlspecialchars($_SESSION['profile_pic']) : 'default.png'; ?>" alt="profilePic" >
    <div class = "upload-profile">
    <form action="upload_profile_pic.php" method="post" enctype="multipart/form-data">
    <label for="profile_pic" name ="profile_pic-label">Choose a profile picture:</label>
    <input type="file" name="profile_pic" id="profile_pic">
    <input type="submit" value="Upload Profile Picture">
    </form>
    <!--Grabes the username, job_title,first_name,last_name from the profile and user table to post the data about the users on the screen -->
    </div>  
            <p class="chat-username"><?php echo htmlspecialchars($user['username']); ?></p>
            <p class="chat-title"><?php echo htmlspecialchars($profile['job_title']); ?></p>
            <p class="chat-name"><?php echo htmlspecialchars($profile['first_name'] . ' ' . $profile['last_name']); ?></p>

            <br>
    <!--Ass labels are clicked it uses the radio button from the div radiolabel and will open the skills,education,experience and interest pages with the help of js will pass
    the correct forms to the selected area. Example if a user clicks on skills, the label onclick uses the radio to open the skills table and js gets called to upload the skils form-->
            <div>
            <label for="experience-label" class="profile-label" data-value="Experience">Experience</label>
            <label for="education-label" class="profile-label" data-value="Education">Education</label>
            <label for="skills-label" class="profile-label" data-value="Skills">Skills</label>
            <label for="interests-label" class="profile-label" data-value="Interests">Interests</label>
            </div>
               
        
        </div>

            <div class = "radioLabel">
            <input type="radio" id="experience-label" name="myradio">
            <input type="radio" id="education-label" name="myradio">
            <input type="radio" id="skills-label" name="myradio">
            <input type="radio" id="interests-label" name="myradio">
            </div>  
    
    <div class="profile-right">

        <div class ="profile-right-container">
        <div class="cover-image">
    <!-- This form uploads the cover picture to the profile page. As the user selects which cover picture they want to use the form calls upload_cover_pic.php and
     the coverimage is uploaded.-->
  <img src="uploads/<?php echo isset($_SESSION['cover_pic']) ? htmlspecialchars($_SESSION['cover_pic']) : 'default.png'; ?>" alt="profilePic" style="width:100%; height:100%;">
  <div class = "upload-profile"></div>
  <form action="upload_cover_pic.php" method="post" enctype="multipart/form-data">
    <label for="cover_pic">Choose a cover picture:</label>
    <input type="file" name="cover_pic" id="cover_pic">
    <input type="submit" value="Upload Cover Picture">
</form>
    </div>   
        </div>
        

    </div>

</div>
    <!-- Each of these forms uses bootstrap to style them. If you change this such as ID or anything on these forms boot strab will not work correctly.
     Example so the various ids, names ect need to stay consistent to ensure the forms are styled correctly and load correct.  There are 4 forms that load
     the skills, interest education and experience. These all grab there data from the skills, interests, education and experience database table and look to the user_id-->
<form action="insert_data.php" method="post">
        <div class="modal fade" id="educationModal" tabindex="-1" role="dialog" aria-labelledby="educationModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="educationModalLabel">Add Education</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="school_name">School Name</label>
                            <input type="text" name="school_name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="degree_obtained">Degree Obtained</label>
                            <input type="text" name="degree_obtained" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="years_attended">Years Attended</label>
                            <input type="text" name="years_attended" class="form-control">
                        </div>
                        <!-- Hidden input field for User ID -->
                        <input type="hidden" name="Education_userId" id="Education_userId" value="<?php echo $_SESSION['user_id']; ?>">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-success" name="insert_education" value="INSERT">
                    </div>
                </div>
            </div>
        </div>
    </form>
<script>
    /** Us Js to pass the data to the correct location. profile-right-container in this case if the data-value is experience for example the experience table gets DOMContentLoaded
     * with the update, delete and insert
     */
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.profile-label').forEach(label => {
            label.addEventListener('click', function() {
                const value = this.getAttribute('data-value');
                const container = document.querySelector('.profile-right-container');
                container.innerHTML ="";

                if (value === "Experience") {
                    container.innerHTML += `
                        <div class="card">
                    <h2>Experience</h2>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#experienceModal">Insert Experience</button>
                    <table class="table table-hover table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User ID</th>
                                <th>Job Name</th>
                                <th>Job Title</th>
                                <th>Years Worked</th>
                                <th>Update</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            /// calls the experienced table on the userId and then loops to insert the data into the rows
                            $query = "SELECT * FROM `experience` WHERE user_id = " . $_SESSION['user_id'];
                            $result = mysqli_query($conn, $query);

                            if (!$result) {
                                die("Query Failed: " . mysqli_error($conn));
                            } else {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $row['id'] . "</td>";
                                    echo "<td>" . $row['user_id'] . "</td>";
                                    echo "<td>" . $row['job_name'] . "</td>";
                                    echo "<td>" . $row['job_title'] . "</td>";
                                    echo "<td>" . $row['years_worked'] . "</td>";
                                    echo "<td><a href='update_experience.php?id={$row['id']}' class='btn btn-success'>Update</a></td>";
                                    echo "<td><a href='delete_data.php?id={$row['id']}&table=experience' class='btn btn-danger'>Delete</a></td>";
                                    echo "</tr>";
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                    <?php if(isset($_GET['message'])){
                        echo "<h6>".$_GET['message']."</h6>";
                        }
                    ?>

                    <?php if(isset($_GET['insert_msg'])){
                        echo "<h6>".$_GET['insert_msg']."</h6>";
                        }
                    ?>
                </div>

                <form action="insert_data.php" method="post">
        <div class="modal fade" id="experienceModal" tabindex="-1" role="dialog" aria-labelledby="experienceModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="experienceModalLabel">Add Experience</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="job_name">Job Name</label>
                            <input type="text" name="job_name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="job_title">Job Title</label>
                            <input type="text" name="job_title" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="years_worked">Years Worked</label>
                            <input type="text" name="years_worked" class="form-control">
                        </div>
                        <!-- Hidden input field for User ID -->
                        <input type="hidden" name="Experience_userId" id="Experience_userId" value="<?php echo $_SESSION['user_id']; ?>">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-success" name="insert_experience" value="INSERT">
                    </div>
                </div>
            </div>
        </div>
    </form>

                    `;
                    //once data-value is education this else if gets called and select statment calls education table in the user_id who the seesion belongs to
                } else if (value === "Education") {
                    container.innerHTML += `
                        <div class="card">
                    <h2>Education</h2>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#educationModal">Insert Education</button>
                    <table class="table table-hover table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User ID</th>
                                <th>School Name</th>
                                <th>Degree Obtained</th>
                                <th>Years Attended</th>
                                <th>Update</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                    
                            <?php 
                            //calls education table on user_id and loops to all education listed for that user to create the tables
                            $query = "SELECT * FROM `education` WHERE user_id = " . $_SESSION['user_id'];
                            $result = mysqli_query($conn, $query);

                            if (!$result) {
                                die("Query Failed: " . mysqli_error($conn));
                            } else {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $row['id'] . "</td>";
                                    echo "<td>" . $row['user_id'] . "</td>";
                                    echo "<td>" . $row['school_name'] . "</td>";
                                    echo "<td>" . $row['degree_obtained'] . "</td>";
                                    echo "<td>" . $row['years_attended'] . "</td>";
                                    echo "<td><a href='update_education.php?id={$row['id']}' class='btn btn-success'>Update</a></td>";
                                    echo "<td><a href='delete_data.php?id={$row['id']}&table=education' class='btn btn-danger'>Delete</a></td>";
                                    echo "</tr>";
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                    <?php if(isset($_GET['message'])){
                        echo "<h6>".$_GET['message']."</h6>";
                        }
                    ?>

                    <?php if(isset($_GET['insert_msg'])){
                        echo "<h6>".$_GET['insert_msg']."</h6>";
                        }
                    ?>
                </div>


                <form action="insert_data.php" method="post">
        <div class="modal fade" id="educationModal" tabindex="-1" role="dialog" aria-labelledby="educationModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="educationModalLabel">Add Education</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="school_name">School Name</label>
                            <input type="text" name="school_name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="degree_obtained">Degree Obtained</label>
                            <input type="text" name="degree_obtained" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="years_attended">Years Attended</label>
                            <input type="text" name="years_attended" class="form-control">
                        </div>
                        <!-- Hidden input field for User ID -->
                        <input type="hidden" name="Education_userId" id="Education_userId" value="<?php echo $_SESSION['user_id']; ?>">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-success" name="insert_education" value="INSERT">
                    </div>
                </div>
            </div>
        </div>
    </form>
                       
                    `;
                    //if data-value is skills calls this statment and load the table information for that user_id by calling the skills table and then grabs all the data
                    //for that user for that id and genertates the table
                } else if (value === "Skills") {
                    container.innerHTML += ` <div class="card">
                    <h2>Skills</h2>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#skillModal">Insert Skill</button>
                    <table class="table table-hover table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User ID</th>
                                <th>Skill Name</th>
                                <th>Update</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $query = "SELECT * FROM `skills` WHERE user_id = " . $_SESSION['user_id'];
                            $result = mysqli_query($conn, $query);

                            if (!$result) {
                                die("Query Failed: " . mysqli_error($conn));
                            } else {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $row['id'] . "</td>";
                                    echo "<td>" . $row['user_id'] . "</td>";
                                    echo "<td>" . $row['skill_name'] . "</td>";
                                    echo "<td><a href='update_skills.php?id={$row['id']}' class='btn btn-success'>Update</a></td>";
                                    echo "<td><a href='delete_data.php?id={$row['id']}&table=skills' class='btn btn-danger'>Delete</a></td>";
                                    echo "</tr>";
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                    <?php if(isset($_GET['message'])){
                        echo "<h6>".$_GET['message']."</h6>";
                        }
                    ?>

                    <?php if(isset($_GET['insert_msg'])){
                        echo "<h6>".$_GET['insert_msg']."</h6>";
                        }
                    ?>
                </div>
                
                <form action="insert_data.php" method="post">
        <div class="modal fade" id="skillModal" tabindex="-1" role="dialog" aria-labelledby="skillModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="skillModalLabel">Add Skill</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="skill_name">Skill</label>
                            <input type="text" name="skill_name" class="form-control">
                        </div>
                        <!-- Hidden input field for User ID -->
                        <input type="hidden" name="Skill_userId" id="Skill_userId" value="<?php echo $_SESSION['user_id']; ?>">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-success" name="insert_skill" value="INSERT">
                    </div>
                </div>
            </div>
        </div>
    </form>`;
                } else if (value === "Interests") {
                    container.innerHTML += `<div class="card">
                    <h2>Interests</h2>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#interestModal">Insert Interest</button>
                    <table class="table table-hover table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User ID</th>
                                <th>Interest Name</th>
                                <th>Update</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $query = "SELECT * FROM `interests` WHERE user_id = " . $_SESSION['user_id'];
                            $result = mysqli_query($conn, $query);

                            if (!$result) {
                                die("Query Failed: " . mysqli_error($conn));
                            } else {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $row['id'] . "</td>";
                                    echo "<td>" . $row['user_id'] . "</td>";
                                    echo "<td>" . $row['interest_name'] . "</td>";
                                    echo "<td><a href='update_interests.php?id={$row['id']}' class='btn btn-success'>Update</a></td>";
                                    echo "<td><a href='delete_data.php?id={$row['id']}&table=interests' class='btn btn-danger'>Delete</a></td>";
                                    echo "</tr>";
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                    <?php if(isset($_GET['message'])){
                        echo "<h6>".$_GET['message']."</h6>";
                        }
                    ?>

                    <?php if(isset($_GET['insert_msg'])){
                        echo "<h6>".$_GET['insert_msg']."</h6>";
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <form action="insert_data.php" method="post">
        <div class="modal fade" id="interestModal" tabindex="-1" role="dialog" aria-labelledby="interestModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="interestModalLabel">Add Interest</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="interest_name">Interest</label>
                            <input type="text" name="interest_name" class="form-control">
                        </div>
                        <!-- Hidden input field for User ID -->
                        <input type="hidden" name="Interest_userId" id="Interest_userId" value="<?php echo $_SESSION['user_id']; ?>">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-success" name="insert_interest" value="INSERT">
                    </div>
                </div>
            </div>
        </div>
    </form>
    
    `;
                }
            });
        });
    });
</script>



</body>
</html>