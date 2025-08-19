<?php
include 'db.php';
session_start();

// Get intent ID
$intent_id = $_GET['id'] ?? null;
if (!$intent_id) {
    die("No intent selected.");
}

// Fetch intent
$stmt = $conn->prepare("SELECT * FROM intents WHERE id = ?");
$stmt->bind_param("i", $intent_id);
$stmt->execute();
$intent = $stmt->get_result()->fetch_assoc();

// Fetch examples
$stmt = $conn->prepare("SELECT * FROM examples WHERE intent_id = ?");
$stmt->bind_param("i", $intent_id);
$stmt->execute();
$examples = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Handle update
if (isset($_POST['update'])) {
    $new_intent = trim($_POST['intent']);
    $new_examples = array_filter($_POST['examples'], fn($ex) => trim($ex) !== ""); // remove empty inputs
    $count_examples = count($new_examples);

    // PHP strict validation
    if (!preg_match('/^[A-Za-z0-9_]+$/', $new_intent)) {
        echo "<p style='color:red;'>Intent name can only contain letters, numbers, and underscores (_).</p>";
    } elseif ($count_examples < 5) {
        echo "<p style='color:red;'>At least 5 examples are required.</p>";
    } else {
        $old_intent_name = $intent['name']; // store old name before updating

        // Update intent name
        $stmt = $conn->prepare("UPDATE intents SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $new_intent, $intent_id);
        $stmt->execute();

        // ✅ Update rules table if old intent exists there
        $stmt = $conn->prepare("UPDATE rules SET intent = ? WHERE intent = ?");
        $stmt->bind_param("ss", $new_intent, $old_intent_name);
        $stmt->execute();

        // Delete old examples
        $conn->query("DELETE FROM examples WHERE intent_id = $intent_id");

        // Insert updated examples
        $stmt = $conn->prepare("INSERT INTO examples (intent_id, example) VALUES (?, ?)");
        foreach ($new_examples as $example) {
            $stmt->bind_param("is", $intent_id, $example);
            $stmt->execute();
        }

        echo "<script>alert('Intent updated successfully'); window.location='view_intents.php';</script>";
        exit;
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Intent</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #1e1e2f;
            color: #eee;
            padding: 20px;
        }
        form {
            background: #2b2b3d;
            padding: 25px;
            max-width: 600px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0 0 12px rgba(0,242,255,0.2);
        }
        input[type=text] {
            width: 100%;
            margin: 8px 0 15px 0;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background: #1e1e2f;
            color: #eee;
            font-family: monospace;
            font-size: 14px;
            outline: none;
        }
        input[type=text]:focus {
            box-shadow: 0 0 5px #00f2ff;
        }
        button {
            padding: 12px 25px;
            background: #00f2ff;
            color: #1e1e2f;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.3s, color 0.3s;
            margin-top: 10px;
        }
        button:hover {
            background: #28c76f;
            color: #1e1e2f;
        }
        h2 {
            text-align: center;
            color: #00f2ff;
            margin-bottom: 25px;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
            color: #28c76f;
        }
    </style>
    <script>
    window.onload = function() {
        const intentInput = document.querySelector("input[name='intent']");
        intentInput.addEventListener('input', function() {
            this.value = this.value.replace(/[\s\-]/g, '_').replace(/[^\w]/g, '');
        });
    };
    </script>
</head>
<body>

<h2>Edit Intent</h2>
<form method="post">
    <label>Intent Name:</label>
    <input type="text" name="intent" value="<?= htmlspecialchars($intent['name']) ?>" required>

    <label>Edit up to 10 Examples (at least 5 required):</label>
    <?php
    for ($i = 0; $i < 10; $i++) {
        $value = $examples[$i]['example'] ?? '';
        echo "<input type='text' name='examples[]' value=\"" . htmlspecialchars($value) . "\">";
    }
    ?>

    <button type="submit" name="update">Update</button>
</form>

</body>
</html>
