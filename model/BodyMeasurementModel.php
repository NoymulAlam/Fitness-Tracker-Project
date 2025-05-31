<?php
function connectDB() {
    $conn = new mysqli("localhost", "root", "", "fitness_tracker");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $conn->set_charset("utf8mb4");
    return $conn;
}

function insertMeasurement($conn, $user_id, $date, $weight, $chest, $waist, $hips) {
    $stmt = $conn->prepare("INSERT INTO body_measurements (user_id, date, weight_kg, chest_cm, waist_cm, hips_cm) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issddd", $user_id, $date, $weight, $chest, $waist, $hips);
    $stmt->execute();
    $stmt->close();
}

function uploadPhoto($conn, $user_id, $photo_path, $photo_date) {
    $stmt = $conn->prepare("INSERT INTO progress_photos (user_id, photo_path, date) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $photo_path, $photo_date);
    $stmt->execute();
    $stmt->close();
}

function getMeasurements($conn, $user_id) {
    $stmt = $conn->prepare("SELECT * FROM body_measurements WHERE user_id = ? ORDER BY date DESC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result();
}

function getPhotos($conn, $user_id) {
    $stmt = $conn->prepare("SELECT * FROM progress_photos WHERE user_id = ? ORDER BY date DESC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result();
}
?>
