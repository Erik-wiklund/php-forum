<?php
session_start();
// Replace with your database connection code
 include_once(__DIR__ . "../../../db/db_connect.php"); 

 global $conn;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['reply_content']) && isset($_POST['reply_id']) && isset($_POST['thread_id'])) {
        $replyContent = $_POST['reply_content'];
        $replyId = $_POST['reply_id'];
        $threadId = $_POST['thread_id'];

        // Insert the quick reply into the replies table
        $insertQuery = "INSERT INTO replies (thread_id, user_id, reply_content) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insertQuery);
        mysqli_stmt_bind_param($stmt, "iis", $threadId, $_SESSION['ID'], $replyContent);
        
        if (mysqli_stmt_execute($stmt)) {
            // Reply inserted successfully
            echo "Reply posted successfully!";
        } else {
            // Error inserting reply
            echo "Error posting reply: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
    }
}
?>
