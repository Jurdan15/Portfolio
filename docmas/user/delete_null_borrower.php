<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "docmas";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to delete rows where the book_title is empty or NULL
$deleteEmptyTitleSql = "DELETE FROM borrowed_books WHERE document = '' OR borrower = ''";
$conn -> query("DELETE FROM history WHERE borrow = '' OR borrow IS NULL");
if ($conn->query($deleteEmptyTitleSql) === TRUE) {
    echo "Borrowing Book Canceled.";
} else {
    echo "Error deleting rows: " . $conn->error;
}

$conn->close();
?>
