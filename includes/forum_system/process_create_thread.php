<?php
session_start();
include_once(__DIR__ . "../../../db/db_connect.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_SESSION['ID'])) {
        $userID = $_SESSION['ID'];
        $threadTitle = $_POST['threadTitle'];
        $threadContent = $_POST['threadContent'];
        $stickyThread = isset($_POST['stickyThread']) ? 1 : 0;
        $subcategoryID = isset($_POST['subcategory_id']) ? $_POST['subcategory_id'] : '';

        // Check if the user ID exists in the users table
        $userCheckQuery = "SELECT ID FROM users WHERE ID = '$userID'";
        $userCheckResult = mysqli_query($conn, $userCheckQuery);
        if (mysqli_num_rows($userCheckResult) > 0) {
            // Check if the subcategory ID exists in the subcategories table
            $subcategoryCheckQuery = "SELECT subcategory_id FROM subcategories WHERE subcategory_id = '$subcategoryID'";
            $subcategoryCheckResult = mysqli_query($conn, $subcategoryCheckQuery);
            if (mysqli_num_rows($subcategoryCheckResult) > 0) {
                // Insert the thread into the database
                $insertQuery = "INSERT INTO threads (thread_title, thread_content, creator_id, sticky, subcategory_id) VALUES ('$threadTitle', '$threadContent', '$userID', '$stickyThread', '$subcategoryID')";
                if (mysqli_query($conn, $insertQuery)) {
                    // Thread created successfully, redirect to the list of threads page using GET
                    header("Location: subforum.php");
                    exit();
                } else {
                    echo "Error creating thread: " . mysqli_error($conn);
                }
            } else {
                echo "Error: Subcategory ID does not exist.";
            }
        } else {
            echo "Error: User ID does not exist.";
        }
    } else {
        echo "Session ID is NOT set!";
    }
}
