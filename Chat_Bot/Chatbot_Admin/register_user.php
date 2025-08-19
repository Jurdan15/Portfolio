<?php
session_start();
include 'db.php';

// If not logged in, redirect to login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);

    if ($stmt->execute()) {
        $message = "<p style='color: #28c76f;'>User created: $username</p>";
    } else {
        $message = "<p style='color: #ff6b6b;'>Error: " . htmlspecialchars($stmt->error) . "</p>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #1e1e2f;
            color: #00f2ff;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: #2b2b3d;
            padding: 30px;
            width: 100%;
            max-width: 400px;
            border-radius: 12px;
            box-shadow: 0 0 12px rgba(0,242,255,0.2);
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
            color: #00f2ff;
        }
        input[type=text], input[type=password] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: none;
            border-radius: 6px;
            background: #1e1e2f;
            color: #00f2ff;
            box-shadow: inset 0 0 5px rgba(0,242,255,0.2);
            font-size: 15px;
        }
        input[type=text]:focus, input[type=password]:focus {
            outline: none;
            box-shadow: 0 0 8px rgba(0,242,255,0.6);
        }
        button {
            width: 100%;
            background: #28c76f;
            color: #1e1e2f;
            padding: 12px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 6px;
            transition: background 0.3s, box-shadow 0.3s;
            box-shadow: 0 0 6px rgba(40,199,111,0.3);
        }
        button:hover {
            background: #00f2ff;
            color: #1e1e2f;
            box-shadow: 0 0 8px rgba(0,242,255,0.5);
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Create New User</h2>
    <form method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Create User</button>
    </form>
    <?= $message ?>
</div>

</body>
</html>
