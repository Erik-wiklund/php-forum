<?php
session_start();
include_once(__DIR__ . "../../../db/db_connect.php");
global $conn;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_SESSION['ID'])) {
        echo "Session ID is set!";
        // Get the logged-in user's ID from the session
        $userID = $_SESSION['ID'];

        // Query to retrieve the username from the 'users' table based on the user's ID
        $usernameQuery = "SELECT username FROM users WHERE ID = $userID";

        // Execute the query
        $result = mysqli_query($conn, $usernameQuery);

        if ($result && mysqli_num_rows($result) > 0) {
            // Fetch the username from the result
            $row = mysqli_fetch_assoc($result);
            $username = $row['username'];

            // Now, you have the username
            echo "Logged-in username: " . $username;
        } else {
            // Handle the case where the user is not found
            echo "User not found.";
        }
    } else {
        echo "Session ID is NOT set!";
    }

    // Rest of your code...
    $threadTitle = $_POST['threadTitle'];
        $threadContent = $_POST['threadContent'];
        $stickyThread = isset($_POST['stickyThread']) ? 1 : 0;
        $userID = $_SESSION['ID'];
    // Insert the retrieved username into the 'threads' table
    $insertQuery = "INSERT INTO threads (thread_title, thread_content, forum_id, user_id, sticky) VALUES ('$threadTitle', '$threadContent', 1, $userID, $stickyThread)";

    if (mysqli_query($conn, $insertQuery)) {
        // Thread created successfully, redirect to the list of threads page using GET
        header("Location: subforum.php");
        exit();
    } else {
        echo "Error creating thread: " . mysqli_error($conn);
    }
}
?>
