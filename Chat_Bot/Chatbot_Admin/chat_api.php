<?php
session_start();
header('Content-Type: application/json');
include 'db.php';

if (!isset($_SESSION['username'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$username = $_SESSION['username'];
$user_msg = strtolower(trim($_GET['message'] ?? ''));
$responses = [];

// 0. Check if message is a payload (like "yes" coming from "/yes")
$is_payload = false;
if (strpos($user_msg, "/") === 0) {
    $user_msg = substr($user_msg, 1); // remove leading "/"
    $is_payload = true;
}

// 1. If it's a payload, skip matching examples and go straight to rules
$matched_intent = null;
if ($is_payload) {
    // Find rule where intent = payload text
    $stmtP = $conn->prepare("SELECT utter_name FROM rules WHERE intent = ? AND created_by = ?");
    $stmtP->bind_param("ss", $user_msg, $username);
    $stmtP->execute();
    $rule_res = $stmtP->get_result();

    if ($rule = $rule_res->fetch_assoc()) {
        $actions_raw = $rule['utter_name'];
        $actions = (substr($actions_raw, 0, 1) == '[') ? json_decode($actions_raw, true) : array_map('trim', explode(',', $actions_raw));

        foreach ($actions as $utter_name) {
            $stmtU = $conn->prepare("SELECT * FROM utters WHERE utter_name = ? AND created_by = ?");
            $stmtU->bind_param("ss", $utter_name, $username);
            $stmtU->execute();
            $utter_res = $stmtU->get_result();

            if ($utter = $utter_res->fetch_assoc()) {
                switch ($utter['type']) {
                    case 'text':
                        $responses[] = ['type' => 'text', 'content' => $utter['content']];
                        break;
                    case 'image':
                        $data = json_decode($utter['content'], true);
                        $responses[] = ['type' => 'image','text' => $data['text'],'image' => $data['image']];
                        break;
                    case 'button':
                        $data = json_decode($utter['content'], true);
                        $responses[] = ['type' => 'button','text' => $data['text'],'buttons' => $data['buttons']];
                        break;
                    case 'card':
                        $data = json_decode($utter['content'], true);
                        $responses[] = ['type' => 'card','title' => $data['title'],'subtitle' => $data['subtitle'],'image' => $data['image_url'],'buttons' => $data['buttons']];
                        break;
                }
            }
        }
    }
} else {
    // === ORIGINAL INTENT MATCHING LOGIC ===
    $stmt = $conn->prepare("SELECT id, name FROM intents WHERE office_in_charge = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($intent = $res->fetch_assoc()) {
        $intent_id = $intent['id'];

        $stmt2 = $conn->prepare("SELECT example FROM examples WHERE intent_id = ?");
        $stmt2->bind_param("i", $intent_id);
        $stmt2->execute();
        $examples = $stmt2->get_result();

        while ($ex = $examples->fetch_assoc()) {
            $example = strtolower($ex['example']);

            similar_text($user_msg, $example, $percent);
            $lev = levenshtein($user_msg, $example);

            if ($percent >= 80 || $lev <= 3) {
                $matched_intent = $intent['name'];
                break 2;
            }
        }
    }

    if (!empty($matched_intent)) {
        $stmt3 = $conn->prepare("SELECT utter_name FROM rules WHERE intent = ? AND created_by = ?");
        $stmt3->bind_param("ss", $matched_intent, $username);
        $stmt3->execute();
        $rule_res = $stmt3->get_result();

        if ($rule = $rule_res->fetch_assoc()) {
            $actions_raw = $rule['utter_name'];
            $actions = (substr($actions_raw, 0, 1) == '[') ? json_decode($actions_raw, true) : array_map('trim', explode(',', $actions_raw));

            foreach ($actions as $utter_name) {
                $stmt4 = $conn->prepare("SELECT * FROM utters WHERE utter_name = ? AND created_by = ?");
                $stmt4->bind_param("ss", $utter_name, $username);
                $stmt4->execute();
                $utter_res = $stmt4->get_result();

                if ($utter = $utter_res->fetch_assoc()) {
                    switch ($utter['type']) {
                        case 'text':
                            $responses[] = ['type' => 'text', 'content' => $utter['content']];
                            break;
                        case 'image':
                            $data = json_decode($utter['content'], true);
                            $responses[] = ['type' => 'image','text' => $data['text'],'image' => $data['image']];
                            break;
                        case 'button':
                            $data = json_decode($utter['content'], true);
                            $responses[] = ['type' => 'button','text' => $data['text'],'buttons' => $data['buttons']];
                            break;
                        case 'card':
                            $data = json_decode($utter['content'], true);
                            $responses[] = ['type' => 'card','title' => $data['title'],'subtitle' => $data['subtitle'],'image' => $data['image_url'],'buttons' => $data['buttons']];
                            break;
                    }
                }
            }
        }
    }
}

// 3. Fallback
if (empty($responses)) {
    $responses[] = ['type' => 'text', 'content' => "Sorry, I don't understand."];
}

echo json_encode($responses);
?>
