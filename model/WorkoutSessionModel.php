<?php
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
?>
