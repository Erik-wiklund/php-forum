<?php
session_start();
 include_once(__DIR__ . "../../../db/db_connect.php");

 global $conn;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['moderation_action']) && isset($_POST['moderation'])) {
        $action = $_POST['moderation_action'];
        $messageIds = $_POST['moderation'];
        $messageType = $_POST['message_type']; // Add this line to get the message type

        // Ensure user is an administrator and the action is 'delete'
        if ($_SESSION['user_role'] === 'administrator' && $action === 'delete') {
            foreach ($messageIds as $messageId) {
                // Perform the deletion based on the message type (thread or reply)
                // Be sure to validate user permissions and sanitize input.
                // Here, we're assuming a simple SQL delete query.

                if ($messageType === 'thread') {
                    // Delete a thread
                    $query = "DELETE FROM threads WHERE thread_id = ?";
                } elseif ($messageType === 'reply') {
                    // Delete a reply
                    $query = "DELETE FROM replies WHERE reply_id = ?";
                }

                // Prepare and execute the query (using prepared statements is safer)
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, 'i', $messageId);
                mysqli_stmt_execute($stmt);
            }

            // Redirect back to the page after deletion or perform any necessary response.
            header('Location: your_page.php');
            exit;
        }
    }
}
?>
