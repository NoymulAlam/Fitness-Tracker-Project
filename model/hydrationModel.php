<?php
function connectDB() {
    $conn = new mysqli("localhost", "root", "", "fitness_tracker");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $conn->set_charset("utf8mb4");
    return $conn;
}

function ensureGoalExists($conn, $user_id) {
    $sql = "INSERT IGNORE INTO water_goals (user_id, daily_goal_ml) VALUES (?, 2000)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) die("Prepare failed: " . $conn->error);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();
}

function logWater($conn, $user_id, $amount) {
    $sql = "INSERT INTO water_logs (user_id, amount_ml) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) die("Prepare failed: " . $conn->error);
    $stmt->bind_param("ii", $user_id, $amount);
    $stmt->execute();
    $stmt->close();
}

function updateGoal($conn, $user_id, $goal) {
    $sql = "REPLACE INTO water_goals (user_id, daily_goal_ml) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) die("Prepare failed: " . $conn->error);
    $stmt->bind_param("ii", $user_id, $goal);
    $stmt->execute();
    $stmt->close();
}

function getTotalToday($conn, $user_id) {
    $sql = "SELECT SUM(amount_ml) AS total FROM water_logs WHERE user_id = ? AND DATE(timestamp) = CURDATE()";
    $stmt = $conn->prepare($sql);
    if (!$stmt) die("Prepare failed: " . $conn->error);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $total = 0;
    if ($row = $result->fetch_assoc()) {
        $total = $row['total'] ?? 0;
    }
    $stmt->close();
    return $total;
}

function getDailyGoal($conn, $user_id) {
    $sql = "SELECT daily_goal_ml FROM water_goals WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) die("Prepare failed: " . $conn->error);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $goal = 2000;
    if ($row = $result->fetch_assoc()) {
        $goal = $row['daily_goal_ml'] ?? 2000;
    }
    $stmt->close();
    return $goal;
}

function getRecentEntries($conn, $user_id) {
    $sql = "SELECT * FROM water_logs WHERE user_id = ? ORDER BY timestamp DESC LIMIT 10";
    $stmt = $conn->prepare($sql);
    if (!$stmt) die("Prepare failed: " . $conn->error);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
}
?>
