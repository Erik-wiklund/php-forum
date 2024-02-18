<?php
session_start();
include_once(__DIR__ . "../../../db/db_connect.php");

// Initialize variables
$subcatToEdit = $subcatNameEdit = $subcatOrderEdit = $subcatCategoryEdit = '';
$updateMessage = "";

// Fetch and display subcategory information for the specified subcategoryId
if (isset($_GET['subcategory_id'])) {
    $subcatToEdit = $_GET['subcategory_id'];

    // Read subcategory data from the database
    $query = "SELECT subcategory_name, subcategory_order, category_id FROM subcategories WHERE subcategory_id = '$subcatToEdit'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $subToEdit = mysqli_fetch_assoc($result);
        $subcatNameEdit = $subToEdit['subcategory_name'];
        $subcatOrderEdit = $subToEdit['subcategory_order'];
        $subcatCategoryEdit = $subToEdit['category_id'];
    } else {
        echo "Error fetching subcategory data: " . mysqli_error($conn);
        exit;
    }
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['editSubCategoryName']) && isset($_POST['editSubcategoryOrder']) && isset($_POST['editCategorySelect'])) {
    // Get the submitted form data
    $editSubcategoryName = $_POST['editSubCategoryName'];
    $editSubcategoryOrder = $_POST['editSubcategoryOrder'];
    $editCategorySelect = $_POST['editCategorySelect'];

    // You should validate and sanitize user inputs to prevent SQL injection here.

    // Assuming you have a valid database connection in $conn
    global $conn;

    // Get the subcategoryId from the query string
    if (isset($_GET['subcategory_id'])) {
        $subcategoryId = $_GET['subcategory_id'];

        // Update subcategory information in the database
        $updateSubcategoryQuery = "UPDATE subcategories SET subcategory_name = '$editSubcategoryName', subcategory_order = '$editSubcategoryOrder', category_id = '$editCategorySelect' WHERE subcategory_id = '$subcategoryId'";

        if (mysqli_query($conn, $updateSubcategoryQuery)) {
            $updateMessage = "Subcategory data updated successfully!";
            // Update the edited data
            $subcatNameEdit = $editSubcategoryName;
            $subcatOrderEdit = $editSubcategoryOrder;
            $subcatCategoryEdit = $editCategorySelect;
        } else {
            $error_message = "Error editing subcategory: " . mysqli_error($conn);
            error_log($error_message); // Log the error message
        }
    }
}
?>

<div class="modal">
    <div class="modal-content">
        <h3>Edit Subcategory</h3>
        <form action="edit_sub_category.php?subcategory_id=<?php echo $subcatToEdit; ?>" method="post">
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

            <button class="button" type="submit">Save</button>
        </form>
    </div>
</div>