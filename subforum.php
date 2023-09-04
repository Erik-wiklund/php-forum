<?php include "db_connect.php" ?>
<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
    <title>Threads - My Basic Website</title>
    <style>
        /* Your common styles for the website layout */
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px;
        }

        header h1 {
            margin: 0;
        }

        nav {
            background-color: #444;
            color: white;
            padding: 10px;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
        }

        nav a:hover {
            text-decoration: underline;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: white;
        }

        .create-thread-button {
            display: block;
            margin: 20px 0;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .thread-list {
            list-style: none;
            padding: 0;
        }

        .thread-item {
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        /* Rest of your CSS styles */
        /* ... */
    </style>
</head>

<body>
    <header>
        <h1>Threads - My Basic Website</h1>
    </header>

    <nav>
        <!-- Navigation links here -->
    </nav>

    <div class="container">
        <?php if (isset($_SESSION['ID'])) : ?>
            <a href="create_thread.php" class="create-thread-button">Create New Thread</a>
        <?php else : ?>
            <a href="registration_form.php" class="create-thread-button">Signup</a>
        <?php endif; ?>

        <!-- Display list of threads -->
        <ul class="thread-list">
            <?php
            // Include your database connection here

            // Fetch threads
            $query = "SELECT threads.*, users.username 
            FROM threads
            INNER JOIN users ON threads.user_id = users.id";
            $result = mysqli_query($conn, $query);

            // Display threads
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<li class="thread-item">';
                echo '<h2><a href="display_thread_content.php?thread_id=' . $row['thread_id'] . '">' . $row['thread_title'] . '</a></h2>';
                echo '<p>' . "Created by" . " " . $row['username'] . '</p>';
                // Display additional thread information here
                echo '</li>';
            }
            ?>
        </ul>
    </div>

    <footer>
        &copy; <?php echo date("Y"); ?> Threads - My Basic Website. All rights reserved.
    </footer>
</body>

</html>