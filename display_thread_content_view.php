<?php session_start(); ?>
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

        .quote-container {
            display: none;
            overflow: hidden;
            position: relative;
            font-style: italic;
            font-size: 9pt;
            background-color: #2a2a2a;
            background-repeat: repeat-x;
            background-position: top;
            padding: 10px;
            border-left: 2px solid #494949;
            margin-top: 10px;
            border-radius: 5px;
            color: white;
        }

        .quoted-content {
            margin-left: 20px;
            /* Add an indentation for better visibility */
        }

        /* Style for the quick reply form */
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

        .moderation-checkbox {
            margin-right: 5px;
        }

        #moderation-dropdown {
            padding: 5px;
        }

        #moderation-action {
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 5px 10px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="thread-title"><?php echo $threadRow['thread_title']; ?></div>
    <?php
    $isAdmin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'administrator';
    if ($isAdmin) {
        // Display moderation controls only if the user is an administrator
        echo '
    <div>
        <select id="moderation-dropdown">
            <option value="delete">Delete</option>
            <option value="approve">Approve</option>
            <!-- Add other moderation choices as needed -->
        </select>
        <button type="submit" id="moderation-action">Apply Moderation</button>
    </div>';
    }
    ?>

    <form id="moderation-form" action="process_moderation.php" method="POST">
        <!-- Other form fields here -->
        <input type="hidden" id="moderation-action" name="moderation_action" value="">
        <ul class="message-list">
            <li class="message">
                <div class="username"><?php echo $threadRow['username']; ?></div>
                <?php echo $threadRow['thread_content']; ?>
                <?php if ($isAdmin) { ?>
                    <input type="checkbox" class="moderation-checkbox" name="moderation[]" value="<?php echo $threadRow['thread_id']; ?>" data-message-type="thread">
                <?php } ?>
            </li>
            <?php foreach ($replies as $reply) { ?>
                <li class="message">
                    <div class="username"><?php echo $reply['username']; ?></div>
                    <?php echo $reply['reply_content']; ?>
                    <?php if ($isAdmin) { ?>
                        <input type="checkbox" class="moderation-checkbox" name="moderation[]" value="<?php echo $reply['reply_id']; ?>" data-message-type="reply">
                    <?php } ?>
                </li>
            <?php } ?>
        </ul>
    </form>

    <form id="quick-reply-form" action="process_quick_reply.php" method="POST">
        <textarea id="quick-reply-content" rows="5" placeholder="Your reply"></textarea>
        <input type="hidden" name="thread_id" value="<?php echo $threadRow['thread_id']; ?>">
        <input type="hidden" id="quick-reply-quote" name="reply_id" value="">
        <button type="submit" id="quick-reply-submit">Post Reply</button>
    </form>






    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const quickReplyForm = document.getElementById('quick-reply-form');
            const quickReplyContent = document.getElementById('quick-reply-content');
            const quickReplyQuote = document.getElementById('quick-reply-quote');
            const quickReplySubmit = document.getElementById('quick-reply-submit');
            const messageList = document.querySelector('.message-list');

            // Update reply_id and content when clicking reply button
            const replyButtons = document.querySelectorAll('.reply-button');
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
                const threadId = <?php echo $threadRow['thread_id']; ?>;

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
                        messageList.appendChild(newMessage);

                        quickReplyContent.value = '';
                        quickReplyQuote.value = '';
                    }
                };
                const data = `reply_content=${encodeURIComponent(replyContent)}&reply_id=${encodeURIComponent(replyId)}&thread_id=${encodeURIComponent(threadId)}`;
                xhr.send(data);
            });
        });
    </script>


</body>



</html>