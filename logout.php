<?php
// Start the session to access session variables
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // If not logged in, redirect to the login page
    header('Location: index.php');
    exit;
}

// If the user clicks the logout link, destroy the session and redirect to the login page
if (isset($_GET['logout'])) {
    // Destroy the session
    session_destroy();

    // Clear the PHPSESSID cookie
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }

    // Clear the loggedIn cookie
    setcookie('loggedIn', '', time() - 3600, '/');

    // Clear any other session-related cookies you may have set
    // For example, if you have set other custom cookies, unset them here using setcookie() with a past expiration time

    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Logout</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
        }

        h2 {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h2>Logout</h2>
    <p>Are you sure you want to log out?</p>
    <p><a href="?logout=1">Yes, Log out</a></p>
    <p><a href="logged_in.php">No, Go back to the logged-in page</a></p>
</body>
</html>
