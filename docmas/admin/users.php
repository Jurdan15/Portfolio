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

// Handle delete request
if (isset($_GET['delete'])) {
    $deleteUsername = $conn->real_escape_string($_GET['delete']);
    $deleteQuery = "DELETE FROM users WHERE username = '$deleteUsername'";
    $conn->query($deleteQuery);
}

// Handle search input
$search = '';
if (isset($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']); // Sanitize input
}

// Fetch users with optional search filter
$query = "SELECT * FROM users";
if (!empty($search)) {
    $query .= " WHERE wholename LIKE '%$search%' OR username LIKE '%$search%'";
}
$getAll = $conn->query($query);
if (!$getAll) {
    die("Query failed: " . $conn->error); // Display error if query fails
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Users</title>
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
            width: 50%;
            margin: auto;
            margin-top: 20px;
        }
        h1 {
            text-align: center;
            margin-top: 20px;
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
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor02"
                aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarColor02">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="admindashboard.php">Home</a>
                </li>
            </ul>
            <form class="d-flex" method="GET" action="">
                <input class="form-control me-sm-2" type="search" name="search"
                       placeholder="Search username" value="<?php echo htmlspecialchars($search); ?>">
                <button class="btn btn-secondary my-2 my-sm-0" type="submit">Search</button>
            </form>
        </div>
    </div>
</nav>

<h1>List of Users</h1>
<table class="table table-hover" style="width:50%; position:absolute; left: 20%;">
    <thead>
    <tr class="table-dark">
        <th>Full Name</th>
        <th>Username</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    <?php if ($getAll->num_rows > 0): ?>
        <?php while ($fetch = $getAll->fetch_array()): ?>
            <tr class="table-primary">
                <td>
                    <a href="user_details.php?user=<?php echo urlencode($fetch['username']); ?>">
                        <?php echo htmlspecialchars($fetch['wholename']); ?>
                    </a>
                </td>
                <td><?php echo htmlspecialchars($fetch['username']); ?></td>
                <td>
                    <a href="?delete=<?php echo urlencode($fetch['username']); ?>"
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="3" class="text-center">No users found.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
