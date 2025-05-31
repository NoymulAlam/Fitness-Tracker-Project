<?php
require_once '../model/WorkoutSessionModel.php';

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $start_time = $_POST['start_time'] ?? '';
    $end_time = $_POST['end_time'] ?? '';
    $duration = $_POST['duration'] ?? '';
    $exercises = $_POST['exercises'] ?? '';
    $notes = $_POST['notes'] ?? '';

    $success = saveWorkoutSession($conn, $start_time, $end_time, $duration, $exercises, $notes);

    if ($success) {
        $msg = "✅ Workout session saved successfully!";
    } else {
        $msg = "❌ Error saving workout session.";
    }
}

require_once '../view/workout.php';
?>
