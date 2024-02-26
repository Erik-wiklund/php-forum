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
    if (mysqli_affected_rows($conn) > 0) {
        echo "Database created successfully" . "<br>";
    } else {
        echo "Database already exist" . "<br>";
    }
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
    forum_name VARCHAR(255),
    forum_permissions VARCHAR(255)
)";

// Execute the query to create the forums table
if ($conn->query($sqlForums) === TRUE) {
    $defaultForumName = "General";
    $sqlCheckForum = "SELECT * FROM forums WHERE forum_name = '$defaultForumName'";
    $result = $conn->query($sqlCheckForum);

    if ($result->num_rows == 0) {
        $sqlInsertForum = "INSERT INTO forums ( forum_name, forum_permissions) VALUES ( '$defaultForumName', 'default_permissions')";
        if ($conn->query($sqlInsertForum) === TRUE) {
            echo "Default forum added successfully" . "<br>";
        } else {
            echo "Error adding default forum: " . $conn->error;
        }
    } else {
        echo "Default forum already exists" . "<br>";
    }
} else {
    echo "Error creating forums table: " . $conn->error;
}

$sqlCategory = "CREATE TABLE IF NOT EXISTS forum_categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(255) NOT NULL,
    category_order INT,
    forum_id INT,
    FOREIGN KEY (forum_id) REFERENCES forums(forum_id)
)";

$sqlSub_categories = "CREATE TABLE IF NOT EXISTS subcategories (
    subcategory_id INT AUTO_INCREMENT PRIMARY KEY,
    subcategory_name VARCHAR(255) NOT NULL,
    category_id INT NOT NULL,
    subcategory_order INT,
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
    creator_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    sticky TINYINT NOT NULL DEFAULT 0,
    subcategory_id INT NOT NULL,
    FOREIGN KEY (subcategory_id) REFERENCES subcategories(subcategory_id),
    FOREIGN KEY (creator_id) REFERENCES users(ID)
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
    // Check if admin user already exists
    $sqlCheckAdmin = "SELECT COUNT(*) as count FROM users WHERE username = 'admin'";
    $result = $conn->query($sqlCheckAdmin);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $adminCount = $row['count'];

        if ($adminCount == 0) {
            $password = 'password';
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
            $sqlInsertAdmin = "INSERT INTO users (
                username,
                password,
                userrole
            ) VALUES (
                'admin',
                '$hashedPassword',
                'administrator'
            )";

            if (mysqli_query($conn, $sqlInsertAdmin)) {
                echo "Admin user added successfully" . "<br>";
            } else {
                echo "Error adding admin user: " . mysqli_error($conn);
            }
        } else {
            echo "Admin user already exists" . "<br>";
        }
    } else {
        echo "Error checking for existing admin user: " . $conn->error . "<br>";
    }
} else {
    echo "Error creating Users table: " . $conn->error . "<br>";
}


if ($conn->query($sqlForums) === TRUE) {
    if (mysqli_affected_rows($conn) > 0) {
        echo "Forums table created successfully<br>";
    } else {
        echo "Forums Table already exist" . "<br>";
    }
} else {
    echo "Error creating Forums table: " . $conn->error . "<br>";
}

if ($conn->query($sqlCategory) === TRUE) {
    echo "Category table created successfully<br>";
} else {
    echo "Error creating Category table: " . $conn->error . "<br>";
}

if ($conn->query($sqlSub_categories) === TRUE) {
    echo "Sub Category table created successfully<br>";
} else {
    echo "Error creating Sub Category table: " . $conn->error . "<br>";
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
