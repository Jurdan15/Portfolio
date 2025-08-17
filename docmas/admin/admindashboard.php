<?php
session_start();
if (!isset($_SESSION['username'])) {
    echo "<script>alert('You must log in first.');</script>";
    echo "<script>window.location.href = '../index.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - DocMaS</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f5f5f5; /* Light off-white background */
      color: #111;
    }

    nav {
      background-color: #3a3f44; /* Slate Gray */
      padding: 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
    }


    .logo-title {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .logo-title img {
      width: 60px;
    }

    .logo-title h1 {
      font-size: 30px;
      color: white;
      text-shadow: 1px 1px 4px #000;
    }

    .nav-links {
      display: flex;
      gap: 20px;
    }

    .nav-links a {
      text-decoration: none;
      color: #00f2ff;
      font-weight: bold;
      transition: color 0.3s;
    }

    .nav-links a:hover {
      color: lime;
    }

    .container {
      padding: 40px;
      text-align: center;
    }

    .container h2 {
      font-size: 36px;
      margin-bottom: 20px;
      color: #222;
      text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
    }

    .btn {
      background: transparent;
      border: 2px solid #00f2ff;
      color: #111;
      padding: 10px 25px;
      border-radius: 25px;
      cursor: pointer;
      transition: 0.3s;
      margin: 10px;
      text-decoration: none;
      display: inline-block;
      font-weight: bold;
    }

    .btn:hover {
      background: #00f2ff;
      color: #111;
    }
  </style>
</head>
<body>

  <nav>
    <div class="logo-title">
      <img src="logo.png" alt="CICS Logo">
      <h1>Document Management System (DocMaS)</h1>
    </div>
    <div class="nav-links">
      <a href="logout.php">Logout</a>
    </div>
  </nav>

  <div class="container">
    <h2>Welcome to the Admin Dashboard</h2>
    <a href="Book_List.php" class="btn">View All Documents</a>
    <a href="borrowed_list.php" class="btn">Borrowed Documents</a>
    <a href="history_list.php" class="btn">History</a>
    <a href="users.php" class="btn">Manage Users</a>
    <a href="adduser.php" class="btn">Create New User</a>
  </div>

</body>
</html>
