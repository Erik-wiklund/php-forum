<?php
// Include your database connection here
include "db_connect.php";

// Handle adding new category
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['categoryName'])) {
    $categoryName = $_POST['categoryName'];

    // Insert category into the database
    $insertQuery = "INSERT INTO forum_categories (category_name) VALUES ('$categoryName')";
    if (mysqli_query($conn, $insertQuery)) {
        // Redirect after successful category addition
        header("Location: admin_dashboard.php");
        exit(); // Important to exit to prevent further execution
    } else {
        echo "Error adding category: " . mysqli_error($conn);
    }
}

// Handle adding new sub-forum
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['subforumName']) && isset($_POST['categoryID'])) {
    $subforumName = $_POST['subforumName'];
    $categoryID = $_POST['categoryID'];

    // Insert sub-forum into the database
    $insertSubforumQuery = "INSERT INTO forums (forum_name, category_id) VALUES ('$subforumName', '$categoryID')";
    if (mysqli_query($conn, $insertSubforumQuery)) {
        // Redirect after successful sub-forum addition
        header("Location: admin_dashboard.php");
        exit(); // Important to exit to prevent further execution
    } else {
        echo "Error adding sub-forum: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Dashboard</title>
    <style>
        /* Your existing CSS styles here */
        /* ... */

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: white;
            width: 50%;
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        /* Button styles */
        .button {
            padding: 8px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 10px;
        }

        /* Form styles */
        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 5px;
        }

        input[type="text"],
        select {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        /* Rest of your CSS styles */
        /* ... */
    </style>
</head>

<body>
    <div class="admin-section">
        <h2>Add New Category</h2>
        <button class="button" onclick="showModal('addCategoryModal')">Add Category</button>
        <!-- Modal for adding category -->
        <div id="addCategoryModal" class="modal">
            <div class="modal-content">
                <h3>Add New Category</h3>
                <form action="admin_dashboard.php" method="post">
                    <label for="categoryName">Category Name:</label>
                    <input type="text" id="categoryName" name="categoryName">
                    <button class="button" type="submit">Add</button>
                    <button class="button" onclick="closeModal('addCategoryModal')">Cancel</button>
                </form>
            </div>
        </div>

        <h2>Add New Sub-forum</h2>
        <button class="button" onclick="showModal('addSubforumModal')">Add Sub-forum</button>
        <!-- Modal for adding sub-forum -->
        <div id="addSubforumModal" class="modal">
            <div class="modal-content">
                <h3>Add New Sub-forum</h3>
                <form action="admin_dashboard.php" method="post">
                    <label for="subforumName">Sub-forum Name:</label>
                    <input type="text" id="subforumName" name="subforumName">
                    <label for="categoryDropdown">Choose Category:</label>
                    <select id="categoryDropdown" name="categoryID">
                        <?php
                        // Fetch and populate categories from database
                        $query = "SELECT * FROM forum_categories";
                        $result = mysqli_query($conn, $query);

                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<option value="' . $row['category_id'] . '">' . $row['category_name'] . '</option>';
                        }
                        ?>
                    </select>
                    <button class="button" type="submit">Add</button>
                    <button class="button" onclick="closeModal('addSubforumModal')">Cancel</button>
                </form>
            </div>
        </div>

        <!-- Rest of your content -->
        <!-- ... -->
    </div>

    <div class="admin-section">
        <!-- Add Category and Sub-forum buttons and modals here -->

        <h2>Categories and Sub-forums</h2>
        <?php
        $categoryQuery = "SELECT * FROM forum_categories";
        $categoryResult = mysqli_query($conn, $categoryQuery);

        while ($categoryRow = mysqli_fetch_assoc($categoryResult)) {
            echo '<div class="category">';
            echo '<h3>' . $categoryRow['category_name'] . '</h3>';

            // Get sub-forums for this category
            $subforumQuery = "SELECT * FROM forums WHERE category_id = '{$categoryRow['category_id']}'";
            $subforumResult = mysqli_query($conn, $subforumQuery);

            echo '<ul>';
            while ($subforumRow = mysqli_fetch_assoc($subforumResult)) {
                echo '<li>' . $subforumRow['forum_name'] . ' - Permissions: ' . $subforumRow['forum_permissions'] . '</li>';
            }
            echo '</ul>';

            // Edit and Delete buttons
            echo '<button class="button" onclick="showModal(\'editCategoryModal\')">Edit</button>';
            echo '<button class="button" onclick="showModal(\'deleteCategoryModal\')">Delete</button>';
            echo '</div>';
        }
        ?>
    </div>

    <!-- Edit Category Modal -->
    <div id="editCategoryModal" class="modal">
        <div class="modal-content">
            <!-- Form for editing category -->
            <!-- ... -->
        </div>
    </div>

    <!-- Delete Category Modal -->
    <div id="deleteCategoryModal" class="modal">
        <div class="modal-content">
            <!-- Form for deleting category -->
            <!-- ... -->
        </div>
    </div>

    <script>
        function showModal(modalId) {
            document.getElementById(modalId).style.display = 'block';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }
    </script>
</body>

</html>
