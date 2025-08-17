<?php

if (isset($_GET['BarcodeScanner1'])) {
    $barcode = $_GET['BarcodeScanner1'];

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "docmas";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if barcode exists in the borrowed_books table
    $checkBorrowedSql = "SELECT * FROM borrowed_books WHERE document = '$barcode'";
    $borrowedResult = $conn->query($checkBorrowedSql);

    if ($borrowedResult->num_rows > 0) {
        // If barcode exists in borrowed_books, return the book and delete it
       // $deleteBorrowedSql = "DELETE FROM borrowed_books WHERE barcode = '$barcode'";

        //if ($conn->query($deleteBorrowedSql) === TRUE) {
        //    echo "Book is returned.";
       // } else {
       //     echo "Error returning the book: " . $conn->error;
       // }
        echo "Book is returned.";
    } else {
        // Check if barcode exists in the barcodes table
        $checkSql = "SELECT * FROM documents WHERE document = '$barcode'";
        $result = $conn->query($checkSql);

        if ($result->num_rows > 0) {
            // If barcode exists, save it to borrowed_books table
           $insertBorrowedSql = "INSERT INTO borrowed_books (document) VALUES ('$barcode')";
           $conn -> query("INSERT INTO History (document) VALUES ('$barcode')");
            if ($conn->query($insertBorrowedSql) === TRUE) {
                echo "Book";
              
            } else {
                echo "Error saving book in borrowed_books: " . $conn->error;
            }
        } else {
            // If barcode does not exist, save it in barcodes table
            $insertSql = "INSERT INTO documents (document,Book_Status) VALUES ('$barcode','Available')";
            if ($conn->query($insertSql) === TRUE) {
                echo "Book saved.";
            } else {
                echo "Error: " . $insertSql . "<br>" . $conn->error;
            }
        }
    }

    $conn->close();
} else {
    echo "No barcode data received.";
}
?>
