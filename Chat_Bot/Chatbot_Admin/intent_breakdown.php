<?php
include 'db.php';

// Fetch all intent logs
$sql = "SELECT intent FROM intent_logs";
$result = $conn->query($sql);

$total = 0;
$office_counts = [];

while ($row = $result->fetch_assoc()) {
    $intent = $row['intent'];
    $total++;

    $stmt = $conn->prepare("SELECT office_in_charge FROM intents WHERE name = ?");
    $stmt->bind_param("s", $intent);
    $stmt->execute();
    $res = $stmt->get_result();

    $office = ($data = $res->fetch_assoc()) ? $data['office_in_charge'] : "Others";

    if (!isset($office_counts[$office])) {
        $office_counts[$office] = 0;
    }
    $office_counts[$office]++;
}

// Prepare data
$labels = [];
$values = [];
$counts = [];
$colors = [];

foreach ($office_counts as $office => $count) {
    $labels[] = $office;
    $counts[] = $count;
    $values[] = round(($count / $total) * 100, 2);
    $colors[] = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Intent Breakdown by Office</title>
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
            margin-left: auto;
            margin-right: auto;
            border-collapse: separate;
            border-spacing: 0;
            width: 500px;
            border: 3px solid #222; /* External border made thicker and darker */
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Optional: soft shadow for clarity */
        }

        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #444;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .color-box {
            width: 20px;
            height: 20px;
            display: inline-block;
            border-radius: 3px;
            margin-right: 8px;
        }
        h2 {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <h2>Intent Breakdown by Office (in %)</h2>

    <div style="display: flex; justify-content: center;">
        <canvas id="intentPie" width="500" height="500"></canvas>
    </div>

    <table>
        <thead>
            <tr>
                <th>Office</th>
                <th>Count</th>
                <th>Percentage</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($labels as $index => $office): ?>
                <tr>
                    <td><span class="color-box" style="background-color: <?= $colors[$index] ?>"></span><?= htmlspecialchars($office) ?></td>
                    <td><?= $counts[$index] ?></td>
                    <td><?= $values[$index] ?>%</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script>
        const ctx = document.getElementById('intentPie').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: <?= json_encode($labels) ?>,
                datasets: [{
                    data: <?= json_encode($values) ?>,
                    backgroundColor: <?= json_encode($colors) ?>,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const percentage = context.parsed;
                                const counts = <?= json_encode($counts) ?>;
                                const count = counts[context.dataIndex];
                                return `${label}: ${percentage}% (${count} count${count !== 1 ? 's' : ''})`;
                            }
                        }
                    }
                }
            }
        });
    </script>

</body>
</html>
