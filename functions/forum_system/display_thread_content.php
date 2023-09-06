<?php
// Replace with your database connection code
include_once(__DIR__ . "../../../db/db_connect.php");
global $conn;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['thread_id'])) {
        $threadId = $_GET['thread_id'];

        // Fetch thread details
        $threadQuery = "SELECT threads.*, users.username 
                        FROM threads
                        INNER JOIN users ON threads.user_id = users.id
                        WHERE threads.thread_id = ?";
        $stmt = mysqli_prepare($conn, $threadQuery);
        mysqli_stmt_bind_param($stmt, "i", $threadId);
        mysqli_stmt_execute($stmt);
        $threadResult = mysqli_stmt_get_result($stmt);
        $threadRow = mysqli_fetch_assoc($threadResult);
        mysqli_stmt_close($stmt);

        // Fetch thread replies
        $repliesQuery = "SELECT replies.*, users.username 
                        FROM replies
                        INNER JOIN users ON replies.user_id = users.id
                        WHERE replies.thread_id = ?";
        $stmt = mysqli_prepare($conn, $repliesQuery);
        mysqli_stmt_bind_param($stmt, "i", $threadId);
        mysqli_stmt_execute($stmt);
        $repliesResult = mysqli_stmt_get_result($stmt);
        $replies = mysqli_fetch_all($repliesResult, MYSQLI_ASSOC);
        mysqli_stmt_close($stmt);

        mysqli_close($conn);

        // Include the presentation part of the page
        include "display_thread_content_view.php";
    } else {
        // Handle case where thread_id is not provided
        echo "Thread ID not provided.";
    }
}


?>
