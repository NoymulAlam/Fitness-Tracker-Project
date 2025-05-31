<?php
session_start();

// Replace these with your actual DB credentials
$conn = new mysqli("localhost", "root", "", "fitness_tracker");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Dummy user_id for testing; replace with actual session or auth logic
$user_id = $_SESSION['user_id'] ?? 1;

// --- FUNCTIONS ---
function addMeal($conn, $user_id, $date, $meal, $carbs, $protein, $fat, $calories) {
    $stmt = $conn->prepare("INSERT INTO nutrition_logs (user_id, date, meal_name, carbs_g, protein_g, fat_g, calories) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("issdddi", $user_id, $date, $meal, $carbs, $protein, $fat, $calories);
    $stmt->execute();
    $stmt->close();
}

function getNutritionLogs($conn, $user_id) {
    $stmt = $conn->prepare("SELECT date, meal_name, carbs_g, protein_g, fat_g, calories 
                            FROM nutrition_logs 
                            WHERE user_id = ? 
                            ORDER BY date DESC");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result();
}

function getDailyTotals($conn, $user_id) {
    $stmt = $conn->prepare("SELECT 
                                SUM(carbs_g) AS total_carbs,
                                SUM(protein_g) AS total_protein,
                                SUM(fat_g) AS total_fat,
                                SUM(calories) AS total_calories 
                            FROM nutrition_logs 
                            WHERE user_id = ? AND date = CURDATE()");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc() ?: [];
}

// --- FORM HANDLER ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date     = $_POST['date'];
    $meal     = $_POST['meal_name'];
    $carbs    = (float)$_POST['carbs'];
    $protein  = (float)$_POST['protein'];
    $fat      = (float)$_POST['fat'];
    $calories = (int)$_POST['calories'];

    addMeal($conn, $user_id, $date, $meal, $carbs, $protein, $fat, $calories);
}

// Fetch data for rendering
$logs = getNutritionLogs($conn, $user_id);
$totals = getDailyTotals($conn, $user_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Nutrition Logging - Fitness Tracker</title>
    <link rel="stylesheet" href="../asset/nutrition.css" />
    <script src="../asset/nutrition.js" defer></script>
</head>
<body>
<div class="container">
    <h2>🍽️ Nutrition Logging</h2>

    <h3>Add Food Entry</h3>
    <form method="POST">
        <input type="date" name="date" required />
        <input type="text" id="meal_name" name="meal_name" placeholder="Meal Name" required />
        <input type="number" step="0.1" id="carbs" name="carbs" placeholder="Carbs (g)" required />
        <input type="number" step="0.1" id="protein" name="protein" placeholder="Protein (g)" required />
        <input type="number" step="0.1" id="fat" name="fat" placeholder="Fat (g)" required />
        <input type="number" id="calories" name="calories" placeholder="Calories" required />
        <button type="submit">Add to Diary</button>
    </form>

    <h4>🍴 Quick Add Meals</h4>
    <div class="quick-add-buttons">
        <button type="button" onclick="quickAdd('Grilled Chicken')">Grilled Chicken</button>
        <button type="button" onclick="quickAdd('Oatmeal with Banana')">Oatmeal with Banana</button>
        <button type="button" onclick="quickAdd('Salmon with Rice')">Salmon with Rice</button>
        <button type="button" onclick="quickAdd('Greek Yogurt')">Greek Yogurt</button>
        <button type="button" onclick="quickAdd('Avocado Toast')">Avocado Toast</button>
        <button type="button" onclick="quickAdd('Protein Shake')">Protein Shake</button>
        <button type="button" onclick="quickAdd('Quinoa Salad')">Quinoa Salad</button>
        <button type="button" onclick="quickAdd('Egg Omelette')">Egg Omelette</button>
    </div>

    <div class="macros">
        <p>Today's Totals: <?= htmlspecialchars($totals['total_calories'] ?? 0) ?> kcal |
            Carbs: <?= htmlspecialchars($totals['total_carbs'] ?? 0) ?>g |
            Protein: <?= htmlspecialchars($totals['total_protein'] ?? 0) ?>g |
            Fats: <?= htmlspecialchars($totals['total_fat'] ?? 0) ?>g
        </p>
    </div>

    <h3>📖 Food Diary</h3>
    <table>
        <tr>
            <th>Date</th>
            <th>Meal</th>
            <th>Carbs (g)</th>
            <th>Protein (g)</th>
            <th>Fat (g)</th>
            <th>Calories</th>
        </tr>
        <?php if ($logs && $logs->num_rows > 0): ?>
            <?php while ($row = $logs->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['date']) ?></td>
                    <td><?= htmlspecialchars($row['meal_name']) ?></td>
                    <td><?= htmlspecialchars($row['carbs_g']) ?></td>
                    <td><?= htmlspecialchars($row['protein_g']) ?></td>
                    <td><?= htmlspecialchars($row['fat_g']) ?></td>
                    <td><?= htmlspecialchars($row['calories']) ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6">No food entries found.</td></tr>
        <?php endif; ?>
    </table>
</div>
</body>
</html>
