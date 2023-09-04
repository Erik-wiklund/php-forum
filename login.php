<?php
session_start();

require_once "db_connect.php";

// Check if the user is already logged in
if (isset($_SESSION['username'])) {
    header('Location: index.php'); // Redirect to the index page if already logged in
    exit;
}

// Check if the login form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["form_submitted"])) {
    if (isset($_POST['login_username']) && isset($_POST['login_password'])) {
        $username = $_POST['login_username'];
        $password = $_POST['login_password'];

        // Fetch the hashed password from the database based on the provided username
        $query = "SELECT id, password FROM users WHERE username = '$username'";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $hashedPassword = $row['password'];
        
            // Verify the provided password against the hashed password
            if (password_verify($password, $hashedPassword)) {
                $_SESSION['username'] = $username;
                $_SESSION['ID'] = $row['id'];
        
                // Fetch the user's role from the database
                $queryRole = "SELECT userrole FROM users WHERE username = '$username'";
                $resultRole = mysqli_query($conn, $queryRole);
        
                if ($resultRole && mysqli_num_rows($resultRole) > 0) {
                    $roleRow = mysqli_fetch_assoc($resultRole);
                    $_SESSION['user_role'] = $roleRow['userrole'];
                }
        
                // Reload the page
                header('Location: index.php');
                exit;
            } else {
                $loginError = true;
            }
        } else {
            $loginError = true;
        }
        
    }
}
?>

<!-- Your modal HTML code and form here -->
