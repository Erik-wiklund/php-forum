<?php include "db_connect.php" ?>
<?php session_start() ?>;
<!DOCTYPE html>
<html>

<head>
    <title>Forums - My Basic Website</title>
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

        .forum-categories {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .category {
            flex: 0 0 48%;
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .subforum-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .subforum-item {
            margin-bottom: 5px;
        }

        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
    </style>
</head>

<body>
    <header>
        <h1>Forums - My Basic Website</h1>
    </header>

    <nav>
        <a href="index.php">Home</a>
        <a href="forums.php">Forums</a>
        <a href="#">Members</a>
        <a href="login.php">Log In</a>
        <a href="#">Register</a>
    </nav>

    <div class="container">
        <!-- Display Forum Categories -->
        <div class="forum-categories">
            <?php
            // Fetch forum categories
            $query = "SELECT * FROM forum_categories";
            $result = mysqli_query($conn, $query);

            // Display forum categories and sub-forums
while ($row = mysqli_fetch_assoc($result)) {
    echo '<div class="category">';
    echo '<h2>' . $row['category_name'] . '</h2>';
    
    // Fetch sub-forums for this category
    $subforumQuery = "SELECT * FROM forums WHERE category_id = {$row['category_id']}";
    $subforumResult = mysqli_query($conn, $subforumQuery);
    
    echo '<ul class="subforum-list">';
    while ($subforumRow = mysqli_fetch_assoc($subforumResult)) {
        echo '<li class="subforum-item">';
        echo '<a href="subforum.php?subforum_id=' . $subforumRow['forum_id'] . '">' . $subforumRow['forum_name'] . '</a>';
        echo '</li>';
    }
    echo '</ul>';
    
    echo '</div>';
}
?>

        </div>

        <!-- More forum posts can be added here -->
    </div>

    <footer>
        &copy; <?php echo date("Y"); ?> Forums - My Basic Website. All rights reserved.
    </footer>
</body>

</html>
