<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>DocMaS Login</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      height: 100vh;
      background-color: #f5f5f5; /* Soft off-white */
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .header {
      text-align: center;
      margin-bottom: 20px;
    }

    .logo-container {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 20px; /* Adjust spacing between logos */
      margin-bottom: 10px;
    }

    .logo-container img {
      height: 70px;
      object-fit: contain;
    }


    .header h2 {
      color: #333;
      font-size: 22px;
      text-shadow: 1px 1px 3px rgba(0,0,0,0.2);
    }

    .login-box {
      background-color: #3a3f44; /* Slate Gray */
      padding: 40px;
      border-radius: 15px;
      text-align: center;
      box-shadow: 0 0 20px rgba(0,0,0,0.3);
      width: 360px;
    }

    .login-box h1 {
      color: #fff;
      margin-bottom: 10px;
    }

    .login-box p {
      color: #ccc;
      font-size: 14px;
      margin-bottom: 30px;
    }

    .login-box input[type="text"],
    .login-box input[type="password"] {
      background: transparent;
      border: 2px solid #00f2ff;
      border-radius: 25px;
      width: 100%;
      padding: 10px 20px;
      margin-bottom: 20px;
      color: white;
      outline: none;
    }

    .login-box input::placeholder {
      color: #aaa;
    }

    .login-box button {
      background: transparent;
      border: 2px solid lime;
      color: white;
      padding: 10px 25px;
      border-radius: 25px;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .login-box button:hover {
      background: lime;
      color: #111;
    }

    .social-icons {
      margin-top: 20px;
    }

    .social-icons i {
      margin: 0 10px;
      color: #fff;
      font-size: 20px;
      cursor: pointer;
      transition: 0.3s;
    }

    .social-icons i:hover {
      color: #00f2ff;
    }
  </style>
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>

  <div class="header">
    <div class="logo-container">
      <img src="logo.png" alt="CICS Logo">
      <img src="csulogo.png" alt="CSU Logo">
    </div>
    <h2>Document Management System (DocMaS)</h2>
  </div>

  <form method="POST" class="login-box">
    <h1>LOGIN</h1>
    <p>Please enter your login and password!</p>
    
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    
    <button name="login" type="submit">Login</button>

    <div class="social-icons">
      <i class="fab fa-facebook-f"></i>
      <i class="fab fa-twitter"></i>
      <i class="fas fa-user-circle"></i>
      <i class="fab fa-google"></i>
    </div>
  </form>

</body>
</html>

<?php
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $servername = "localhost";
    $db_username = "root";
    $db_password = "";
    $dbname = "docmas";

    $conn = new mysqli($servername, $db_username, $db_password, $dbname);
    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    $query = "SELECT role FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($role);
        $stmt->fetch();
        $_SESSION['username'] = $username;

        if ($role === "admin") {
            echo "<script>alert('Welcome, Admin! Redirecting to the admin dashboard.');</script>";
            echo "<script>window.location.href = 'admin/admindashboard.php';</script>";
        } else {
            echo "<script>alert('Invalid Username');</script>";
        }
    } else {
        echo "<script>alert('Invalid username or password.');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
