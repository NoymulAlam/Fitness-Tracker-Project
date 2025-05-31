<?php 
session_start();
require_once "../model/bodyMeasurementModel.php";

// Get the user ID from session or set default
$user_id = $_SESSION['user_id'] ?? 1;

// Connect to DB and fetch data
$conn = connectDB();
$measurements = getMeasurements($conn, $user_id);
$photos = getPhotos($conn, $user_id);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Body Measurements - Fitness Tracker</title>
    <link rel="stylesheet" href="../asset/measurements.css" />
</head>
<body>
<div class="container">
    <h2>📏 Body Measurements Tracker</h2>

    <div class="section">
        <h3>Log Measurements</h3>
        <form method="POST" action="../controller/bodyMeasurementController.php">
            <label>Date:</label>
            <input type="date" name="date" required>

            <label>Weight (kg):</label>
            <input type="number" step="0.1" name="weight" required>

            <label>Chest (cm):</label>
            <input type="number" step="0.1" name="chest">

            <label>Waist (cm):</label>
            <input type="number" step="0.1" name="waist">

            <label>Hips (cm):</label>
            <input type="number" step="0.1" name="hips">

            <button type="submit" name="submit_measurement">Add Measurement</button>
        </form>
    </div>

    <div class="section">
        <h3>📉 Trend Analysis</h3>
        <table>
            <tr>
                <th>Date</th>
                <th>Weight</th>
                <th>Chest</th>
                <th>Waist</th>
                <th>Hips</th>
            </tr>
            <?php if ($measurements && $measurements->num_rows > 0): ?>
                <?php while ($row = $measurements->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['date']) ?></td>
                        <td><?= htmlspecialchars($row['weight_kg']) ?></td>
                        <td><?= htmlspecialchars($row['chest_cm']) ?></td>
                        <td><?= htmlspecialchars($row['waist_cm']) ?></td>
                        <td><?= htmlspecialchars($row['hips_cm']) ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5">No data available</td></tr>
            <?php endif; ?>
        </table>
    </div>

    <div class="section">
        <h3>📸 Upload Progress Photo</h3>
        <form method="POST" action="../controller/bodyMeasurementController.php" enctype="multipart/form-data">
            <label>Date:</label>
            <input type="date" name="photo_date" required>

            <label>Select Photo:</label>
            <input type="file" name="progress_photo" accept="image/*" required>

            <button type="submit" name="upload_photo">Upload</button>
        </form>

        <h3>📷 Photo Timeline</h3>
        <div class="photo-grid">
            <?php if ($photos && $photos->num_rows > 0): ?>
                <?php while ($photo = $photos->fetch_assoc()): ?>
                    <div>
                        <img src="<?= htmlspecialchars($photo['photo_path']) ?>" alt="Progress Photo" />
                        <p style="text-align:center;"><?= htmlspecialchars($photo['date']) ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No photos uploaded yet.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
