<!-- edit_category.php -->
<!-- ... (previous code) ... -->

<?php "db_connect.php"; ?>

<div class="admin-section">
    <h2>Edit Category</h2>
    <?php
    $categoryID = $_GET['id'];

    // Fetch the category details from the database
    $query = "SELECT * FROM forum_categories WHERE id = $categoryID";
    $result = mysqli_query($conn, $query);
    $category = mysqli_fetch_assoc($result);

    if (!$category) {
        echo 'Category not found.';
    } else {
        // Display a form with the category details for editing
        echo '<form action="update_category.php" method="post">';
        echo '<input type="hidden" name="category_id" value="' . $category['id'] . '">';
        echo 'Category Name: <input type="text" name="category_name" value="' . $category['category_name'] . '">';
        echo '<input type="submit" value="Update">';
        echo '</form>';
    }
    ?>
</div>

<!-- ... (remaining code) ... -->
