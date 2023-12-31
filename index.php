<?php session_start(); 
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Basic Website</title>
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

        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px;
            position: fixed;
            width: 100%;
            bottom: 0;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
        }

        .close {
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        #myaccountLink {
            margin-left: auto;
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
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
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
<?php include "includes/login_modal.php"; ?>
    
    <header>
        <h1>Welcome to My Basic Website</h1>
    </header>

    <nav>
    <a href="index.php">Home</a>
    <a href="includes/forum_system/forums.php">Forums</a>
    <a href="#">About</a>
    <a href="#">Contact</a>

    <div class="dropdown">
        <?php
        // Display login or logout link based on session status
        if (isset($_SESSION['username']) ) {
            $username = $_SESSION['username'];
            echo '<span class="dropbtn">' . $username . '</span>';
            echo '<div class="dropdown-content">';
            echo '<a href="includes/myaccount.php">My Account</a>';
            if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'administrator') {
                echo "<a href='admin/admin_dashboard.php'>Admin</a>";
            }
            echo '<a href="includes/logout.php">Logout</a>';
            echo '</div>';
        } else {
            echo '<a id="loginLink" style="cursor: pointer;">Login</a>';
            echo "<a href='Registration_form.php'>Register</a>";
        }
        ?>

        <?php 
        
        ?>
    </div>
</nav>


    <div class="container">
        <h2>Welcome to Our Website</h2>
        <p>This is the main content area of our website. You can add more text and elements here.</p>
        <?php
        if (isset($_SESSION['username'])) {
            echo '<p>You are logged in as ' . $_SESSION['username'] . '</p>';
        }
        ?>
    </div>

    <footer>
        &copy; <?php echo date("Y"); ?> My Basic Website. All rights reserved.
    </footer>

  
</body>
</html>
