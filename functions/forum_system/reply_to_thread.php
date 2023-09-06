<?php
session_start();
include_once(__DIR__ . "../../../db/db_connect.php"); 

global $conn;
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['thread_id']) && isset($_POST['reply_content']) && isset($_SESSION['ID'])) {
        $threadId = $_POST['thread_id'];
        $replyContent = $_POST['reply_content'];
        $userId = $_SESSION['ID'];

        $insertQuery = "INSERT INTO replies (thread_id, user_id, reply_content) VALUES ($threadId, $userId, '$replyContent')";
        if (mysqli_query($conn, $insertQuery)) {
            // Reply added successfully, redirect back to the thread's content page
            header("Location: display_thread_content.php?thread_id=$threadId");
            exit();
        } else {
            echo "Error adding reply: " . mysqli_error($conn);
        }
    }
}


?>

<!DOCTYPE html>
<html>
<head>
    <title>Reply to Thread</title>
    <style>
         body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-top: 0;
        }

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            resize: vertical;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Reply to Thread</h2>
        <form action="reply_to_thread.php" method="post">
            <input type="hidden" name="thread_id" value="<?php echo $_GET['thread_id']; ?>">
            <label for="reply_content">Your Reply:</label>
            <textarea name="reply_content" rows="6" required></textarea><br>
            <input type="submit" value="Submit Reply">
        </form>
    </div>
</body>
</html>
