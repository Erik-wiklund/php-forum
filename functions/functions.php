<?php
include_once(__DIR__ . "/../db/db_connect.php");

// Function to handle user login
function login_user($username, $password)
{
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

// Forum querys

function add_new_forum_category()
{
    global $conn;
    // Handle adding new category
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['categoryName'])) {
        $categoryName = $_POST['categoryName'];
        $categoryOrder = $_POST['categoryOrder'];

        // Insert category into the database
        $insertQuery = "INSERT INTO forum_categories (category_name,category_order) VALUES ('$categoryName', '$categoryOrder')";
        if (mysqli_query($conn, $insertQuery)) {
            // Redirect after successful category addition
            header("Location: admin_dashboard.php");
            exit(); // Important to exit to prevent further execution
        } else {
            echo "Error adding category: " . mysqli_error($conn);
        }
    }
}





function edit_forum_category($categoryId)
{
    global $conn;
    $query = "SELECT category_id, category_name, category_order FROM forum_categories";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        echo "Error fetching categories: " . mysqli_error($conn);
        exit;
    }


    while ($categoryRow = mysqli_fetch_assoc($result)) {
        $catToEdit = $categoryRow['category_id'];
        $catNameEdit = $categoryRow['category_name'];
        $catOrderEdit = $categoryRow['category_order'];
        ?>
        
        <!-- Modal for editing this category -->
        <div class="modal" id="editCategoryModal<?php echo $catToEdit; ?>">
            <div class="modal-content">
                <h3>Edit Category</h3>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <label for="editCategoryName">Category Name:</label>
                    <input type="text" name="editCategoryName" value="<?php echo $catNameEdit; ?>" required>

                    <label for="editCategoryOrder">Category Order:</label>
                    <input type="number" name="editCategoryOrder" value="<?php echo $catOrderEdit; ?>" required>

                    <input type="hidden" name="categoryId" value="<?php echo $catToEdit; ?>">

                    <button class="button" type="submit" name="savecatEdit">Save</button>
                    <button class="button" onclick="closeModal('editCategoryModal<?php echo $catToEdit; ?>')">Cancel</button>
                </form>
            </div>
        </div>
        <?php
    }
    

    if (isset($_POST['savecatEdit'])) {
        // Get the submitted form data
        $editcategoryName = $_POST['editCategoryName'];
        $editcategoryOrder = $_POST['editCategoryOrder'];
        $categoryId = $_POST['categoryId'];

        // You should validate and sanitize user inputs to prevent SQL injection here.

        // Update category information in the database (corrected SQL query)
        $updatecategoryQuery = "UPDATE forum_categories SET category_name = '$editcategoryName', category_order = '$editcategoryOrder' WHERE category_id = '$categoryId'";

        if (mysqli_query($conn, $updatecategoryQuery)) {
            // Category data updated successfully!
            // You can add a success message here if needed.
        } else {
            $error_message = "Error editing category: " . mysqli_error($conn);
            error_log($error_message); // Log the error message
        }
    }
}





function delete_forum_category()
{
    global $conn;
    // Handle deleting category
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['deleteCategoryID'])) {
        $deleteCategoryID = $_POST['deleteCategoryID'];

        // Delete category from the database
        $deleteQuery = "DELETE FROM forum_categories WHERE category_id = '$deleteCategoryID'";
        if (mysqli_query($conn, $deleteQuery)) {
            // Redirect after successful category deletion
            header("Location: admin_dashboard.php");
            exit(); // Important to exit to prevent further execution
        } else {
            echo "Error deleting category: " . mysqli_error($conn);
        }
    }
}

function add_new_forum_subcategory()
{
    global $conn;

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['subCategoryName']) && isset($_POST['subcategoryOrder']) && isset($_POST['categorySelect'])) {
        $subcategoryName = $_POST['subCategoryName'];
        $subcategoryOrder = $_POST['subcategoryOrder'];
        $categorySelect = $_POST['categorySelect'];

        // Insert subcategory into the database
        $insertQuery = "INSERT INTO subcategories (subcategory_name, subcategory_order, category_id) VALUES ('$subcategoryName', '$subcategoryOrder', '$categorySelect')";

        if (mysqli_query($conn, $insertQuery)) {
            // Redirect after successful subcategory addition
            header("Location: admin_dashboard.php");
            exit(); // Important to exit to prevent further execution
        } else {
            echo "Error adding subcategory: " . mysqli_error($conn);
        }
    }
}

function delete_forum_subcategory()
{
    global $conn;
    // Handle deleting category
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['deleteSubcategoryID'])) {
        $deleteCategoryID = $_POST['deleteSubcategoryID'];

        // Delete category from the database
        $deleteQuery = "DELETE FROM subcategories WHERE subcategory_id = '$deleteCategoryID'";
        if (mysqli_query($conn, $deleteQuery)) {
            // Redirect after successful category deletion
            header("Location: admin_dashboard.php");
            exit(); // Important to exit to prevent further execution
        } else {
            echo "Error deleting category: " . mysqli_error($conn);
        }
    }
}




// Modals

