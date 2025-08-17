<?php
if (isset($_GET['BarcodeScanner1']) && isset($_GET['author']) && isset($_GET['scan'])) {
    $barcode = $_GET['BarcodeScanner1'];
    $Author = $_GET['author'];
    $scanner = $_GET['scan'];

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "docmas";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update the book title for the given barcode
    $updateSql = "UPDATE documents SET author = '$Author',scanner='$scanner' WHERE document = '$barcode'";
    if ($conn->query($updateSql) === TRUE) {
        echo "Book title $barcode saved";
    } else {
        echo "Error updating book title: " . $conn->error;
    }

    $conn->close();
} else {
    echo "No book title or barcode received.";
}
?>
