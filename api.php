<?php
// CONFIGURATION: PASTE RDS ENDPOINT HERE
$db_host = ''; 
$db_user = '';
$db_pass = '';
$db_name = '';

header('Content-Type: application/json');

// Connect
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) { http_response_code(500); die(json_encode(["error" => "DB Error"])); }

// Auto-Schema
$conn->query("CREATE TABLE IF NOT EXISTS books (id INT AUTO_INCREMENT PRIMARY KEY, title VARCHAR(255), author VARCHAR(255), category VARCHAR(100), status VARCHAR(20) DEFAULT 'Available')");
$conn->query("CREATE TABLE IF NOT EXISTS students (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(255), email VARCHAR(255), dept VARCHAR(50))");

$action = $_POST['action'] ?? '';

if ($action === 'get_all') {
    $books = $conn->query("SELECT * FROM books ORDER BY id DESC")->fetch_all(MYSQLI_ASSOC);
    $students = $conn->query("SELECT * FROM students")->fetch_all(MYSQLI_ASSOC);
    echo json_encode(['books' => $books, 'students' => $students]);
}
elseif ($action === 'add_book') {
    $stmt = $conn->prepare("INSERT INTO books (title, author, category) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $_POST['title'], $_POST['author'], $_POST['category']);
    $stmt->execute();
    echo json_encode(['status' => 'success']);
}
elseif ($action === 'add_student') {
    $stmt = $conn->prepare("INSERT INTO students (name, email, dept) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $_POST['name'], $_POST['email'], $_POST['dept']);
    $stmt->execute();
    echo json_encode(['status' => 'success']);
}
elseif ($action === 'toggle_status') {
    $id = $_POST['id'];
    $current = $conn->query("SELECT status FROM books WHERE id=$id")->fetch_assoc()['status'];
    $new = ($current === 'Available') ? 'Issued' : 'Available';
    $conn->query("UPDATE books SET status='$new' WHERE id=$id");
    echo json_encode(['status' => 'success']);
}
?>