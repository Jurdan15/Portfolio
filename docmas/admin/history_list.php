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

// Initialize filters
$search = '';
$month = '';

if (isset($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
}
if (isset($_GET['month'])) {
    $month = $conn->real_escape_string($_GET['month']);
}

// Full list query
$query = "SELECT * FROM history WHERE 1";
if (!empty($search)) {
    $query .= " AND (author LIKE '%$search%' 
                OR borrow LIKE '%$search%' 
                OR document LIKE '%$search%' 
                OR tdate LIKE '%$search%')";
}
if (!empty($month)) {
    $query .= " AND MONTH(tdate) = $month";
}
$query .= " ORDER BY tdate ASC";
$getAll = $conn->query($query);

// Fetch returned timestamps from returned_books
$returnedTimestamps = [];
$returnedQuery = "SELECT history_id, timestamp FROM returned_books";
$returnedResult = $conn->query($returnedQuery);
if ($returnedResult->num_rows > 0) {
    while ($row = $returnedResult->fetch_assoc()) {
        $returnedTimestamps[$row['history_id']] = $row['timestamp'];
    }
}


$monthNames = [
    1 => "January", 2 => "February", 3 => "March", 4 => "April", 
    5 => "May", 6 => "June", 7 => "July", 8 => "August", 
    9 => "September", 10 => "October", 11 => "November", 12 => "December"
];
$selectedMonthName = $month ? $monthNames[$month] : "All Months";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrowed List</title>
    <link rel="stylesheet" href="bootstrap.css">
    <link rel="stylesheet" href="bootstrap.min.css">
    <link rel="stylesheet" href="bootstrap.rtl.css">
    <link rel="stylesheet" href="bootstrap.rtl.min.css">
    <style>
        #Title {
            font-size: 50px;
            font-family: "Times New Roman", Times, serif;
        }
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 9999;
            padding: 10px 0;
        }
        body {
            padding-top: 80px;
        }
        .navbar-brand {
            font-size: 36px;
            font-family: 'Georgia', serif;
        }
        .form-inline {
            display: flex;
            flex-direction: row;
            gap: 10px;
            justify-content: flex-end;
            align-items: center;
            flex-wrap: nowrap;
            margin-left: auto;
        }

        .form-inline input,
        .form-inline select,
        .form-inline button {
            margin: 5px 0;
        }
        @media print {
            .navbar, .form-inline, button {
                display: none !important;
            }
            body {
                padding-top: 0;
            }
        }
        #borrowedBooksTitle {
            font-size: 24px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-dark" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center gap-2" id="Title" href="#">
                <img src="logo.png" alt="Logo" width="50" height="50">
                DocMaS
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor02">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarColor02">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link active" href="admindashboard.php">Home</a></li>
                </ul>
                <form class="form-inline" method="GET" action="">
                    <button type="button" class="btn btn-success" onclick="window.print()">Print</button>
                    <input class="form-control" type="search" name="search" placeholder="Search" value="<?php echo htmlspecialchars($search); ?>">
                    <button class="btn btn-secondary" type="submit">Search</button>
                    
                </form>
            </div>
        </div>
    </nav>

    <div class="content"><br><br>
        <h3 id="borrowedBooksTitle">All Borrowed Books</h3>
        <table class="table table-hover">
            <thead>
                <tr class="table-dark">
                    <th>Title</th>
                    <th>Author</th>
                    <th>Student ID</th>
                    <th>Borrower</th>
                    <th>Contact Number</th>
                    <th>Purpose</th>
                    
                    <th>Time Borrowed</th>
                    <th>Time Returned</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $i = 0;
                if ($getAll->num_rows > 0): 
                    while ($row = $getAll->fetch_assoc()): ?>
                        <tr class="table-primary">
                            <td><?php echo htmlspecialchars($row['document']); ?></td>
                            <td><?php echo htmlspecialchars($row['author']); ?></td>
                            <td><?php echo htmlspecialchars($row['student_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['borrow']); ?></td>
                            <td><?php echo htmlspecialchars($row['contact_number']); ?></td>
                            <td><?php echo htmlspecialchars($row['purpose']); ?></td>
                            
                            <td><?php echo htmlspecialchars($row['tdate']); ?></td>
                            <td>
                                <?php 
                                    echo isset($returnedTimestamps[$row['id']]) 
                                        ? htmlspecialchars($returnedTimestamps[$row['id']]) 
                                        : 'Not returned';
                                ?>
                            </td>

                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="9">No records found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
