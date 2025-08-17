<?php
session_start();
include 'db.php';

// Ensure user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];

// Fetch intents for the current user's office_in_charge
$sql = "SELECT intent FROM intent_logs";
$result = $conn->query($sql);

$total = 0;
$intent_counts = [];

while ($row = $result->fetch_assoc()) {
    $intent = $row['intent'];

    // Get office_in_charge for the intent
    $stmt = $conn->prepare("SELECT office_in_charge FROM intents WHERE name = ?");
    $stmt->bind_param("s", $intent);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($data = $res->fetch_assoc()) {
        $office = $data['office_in_charge'];
        if ($office === $username) {
            if (!isset($intent_counts[$intent])) {
                $intent_counts[$intent] = 0;
            }
            $intent_counts[$intent]++;
            $total++;
        }
    }
}

$labels = [];
$values = [];
$colors = [];

foreach ($intent_counts as $intent => $count) {
    $labels[] = $intent;
    $values[] = round(($count / $total) * 100, 2);
    $colors[] = sprintf('#%06X', mt_rand(0, 0xFFFFFF)); // Random color
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Intent Breakdown</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px;
        }
        canvas {
            max-width: 500px;
        }
        table {
            margin-top: 20px;
            border-collapse: collapse;
            width: 60%;
            border: 2px solid #333;
        }
        th, td {
            border: 1px solid #333;
            padding: 8px 12px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

    <h2><?= htmlspecialchars($username) ?>'s Intent Breakdown (in %)</h2>

    <div style="display: flex; justify-content: center; margin-top: 0px;">
        <canvas id="intentPie" width="500" height="500"></canvas>
    </div>

    <table>
        <tr>
            <th>Intent</th>
            <th>Percentage</th>
            <th>Count</th>
        </tr>
        <?php foreach ($intent_counts as $intent => $count): ?>
            <tr>
                <td><?= htmlspecialchars($intent) ?></td>
                <td><?= round(($count / $total) * 100, 2) ?>%</td>
                <td><?= $count ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <script>
        const ctx = document.getElementById('intentPie').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: <?= json_encode($labels) ?>,
                datasets: [{
                    data: <?= json_encode($values) ?>,
                    backgroundColor: <?= json_encode($colors) ?>
                }]
            },
            options: {
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const countMap = <?= json_encode($intent_counts) ?>;
                                const label = context.label || '';
                                const percentage = context.parsed;
                                return `${label}: ${percentage}% (${countMap[label]} count)`;
                            }
                        }
                    },
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>

</body>
</html>
