<?php
$conn = new mysqli("localhost", "root", "", "fitness_tracker");
$body_parts = ['Chest', 'Back', 'Legs', 'Shoulders'];
$filter = $_GET['filter'] ?? '';

if ($filter) {
    $stmt = $conn->prepare("SELECT * FROM exercises WHERE body_part = ?");
    $stmt->bind_param("s", $filter);
    $stmt->execute();
    $exercises = $stmt->get_result();
} else {
    $exercises = $conn->query("SELECT * FROM exercises");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Exercise Library - Fitness Tracker</title>
  <link rel="stylesheet" href="../asset/exercise_library_style.css" />
  <script src="../asset/js/exercise_library.js" defer></script>
</head>
<body>

<div class="container">
  <!-- Header Section with Title + Buttons -->
  <div class="header-bar">
    <h2>Exercise Library</h2>
    <div class="header-buttons">
      <button class="header-btn workout-btn" onclick="window.location.href='workout.php'">🏋️ Workout Logging</button>
      <button class="header-btn profile-btn" onclick="window.location.href='../view/profileView.php'">👤 View Profile</button>
    </div>
  </div>

  <!-- Filter Buttons -->
  <div class="filters">
    <button onclick="window.location.href='exercise_controller.php'">All</button>
    <?php foreach ($body_parts as $part): ?>
      <button onclick="filterBy('<?= htmlspecialchars($part) ?>')"><?= htmlspecialchars($part) ?></button>
    <?php endforeach; ?>
  </div>

  <!-- Exercise Cards -->
  <div class="exercises">
    <?php if ($exercises && $exercises->num_rows > 0): ?>
      <?php while ($row = $exercises->fetch_assoc()): ?>
        <div class="card">
          <h3><?= htmlspecialchars($row['name']) ?></h3>
          <p><strong>Body Part:</strong> <?= htmlspecialchars($row['body_part']) ?></p>
          <p><strong>Equipment:</strong> <?= htmlspecialchars($row['equipment']) ?></p>
          <button onclick="saveFavorite('<?= htmlspecialchars($row['name']) ?>')">Save to Favorites</button>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>No exercises found.</p>
    <?php endif; ?>
  </div>
</div>

</body>
</html>
