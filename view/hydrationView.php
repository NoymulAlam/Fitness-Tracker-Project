<?php
// Set default values if variables not passed from controller
if (!isset($total_today)) {
    $total_today = 0;
}
if (!isset($daily_goal) || $daily_goal == 0) {
    $daily_goal = 2000; // default goal in ml
}
if (!isset($history)) {
    $history = null;
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Water Intake - Fitness Tracker</title>
  <link rel="stylesheet" href="../asset/hydration.css">
  <script src="../asset/hydration.js" defer></script>
  <script>
    const totalToday = <?= json_encode($total_today) ?>;
    const dailyGoal = <?= json_encode($daily_goal) ?>;
  </script>
</head>
<body>
<div class="container">
  <h2>💧 Water Intake Tracker</h2>

  <div id="reminder" class="reminder" style="display:none;">
    <span class="icon">⚠️</span> Don't forget to drink water! You're behind your daily goal.
  </div>

  <div class="section">
    <h3>Today's Progress</h3>
    <p style="text-align:center;">
      <strong><?= htmlspecialchars($total_today) ?> ml / <?= htmlspecialchars($daily_goal) ?> ml</strong>
    </p>
    <div class="progress-container">
      <div class="progress-bar" style="width: <?= min(100, ($total_today / $daily_goal) * 100) ?>%;">
        <?= round(min(100, ($total_today / $daily_goal) * 100)) ?>%
      </div>
    </div>
  </div>

  <div class="section">
    <h3>Quick Log</h3>
    <div class="glass-buttons">
      <?php foreach ([250 => "🥤 Cup", 500 => "💧 Glass", 750 => "🍼 Bottle"] as $amount => $label): ?>
        <form method="post" action="../controller/hydrationController.php" style="display:inline;">
          <input type="hidden" name="amount" value="<?= htmlspecialchars($amount) ?>">
          <button name="log_water"><?= htmlspecialchars($label) ?> (<?= htmlspecialchars($amount) ?>ml)</button>
        </form>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="section">
    <h3>Set Daily Goal</h3>
    <form method="post" action="../controller/hydrationController.php">
      <input type="number" name="daily_goal" value="<?= htmlspecialchars($daily_goal) ?>" min="500" step="100" required>
      <button name="update_goal">Update Goal</button>
    </form>
  </div>

  <div class="section">
    <h3>Recent Entries</h3>
    <ul>
      <?php if ($history): ?>
        <?php while ($row = $history->fetch_assoc()): ?>
          <li><?= htmlspecialchars($row['amount_ml']) ?>ml - <?= htmlspecialchars(date("d M, h:i A", strtotime($row['timestamp']))) ?></li>
        <?php endwhile; ?>
      <?php else: ?>
        <li>No recent entries found.</li>
      <?php endif; ?>
    </ul>
  </div>
</div>
</body>
</html>
