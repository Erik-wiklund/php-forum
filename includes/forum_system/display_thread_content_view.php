<?php
session_start();
include_once(__DIR__ . "../../../db/db_connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    global $conn;

    if (isset($_POST['checkBoxArray'])) {
        foreach ($_POST['checkBoxArray'] as $postValueId) {
            $bulk_option = $_POST['bulk_options'];

            // Check if both thread and reply checkboxes are checked
            $delete_threads = false;
            $delete_replies = false;

            if (in_array('thread_id', $_POST['checkBoxArray'])) {
                $delete_threads = true;
            }

            if (in_array('reply_id', $_POST['checkBoxArray'])) {
                $delete_replies = true;
            }

            switch ($bulk_option) {
                case 'delete':

                    $query = "DELETE FROM threads WHERE thread_id = {$postValueId}  ";
                    $query_reply = "DELETE FROM replies WHERE reply_id = {$postValueId}  ";

                    $update_to_delete_status_reply = mysqli_query($conn, $query_reply);
                    $query = mysqli_query($conn, $query);
                    break;
            }
        }
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Thread Content</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .thread-title {
            background-color: #333;
            color: white;
            padding: 10px;
            text-align: center;
        }

        .message-list {
            list-style: none;
            padding: 0;
            margin: 20px;
        }

        .message {
            list-style: none;
            padding: 10px;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        .message:first-child {
            background-color: #333;
            color: white;
        }

        .username {
            font-weight: bold;
            margin-right: 10px;
        }

        .reply-button {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            float: right;
            margin-top: 5px;
            cursor: pointer;
        }

        /* Add your additional CSS styles here */
        /* For example, you can add styles for the quick reply form */
        #quick-reply-form {
            background-color: #f9f9f9;
            padding: 10px;
            border-radius: 5px;
            margin-top: 20px;
        }

        #quick-reply-content {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        #quick-reply-submit {
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <form action="" method='post'>
        <!-- "Select All" container -->
        <div id="selectAllContainer">
            <div>
                <select class="form-control" name="bulk_options" id="">
                    <option value="">Select Options</option>
                    <option value="delete">Delete</option>
                </select>
                <input type="submit" name="submit" class="btn btn-success" value="Apply">
            </div>
            <div>
                <input id="selectAllBoxes" type="checkbox">
                <label for="selectAllBoxes">Select All</label>
            </div>
        </div>

        <!-- Your table content here -->
        <table class="table table-bordered table-hover">
            <tbody>
                <?php
                $query = "SELECT threads.*, users.username 
                FROM threads
                INNER JOIN users ON threads.creator_id = users.id
                ORDER BY threads.thread_id ASC";
                $select_posts = mysqli_query($conn, $query);

                while ($row = mysqli_fetch_assoc($select_posts)) {
                    $thread_id = $row['thread_id'];
                    $thread_title = $row['thread_title'];
                    $thread_content = $row['thread_content'];
                    $username = $row['username'];

                    // Display thread
                    echo "<ul class='message-list'>";
                    echo "<li class='message'>";
                    echo "<div class='username'>$username</div>"; // Display the username
                    echo "<div style='display: flex;'>";
                    // echo "<input type='checkbox' name='checkBoxArray[]' id='' value='$thread_id'>";
                    echo "<div class='message-content'>$thread_content</div>";
                    echo "</div>";
                    // Add reply button here if needed
                    echo "</li>";

                    // Fetch and display replies for this thread
                    $query_reply = "SELECT replies.*, users.username 
                    FROM replies
                    INNER JOIN users ON replies.user_id = users.id
                    WHERE replies.thread_id = $thread_id
                    ORDER BY replies.reply_id ASC";

                    $select_replies = mysqli_query($conn, $query_reply);

                    while ($row_reply = mysqli_fetch_assoc($select_replies)) {
                        $reply_id = $row_reply['reply_id'];
                        $reply_content = $row_reply['reply_content'];
                        $reply_username = $row_reply['username']; // Username for this reply

                        // Display reply
                        echo "<li class='message'>";
                        echo "<div class='username'>$reply_username</div>"; // Display the username
                        echo "<div style='display: flex;'>";
                        echo "<input class='checkBoxes' type='checkbox' name='checkBoxArray[]' id='' value='$reply_id'>";
                        echo "<div class='message-content'>$reply_content</div>";
                        echo "</div>";
                        // Add reply button here if needed
                        echo "</li>";
                    }

                    echo "</ul>"; // Close the message-list for this thread
                }
                ?>
            </tbody>
        </table>
    </form>

    <form id="quick-reply-form" action="process_quick_reply.php" method="POST">
        <textarea id="quick-reply-content" rows="5" placeholder="Your reply"></textarea>
        <input type="hidden" name="thread_id" value="<?php echo $thread_id ?>">
        <input type="hidden" id="quick-reply-quote" name="reply_id" value="">
        <button type="submit" id="quick-reply-submit">Post Reply</button>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Function to initialize checkboxes and their behavior
            function initializeCheckboxes() {
                const quickReplyForm = document.getElementById('quick-reply-form');
                const quickReplyContent = document.getElementById('quick-reply-content');
                const quickReplyQuote = document.getElementById('quick-reply-quote');
                const quickReplySubmit = document.getElementById('quick-reply-submit');
                const messageLists = document.querySelectorAll('.message-list');
                const replyButtons = document.querySelectorAll('.reply-button');

                // Update reply_id and content when clicking reply button
                replyButtons.forEach(button => {
                    button.addEventListener('click', () => {
                        quickReplyQuote.value = button.getAttribute('data-quote');
                        const quotedContent = button.getAttribute('data-quote-content');

                        // Insert quoted content into the textarea with a visual representation
                        quickReplyContent.value = `-------- Quoted Content --------\n${quotedContent}\n-------------------------------\n`;
                    });
                });

                quickReplySubmit.addEventListener('click', (event) => {
                    event.preventDefault();

                    const replyContent = quickReplyContent.value;
                    const replyId = quickReplyQuote.value;
                    const threadId = <?php echo $thread_id ?>;

                    // Use AJAX to post the reply to the replies table
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', 'process_quick_reply.php', true);
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            const newMessage = document.createElement('li');
                            newMessage.className = 'message';
                            newMessage.innerHTML = `
                    <div class="username"><?php echo $_SESSION['username']; ?></div>
                    ${replyContent}
                `;

                            // Append the new message to the last message-list
                            messageLists[messageLists.length - 1].appendChild(newMessage);

                            quickReplyContent.value = '';
                            quickReplyQuote.value = '';

                            // Reinitialize checkboxes for the new content
                            initializeCheckboxes();
                        }
                    };
                    const data = `reply_content=${encodeURIComponent(replyContent)}&reply_id=${encodeURIComponent(replyId)}&thread_id=${encodeURIComponent(threadId)}`;
                    xhr.send(data);
                });
            }

            // Call the function to initialize checkboxes when the page loads
            initializeCheckboxes();
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            // Check/uncheck all checkboxes when "Select All" is clicked
            $('#selectAllBoxes').click(function(event) {
                if (this.checked) {
                    $('.checkBoxes').each(function() {
                        this.checked = true;
                    });
                } else {
                    $('.checkBoxes').each(function() {
                        this.checked = false;
                    });
                }
            });

            // Other jQuery code goes here...
        });
    </script>
</body>

</html>