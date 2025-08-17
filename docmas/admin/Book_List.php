<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    echo "<script>alert('You must log in first.');</script>";
    echo "<script>window.location.href = '../user/landing.php';</script>";
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

// Handle author update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_author'])) {
    $new_author = $conn->real_escape_string($_POST['author']);
    $document = $conn->real_escape_string($_POST['document']);

    $updateDoc = "UPDATE documents SET author = '$new_author' WHERE document = '$document'";
    $updateBorrowed = "UPDATE borrowed_books SET author = '$new_author' WHERE document = '$document'";
    $updateHistory = "UPDATE history SET author = '$new_author' WHERE document = '$document'";

    if (
        $conn->query($updateDoc) &&
        $conn->query($updateBorrowed) &&
        $conn->query($updateHistory)
    ) {
        echo "<script>alert('Author updated successfully.');</script>";
        echo "<script>window.location.href = window.location.href;</script>";
        exit();
    } else {
        echo "<script>alert('Update failed: " . $conn->error . "');</script>";
    }
}

// Handle document deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_document'])) {
    $docToDelete = $conn->real_escape_string($_POST['document']);

    $deleteDoc = "DELETE FROM documents WHERE document = '$docToDelete'";
    $deleteBorrowed = "DELETE FROM borrowed_books WHERE document = '$docToDelete'";

    if ($conn->query($deleteDoc) && $conn->query($deleteBorrowed)) {
        echo "<script>alert('Document deleted successfully.');</script>";
        echo "<script>window.location.href = window.location.href;</script>";
        exit();
    } else {
        echo "<script>alert('Deletion failed: " . $conn->error . "');</script>";
    }
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

$query = "SELECT * FROM documents WHERE 1";

if (!empty($search)) {
    $query .= " AND (book_title LIKE '%$search%' OR author LIKE '%$search%' OR Book_Status LIKE '%$search%' OR scanner LIKE '%$search%' OR document LIKE '%$search%')";
}

if (!empty($month)) {
    $query .= " AND MONTH(timestamp) = $month";
}

$query .= " ORDER BY document ASC";

$getAll = $conn->query($query);

if (!$getAll) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ALL DOCS</title>
    <link rel="stylesheet" href="bootstrap.css">
    <link rel="stylesheet" href="bootstrap.min.css">
    <style>
        #Title {
            font-size: 50px;
            font-family: "Times New Roman", Times, serif;
        }
        .month-title {
            font-size: 24px;
            font-weight: bold;
            margin-top: 20px;
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
        .content {
            padding-top: 20px;
        }
        .form-inline {
            display: flex;
            align-items: center;
        }
        .form-inline input[type="text"] {
            width: 150px;
        }
        .form-inline button {
            margin-left: 5px;
        }
        .nav-link.active {
            margin-left: 300px;
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
                    <li class="nav-item">
                        <a class="nav-link active" href="admindashboard.php">Home</a>
                    </li>
                </ul>
                <form method="GET" class="d-flex">
                    <input class="form-control me-sm-2" type="search" name="search" placeholder="Search" value="<?php echo htmlspecialchars($search); ?>">
                    <button class="btn btn-secondary my-2 my-sm-0 me-3" type="submit">Search</button>
                    
                </form>
            </div>
        </div>
    </nav>

    <div class="content">
        <?php if (!empty($month)) : ?>
            <div class="month-title">
                <h3>Books Saved on the Month of: <?php echo date('F', mktime(0, 0, 0, $month, 10)); ?></h3>
            </div>
        <?php endif; ?>
        <br>
        <h3>All Documents</h3>
        <table class="table table-hover">
            <thead>
                <tr class="table-dark">
                    <th scope="row">Title</th>
                    <th>Author</th>
                    <th>Book Status</th>
                    <th>Person in Charge</th>
                    <th>Timestamp</th>
                    <th>Action</th>
                </tr>         
            </thead>
            <?php if ($getAll->num_rows > 0) : ?>
                <?php while ($fetch = $getAll->fetch_assoc()) : ?>
                    <tbody>
                        <tr class="table-primary">
                            <form method="POST" class="form-inline">
                                <input type="hidden" name="document" value="<?php echo htmlspecialchars($fetch['document']); ?>">
                                <th scope="row"><?php echo htmlspecialchars($fetch['document']); ?></th>
                                <td>
                                    <input type="text" name="author" value="<?php echo htmlspecialchars($fetch['author']); ?>" class="form-control">
                                </td>
                                <td><?php echo htmlspecialchars($fetch['Book_Status']); ?></td>
                                <td><?php echo htmlspecialchars($fetch['scanner']); ?></td>
                                <td><?php echo htmlspecialchars($fetch['timestamp']); ?></td>
                                <td class="d-flex gap-2">
                                    <button type="submit" name="update_author" class="btn btn-success btn-sm">Update</button>
                                    <button type="submit" name="delete_document" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this document?');">Delete</button>
                                </td>
                            </form>
                        </tr>
                    </tbody>
                <?php endwhile; ?>
            <?php else : ?>
                <tbody>
                    <tr>
                        <td colspan="6" class="text-center">No records found</td>
                    </tr>
                </tbody>
            <?php endif; ?>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
