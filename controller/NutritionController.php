<?php 
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "fitness_tracker");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Simulate logged-in user (fallback to ID 1)
$user_id = $_SESSION['user_id'] ?? 1;

// --- FUNCTIONS ---
function addMeal($conn, $user_id, $date, $meal, $carbs, $protein, $fat, $calories) {
    $stmt = $conn->prepare("INSERT INTO nutrition_logs (user_id, date, meal_name, carbs_g, protein_g, fat_g, calories)
                            VALUES (?, ?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die("Prepare failed: " . htmlspecialchars($conn->error));
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
        die("Prepare failed: " . htmlspecialchars($conn->error));
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
        die("Prepare failed: " . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc() ?: [];
}

// --- Handle form submission ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['meal_name'])) {
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

// Load the view
include '../view/nutritionView.php';
?>
