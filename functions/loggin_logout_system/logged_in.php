<?php
// Start the session to access session variables
session_start();
include_once './includes/includes_paths.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // If not logged in, redirect to the login page
    header('Location: login.php');
    exit;
}

require_once "db/db_connect.php";

// Display the logged-in user's information
$username = $_SESSION['username'];

// Query the database to get the user's role
$query = "SELECT userrole FROM users WHERE username = '$username'";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error: " . mysqli_error($conn));
}

$row = mysqli_fetch_assoc($result);
$storedUserRole = $row['userrole'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Logged-in Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
        }

        h2 {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h2>Welcome, <?php echo $username; ?>!</h2>
    <p>Your role is: <?php echo $storedUserRole; ?></p>
    
    <!-- Display menu choices based on user role -->
    <?php if ($storedUserRole === 'administrator') { ?>
        <p><a href="admin_dashboard.php">Admin Dashboard</a></p>
        <p><a href="manage_users.php">Manage Users</a></p>
        <p><a href="profile.php">My Profile</a></p>
    <?php } else if ($storedUserRole === 'user') { ?>
        <p><a href="user_dashboard.php">User Dashboard</a></p>
        <p><a href="profile.php">My Profile</a></p>
    <?php } ?>
    
    <p><a href="functions/logout.php">Logout</a></p>
</body>
</html>
