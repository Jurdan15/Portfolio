<?php
session_start();
include 'db.php';

// If not logged in, redirect to login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Fetch existing usernames excluding 'admin'
$offices = [];
$result = $conn->query("SELECT username FROM users WHERE username != 'admin' ORDER BY username ASC");
while ($row = $result->fetch_assoc()) {
    $offices[] = $row['username'];
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selectedOffice = trim($_POST['username']);

    if ($selectedOffice) {
        // Set session like login
        $_SESSION['username'] = $selectedOffice;
        header("Location: dashboard.php");
        exit;
    } else {
        $message = "<p style='color: #ff6b6b;'>Please select an office.</p>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Choose Office</title>
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
        select {
            width: 100%;
            padding: 12px;
            margin: 10px 0 20px;
            border: none;
            border-radius: 6px;
            background: #1e1e2f;
            color: #00f2ff;
            box-shadow: inset 0 0 5px rgba(0,242,255,0.2);
            font-size: 15px;
            appearance: none;
        }
        select:focus {
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
    <h2>Choose an Office</h2>
    <form method="post">
        <select name="username" required>
            <option value="">Select an office</option>
            <?php foreach ($offices as $office): ?>
                <option value="<?= htmlspecialchars($office) ?>"><?= htmlspecialchars($office) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Go to Dashboard</button>
    </form>
    <?= $message ?>
</div>

</body>
</html>
