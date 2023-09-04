<?php
// Start the session to access session variables
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // If not logged in, redirect to the login page
    header('Location: login.php');
    exit;
}

// Include the database connection
require_once "db_connect.php";

// Read user data from the database
$query = "SELECT ID, username, userrole FROM users";
$result = mysqli_query($conn, $query);

$users = [];
while ($row = mysqli_fetch_assoc($result)) {
    $users[] = $row;
}

// Handle delete request
if (isset($_GET['delete']) && isset($_GET['ID'])) {
    $deleteId = $_GET['ID'];

    $deleteQuery = "DELETE FROM users WHERE ID = $deleteId";
    if (mysqli_query($conn, $deleteQuery)) {
        header('Location: manage_users.php');
        exit;
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
        }

        h2 {
            margin-bottom: 10px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
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
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .modal-buttons {
            text-align: center;
            margin-top: 10px;
        }

        .modal-button {
            margin: 0 10px;
            padding: 5px 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h2>Manage Users</h2>
    <p>Welcome, <?php echo $_SESSION['username']; ?>!</p>

    <table>
        <tr>
            <th>Username</th>
            <th>Role</th>
            <th>Action</th>
        </tr>
        <?php foreach ($users as $user) { ?>
            <tr>
                <td><?php echo $user['username']; ?></td>
                <td><?php echo $user['userrole']; ?></td>
                <td>
                    <a href="edit_user.php?ID=<?php echo $user['ID']; ?>">Edit</a>
                    <a href="#" onclick="handleDeleteClick(<?php echo $user['ID']; ?>)">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>

    <p><a href="logged_in.php">Back to Dashboard</a></p>
    <p><a href="logout.php">Logout</a></p>

    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <h3>Delete User</h3>
            <p>Are you sure you want to delete this user?</p>
            <div class="modal-buttons">
                <button id="confirmDelete" class="modal-button">Yes, Delete</button>
                <button id="cancelDelete" class="modal-button">No, Cancel</button>
            </div>
        </div>
    </div>

    <script>
        // Get references to modal elements
        var deleteModal = document.getElementById('deleteModal');
        var confirmDelete = document.getElementById('confirmDelete');
        var cancelDelete = document.getElementById('cancelDelete');

        // Show the modal
        function showModal() {
            deleteModal.style.display = 'block';
        }

        // Close the modal
        function closeModal() {
            deleteModal.style.display = 'none';
        }

        // Handle the "Delete" button click
        function handleDeleteClick(ID) {
            showModal();

            // Handle confirm delete
            confirmDelete.onclick = function() {
                // Redirect to manage_users.php with delete parameter
                window.location.href = 'manage_users.php?delete=true&ID=' + ID;
            };

            // Handle cancel delete
            cancelDelete.onclick = function() {
                closeModal();
            };
        }
    </script>
</body>
</html>
