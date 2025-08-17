<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
$message = "";

// Fetch intents for dropdown
$intent_stmt = $conn->prepare("SELECT name FROM intents WHERE office_in_charge = ?");
$intent_stmt->bind_param("s", $username);
$intent_stmt->execute();
$intents_result = $intent_stmt->get_result();

$intents = [];
while ($row = $intents_result->fetch_assoc()) {
    $intents[] = $row['name'];
}

// Save data
if (isset($_POST['save'])) {
    $utter_name = trim($_POST['utter_name']);
    $type = $_POST['type'];

    if ($type == 'text') {
        $content = trim($_POST['text']);
    } elseif ($type == 'image') {
        $image_text = trim($_POST['image_text']);
        $image_url = trim($_POST['image_url']);
        $content = json_encode([
            'text' => $image_text,
            'image' => $image_url
        ]);
    } elseif ($type == 'button') {
        $button_text = trim($_POST['button_text']);
        $buttons = [];
        if (isset($_POST['button_titles']) && isset($_POST['button_payloads'])) {
            foreach ($_POST['button_titles'] as $i => $title) {
                $buttons[] = [
                    'title' => $title,
                    'payload' => $_POST['button_payloads'][$i]
                ];
            }
        }
        $content = json_encode([
            'text' => $button_text,
            'buttons' => $buttons
        ]);
    } elseif ($type == 'card') {
        $card_title = trim($_POST['card_title']);
        $card_subtitle = trim($_POST['card_subtitle']);
        $card_image_url = trim($_POST['card_image_url']);
        $buttons = [];
        if (isset($_POST['card_button_titles']) && isset($_POST['card_button_payloads'])) {
            foreach ($_POST['card_button_titles'] as $i => $title) {
                $buttons[] = [
                    'title' => $title,
                    'payload' => $_POST['card_button_payloads'][$i]
                ];
            }
        }
        $content = json_encode([
            'title' => $card_title,
            'subtitle' => $card_subtitle,
            'image_url' => $card_image_url,
            'buttons' => $buttons
        ]);
    } else {
        $content = "";
    }

    $stmt = $conn->prepare("INSERT INTO utters (utter_name, type, content, created_by) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $utter_name, $type, $content, $username);
    if ($stmt->execute()) {
        $message = "<p style='color:#28c76f;'>✅ Utter saved successfully!</p>";
    } else {
        $message = "<p style='color:#ff6f61;'>⚠️ Error saving utter: " . $stmt->error . "</p>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Utter</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #1e1e2f;
            color: #eee;
            padding: 20px;
        }
        form {
            background: #2b2b3d;
            padding: 30px;
            max-width: 700px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0 0 12px rgba(0,242,255,0.15);
        }
        input[type=text], textarea, select {
            width: 100%;
            padding: 10px;
            margin: 6px 0 16px;
            background: #1e1e2f;
            border: 1px solid #00f2ff;
            color: #eee;
            border-radius: 5px;
            font-size: 14px;
        }
        textarea { resize: vertical; }
        button {
            border: none;
            cursor: pointer;
            border-radius: 6px;
            transition: background 0.3s, box-shadow 0.3s;
        }
        .add-btn {
            background: #00f2ff;
            color: #1e1e2f;
            padding: 10px 18px;
            box-shadow: 0 0 8px rgba(0,242,255,0.4);
        }
        .add-btn:hover {
            background: #28c76f;
            color: #1e1e2f;
            box-shadow: 0 0 10px rgba(40,199,111,0.6);
        }
        button[type=submit] {
            background: #28c76f;
            color: #1e1e2f;
            padding: 12px 24px;
            box-shadow: 0 0 8px rgba(40,199,111,0.4);
            display: block;
            margin: 20px auto 0;
        }
        button[type=submit]:hover {
            background: #00f2ff;
            color: #1e1e2f;
            box-shadow: 0 0 10px rgba(0,242,255,0.6);
        }
        .back-btn {
            display: inline-block;
            margin-bottom: 20px;
            padding: 8px 16px;
            background: #555;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .back-btn:hover {
            background: #00f2ff;
            color: #1e1e2f;
        }
        h2 {
            text-align: center;
            color: #00f2ff;
            margin-bottom: 20px;
        }
    </style>
    <script>
        function showFields(type) {
            document.getElementById('text_field').style.display = (type === 'text') ? 'block' : 'none';
            document.getElementById('image_field').style.display = (type === 'image') ? 'block' : 'none';
            document.getElementById('button_field').style.display = (type === 'button') ? 'block' : 'none';
            document.getElementById('card_field').style.display = (type === 'card') ? 'block' : 'none';
        }

        function addButton() {
            var container = document.getElementById('buttons_list');
            var div = document.createElement('div');
            div.innerHTML = 
                `<input type="text" name="button_titles[]" placeholder="Button Title" required>
                <select name="button_payloads[]" required>
                    <option value="">Select Intent...</option>
                    <?php foreach ($intents as $intentName): ?>
                        <option value="/<?= htmlspecialchars($intentName) ?>">/<?= htmlspecialchars($intentName) ?></option>
                    <?php endforeach; ?>
                </select>`;
            container.appendChild(div);
        }

        function addCardButton() {
            var container = document.getElementById('card_buttons_list');
            var div = document.createElement('div');
            div.innerHTML = 
                `<input type="text" name="card_button_titles[]" placeholder="Button Title" required>
                <select name="card_button_payloads[]" required>
                    <option value="">Select Intent...</option>
                    <?php foreach ($intents as $intentName): ?>
                        <option value="/<?= htmlspecialchars($intentName) ?>">/<?= htmlspecialchars($intentName) ?></option>
                    <?php endforeach; ?>
                </select>`;
            container.appendChild(div);
        }
        function formatUtterName(input) {
        const prefix = "utter_";

        // Ensure it always starts with "utter_"
        if (!input.value.startsWith(prefix)) {
            input.value = prefix;
        }

        // Get part after prefix
        let suffix = input.value.substring(prefix.length);

        // Allow only letters, underscores, and spaces
        suffix = suffix.replace(/[^a-zA-Z_ ]/g, ''); // keep underscores
        suffix = suffix.replace(/\s+/g, '_');       // convert space to underscore

        // Re-apply prefix and cleaned suffix
        input.value = prefix + suffix;
        }

    </script>
</head>
<body>

<a class="back-btn" href="view_utters.php">⬅ Back to Utters</a>

<h2>Add Utter</h2>
<?= $message ?>

<form method="post">
    <label>Utter Name:</label>
    <input type="text" id="utter_name" name="utter_name" required placeholder="e.g. utter_greet" value="utter_" oninput="formatUtterName(this)">


    <label>Type:</label>
    <select name="type" onchange="showFields(this.value)" required>
        <option value="text">Text</option>
        <option value="image">Image with Text</option>
        <option value="button">Button Template</option>
        <option value="card">Generic Card Template</option>
    </select>

    <div id="text_field" style="display:block;">
        <label>Text Response:</label>
        <textarea name="text" placeholder="Hello, how can I help you?"></textarea>
    </div>

    <div id="image_field" style="display:none;">
        <label>Text Above Image:</label>
        <input type="text" name="image_text" placeholder="Here is our brochure:">
        <label>Image URL:</label>
        <input type="text" name="image_url" placeholder="https://...">
    </div>

    <div id="button_field" style="display:none;">
        <label>Main Question Text:</label>
        <input type="text" name="button_text" placeholder="How can I help you?">
        <div id="buttons_list"></div>
        <button type="button" class="add-btn" onclick="addButton()">➕ Add Button</button>
    </div>

    <div id="card_field" style="display:none;">
        <label>Card Title:</label>
        <input type="text" name="card_title" placeholder="Balance Inquiry">
        <label>Card Subtitle:</label>
        <input type="text" name="card_subtitle" placeholder="Easily check your balance.">
        <label>Image URL:</label>
        <input type="text" name="card_image_url" placeholder="https://...">
        <div id="card_buttons_list"></div>
        <button type="button" class="add-btn" onclick="addCardButton()">➕ Add Card Button</button>
    </div>

    <button type="submit" name="save">💾 Save Utter</button>
</form>

</body>
</html>
