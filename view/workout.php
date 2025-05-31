<?php
// Database connection and session saving logic
$host = "localhost";
$username = "root";
$password = "";
$database = "fitness_tracker";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function saveWorkoutSession($conn, $start_time, $end_time, $duration, $exercises, $notes) {
    $stmt = $conn->prepare("INSERT INTO workout_sessions (start_time, end_time, duration, exercises, notes) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $start_time, $end_time, $duration, $exercises, $notes);
    return $stmt->execute();
}

$msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $start_time = $_POST['start_time'] ?? '';
    $end_time = $_POST['end_time'] ?? '';
    $duration = $_POST['duration'] ?? '';
    $exercises = $_POST['exercises'] ?? '';
    $notes = $_POST['notes'] ?? '';

    $success = saveWorkoutSession($conn, $start_time, $end_time, $duration, $exercises, $notes);
    $msg = $success ? "✅ Workout session saved successfully!" : "❌ Error saving workout session.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Workout Logging - Fitness Tracker</title>
  <link rel="stylesheet" href="../asset/workout_logging_style.css" />
</head>
<body>
<div class="container">

  <!-- Navigation Buttons -->
  <div class="top-nav">
    <a href="hydrationView.php" class="nav-btn water-btn">💧 Water Intake</a>
    <a href="body_measurements.php" class="nav-btn body-btn">📏 Body Measurement</a>
    <a href="nutritionView.php" class="nav-btn nutrition-btn">🥗 Nutrition Logging</a>
  </div>

  <h2>Workout Logger</h2>

  <?php if (!empty($msg)): ?>
    <div class="msg <?= strpos($msg, '✅') !== false ? 'success' : 'error' ?>"><?= htmlspecialchars($msg) ?></div>
  <?php endif; ?>

  <div class="timer" id="timer">00:00:00</div>
  <div style="text-align:center;">
    <button class="start-btn" onclick="startTimer()">Start Workout</button>
    <button class="stop-btn" onclick="stopTimer()">Stop Workout</button>
  </div>

  <form method="POST" onsubmit="return prepareForm();">
    <input type="hidden" name="start_time" id="start_time" />
    <input type="hidden" name="end_time" id="end_time" />
    <input type="hidden" name="duration" id="duration" />
    <input type="hidden" name="exercises" id="exercise_data" />

    <h3>Log Exercises</h3>
    <input type="text" id="exercise" placeholder="Exercise Name" />
    <input type="number" id="sets" placeholder="Sets" />
    <input type="number" id="reps" placeholder="Reps" />
    <input type="number" id="weight" placeholder="Weight (kg)" />
    <button type="button" onclick="addExercise()">Add Exercise</button>

    <table class="log-table" id="exerciseTable">
      <thead>
        <tr>
          <th>Exercise</th>
          <th>Sets</th>
          <th>Reps</th>
          <th>Weight</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>

    <h3>Session Notes</h3>
    <textarea name="notes" rows="4" placeholder="Add any notes..."></textarea>

    <button type="submit" class="save-session-btn">Save Session</button>
  </form>
</div>

<!-- Inline JS -->
<script>
let timerInterval;
let seconds = 0;
let startTimestamp, endTimestamp;

function formatTime(sec) {
  const h = String(Math.floor(sec / 3600)).padStart(2, '0');
  const m = String(Math.floor((sec % 3600) / 60)).padStart(2, '0');
  const s = String(sec % 60).padStart(2, '0');
  return `${h}:${m}:${s}`;
}

function startTimer() {
  startTimestamp = new Date();
  document.getElementById('start_time').value = startTimestamp.toISOString().slice(0, 19).replace('T', ' ');
  timerInterval = setInterval(() => {
    seconds++;
    document.getElementById("timer").textContent = formatTime(seconds);
  }, 1000);
}

function stopTimer() {
  clearInterval(timerInterval);
  endTimestamp = new Date();
  document.getElementById('end_time').value = endTimestamp.toISOString().slice(0, 19).replace('T', ' ');
  document.getElementById('duration').value = formatTime(seconds);
}

let exercises = [];

function addExercise() {
  const name = document.getElementById("exercise").value;
  const sets = document.getElementById("sets").value;
  const reps = document.getElementById("reps").value;
  const weight = document.getElementById("weight").value;

  if (!name || !sets || !reps || !weight) {
    alert("Please fill all exercise fields.");
    return;
  }

  exercises.push({ name, sets, reps, weight });
  renderExercises();

  document.getElementById("exercise").value = "";
  document.getElementById("sets").value = "";
  document.getElementById("reps").value = "";
  document.getElementById("weight").value = "";
}

function renderExercises() {
  const tbody = document.getElementById("exerciseTable").querySelector("tbody");
  tbody.innerHTML = "";
  exercises.forEach((ex, i) => {
    tbody.innerHTML += `<tr>
      <td>${ex.name}</td>
      <td>${ex.sets}</td>
      <td>${ex.reps}</td>
      <td>${ex.weight}</td>
      <td><button type="button" onclick="removeExercise(${i})">Remove</button></td>
    </tr>`;
  });
}

function removeExercise(index) {
  exercises.splice(index, 1);
  renderExercises();
}

function prepareForm() {
  if (!document.getElementById('start_time').value || !document.getElementById('end_time').value) {
    alert("Please start and stop the timer before submitting.");
    return false;
  }
  document.getElementById("exercise_data").value = JSON.stringify(exercises);
  return true;
}
</script>
</body>
</html>
