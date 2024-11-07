
<!--Grabs the id of the user based on the username from the request_reset_form. Puts that id and the question into a session variable and
passes the results to the answer_sercurity_question.php where it writes the security question into the form and the user is allowed to put in the security answer
based on the question. 
-->
<?php
session_start();
include 'DatabaseConnection.php';
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];

    // Fetch user's ID and security question
    $stmt = $conn->prepare("SELECT id, security_question FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id, $security_question);
    $stmt->fetch();

    if ($stmt->num_rows > 0) {
        $_SESSION['user_id'] = $user_id;
        $_SESSION['security_question'] = $security_question;
        header('Location: answer_security_question.php');
        exit();
    } else {
        $_SESSION['error'] = "No user found with that username."; 
        header('Location: request_reset_form.php'); 
        exit();
    }

    $stmt->close();
    $conn->close();
}

