<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$current_user = $_SESSION['username'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Intents & Examples</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #1e1e2f;
            color: #eee;
            padding: 20px;
        }
        h2, h3 {
            color: #00f2ff;
        }
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .button-group {
            display: flex;
            gap: 10px;
        }
        .btn-home, .btn-add, .btn-generate {
            background: #00f2ff;
            color: #1e1e2f;
            border: none;
            padding: 10px 18px;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.3s, color 0.3s;
        }
        .btn-home {
            background: #28c76f;
        }
        .btn-home:hover, .btn-add:hover, .btn-generate:hover {
            background: #fff;
            color: #1e1e2f;
        }
        form {
            display: inline;
        }
        .intent-box {
            background: #2b2b3d;
            border-radius: 8px;
            margin-bottom: 20px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,242,255,0.2);
        }
        .intent-title {
            font-size: 18px;
            font-weight: bold;
            color: #28c76f;
            margin-bottom: 15px;
        }
        .example-list {
            list-style: none;
            padding-left: 0;
        }
        .example-list li {
            background: #1e1e2f;
            margin-bottom: 8px;
            padding: 8px 12px;
            border-left: 4px solid #00f2ff;
            border-radius: 4px;
            font-family: monospace;
            color: #ccc;
        }
        .actions {
            margin-top: 12px;
        }
        .actions a {
            color: #00f2ff;
            text-decoration: none;
            margin-right: 15px;
            transition: color 0.3s;
        }
        .actions a:hover {
            color: #28c76f;
        }
    </style>
</head>
<body>

<div class="top-bar">
    <h2>👋 Welcome, <?= htmlspecialchars($current_user) ?></h2>
    <div class="button-group">
        <a href="dashboard.php" class="btn-home">🏠 Home</a>
        <a href="add_intent.php" class="btn-add">➕ Add Intent</a>
        <form action="generate_view_nlu.php" method="post">
            <button type="submit" class="btn-generate">📝 Generate `nlu.yml`</button>
        </form>
    </div>
</div>

<h3>Your Intents & Examples</h3>

<?php
$stmt = $conn->prepare("SELECT * FROM intents WHERE office_in_charge = ?");
$stmt->bind_param("s", $current_user);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($intent = $result->fetch_assoc()) {
        echo "<div class='intent-box'>";
        echo "<div class='intent-title'>Intent: <span style='color:#00f2ff'>" . htmlspecialchars($intent['name']) . "</span></div>";

        // Fetch examples
        $stmt_ex = $conn->prepare("SELECT example FROM examples WHERE intent_id = ?");
        $stmt_ex->bind_param("i", $intent['id']);
        $stmt_ex->execute();
        $examples = $stmt_ex->get_result();

        echo "<ul class='example-list'>";
        while ($row = $examples->fetch_assoc()) {
            echo "<li>" . htmlspecialchars($row['example']) . "</li>";
        }
        echo "</ul>";

        echo "<div class='actions'>";
        echo "<a href='edit_intent.php?id=" . $intent['id'] . "'>✏️ Edit</a>";
        echo "<a href='delete_intent.php?id=" . $intent['id'] . "' onclick=\"return confirm('Delete this intent and all examples?')\">🗑️ Delete</a>";
        echo "</div>";

        echo "</div>";
    }
} else {
    echo "<p style='color:#ccc;'>No intents found for you.</p>";
}
?>

</body>
</html>
