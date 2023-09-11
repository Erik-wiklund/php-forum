<?php
// Database configuration
$dbHost = 'localhost';
$dbUser = 'your_db_user';
$dbPassword = 'your_db_password';
$dbName = 'php_forum';

// Create a database connection
$conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create the database if it doesn't exist
$sqlDb = "CREATE DATABASE IF NOT EXISTS $dbName";
if ($conn->query($sqlDb) === TRUE) {
    echo "Database created successfully";
} else {
    echo "Error creating database: " . $conn->error;
}


// SQL queries to create forum tables
$sqlForums = "CREATE TABLE IF NOT EXISTS forums (
    forum_id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    forum_name VARCHAR(255),
    forum_permissions VARCHAR(255)
)";

$sqlCategory = "CREATE TABLE IF NOT EXISTS category (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(255) NOT NULL
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
?>
