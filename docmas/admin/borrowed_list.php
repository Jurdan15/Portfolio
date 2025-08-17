<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    echo "<script>alert('You must log in first.');</script>";
    echo "<script>window.location.href = '../index.php';</script>";
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "docmas";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle return logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['return_document'])) {
    $returnDocument = $conn->real_escape_string($_POST['return_document']);
    $currentTimestamp = date('Y-m-d H:i:s');

    // Get history ID
    $historyQuery = "SELECT id FROM history WHERE document = '$returnDocument' ORDER BY id DESC LIMIT 1";
    $historyResult = $conn->query($historyQuery);
    $historyId = ($historyResult && $historyResult->num_rows > 0) ? $historyResult->fetch_assoc()['id'] : 'NULL';

    // Insert into returned_books
    $conn->query("INSERT INTO returned_books (document, scanner, timestamp, history_id) 
                  VALUES ('$returnDocument', 'admin', '$currentTimestamp', $historyId)");

    // Delete from borrowed_books
    $conn->query("DELETE FROM borrowed_books WHERE document = '$returnDocument'");

    // Update document status
    $conn->query("UPDATE documents SET Book_Status = 'Available' WHERE document = '$returnDocument'");

    echo "<script>alert('Document returned successfully.'); window.location.href = window.location.href;</script>";
    exit();
}

$search = '';
$monthFilter = '';

if (isset($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
}

if (isset($_GET['month'])) {
    $monthFilter = $_GET['month'];
}

$query = "SELECT * FROM borrowed_books";
$conditions = [];

if (!empty($search)) {
    $conditions[] = "(author LIKE '%$search%' OR scanner LIKE '%$search%' OR borrower LIKE '%$search%' OR borrow_date LIKE '%$search%' OR document LIKE '%$search%')";
}

if (!empty($monthFilter)) {
    $conditions[] = "MONTH(borrow_date) = $monthFilter";
}

if (count($conditions) > 0) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}
$query .= " ORDER BY borrow_date ASC";

$getAll = $conn->query($query);
if (!$getAll) {
    die("Query failed: " . $conn->error);
}

$heading = "Currently Borrowed Books";
if (!empty($monthFilter)) {
    $heading = date('F', mktime(0, 0, 0, $monthFilter, 10)) . " Borrowed Books";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Currently Borrowed Books</title>
    <link rel="stylesheet" href="bootstrap.css">
    <link rel="stylesheet" href="bootstrap.min.css">
    <link rel="stylesheet" href="bootstrap.rtl.css">
    <link rel="stylesheet" href="bootstrap.rtl.min.css">
    <style>
        #Title {
            font-size: 50px;
            font-family: "Times New Roman", Times, serif;
        }
        body {
            padding-top: 40px;
        }
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 9999;
            padding: 10px 0;
        }
        .navbar-brand {
            font-size: 36px;
            font-family: 'Georgia', serif;
        }
        .navbar-nav .nav-item {
            margin-left: 20px;
        }
        .navbar-nav .nav-link {
            font-size: 18px;
            padding: 8px 15px;
        }
        .navbar-nav .nav-link:hover {
            background-color: #555;
            border-radius: 5px;
        }
        .filter-container {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .filter-container select,
        .filter-container input {
            min-width: 200px;
        }
        .content {
            margin-top: 80px; 
        }
        .table-container {
            width: 100%;
            overflow-x: auto;
        }
        table {
            width: 100% !important;
            border-collapse: collapse;
        }
        th, td {
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-dark navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center gap-2" id="Title" href="#">
                <img src="logo.png" alt="Logo" width="50" height="50">
                DocMaS
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarColor02">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="admindashboard.php">Home</a>
                    </li>
                </ul>
                <div class="filter-container">
                    <form method="GET" action="" style="display: flex; gap: 10px;">
                        <input class="form-control" type="search" name="search" placeholder="Search" value="<?php echo htmlspecialchars($search); ?>">
                        <button class="btn btn-secondary" type="submit">Search</button>
                    </form>
                    
                </div>
            </div>
        </div>
    </nav>
    <div class="container-fluid content">
        <h3><?php echo $heading; ?></h3>
        <div class="table-container">
            <table class="table table-hover">
                <thead>
                    <tr class="table-dark">
                        <th>Title</th>
                        <th>Author</th>
                        <th>Student ID</th>
                        <th>Borrower</th>
                        <th>Contact Number</th>
                        <th>Purpose</th>
                        <th>Person in Charge</th>
                        <th>Time Borrowed</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($getAll->num_rows > 0): ?>
                        <?php while ($fetch = $getAll->fetch_array()): ?>
                            <tr class="table-primary">
                                <td><?php echo htmlspecialchars($fetch['document']); ?></td>
                                <td><?php echo htmlspecialchars($fetch['author']); ?></td>
                                <td><?php echo htmlspecialchars($fetch['student_id']); ?></td>
                                <td><?php echo htmlspecialchars($fetch['borrower']); ?></td>
                                <td><?php echo htmlspecialchars($fetch['contact_number']); ?></td>
                                <td><?php echo htmlspecialchars($fetch['purpose']); ?></td>
                                <td><?php echo htmlspecialchars($fetch['scanner']); ?></td>
                                <td><?php echo htmlspecialchars($fetch['borrow_date']); ?></td>
                                <td>
                                    <form method="POST" onsubmit="return confirm('Are you sure you want to return this document?');">
                                        <input type="hidden" name="return_document" value="<?php echo htmlspecialchars($fetch['document']); ?>">
                                        <button type="submit" class="btn btn-success btn-sm">Return</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="9" class="text-center">No records found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
