<?php
// Include the database connection
require_once "db_connect.php";

// Check if the username parameter is provided
if (isset($_GET['username'])) {
    $usernameToDelete = $_GET['username'];

    // Delete the user from the database
    $deleteQuery = "DELETE FROM users WHERE username = '$usernameToDelete'";
    if (mysqli_query($conn, $deleteQuery)) {
        // Redirect back to the manage_users.php page
        header('Location: manage_users.php');
        exit;
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}
?>
