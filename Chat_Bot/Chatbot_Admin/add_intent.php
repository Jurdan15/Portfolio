<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>NLU Intent Editor</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #1e1e2f;
            color: #eee;
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: #00f2ff;
            margin-bottom: 25px;
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
            padding: 10px;
            margin: 8px 0 15px 0;
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
            display: block;
            margin: 20px auto 0 auto;
        }
        button:hover {
            background: #28c76f;
            color: #1e1e2f;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
            color: #28c76f;
        }
        .back-btn {
            display: inline-block;
            margin-bottom: 20px;
            padding: 8px 16px;
            background: #28c76f;
            color: #1e1e2f;
            text-decoration: none;
            border-radius: 6px;
            transition: background 0.3s, color 0.3s;
        }
        .back-btn:hover {
            background: #fff;
            color: #1e1e2f;
        }
        .message {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }
        .hint {
            font-size: 12px;
            color: #ccc;
            margin-top: -8px;
            margin-bottom: 10px;
        }
        .counter {
            text-align: right;
            font-size: 12px;
            color: #00f2ff;
            margin-top: -10px;
            margin-bottom: 10px;
        }
    </style>
    <script>
    window.onload = function() {
        // Sanitize intent as you type
        const intentInput = document.querySelector("input[name='intent']");
        intentInput.addEventListener('input', function() {
            // Replace spaces and dashes with underscores
            this.value = this.value.replace(/[\s\-]/g, '_');
            // Remove characters that are not letters, numbers, or underscores
            this.value = this.value.replace(/[^\w]/g, '');
        });

        // Require at least 5 non-empty examples (up to 10 allowed)
        const exampleInputs = document.querySelectorAll("input[name='examples[]']");
        const submitBtn = document.getElementById('saveBtn');
        const counterEl = document.getElementById('filledCounter');

        function updateCounterAndValidity() {
            let filled = 0;
            exampleInputs.forEach(inp => {
                if (inp.value.trim() !== '') filled++;
            });
            counterEl.textContent = filled + " / 10 filled (min 5)";
            submitBtn.disabled = filled < 5;
            submitBtn.style.opacity = filled < 5 ? 0.6 : 1;
            submitBtn.style.cursor  = filled < 5 ? 'not-allowed' : 'pointer';
        }

        exampleInputs.forEach(inp => {
            inp.addEventListener('input', updateCounterAndValidity);
        });

        // Initialize state on load
        updateCounterAndValidity();

        // Final client validation on submit
        document.getElementById('intentForm').addEventListener('submit', function(e) {
            let filled = 0;
            exampleInputs.forEach(inp => {
                if (inp.value.trim() !== '') filled++;
            });
            if (filled < 5) {
                e.preventDefault();
                alert('Please provide at least 5 example sentences (you can add up to 10).');
            }
        });
    };
    </script>
</head>
<body>

<a class="back-btn" href="view_intents.php">⬅ Back to Intents</a>

<h2>Add New Intent</h2>
<form method="post" id="intentForm">
    <label>Intent Name (no "-"):</label>
    <input type="text" name="intent" required>

    <label>Enter Examples</label><br>
    <div class="hint">At least 5 required, up to 10 allowed.</div>
    <div class="counter" id="filledCounter">0 / 10 filled (min 5)</div>

    <?php for ($i = 0; $i < 10; $i++): ?>
        <!-- No 'required' here; JS + PHP enforce "at least 5" -->
        <input type="text" name="examples[]" placeholder="Example <?= $i + 1 ?>">
    <?php endfor; ?>

    <button type="submit" id="saveBtn" name="save">💾 Save to Database</button>
</form>

<?php
if (isset($_POST['save'])) {
    $intent = isset($_POST['intent']) ? trim($_POST['intent']) : '';
    $username = $_SESSION['username'];

    // PHP validation: allow only letters, numbers, underscores
    if (!preg_match('/^[A-Za-z0-9_]+$/', $intent)) {
        echo "<p class='message' style='color:red;'>Intent name can only contain letters, numbers, and underscores (_).</p>";
        exit;
    }

    // Gather, trim, and keep only non-empty examples
    $examples_raw = isset($_POST['examples']) ? $_POST['examples'] : [];
    $examples = [];
    foreach ($examples_raw as $ex) {
        $trimmed = trim($ex);
        if ($trimmed !== '') {
            $examples[] = $trimmed;
        }
    }

    // Server-side rule: at least 5 examples, max 10
    if (count($examples) < 5) {
        echo "<p class='message' style='color:red;'>Please provide at least 5 example sentences. You entered ".count($examples).".</p>";
        exit;
    }
    if (count($examples) > 10) {
        echo "<p class='message' style='color:red;'>You can provide up to 10 examples only.</p>";
        exit;
    }

    // Insert intent
    $stmt = $conn->prepare("INSERT INTO intents (name, office_in_charge) VALUES (?, ?)");
    $stmt->bind_param("ss", $intent, $username);
    $stmt->execute();
    $intent_id = $stmt->insert_id;

    // Insert examples
    $stmtEx = $conn->prepare("INSERT INTO examples (intent_id, example) VALUES (?, ?)");
    $stmtEx->bind_param("is", $intent_id, $exVal);
    foreach ($examples as $exVal) {
        $stmtEx->execute();
    }

    echo "<p class='message' style='color:#28c76f;'>Intent and ".count($examples)." example(s) saved successfully!</p>";
}
?>
</body>
</html>
