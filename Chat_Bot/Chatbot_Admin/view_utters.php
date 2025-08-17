<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Utters</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #1e1e2f;
            color: #eee;
            padding: 20px;
        }
        .top-bar {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-bottom: 25px;
        }
        .btn {
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            transition: background 0.3s, color 0.3s;
            box-shadow: 0 0 8px rgba(0,242,255,0.4);
        }
        .btn:hover {
            color: #1e1e2f;
        }
        .home-btn {
            background: #28c76f;
            color: #1e1e2f;
        }
        .home-btn:hover { background: #fff; }
        .add-btn, .generate-btn {
            background: #00f2ff;
            color: #1e1e2f;
        }
        .add-btn:hover, .generate-btn:hover {
            background: #28c76f;
        }

        h2 {
            margin-left: 10px;
            color: #00f2ff;
            margin-bottom: 20px;
            text-align: left;
        }

        .utter-box {
            background: #2b2b3d;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 0 12px rgba(0,242,255,0.1);
        }
        .utter-title {
            font-size: 20px;
            font-weight: bold;
            color: #28c76f;
            margin-bottom: 12px;
        }
        .utter-content {
            margin-left: 15px;
            font-family: monospace;
            color: #ccc;
        }
        .buttons-list {
            list-style: none;
            padding-left: 0;
            margin-top: 10px;
        }
        .buttons-list li {
            background: rgba(0,242,255,0.1);
            margin: 5px 0;
            padding: 6px 10px;
            border-radius: 5px;
            color: #eee;
        }
        .actions {
            margin-top: 12px;
        }
        .actions a {
            margin-right: 12px;
            color: #00f2ff;
            text-decoration: none;
            transition: color 0.3s;
        }
        .actions a:hover {
            color: #28c76f;
        }
        img {
            border-radius: 5px;
            margin-top: 8px;
        }
        small {
            color: #aaa;
            font-family: monospace;
        }
    </style>
</head>
<body>

<div class="top-bar">
    <a class="btn home-btn" href="dashboard.php">🏠 Home</a>
    <a class="btn add-btn" href="add_utter.php">➕ Add Utter</a>
    <a class="btn generate-btn" href="generate_view_domain.php">⚡ Generate domain.yml</a>
</div>

<h2>Your Utters</h2>

<?php
$stmt = $conn->prepare("SELECT * FROM utters WHERE created_by = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($utter = $result->fetch_assoc()) {
        echo "<div class='utter-box'>";
        echo "<div class='utter-title'>" . htmlspecialchars($utter['utter_name']) . " (" . htmlspecialchars($utter['type']) . ")</div>";

        echo "<div class='utter-content'>";
        if ($utter['type'] == 'text') {
            echo "<p>" . nl2br(htmlspecialchars($utter['content'])) . "</p>";
        } elseif ($utter['type'] == 'image') {
            $data = json_decode($utter['content'], true);
            echo "<p>" . htmlspecialchars($data['text']) . "</p>";
            echo "<img src='" . htmlspecialchars($data['image']) . "' alt='Image' style='max-width:300px;'><br>";
            echo "<small>" . htmlspecialchars($data['image']) . "</small>";
        } elseif ($utter['type'] == 'button') {
            $data = json_decode($utter['content'], true);
            if ($data) {
                echo "<p><strong>Text:</strong> " . htmlspecialchars($data['text']) . "</p>";
                echo "<ul class='buttons-list'>";
                foreach ($data['buttons'] as $btn) {
                    echo "<li><strong>Title:</strong> " . htmlspecialchars($btn['title']) . 
                         " | <strong>Payload:</strong> " . htmlspecialchars($btn['payload']) . "</li>";
                }
                echo "</ul>";
            }
        } elseif ($utter['type'] == 'card') {
            $data = json_decode($utter['content'], true);
            if ($data) {
                echo "<p><strong>Title:</strong> " . htmlspecialchars($data['title']) . "</p>";
                echo "<p><strong>Subtitle:</strong> " . htmlspecialchars($data['subtitle']) . "</p>";
                echo "<img src='" . htmlspecialchars($data['image_url']) . "' alt='Card Image' style='max-width:300px;'><br>";
                echo "<small>" . htmlspecialchars($data['image_url']) . "</small>";
                echo "<ul class='buttons-list'>";
                foreach ($data['buttons'] as $btn) {
                    echo "<li><strong>Title:</strong> " . htmlspecialchars($btn['title']) . 
                         " | <strong>Payload:</strong> " . htmlspecialchars($btn['payload']) . "</li>";
                }
                echo "</ul>";
            }
        }
        echo "</div>";

        echo "<div class='actions'>";
        echo "<a href='edit_utter.php?id=" . $utter['id'] . "'>✏️ Edit</a>";
        echo "<a href='delete_utter.php?id=" . $utter['id'] . "' onclick=\"return confirm('Delete this utter?')\">🗑️ Delete</a>";
        echo "</div>";

        echo "</div>";
    }
} else {
    echo "<p style='margin-left:10px;'>No utters found for you.</p>";
}
?>

</body>
</html>
