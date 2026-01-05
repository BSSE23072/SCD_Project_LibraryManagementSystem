<?php
// --- CONFIGURATION ---
$db_host = ''; // <--- PASTE YOUR RDS ENDPOINT
$db_user = '';
$db_pass = '';
$db_name = '';

// Connect
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) { die("Connection Failed: " . $conn->connect_error); }

// 1. WIPE OLD DATA (Reset)
// We turn off foreign key checks briefly to allow force-cleaning
$conn->query("SET FOREIGN_KEY_CHECKS = 0");
$conn->query("TRUNCATE TABLE books");
$conn->query("TRUNCATE TABLE students");
$conn->query("SET FOREIGN_KEY_CHECKS = 1");

echo "<h3>1. Old Data Wiped... ✅</h3>";

// 2. INSERT BOOKS
$sql_books = "INSERT INTO books (title, author, category, status) VALUES 
('Clean Code', 'Robert C. Martin', 'Computer Science', 'Available'),
('The Pragmatic Programmer', 'Andrew Hunt', 'Computer Science', 'Issued'),
('Dune', 'Frank Herbert', 'Literature', 'Available'),
('Atomic Habits', 'James Clear', 'Self Help', 'Available'),
('Introduction to Algorithms', 'Thomas H. Cormen', 'Computer Science', 'Issued')";

if ($conn->query($sql_books) === TRUE) {
    echo "<h3>2. Books Inserted... ✅</h3>";
} else {
    echo "Error inserting books: " . $conn->error;
}

// 3. INSERT STUDENTS
$sql_students = "INSERT INTO students (name, email, dept) VALUES 
('Alice Johnson', 'alice@uni.edu', 'CS'),
('Bob Smith', 'bob@uni.edu', 'Physics'),
('Charlie Brown', 'charlie@uni.edu', 'Math')";

if ($conn->query($sql_students) === TRUE) {
    echo "<h3>3. Students Inserted... ✅</h3>";
} else {
    echo "Error inserting students: " . $conn->error;
}

echo "<h1>🚀 Database Seeding Complete! <a href='index.php'>Go to Dashboard</a></h1>";
?>