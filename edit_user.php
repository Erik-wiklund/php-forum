<?php
session_start();
// Check if the username parameter is provided

// Include the database connection
require_once "db_connect.php";

// Initialize variables
$userIdToEdit = $usernameToEdit = $firstNameToEdit = $lastNameToEdit = $passwordToEdit = "";
$updateMessage = "";

// Check if the ID parameter is provided
if (isset($_GET['ID'])) {
    $userIdToEdit = $_GET['ID'];

    // Read user data from the database
    $query = "SELECT username, firstname, lastname, password, userrole FROM users WHERE ID = '$userIdToEdit'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $userToEdit = mysqli_fetch_assoc($result);
        $usernameToEdit = $userToEdit['username'];
        $firstNameToEdit = $userToEdit['firstname'];
        $lastNameToEdit = $userToEdit['lastname'];
        $passwordToEdit = $userToEdit['password'];
        $userRoleToEdit = $userToEdit['userrole']; // Make sure this line is included
    } else {
        echo "Error fetching user data: " . mysqli_error($conn);
        exit;
    }
}

// Update user data
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['username']) && isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['password']) && isset($_POST['userrole'])) {
    $newUsername = $_POST['username'];
    $newFirstName = $_POST['firstname'];
    $newLastName = $_POST['lastname'];
    $newPassword = $_POST['password'];
    $newUserRole = $_POST['userrole'];

    // Hash the new password
    $hashedNewPassword = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 10]);

   // Update user data in the database
$updateQuery = "UPDATE users SET username = '$newUsername', firstname = '$newFirstName', lastname = '$newLastName', password = '$hashedNewPassword', userrole = '$newUserRole' WHERE username = '$usernameToEdit'";
if (mysqli_query($conn, $updateQuery)) {
    $updateMessage = "User data updated successfully!";
    // Update the edited data
    $usernameToEdit = $newUsername;
    $firstNameToEdit = $newFirstName;
    $lastNameToEdit = $newLastName;
    $userRoleToEdit = $newUserRole; // Update the user role
    // Note: We don't update $passwordToEdit as we shouldn't retrieve and display the hashed password
} else {
    echo "Error updating record: " . mysqli_error($conn);
}
    mysqli_close($conn);
}





?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <style>
        /* Your CSS styles here */
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
        }

        h2 {
            margin-bottom: 10px;
        }

        .user-form {
            width: 40%;
            margin: 0 auto;
        }

        .user-form label {
            display: block;
            margin-bottom: 5px;
        }

        .user-form input {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
        }

        .password-toggle {
            margin-left: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h2>Edit User</h2>
    <p>Welcome, <?php echo $_SESSION['username']; ?>!</p>

    <!-- Edit User Form -->
<div class="user-form">
    <form method="post" action="edit_user.php?ID=<?php echo $userIdToEdit; ?>">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" value="<?php echo $usernameToEdit; ?>"><br>
        <label for="firstname">First Name:</label>
        <input type="text" name="firstname" id="firstname" value="<?php echo $firstNameToEdit; ?>"><br>
        <label for="lastname">Last Name:</label>
        <input type="text" name="lastname" id="lastname" value="<?php echo $lastNameToEdit; ?>"><br>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" value="<?php echo $passwordToEdit; ?>">
        <span class="password-toggle" onclick="togglePasswordVisibility()">
            <img src="eye-icon.png" id="eye-icon" alt="Toggle Password Visibility">
        </span><br>
        <label for="userrole">User Role:</label>
<select name="userrole" id="userrole">
    <option value="user" <?php if ($userRoleToEdit === 'user') echo 'selected'; ?>>User</option>
    <option value="subscriber" <?php if ($userRoleToEdit === 'subscriber') echo 'selected'; ?>>Subscriber</option>
    <option value="administrator" <?php if ($userRoleToEdit === 'administrator') echo 'selected'; ?>>Administrator</option>
</select><br>
        <button type="submit" class="modal-button" name="saveChanges">Save</button>
        <a href="manage_users.php">Cancel</a>
    </form>
</div>


    <?php if (!empty($updateMessage)) { ?>
    <p><?php echo $updateMessage; ?></p>
    <?php } ?>

    <p><a href="manage_users.php">Back to Manage Users</a></p>
    <p><a href="logout.php">Logout</a></p>

    <script>
        // Get references to modal elements
        var passwordInput = document.getElementById('password');
        var eyeIcon = document.getElementById('eye-icon');

        // Toggle password visibility
        function togglePasswordVisibility() {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.src = 'eye-off-icon.png';
            } else {
                passwordInput.type = 'password';
                eyeIcon.src = 'eye-icon.png';
            }
        }
    </script>
</body>
</html>
