<?php
session_start();
require_once "../model/BodyMeasurementModel.php";

$conn = connectDB();
$user_id = $_SESSION['user_id'] ?? 1;

// === 1. HANDLE FORM SUBMISSION ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle measurement submission
    if (isset($_POST['submit_measurement'])) {
        $date = $_POST['date'];
        $weight = $_POST['weight'];
        $chest = $_POST['chest'] ?? 0;
        $waist = $_POST['waist'] ?? 0;
        $hips = $_POST['hips'] ?? 0;

        insertMeasurement($conn, $user_id, $date, $weight, $chest, $waist, $hips);
        header("Location: ../view/body_measurements.php");
        exit;
    }

    // Handle photo upload
    if (isset($_POST['upload_photo']) && isset($_FILES['progress_photo']) && $_FILES['progress_photo']['error'] === UPLOAD_ERR_OK) {
        $photo_date = $_POST['photo_date'];
        $upload_dir = '../uploads/';
        
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $filename = uniqid() . "_" . basename($_FILES['progress_photo']['name']);
        $targetPath = $upload_dir . $filename;

        if (move_uploaded_file($_FILES['progress_photo']['tmp_name'], $targetPath)) {
            uploadPhoto($conn, $user_id, $targetPath, $photo_date);
        } else {
            echo "Photo upload failed!";
        }

        header("Location: ../view/body_measurements.php");
        exit;
    }
}

// === 2. FETCH DATA FOR VIEW ===
$measurements = getMeasurements($conn, $user_id);
$photos = getPhotos($conn, $user_id);

// === 3. LOAD VIEW ===
include '../view/body_measurements.php';
?>
