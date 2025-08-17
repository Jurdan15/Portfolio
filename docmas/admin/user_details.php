<?php
session_start(); // Start session

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "docmas";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the username from the session or URL
if (isset($_GET['user'])) {
    $_SESSION['selected_user'] = $_GET['user']; // Store in session
}
$username = isset($_SESSION['selected_user']) ? $_SESSION['selected_user'] : null;

if ($username) {
    // Handle search input
    $search = '';
    if (isset($_GET['search'])) {
        $search = $conn->real_escape_string($_GET['search']); // Sanitize input
    }

    // Fetch user details
    $querySavedBooks = "SELECT * FROM barcodes WHERE scanner = '$username'";
    $queryBorrowedBooks = "SELECT * FROM borrowed_books WHERE scanner = '$username'";
    $queryHistory = "SELECT * FROM history WHERE scanner = '$username'";

    // Apply search filter if search term is provided
    if (!empty($search)) {
        $querySavedBooks .= " AND (book_title LIKE '%$search%' OR author LIKE '%$search%' OR Book_Status LIKE '%$search%' OR scanner LIKE '%$search%' OR barcode LIKE '%$search%')";
        $queryBorrowedBooks .= " AND (Book LIKE '%$search%' OR author LIKE '%$search%' OR borrower LIKE '%$search%' OR borrow_date LIKE '%$search%' OR barcode LIKE '%$search%')";
        $queryHistory .= " AND (hbook LIKE '%$search%' 
                OR author LIKE '%$search%' 
                OR borrow LIKE '%$search%' 
                OR bcode LIKE '%$search%' 
                OR tdate LIKE '%$search%')";
    }

    $getSavedBooks = $conn->query($querySavedBooks);
    $getBorrowedBooks = $conn->query($queryBorrowedBooks);
    $getHistory = $conn->query($queryHistory);

} else {
    echo "<script>alert('No user selected.');</script>";
    echo "<script>window.location.href = 'users.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>
    <link rel="stylesheet" href="bootstrap.css">
    <link rel="stylesheet" href="bootstrap.min.css">
    <link rel="stylesheet" href="bootstrap.rtl.css">
    <link rel="stylesheet" href="bootstrap.rtl.min.css">
    <style>
        #Title {
            font-size: 50px;
            font-family: "Times New Roman", Times, serif;
        }
        table {
            width: 90%;
            margin: auto;
        }
        .section-title {
            text-align: center;
            margin: 20px 0;
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
                <form class="d-flex" method="GET" action="">
                    <input type="hidden" name="user" value="<?php echo htmlspecialchars($username); ?>">
                    <input class="form-control me-sm-2" type="search" name="search" placeholder="Search" value="<?php echo htmlspecialchars($search); ?>">
                    <button class="btn btn-secondary my-2 my-sm-0" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="section-title">Books Saved</h1>
        <table class="table table-hover">
            <thead>
                <tr class="table-dark">
                    <th>Title</th>
                    <th>Author</th>
                    <th>Book Status</th>
                    <th>Scanner</th>
                    <th>Barcode Number</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($getSavedBooks->num_rows > 0): ?>
                    <?php while ($row = $getSavedBooks->fetch_array()): ?>
                        <tr class="table-primary">
                            <td><?php echo htmlspecialchars($row[2]); ?></td>
                            <td><?php echo htmlspecialchars($row[3]); ?></td>
                            <td><?php echo htmlspecialchars($row[4]); ?></td>
                            <td><?php echo htmlspecialchars($row[5]); ?></td>
                            <td><?php echo htmlspecialchars($row[1]); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center">No records found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h1 class="section-title">Books Currently Borrowed</h1>
        <table class="table table-hover">
            <thead>
                <tr class="table-dark">
                    <th>Title</th>
                    <th>Author</th>
                    <th>Borrower</th>
                    <th>Barcode Number</th>
                    <th>Time Borrowed</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($getBorrowedBooks->num_rows > 0): ?>
                    <?php while ($row = $getBorrowedBooks->fetch_assoc()): ?>
                        <tr class="table-primary">
                            <td><?php echo htmlspecialchars($row['Book']); ?></td>
                            <td><?php echo htmlspecialchars($row['author']); ?></td>
                            <td><?php echo htmlspecialchars($row['borrower']); ?></td>
                            <td><?php echo htmlspecialchars($row['barcode']); ?></td>
                            <td><?php echo htmlspecialchars($row['borrow_date']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center">No records found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h1 class="section-title">History</h1>
        <table class="table table-hover">
            <thead>
                <tr class="table-dark">
                    <th>Title</th>
                    <th>Author</th>
                    <th>Borrower</th>
                    <th>Barcode Number</th>
                    <th>Time Borrowed</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($getHistory->num_rows > 0): ?>
                    <?php while ($row = $getHistory->fetch_assoc()): ?>
                        <tr class="table-primary">
                            <td><?php echo htmlspecialchars($row['hbook']); ?></td>
                            <td><?php echo htmlspecialchars($row['author']); ?></td>
                            <td><?php echo htmlspecialchars($row['borrow']); ?></td>
                            <td><?php echo htmlspecialchars($row['bcode']); ?></td>
                            <td><?php echo htmlspecialchars($row['tdate']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center">No records found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
