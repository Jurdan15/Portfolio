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

    // Query to fetch book details (title and author) based on barcode
    $query = "SELECT document, author FROM documents WHERE document = '$barcode'";

    // Execute query and check for errors
    $result = $conn->query($query);
    
    // Check if query was successful
    if ($result === false) {
        // Display the MySQL error
        echo "Error with query: " . $conn->error;
    } else {
        // Check if there are results
        if ($result->num_rows > 0) {
            // Fetch the book details
            $row = $result->fetch_assoc();
            $titles = $row['document'];
            $authors = $row['author'];
            echo "Document Title: " . $titles . " ". "<br>";
            echo "-- Author: " . $authors . "<br>";
        } else {
            echo "No book found with the provided barcode.";
        }
    }

    $conn->close();
} else {
    echo "No barcode data received.";
}
?>
