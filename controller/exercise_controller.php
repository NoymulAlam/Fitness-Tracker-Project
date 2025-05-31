<?php
require_once __DIR__ . '/../model/exercise_model.php';

$conn = connectDB();

createExerciseTable($conn);
preloadDummyExercises($conn);

$body_parts = ['Chest', 'Back', 'Legs', 'Shoulders'];
$filter = $_GET['filter'] ?? '';

$exercises = getExercises($conn, $filter);

require __DIR__ . '/../view/exercise_library.php';
?>
