<?php
// Start the session to access session variables
session_start();
include_once(__DIR__ . "./db/db_connect.php");

$registrationMessage = ""; // Initialize the registration message

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['firstname']) && isset($_POST['lastname'])) {

        $username = $_POST['username'];
        $password = $_POST['password'];
        $userrole = "user"; // Set the default role to "user"
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);

        // Get the current timestamp as the registration date
        $registerDate = date('Y-m-d H:i:s');

        // Prepare and execute the insert query
        $insertQuery = "INSERT INTO users (username, password, userrole, firstname, lastname, register_date) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insertQuery);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssssss", $username, $hashedPassword, $userrole, $firstname, $lastname, $registerDate);

            if (mysqli_stmt_execute($stmt)) {
                $registrationMessage = "User has been registered successfully!";
                // Redirect to a confirmation page or the registration form
                header("Location: confirmation.php"); // Replace 'confirmation.php' with the desired URL
                exit(); // Make sure to exit the script after redirection
            } else {
                $registrationMessage = "Error inserting record: " . mysqli_stmt_error($stmt);
            }

            mysqli_stmt_close($stmt);
        } else {
            $registrationMessage = "Prepared statement error: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Registration form</title>
    <style>
        /* Your CSS styles here */
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
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
        }

        header a:hover {
            color: #4CAF50;
        }

        h1 {
            margin-bottom: 20px;
        }

        h2 {
            margin-bottom: 10px;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="password"] {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 200px;
            margin-bottom: 10px;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .form-container {
            max-width: 400px;
            margin: 50px auto auto auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .menu-icon {
            font-size: 24px;
            cursor: pointer;
            margin-right: 10px;
        }

        .menu-container {
            position: relative;
        }

        .menu {
            position: fixed;
            top: 0;
            left: auto;
            /* Change left to auto */
            bottom: 0;
            right: 0;
            background-color: white;
            border-radius: 0 0 5px 5px;
            z-index: 9999;
            display: none;
            flex-direction: column;
            padding: 10px;
            overflow-y: auto;
            width: 200px;
            height: 100%;
            /* Change height to 100% */
        }

        .menu.active {
            display: flex;
        }

        .menu a {
            color: black;
            text-decoration: none;
            margin: 5px 0;
        }

        .sidebar {
            display: none;
            background-color: rgba(0, 0, 0, 0.4);
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            z-index: 998;
            /* Lower than menu's z-index */
        }

        .sidebar.active {
            display: block;
        }

        @media screen and (max-width: 600px) {
            .menu-icon {
                display: block;
            }

            .menu {
                width: 100%;
            }
        }
    </style>
</head>

<body>
<header>
        <h1>Welcome to My Website</h1>
        <div class="sidebar" onclick="closeMenu()"></div>
        <div class="menu-container">
            <div class="menu-icon" onclick="toggleMenu()">&#9776;</div>
            <nav class="menu">
                <a href="index.php">Home</a>
                <a href="registration_form.php">Register Account</a>
                <a href="login.php">Login</a>
            </nav>
        </div>
    </header>
    <div class="form-container">
        <h2>Registration form</h2>
        <?php if (!empty($registrationMessage)) { ?>
            <p><?php echo $registrationMessage; ?></p>
        <?php } ?>
        <form action="registration_form.php" method="post">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username"><br>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password"><br>
            <label for="firstname">First Name:</label>
            <input type="text" name="firstname" id="firstname"><br>
            <label for="lastname">Last Name:</label>
            <input type="text" name="lastname" id="lastname"><br>
            <input type="submit" value="Submit">
        </form>
    </div>
    <script>
        function toggleMenu() {
            var menu = document.querySelector('.menu');
            var menuIcon = document.querySelector('.menu-icon');
            menu.classList.toggle('active');
            menuIcon.style.display = menu.classList.contains('active') ? 'none' : 'block';
            document.querySelector('.sidebar').classList.toggle('active');
        }

        function closeMenu() {
            var menu = document.querySelector('.menu');
            var menuIcon = document.querySelector('.menu-icon');
            menu.classList.remove('active');
            menuIcon.style.display = 'block';
            document.querySelector('.sidebar').classList.remove('active');
        }
    </script>
</body>

</html>