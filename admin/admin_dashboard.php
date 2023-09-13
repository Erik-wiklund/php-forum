<?php
// Include your database connection here
include_once(__DIR__ . "../../db/db_connect.php");
include_once(__DIR__ . "../../functions/functions.php");

add_new_forum_category();
edit_forum_category();
delete_forum_category();
add_new_forum_subcategory();
delete_forum_subcategory();

// Handle adding new user
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['userrole'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $userrole = $_POST['userrole'];

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);

    // Insert user into the database
    $insertUserQuery = "INSERT INTO users (username, password, userrole) VALUES ('$username', '$hashedPassword', '$userrole')";
    if (mysqli_query($conn, $insertUserQuery)) {
        // Redirect after successful user addition
        header("Location: admin_dashboard.php");
        exit(); // Important to exit to prevent further execution
    } else {
        echo "Error adding user: " . mysqli_error($conn);
    }
}

// Handle editing user
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['editUserID'])) {
    $editUserID = $_POST['editUserID'];
    $newUsername = $_POST['editUsername'];
    $newUserRole = $_POST['editUserRole'];

    // Define other fields you want to edit
    $newFirstName = $_POST['editFirstName'];
    $newLastName = $_POST['editLastName'];
    $newPassword = $_POST['editPassword'];

    // Hash the new password
    $hashedNewPassword = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 10]);

    // Update user information in the database
    $updateUserQuery = "UPDATE users SET username = '$newUsername', userrole = '$newUserRole', firstname = '$newFirstName', lastname = '$newLastName', password = '$hashedNewPassword' WHERE ID = '$editUserID'";
    if (mysqli_query($conn, $updateUserQuery)) {
        // Redirect after successful user edit
        header("Location: admin_dashboard.php");
        exit(); // Important to exit to prevent further execution
    } else {
        echo "Error editing user: " . mysqli_error($conn);
    }
}

// Handle deleting user
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['deleteUserID'])) {
    $deleteUserID = $_POST['deleteUserID'];

    // Delete user from the database
    $deleteUserQuery = "DELETE FROM users WHERE ID = '$deleteUserID'";
    if (mysqli_query($conn, $deleteUserQuery)) {
        // Redirect after successful user deletion
        header("Location: admin_dashboard.php");
        exit(); // Important to exit to prevent further execution
    } else {
        echo "Error deleting user: " . mysqli_error($conn);
    }
}



?>

<!-- Add/Edit/Delete Subcategories -->
<?php



?>

<!DOCTYPE html>
<html>
<link rel="stylesheet" href="../style/admin_dashboard.css">

<head>
    <title>Admin Dashboard</title>

</head>

