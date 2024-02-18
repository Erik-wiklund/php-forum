<?php
// Database configuration
$dbHost = 'localhost';
$dbUser = 'root';
$dbPassword = '';

// Create a database connection to MySQL server
$conn = new mysqli($dbHost, $dbUser, $dbPassword);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create the database if it doesn't exist
$dbName = 'php_forum';
$sqlCreateDb = "CREATE DATABASE IF NOT EXISTS $dbName";
if ($conn->query($sqlCreateDb) === TRUE) {
    echo "Database created successfully";
} else {
    echo "Error creating database: " . $conn->error;
}

// Close the connection to the MySQL server
$conn->close();

// Re-establish connection with the specified database
$conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// SQL query to create forums table
$sqlForums = "CREATE TABLE IF NOT EXISTS forums (
    forum_id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    forum_name VARCHAR(255),
    forum_permissions VARCHAR(255)
)";

// Execute the query to create the forums table
if ($conn->query($sqlForums) === TRUE) {
    // Check if the default forum already exists
    $defaultForumName = "General"; // Change this to the default forum name
    $sqlCheckForum = "SELECT * FROM forums WHERE forum_name = '$defaultForumName'";
    $result = $conn->query($sqlCheckForum);

    if ($result->num_rows == 0) {
        // Insert default forum into the forums table
        $sqlInsertForum = "INSERT INTO forums (category_id, forum_name, forum_permissions) VALUES (1, '$defaultForumName', 'default_permissions')";

        // Execute the query to insert the default forum
        if ($conn->query($sqlInsertForum) === TRUE) {
            echo "Default forum added successfully";
        } else {
            echo "Error adding default forum: " . $conn->error;
        }
    } else {
        echo "Default forum already exists";
    }
} else {
    echo "Error creating forums table: " . $conn->error;
}



$sqlCategory = "CREATE TABLE IF NOT EXISTS category (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(255) NOT NULL
)";

$sqlSub_categories = "CREATE TABLE subcategories (
    subcategory_id INT AUTO_INCREMENT PRIMARY KEY,
    subcategory_name VARCHAR(255) NOT NULL,
    category_id INT NOT NULL,
    FOREIGN KEY (category_id) REFERENCES forum_categories(category_id)
)";

$sqlReplies = "CREATE TABLE IF NOT EXISTS replies (
    reply_id INT AUTO_INCREMENT PRIMARY KEY,
    thread_id INT NOT NULL,
    user_id INT NOT NULL,
    reply_content TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)";

$sqlThreads = "CREATE TABLE IF NOT EXISTS threads (
    thread_id INT AUTO_INCREMENT PRIMARY KEY,
    thread_title VARCHAR(255) NOT NULL,
    thread_content TEXT NOT NULL,
    forum_id INT NOT NULL,
    user_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    sticky TINYINT NOT NULL DEFAULT 0
)";

$sqlUsers = "CREATE TABLE IF NOT EXISTS users (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    userrole VARCHAR(25) NOT NULL,
    firstname VARCHAR(255),
    lastname VARCHAR(255),
    register_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sqlUsers) === TRUE) {
    // Insert admin user after creating the users table
    $sqlInsertAdmin = "INSERT INTO users (
        username,
        password,
        userrole
    ) VALUES (
        'admin',
        'admin',
        'administrator'
    )";

    if (mysqli_query($conn, $sqlInsertAdmin)) {
        echo "Admin user added successfully";
    } else {
        echo "Error adding admin user: " . mysqli_error($conn);
    }
} else {
    echo "Error creating Users table: " . $conn->error . "<br>";
}

// Execute the queries
if ($conn->query($sqlForums) === TRUE) {
    echo "Forums table created successfully<br>";
} else {
    echo "Error creating Forums table: " . $conn->error . "<br>";
}

if ($conn->query($sqlCategory) === TRUE) {
    echo "Category table created successfully<br>";
} else {
    echo "Error creating Category table: " . $conn->error . "<br>";
}

if ($conn->query($sqlReplies) === TRUE) {
    echo "Replies table created successfully<br>";
} else {
    echo "Error creating Replies table: " . $conn->error . "<br>";
}

if ($conn->query($sqlThreads) === TRUE) {
    echo "Threads table created successfully<br>";
} else {
    echo "Error creating Threads table: " . $conn->error . "<br>";
}

if ($conn->query($sqlUsers) === TRUE) {
    echo "Users table created successfully<br>";
} else {
    echo "Error creating Users table: " . $conn->error . "<br>";
}

// Close the database connection
$conn->close();
