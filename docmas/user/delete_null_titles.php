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
$deleteEmptyTitleSql = "DELETE FROM documents WHERE author = '' OR author IS NULL";

if ($conn->query($deleteEmptyTitleSql) === TRUE) {
    echo "Saving Barcode Cancelled!!.";
} else {
    echo "Error deleting rows: " . $conn->error;
}

$conn->close();
?>
