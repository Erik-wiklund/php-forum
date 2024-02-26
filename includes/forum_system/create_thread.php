<?php
session_start();
include_once(__DIR__ . "/../../db/db_connect.php"); // Adjust the path as necessary
?>

<!DOCTYPE html>
<html>

<head>
    <title>Create New Thread - My Basic Website</title>
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

        .create-thread-form {
            display: flex;
            flex-direction: column;
        }

        .create-thread-form label {
            margin-bottom: 5px;
        }

        .create-thread-form input[type="text"],
        .create-thread-form textarea {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        .create-thread-button {
            padding: 8px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            max-width: 150px;
        }

        /* Rest of your CSS styles */
        /* ... */
    </style>
</head>

<body>
    <header>
        <h1>Create New Thread - My Basic Website</h1>
    </header>

    <nav>
        <?php if (isset($_SESSION['ID'])) : ?>
            <!-- Navigation links for logged-in users -->
            <a href="#">Home</a>
            <a href="#">Forums</a>
            <a href="#">Members</a>
            <a href="logout.php">Log Out</a>
        <?php else : ?>
            <!-- Navigation links for users not logged in -->
            <a href="#">Home</a>
            <a href="#">Forums</a>
            <a href="#">Members</a>
            <a href="signup.php">Sign Up</a>
            <a href="login.php">Log In</a>
        <?php endif; ?>
    </nav>

    <div class="container">
        <?php if (isset($_SESSION['ID'])) : ?>
            <h2>Fill in the details to create a new thread</h2>
            <form class="create-thread-form" action="process_create_thread.php" method="post">
                <label for="threadTitle">Thread Title:</label>
                <input type="text" id="threadTitle" name="threadTitle" required>

                <label for="threadContent">Thread Content:</label>
                <textarea id="threadContent" name="threadContent" rows="6" required></textarea>

                <label>
                    <input type="checkbox" id="stickyThread" name="stickyThread">
                    Sticky Thread
                </label>
                <input type="hidden" id="subcategory_id" name="subcategory_id" value="<?php echo isset($_GET['subcategory_id']) ? $_GET['subcategory_id'] : ''; ?>">

                <button class="create-thread-button" type="submit">Create Thread</button>
            </form>
        <?php else : ?>
            <p>Please log in to create a new thread.</p>
        <?php endif; ?>
    </div>

    <footer>
        &copy; <?php echo date("Y"); ?> Create New Thread - My Basic Website. All rights reserved.
    </footer>
</body>

</html>