<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];

// Handle save (add or update existing)
if (isset($_POST['save'])) {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $rule_name = trim($_POST['rule_name']);
    $intent = $_POST['intent'];
    $utter_names = isset($_POST['utter_name']) ? $_POST['utter_name'] : [];
    $utter_json = json_encode($utter_names);

    if ($id > 0) {
        // Update existing rule
        $stmt = $conn->prepare("UPDATE rules SET rule_name = ?, intent = ?, utter_name = ? WHERE id = ? AND created_by = ?");
        $stmt->bind_param("sssis", $rule_name, $intent, $utter_json, $id, $username);
        $stmt->execute();
    } else {
        // Insert new rule
        $stmt = $conn->prepare("INSERT INTO rules (rule_name, intent, utter_name, created_by) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $rule_name, $intent, $utter_json, $username);
        $stmt->execute();
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM rules WHERE id = ? AND created_by = ?");
    $stmt->bind_param("is", $id, $username);
    $stmt->execute();
    header("Location: manage_rules.php");
    exit;
}

// Fetch rules
$rules = [];
$stmt = $conn->prepare("SELECT * FROM rules WHERE created_by = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $rules[] = $row;
}

// Fetch intents
$intents = [];
$used_intents = [];

$i_stmt = $conn->prepare("SELECT name FROM intents WHERE office_in_charge = ?");
$i_stmt->bind_param("s", $username);
$i_stmt->execute();
$i_result = $i_stmt->get_result();
while ($i_row = $i_result->fetch_assoc()) {
    $intents[] = $i_row['name'];
}

// Get used intents
foreach ($rules as $r) {
    $used_intents[] = $r['intent'];
}

// Fetch utters
$utters = [];
$u_stmt = $conn->prepare("SELECT utter_name FROM utters WHERE created_by = ?");
$u_stmt->bind_param("s", $username);
$u_stmt->execute();
$u_result = $u_stmt->get_result();
while ($u_row = $u_result->fetch_assoc()) {
    $utters[] = $u_row['utter_name'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Rules</title>
    <style>
    body { font-family: Arial, sans-serif; background: #1e1e2f; color: #eee; padding: 20px; }
    table { border-collapse: collapse; width: 100%; background: #2b2b3d; border-radius: 8px; box-shadow: 0 0 12px rgba(0,242,255,0.15);}
    th, td { border: 1px solid #00f2ff33; padding: 10px; text-align: left; }
    th { background: #00f2ff22; color: #00f2ff; }
    tr:hover { background: #00f2ff11; }
    input, select { background: #1e1e2f; border: 1px solid #00f2ff; color: #eee; padding: 8px; margin: 4px 0; border-radius: 4px;}
    .btn, .btn-small { background: #28c76f; color: #1e1e2f; padding: 8px 16px; border: none; border-radius: 6px; cursor: pointer; transition: all 0.3s; box-shadow: 0 0 6px rgba(40,199,111,0.3); text-decoration: none;}
    .btn:hover, .btn-small:hover { background: #00f2ff; color: #1e1e2f; box-shadow: 0 0 8px rgba(0,242,255,0.5); }
    .btn-delete { background: #e53935; box-shadow: 0 0 6px rgba(229,57,53,0.3);}
    .btn-delete:hover { background: #ff6f61; box-shadow: 0 0 8px rgba(255,111,97,0.5);}
    .top-bar { display: flex; justify-content: flex-end; gap: 10px; margin-bottom: 20px;}
    h2, h3 { color: #00f2ff; }
    </style>
</head>
<body>

<div class="top-bar">
    <a class="btn" href="dashboard.php">🏠 Home</a>
    <a class="btn" href="generate_view_rules.php">⚡ Generate rules.yml</a>
</div>

<h2>Manage Rules</h2>

<table>
    <tr>
        <th>Rule Name</th>
        <th>Intent</th>
        <th>Utter Actions</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($rules as $r): 
        $utter_list = json_decode($r['utter_name'], true);
        if (!is_array($utter_list)) $utter_list = [$r['utter_name']];
    ?>
    <tr>
        <td><?= htmlspecialchars($r['rule_name']) ?></td>
        <td><?= htmlspecialchars($r['intent']) ?></td>
        <td><?= htmlspecialchars(implode(", ", $utter_list)) ?></td>
        <td>
        <?php
            $ruleNameJs = json_encode($r['rule_name']);
            $intentJs = json_encode($r['intent']);
            $utterListJs = json_encode($utter_list);
        ?>
            <button class="btn-small" onclick='editRow(<?= $ruleNameJs ?>, <?= $intentJs ?>, <?= $utterListJs ?>, <?= $r['id'] ?>)'>Edit</button>
            <a class="btn-small btn-delete" href="?delete=<?= $r['id'] ?>" onclick="return confirm('Delete this rule?')">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<h3 id="form-title">Add New Rule</h3>
<form method="post">
    <input type="hidden" name="id" id="form-id" value="0">
    <input type="text" name="rule_name" id="form-rule-name" placeholder="Rule Name" required>
    <select name="intent" id="form-intent" required>
    <option value="">Select Intent</option>
    <?php foreach ($intents as $intent): ?>
        <?php if (!in_array($intent, $used_intents)): ?>
            <option value="<?= htmlspecialchars($intent) ?>"><?= htmlspecialchars($intent) ?></option>
        <?php endif; ?>
    <?php endforeach; ?>
    </select>

    <div id="actions">
        <div style="display: flex; align-items: center; gap: 5px;">
            <select name="utter_name[]" required>
                <?php foreach ($utters as $utter): ?>
                    <option value="<?= $utter ?>"><?= $utter ?></option>
                <?php endforeach; ?>
            </select>
            <button type="button" class="btn-small btn-delete" onclick="removeAction(this)">✖</button>
        </div>
    </div>
    <button type="button" class="btn-small" onclick="addNewAction()">➕ Add another action</button>
    <button type="submit" name="save" class="btn">Save Rule</button>
</form>

<script>
function editRow(ruleName, intent, utters, id) {
    document.getElementById("form-title").innerText = "Edit Rule";
    document.getElementById("form-rule-name").value = ruleName;
    document.getElementById("form-id").value = id; // set hidden id

    let intentSelect = document.getElementById("form-intent");
    let existingOption = Array.from(intentSelect.options).find(opt => opt.value === intent);
    if (!existingOption) {
        let newOption = document.createElement("option");
        newOption.value = intent;
        newOption.text = intent + " (in use)";
        intentSelect.add(newOption);
    }
    intentSelect.value = intent;

    let container = document.getElementById("actions");
    container.innerHTML = "";
    utters.forEach(function(utter) {
        let div = document.createElement("div");
        div.style.display = "flex";
        div.style.alignItems = "center";
        div.style.gap = "5px";
        div.innerHTML = `<select name="utter_name[]" required>
            <?php foreach ($utters as $utter): ?>
            <option value="<?= $utter ?>"><?= $utter ?></option>
            <?php endforeach; ?>
        </select>
        <button type="button" class="btn-small btn-delete" onclick="removeAction(this)">✖</button>`;
        container.appendChild(div);

        div.querySelector("select").value = utter;
    });
}

function addNewAction() {
    let container = document.getElementById("actions");
    let div = document.createElement("div");
    div.style.display = "flex";
    div.style.alignItems = "center";
    div.style.gap = "5px";
    div.style.marginTop = "5px";
    div.innerHTML = `<select name="utter_name[]" required>
        <?php foreach ($utters as $utter): ?>
        <option value="<?= $utter ?>"><?= $utter ?></option>
        <?php endforeach; ?>
    </select>
    <button type="button" class="btn-small btn-delete" onclick="removeAction(this)">✖</button>`;
    container.appendChild(div);
}

function removeAction(button) {
    button.parentElement.remove();
}
</script>

</body>
</html>
