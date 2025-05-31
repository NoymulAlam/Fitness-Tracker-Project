<?php
session_start();
require_once '../model/hydrationModel.php';

// Use session user_id or fallback to 1 for development/testing
$user_id = $_SESSION['user_id'] ?? 1;

// Connect to the database
$conn = connectDB();

// Ensure hydration goal exists for the user
ensureGoalExists($conn, $user_id);

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['log_water']) && isset($_POST['amount'])) {
        logWater($conn, $user_id, intval($_POST['amount']));
    }

    if (isset($_POST['update_goal']) && isset($_POST['daily_goal'])) {
        updateGoal($conn, $user_id, intval($_POST['daily_goal']));
    }

    // Redirect to avoid form resubmission on reload
    header("Location: ../controller/hydrationController.php");
    exit;
}

// Fetch current hydration stats
$total_today = getTotalToday($conn, $user_id);
$daily_goal = getDailyGoal($conn, $user_id);
$history = getRecentEntries($conn, $user_id);

// Close the connection
$conn->close();

// Load the hydration view
require_once '../view/hydrationView.php';
?>
