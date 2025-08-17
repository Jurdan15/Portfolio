<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="bootstrap.css">
    <link rel="stylesheet" href="bootstrap.min.css">
    <link rel="stylesheet" href="bootstrap.rtl.css">
    <link rel="stylesheet" href="bootstrap.rtl.min.css">
    <style>
        #Title {
            font-size: 50px;
            font-family: "Times New Roman", Times, serif;
        }
        #exampleInputEmail1, #exampleInputPassword1, #exampleWholeName {
            width: 30%;
        }
        #exampleSelect1 {
            width: 20%;
        }
        .form-container {
            background-color:rgb(97, 85, 85);
            padding: 30px;
            border-radius: 10px;
            width: 60%;
            margin: 30px auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
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
                <form class="d-flex">
                    <input class="form-control me-sm-2" type="search" placeholder="Search">
                    <button class="btn btn-secondary my-2 my-sm-0" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="form-container">
        <form method="POST">
            <h1 class="text-center mb-4">Create New User</h1>
            <div class="mb-3 text-center">
                <label for="exampleWholeName" class="form-label mt-4">Full Name</label>
                <input type="text" class="form-control mx-auto" name="wholename" id="exampleWholeName" placeholder="Enter full name" required>
            </div>
            <div class="mb-3 text-center">
                <label for="exampleInputEmail1" class="form-label mt-4">Username</label>
                <input type="text" class="form-control mx-auto" name="username" id="exampleInputEmail1" placeholder="Enter username" required>
            </div>
            <div class="mb-3 text-center">
                <label for="exampleInputPassword1" class="form-label mt-4">Password</label>
                <input type="password" class="form-control mx-auto" name="password" id="exampleInputPassword1" placeholder="Password" autocomplete="off" required>
            </div>
            <div class="mb-3 text-center">
                <label for="exampleSelect1" class="form-label mt-4">Role</label>
                <select class="form-select mx-auto" name="role" id="exampleSelect1">
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select>
            </div>
            <div class="text-center">
                <button name="submit" type="submit" class="btn btn-outline-success">Save</button>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>

<?php
if (isset($_POST['submit'])) {
    // Retrieve form data
    $wholename = $_POST['wholename'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Database connection
    $servername = "localhost";
    $db_username = "root";
    $db_password = "";
    $dbname = "docmas";

    $conn = new mysqli($servername, $db_username, $db_password, $dbname);

    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    // Insert query
    $query = "INSERT INTO users (wholename, username, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $wholename, $username, $password, $role);

    if ($stmt->execute()) {
        echo "<script>alert('User added successfully!');</script>";
    } else {
        echo "<script>alert('Error adding user: " . $conn->error . "');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
