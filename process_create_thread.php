<?php include "db_connect.php" ?>

<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_SESSION['ID'])) {
        echo "Session ID is set!";
    } else {
        echo "Session ID is NOT set!";
    }
    
    if (isset($_POST['threadTitle']) && isset($_POST['threadContent']) && isset($_SESSION['ID'])) {
        $threadTitle = $_POST['threadTitle'];
        $threadContent = $_POST['threadContent'];
        $stickyThread = isset($_POST['stickyThread']) ? 1 : 0;
        $userID = $_SESSION['ID'];
        $username = $_SESSION['username'];

        $insertQuery = "INSERT INTO threads (thread_title, thread_content, forum_id, user_id, username, sticky) VALUES ('$threadTitle', '$threadContent', 1, $userID, '$username', $stickyThread)";
        
        if (mysqli_query($conn, $insertQuery)) {
            // Thread created successfully, redirect to the list of threads page using GET
            header("Location: subforum.php");
            exit();
        } else {
            echo "Error creating thread: " . mysqli_error($conn);
        }
    }
}
?>
