<?php
if (isset($_GET['BarcodeScanner1']) && isset($_GET['Borrower']) && isset($_GET['scan']) && isset($_GET['college']) && isset($_GET['student_id']) && isset($_GET['contact']) && isset($_GET['purpose'])) {
    $barcode = $_GET['BarcodeScanner1'];
    $borrower = $_GET['Borrower'];
    $scanner = $_GET['scan'];
    $col = $_GET['college'];
    $stud_id = $_GET['student_id'];
    $contact = $_GET['contact'];
    $purpose = $_GET['purpose'];

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "docmas";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert barcode into borrowed_books table
    $getAll = $conn -> query ("Select * from documents where document = '$barcode'");
    while($fetch = $getAll -> fetch_array()){
    $insertBorrowedSql = "UPDATE borrowed_books SET borrower = '$borrower',author='$fetch[2]',scanner='$scanner',college='$col',student_id='$stud_id', contact_number='$contact', purpose='$purpose'  WHERE document = '$barcode'";
    $conn -> query("UPDATE documents SET Book_Status = 'Borrowed' where document = '$barcode'");
    //$conn -> query("INSERT INTO returned_books (scanner) VALUES ('$scanner')");
    if ($conn->query($insertBorrowedSql) === TRUE) {
        echo "Saved Succesfully";
    } else {
        echo "Error saving book in borrowed_books: " . $conn->error;
    }}

    $conn->close();
} else {
    echo "No barcode data received.";
}
?>
