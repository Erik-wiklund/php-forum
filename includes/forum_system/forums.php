<?php session_start() ?>
<?php include_once(__DIR__ . "../../../db/db_connect.php"); ?>;
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

        /* Dropdown styles */
        .dropdown {
            margin-left: 70%;
            /* float: right; */
            position: relative;
            display: inline-block;
        }

        .dropdown .dropbtn {
            color: white;
            text-decoration: none;
            padding: 10px;
            cursor: pointer;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #444;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: white;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #333;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }
    </style>
</head>

<body>
    <header>
        <h1>Forums - My Basic Website</h1>
    </header>

    <nav>
        <a href="../../index.php">Home</a>
        <a href="forums.php">Forums</a>
        <a href="#">Members</a>
        <div class="dropdown">
            <?php
            // Display login or logout link based on session status
            if (isset($_SESSION['username'])) {
                $username = $_SESSION['username'];
                echo '<span class="dropbtn">' . $username . '</span>';
                echo '<div class="dropdown-content">';
                echo '<a href="includes/myaccount.php">My Account</a>';
                if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'administrator') {
                    echo "<a href='../../admin/admin_dashboard.php'>Admin</a>";
                }
                echo '<a href="../../includes/logout.php">Logout</a>';
                echo '</div>';
            } else {
                echo '<a id="loginLink" style="cursor: pointer;">Login</a>';
                echo "<a href='Registration_form.php'>Register</a>";
            }
            ?>

    </nav>

    <div class="container">
        <!-- Display Forum Categories -->
        <div class="forum-categories">
            <?php
            global $conn;
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