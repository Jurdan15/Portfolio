<?php
if (isset($_GET['BarcodeScanner1']) && isset($_GET['scan'])) {
    $barcode = $_GET['BarcodeScanner1'];
    $scanner = $_GET['scan'];

    // Database connection
    $conn = new mysqli("localhost", "root", "", "docmas");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $barcode = $conn->real_escape_string($barcode);
    $scanner = $conn->real_escape_string($scanner);
    $currentTimestamp = date('Y-m-d H:i:s');

    // Find latest matching history record (not yet returned)
    $historySql = "
        SELECT h.id 
        FROM history h 
        LEFT JOIN returned_books r ON h.id = r.history_id 
        WHERE h.document = '$barcode' AND r.id IS NULL 
        ORDER BY h.tdate DESC 
        LIMIT 1";

    $historyResult = $conn->query($historySql);

    if ($historyResult && $historyResult->num_rows > 0) {
        $row = $historyResult->fetch_assoc();
        $historyId = $row['id'];

        $insertSql = "
            INSERT INTO returned_books (document, scanner, timestamp, history_id) 
            VALUES ('$barcode', '$scanner', '$currentTimestamp', $historyId)";
        
        if ($conn->query($insertSql) === TRUE) {
            echo "Book return saved successfully.";
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "No unmatched borrow record found for this document.";
    }

    $conn->close();
} else {
    echo "No barcode data received.";
}
?>
