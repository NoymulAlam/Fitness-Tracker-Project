<?php
require_once '../model/landing_database.php';

function handleCTA() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cta'])) {
        $conn = connectDB();
        $btn = $_POST['cta'];
        $stmt = $conn->prepare("INSERT INTO cta_clicks (button_name) VALUES (?)");
        $stmt->bind_param("s", $btn);
        $stmt->execute();
        $stmt->close();
        $conn->close();
    }
}
?>
