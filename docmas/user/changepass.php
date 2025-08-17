<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    echo "<script>alert('You must log in first. Redirecting to login page.');</script>";
    echo "<script>window.location.href = 'landing.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="bootstrap (1).css">
    <link rel="stylesheet" href="bootstrap.min (1).css">
    <link rel="stylesheet" href="bootstrap.rtl (1).css">
    <link rel="stylesheet" href="bootstrap.rtl.min (1).css">
    <style>
        #Title {
            font-size: 50px;
            font-family: "Times New Roman", Times, serif;
        }
        #exampleInputUsername, #exampleInputPassword {
            width: 30%;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-dark navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" id="Title" href="#">BOOK RECORDS</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarColor02">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.html">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Records</a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="Book_List.php">All BOOKS</a>
                            <a class="dropdown-item" href="borrowed_list.php">BORROWED BOOKS</a>
                            <a class="dropdown-item" href="history_list.php">History</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="changepass.php">Change Password</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="landing.php">Logout</a>
                    </li>
                </ul>
                <form class="d-flex">
                    <input class="form-control me-sm-2" type="search" placeholder="Search">
                    <button class="btn btn-secondary my-2 my-sm-0" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>
    
    <center>
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Database connection
            $servername = "localhost";
            $db_username = "root";
            $db_password = "";
            $dbname = "barcode_scanner_db";

            $conn = new mysqli($servername, $db_username, $db_password, $dbname);

            if ($conn->connect_error) {
                die("Database connection failed: " . $conn->connect_error);
            }

            // Retrieve form data
            $username = $_SESSION['username']; // Get username from session
            $existingPassword = $_POST['existing_password'];
            $newPassword = $_POST['new_password'];

            // Verify existing password
            $query = "SELECT * FROM users WHERE username = ? AND password = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $username, $existingPassword);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                // Update password
                $updateQuery = "UPDATE users SET password = ? WHERE username = ?";
                $updateStmt = $conn->prepare($updateQuery);
                $updateStmt->bind_param("ss", $newPassword, $username);

                if ($updateStmt->execute()) {
                    echo "<div class='alert alert-success'>Password updated successfully!</div>";
                } else {
                    echo "<div class='alert alert-danger'>Error updating password. Please try again.</div>";
                }

                $updateStmt->close();
            } else {
                echo "<div class='alert alert-danger'>Existing password is incorrect.</div>";
            }

            $stmt->close();
            $conn->close();
        }
        ?>
        <form method="POST">
            <h1>Change Password</h1>
            <div>
                <label for="exampleInputUsername" class="form-label mt-4">Existing Password</label>
                <input type="password" class="form-control" name="existing_password" id="exampleInputUsername" placeholder="Enter existing password" required>
            </div>
            <div>
                <label for="exampleInputPassword" class="form-label mt-4">New Password</label>
                <input type="password" class="form-control" name="new_password" id="exampleInputPassword" placeholder="New password" autocomplete="off" required>
            </div>
            <br>
            <button type="submit" class="btn btn-outline-success">Save</button>
        </form>
    </center>
    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
