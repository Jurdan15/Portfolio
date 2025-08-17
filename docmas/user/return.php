<?php

if (isset($_GET['BarcodeScanner1'])) {
    $barcode = $_GET['BarcodeScanner1'];

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "docmas";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Sanitize barcode to prevent SQL injection
    $barcode = $conn->real_escape_string($barcode);

    // Check if barcode exists in borrowed_books
    $checkBorrowedSql = "SELECT * FROM borrowed_books WHERE document = '$barcode'";
    $borrowedResult = $conn->query($checkBorrowedSql);

    if ($borrowedResult->num_rows > 0) {
        // If barcode exists in borrowed_books, delete it
        $deleteBorrowedSql = "DELETE FROM borrowed_books WHERE document = '$barcode'";
        $conn -> query("UPDATE documents SET Book_Status = 'Available' where document = '$barcode'");
        if ($conn->query($deleteBorrowedSql) === TRUE) {
            echo "Book is returned.";
        } else {
            echo "Error returning the book: " . $conn->error;
        }
    } else {
        echo "No book found with the given barcode.";
    }
    
    // Close the connection
    $conn->close();
} else {
    echo "No barcode data received.";
}
?>