<body>
    <div class="menu">
        <div class="menu-item" onclick="showSection('forumManagement')">Forum Management</div>
        <div class="menu-item" onclick="showSection('userManagement')">User Management</div>
    </div>

    <div class="admin-section" id="forumManagement">
        <h2>Forum Management</h2>
        <!-- Add New Category -->
        <div style="justify-content: center; display: flex;">
            <button class="button" onclick="showModal('addCategoryModal')">Add Category</button>
            <button class="button" onclick="showModal('addSubCategoryModal')">Add Subcategory</button>
        </div>
        <!-- Modal for adding category -->
        <div id="addCategoryModal" class="modal">
            <div class="modal-content">
                <h3>Add Category</h3>
                <form action="admin_dashboard.php" method="post">
                    <label for="categoryName">Category Name:</label>
                    <input type="text" id="categoryName" name="categoryName">
                    <button class="button" type="submit">Add</button>
                    <button class="button" onclick="closeModal('addCategoryModal')">Cancel</button>
                </form>
            </div>
        </div>
        <!-- Edit SubCategory Modal -->
        <!-- Modal for editing subcategory -->




        <h3>Forum:</h3>
        <table>
            <tr class="tag-names">
                <th>Category Name</th>
                <th>SubCategories</th>
                <th>Action</th>
            </tr>
            <?php
            // Fetch and display categories from the database
            $categoryQuery = "SELECT * FROM forum_categories";
            $categoryResult = mysqli_query($conn, $categoryQuery);

            while ($categoryRow = mysqli_fetch_assoc($categoryResult)) {
                echo '<tr>';
                echo '<td>' . $categoryRow['category_name'] . '</td>';
                echo '<td>';
                // Fetch and display subcategories for the current category
                $subcategoryQuery = "SELECT * FROM subcategories WHERE category_id = " . $categoryRow['category_id'];
                $subcategoryResult = mysqli_query($conn, $subcategoryQuery);

                while ($subcategoryRow = mysqli_fetch_assoc($subcategoryResult)) {
                    echo '<div>';
                    echo '&emsp;&emsp;' . $subcategoryRow['subcategory_name'];
            ?>
                    <a class="button" href="includes/edit_sub_category.php?subcategory_id=<?php echo $subcategoryRow['subcategory_id'] ?>">Edit</a> <?php
                     echo '<button class="button" type="button" onclick="showModal(\'deleteSubcategoryModal' . $subcategoryRow['subcategory_id'] . '\')">Delete</button>';                              echo '</div>';
                      add_new_subcategory_modal();                    
                }
                delete_subcategory_modal();

                echo '</td>';
                echo '<td>';
                echo '<form action="admin_dashboard.php" method="post">';
                echo '<input type="hidden" name="editCategoryID" value="' . $categoryRow['category_id'] . '">';
                // Inside your existing loop for displaying categories
                echo '<button class="button" type="button" onclick="showModal(\'editCategoryModal' . $categoryRow['category_id'] . '\')">Edit</button>';
                echo '<button class="button" type="button" onclick="showModal(\'deleteCategoryModal' . $categoryRow['category_id'] . '\')">Delete</button>';

                echo '</form>';
                echo '</td>';
                echo '</tr>';

                // Inside your loop for displaying categories
                echo '<div id="editCategoryModal' . $categoryRow['category_id'] . '" class="modal">';
                echo '<div class="modal-content">';
                echo '<h3>Edit Category</h3>';
                echo '<form action="admin_dashboard.php" method="post">';
                echo '<input type="hidden" name="editSubcategoryID" value="' . $categoryRow['category_id'] . '">';
                echo '<label for="editCategoryName">Category Name:</label>';
                echo '<input type="text" name="editCategoryName" value="' . $categoryRow['category_name'] . '">';
                echo '<button class="button" type="submit">Save</button>';
                echo '<button class="button" onclick="closeModal(\'editCategoryModal' . $categoryRow['category_id'] . '\')">Cancel</button>';
                echo '</form>';
                echo '</div>';
                echo '</div>';

                // Inside your loop for displaying categories
                echo '<div id="deleteCategoryModal' . $categoryRow['category_id'] . '" class="modal">';
                echo '<div class="modal-content">';
                echo '<h3>Delete Category</h3>';
                echo '<p>Are you sure you want to delete this category?</p>';
                echo '<form action="admin_dashboard.php" method="post">';
                echo '<input type="hidden" name="deleteCategoryID" value="' . $categoryRow['category_id'] . '">';
                echo '<button class="button" type="submit">Delete</button>';
                echo '<button class="button" onclick="closeModal(\'deleteCategoryModal' . $categoryRow['category_id'] . '\')">Cancel</button>';
                echo '</form>';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </table>



    </div>

    <div class="admin-section" id="userManagement">
        <h2>User Management</h2>
        <!-- Add New User -->
        <button class="button" onclick="showModal('addUserModal')">Add User</button>
        <!-- Modal for adding user -->
        <div id="addUserModal" class="modal">
            <!-- ... (similar structure as before for adding user) ... -->
        </div>

        <!-- User List Table -->
        <h3>User List:</h3>
        <table>
            <tr>
                <th>Username</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
            <?php
            // Fetch and display users from the database
            $userQuery = "SELECT * FROM users";
            $userResult = mysqli_query($conn, $userQuery);

            while ($userRow = mysqli_fetch_assoc($userResult)) {
                echo '<tr>';
                echo '<td>' . $userRow['username'] . '</td>';
                echo '<td>' . $userRow['userrole'] . '</td>';
                echo '<td>';
                echo '<form action="admin_dashboard.php" method="post">';
                echo '<input type="hidden" name="editUserID" value="' . $userRow['ID'] . '">';
                echo '<button class="button" type="button" onclick="showModal(\'editUserModal' . $userRow['ID'] . '\')">Edit</button>';
                echo '<button class="button" type="button" onclick="showModal(\'deleteUserModal' . $userRow['ID'] . '\')">Delete</button>';
                echo '</form>';
                echo '</td>';
                echo '</tr>';

                // Edit User Modal
                echo '<div id="editUserModal' . $userRow['ID'] . '" class="modal">';
                echo '<div class="modal-content">';
                echo '<h3>Edit User</h3>';
                echo '<form action="admin_dashboard.php" method="post">';
                echo '<input type="hidden" name="editUserID" value="' . $userRow['ID'] . '">';
                echo '<label for="editUsername">Username:</label>';
                echo '<input type="text" id="editUsername" name="editUsername" value="' . $userRow['username'] . '">';
                echo '<label for="editFirstname">Firstname:</label>';
                echo '<input type="text" id="editFirstname" name="editFirstname" value="' . $userRow['firstname'] . '">';
                echo '<label for="editLastname">Lastname:</label>';
                echo '<input type="text" id="editLastname" name="editLastname" value="' . $userRow['lastname'] . '">';
                echo '<label for="editPassword">Password:</label>';
                echo '<input type="password" id="editPassword" name="editPassword" value="' . $userRow['password'] . '">';
                echo '<label for="editUserRole">Role:</label>';
                echo '<select id="editUserRole" name="editUserRole">';
                echo '<option value="user" ' . ($userRow['userrole'] == 'user' ? 'selected' : '') . '>User</option>';
                echo '<option value="administrator" ' . ($userRow['userrole'] == 'administrator' ? 'selected' : '') . '>Administrator</option>';
                echo '</select>';
                echo '<button class="button" type="submit">Save</button>';
                echo '<button class="button" onclick="closeModal(\'editUserModal' . $userRow['ID'] . '\')">Cancel</button>';
                echo '</form>';
                echo '</div>';
                echo '</div>';

                // Delete User Modal
                echo '<div id="deleteUserModal' . $userRow['ID'] . '" class="modal">';
                echo '<div class="modal-content">';
                echo '<h3>Delete User</h3>';
                echo '<p>Are you sure you want to delete this user?</p>';
                echo '<form action="admin_dashboard.php" method="post">';
                echo '<input type="hidden" name="deleteUserID" value="' . $userRow['ID'] . '">';
                echo '<button class="button" type="submit">Delete</button>';
                echo '<button class="button" onclick="closeModal(\'deleteUserModal' . $userRow['ID'] . '\')">Cancel</button>';
                echo '</form>';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </table>
        <!-- ... (rest of the User Management content) ... -->
    </div>

    <!-- JavaScript for showing/hiding modals and sections -->
    <script>
        function showModal(modalId) {
            document.getElementById(modalId).style.display = 'block';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // Function to show the edit subcategory modal
        function showModal(modalId) {
            // Redirect to edit_sub_category.php with subcategoryId as a query parameter
            window.location.href = 'edit_sub_category.php?subcategoryId=' + modalId.substring('editSubcategoryModal'.length);
        }


        function showSection(sectionId) {
            // Hide all admin sections
            const adminSections = document.querySelectorAll('.admin-section');
            adminSections.forEach(section => {
                section.style.display = 'none';
            });

            // Show the selected section
            document.getElementById(sectionId).style.display = 'block';
        }
        // Function to show the edit category modal
        // function showEditCategoryModal(categoryId) {
        //     showModal('editCategoryModal' + categoryId);
        // }

        // Function to show the delete category modal
        function showDeleteCategoryModal(categoryId) {
            showModal('deleteCategoryModal' + categoryId);
        }


        // Function to show the delete subcategory modal
        function showDeleteSubcategoryModal(subcategoryId) {
            showModal('deleteSubcategoryModal' + subcategoryId);
        }
                function showModal(modalId) {
            console.log("Showing modal with ID:", modalId);
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'block';
            } else {
                console.log("Modal not found for ID:", modalId);
            }
        }
    </script>
</body>

</html>