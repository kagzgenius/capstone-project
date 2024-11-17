/**
 * logout.js
 * This script manages the logout process for the user.
 * 
 * When executed, it calls `logout.php`, which handles the 
 * logout functionality by closing the user session and 
 * redirecting them to the appropriate page.
 */


function logout() {
    window.location.href = 'logout.php';
}