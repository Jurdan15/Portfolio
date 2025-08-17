<?php
if (
    isset($_GET['BarcodeScanner1']) &&
    isset($_GET['Borrower']) &&
    isset($_GET['scan']) &&
    isset($_GET['college']) &&
    isset($_GET['student_id']) &&
    isset($_GET['contact']) &&
    isset($_GET['purpose'])
) {
    $barcode = $_GET['BarcodeScanner1'];
    $borrower = $_GET['Borrower'];
    $scanner = $_GET['scan'];
    $col = $_GET['college'];
    $stud_id = $_GET['student_id'];
    $contact = $_GET['contact'];
    $purpose = $_GET['purpose'];

    // Database connection
    $conn = new mysqli("localhost", "root", "", "docmas");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the barcode exists in the barcodes table
    $getAll = $conn->query("SELECT * FROM documents WHERE document = '$barcode'");
    if ($getAll->num_rows > 0) {
        $fetch = $getAll->fetch_array();

        // Get the most recent ID from History where bcode matches
        $getLatest = $conn->query("SELECT id FROM History WHERE document = '$barcode' ORDER BY id DESC LIMIT 1");

        if ($getLatest->num_rows > 0) {
            $latestId = $getLatest->fetch_assoc()['id'];

            // Update only the most recent record
            $updateHistory = "UPDATE History 
                              SET borrow = '$borrower',
                                  author = '$fetch[2]',
                                  scanner = '$scanner',
                                  college = '$col',
                                  student_id = '$stud_id',
                                  contact_number = '$contact',
                                  purpose = '$purpose'
                              WHERE id = '$latestId'";

            // Update book status
            $updateStatus = "UPDATE documents 
                             SET Book_Status = 'Borrowed' 
                             WHERE document = '$barcode' AND Book_Status != 'Borrowed'";

            if ($conn->query($updateHistory) === TRUE && $conn->query($updateStatus) === TRUE) {
                echo "Saved Successfully";
            } else {
                echo "Error saving data: " . $conn->error;
            }
        } else {
            echo "No matching history entry found to update.";
        }
    } else {
        echo "No matching barcode found in the database.";
    }

    $conn->close();
} else {
    echo "No barcode data received.";
}
?>
