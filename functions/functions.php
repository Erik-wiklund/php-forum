<?php
include_once(__DIR__ . "/../db/db_connect.php");

// Function to handle user login
function login_user($username, $password) {
    global $conn;
    // Check if the user is already logged in
    if (isset($_SESSION['username'])) {
        return false; // Already logged in
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

                    // Redirect to the index page
                    header('Location: ../index.php');
                    exit;
                } else {
                    return false; // Login failed
                }
            } else {
                return false; // User not found
            }
        }
    }

    return true; // No login attempt made yet
}
?>