function add_new_subcategory_modal()
{
    global $conn;
?>
    <!-- Modal for adding subcategory -->
    <div id="addSubCategoryModal" class="modal">
        <div class="modal-content">
            <h3>Add Subcategory</h3>
            <form action="admin_dashboard.php" method="post"> <!-- Update 'your_script.php' to the actual script handling form submission -->
                <label for="subCategoryName">Subcategory Name:</label>
                <input type="text" name="subCategoryName" required>

                <!-- Select Category for the new subcategory -->
                <label for="categorySelect">Select Category:</label>
                <select name="categorySelect" required>
                    <?php
                    // Fetch and display category options
                    $categoryOptionsQuery = "SELECT * FROM forum_categories";
                    $categoryOptionsResult = mysqli_query($conn, $categoryOptionsQuery);

                    while ($categoryOptionRow = mysqli_fetch_assoc($categoryOptionsResult)) {
                        echo '<option value="' . $categoryOptionRow['category_id'] . '">'
                            . $categoryOptionRow['category_name'] . '</option>';
                    }
                    ?>
                </select>

                <label for="subcategoryOrder">Subcategory Order:</label>
                <input type="number" name="subcategoryOrder" required>

                <button class="button" type="submit">Add</button>
                <button class="button" onclick="closeModal('addSubCategoryModal')">Cancel</button>
            </form>
        </div>
    </div>
    <?php
}


function edit_forum_subcategory_modal()
{
    global $conn;
    $query = "SELECT subcategory_id, subcategory_name, subcategory_order, category_id FROM subcategories";
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        echo "Error fetching subcategories: " . mysqli_error($conn);
        exit;
    }
    
    while ($subcategoryRow = mysqli_fetch_assoc($result)) {
        $subcatToEdit = $subcategoryRow['subcategory_id'];
        $subcatNameEdit = $subcategoryRow['subcategory_name'];
        $subcatOrderEdit = $subcategoryRow['subcategory_order'];
        $subcatCategoryEdit = $subcategoryRow['category_id'];
    ?>
            
        <!-- Modal for editing subcategory -->
        <div class="modal" id="editSubcategoryModal<?php echo $subcatToEdit; ?>">
            <div class="modal-content">
                <h3>Edit Subcategory</h3>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <label for="editSubCategoryName">Subcategory Name:</label>
                    <input type="text" name="editSubCategoryName" value="<?php echo $subcatNameEdit; ?>" required>
    
                    <!-- Select Category for the new subcategory -->
                    <label for="editCategorySelect">Select Category:</label>
                    <select name="editCategorySelect" required>
                        <?php
                        // Fetch and display category options
                        $categoryOptionsQuery = "SELECT * FROM forum_categories";
                        $categoryOptionsResult = mysqli_query($conn, $categoryOptionsQuery);
    
                        while ($categoryOptionRow = mysqli_fetch_assoc($categoryOptionsResult)) {
                            $selected = ($categoryOptionRow['category_id'] == $subcatCategoryEdit) ? 'selected' : '';
                            echo '<option value="' . $categoryOptionRow['category_id'] . '" ' . $selected . '>'
                                . $categoryOptionRow['category_name'] . '</option>';
                        }
                        ?>
                    </select>
    
                    <label for="editSubcategoryOrder">Subcategory Order:</label>
                    <input type="number" name="editSubcategoryOrder" value="<?php echo $subcatOrderEdit; ?>" required>
    
                    <input type="hidden" name="subcategoryId" value="<?php echo $subcatToEdit; ?>">
    
                    <button class="button" type="submit" name="saveEdit">Save</button>
                    <button class="button" onclick="closeModal('editSubcategoryModal<?php echo $subcatToEdit; ?>')">Cancel</button>
                </form>
            </div>
        </div>
    <?php
    }
    ?>
    
    <?php
    if (isset($_POST['saveEdit'])) {
        // Get the submitted form data
        $editSubcategoryName = $_POST['editSubCategoryName'];
        $editSubcategoryOrder = $_POST['editSubcategoryOrder'];
        $editCategorySelect = $_POST['editCategorySelect'];
        $subcategoryId = $_POST['subcategoryId'];
    
        // You should validate and sanitize user inputs to prevent SQL injection here.
    
        // Update subcategory information in the database
        $updateSubcategoryQuery = "UPDATE subcategories SET subcategory_name = '$editSubcategoryName', subcategory_order = '$editSubcategoryOrder', category_id = '$editCategorySelect' WHERE subcategory_id = '$subcategoryId'";
    
        if (mysqli_query($conn, $updateSubcategoryQuery)) {
            // Subcategory data updated successfully!
            // You can add a success message here if needed.
        } else {
            $error_message = "Error editing subcategory: " . mysqli_error($conn);
            error_log($error_message); // Log the error message
        }
    }
    
    
}

function delete_subcategory_modal()
{
    global $conn;
    $categoryQuery = "SELECT * FROM forum_categories";
    $categoryResult = mysqli_query($conn, $categoryQuery);
    while ($categoryRow = mysqli_fetch_assoc($categoryResult)) {

        $subcategoryQuery = "SELECT * FROM subcategories WHERE category_id = " . $categoryRow['category_id'];
        $subcategoryResult = mysqli_query($conn, $subcategoryQuery);

        while ($subcategoryRow = mysqli_fetch_assoc($subcategoryResult)) {
            echo '<div id="deleteSubcategoryModal' . $subcategoryRow['subcategory_id'] . '" class="modal">';
            echo '<div class="modal-content">';
            echo '<h3>Delete Subcategory</h3>';
            echo '<p>Are you sure you want to delete this subcategory?</p>';
            echo '<form action="admin_dashboard.php" method="post">';
            echo '<input type="hidden" name="deleteSubcategoryID" value="' . $subcategoryRow['subcategory_id'] . '">';
            echo '<button class="button" type="submit">Delete</button>';
            echo '<button class="button" onclick="closeModal(\'deleteSubcategoryModal' . $subcategoryRow['subcategory_id'] . '\')">Cancel</button>';
            echo '</form>';
            echo '</div>';
            echo '</div>';
        }
    }
}
?>