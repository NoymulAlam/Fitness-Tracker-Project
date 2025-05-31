<?php
function connectDB() {
    return new mysqli("localhost", "root", "", "fitness_tracker");
}

function insertContactMessage($conn, $name, $email, $subject, $message) {
    $stmt = $conn->prepare("INSERT INTO contact_us (name, email, subject, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $subject, $message);
    return $stmt->execute();
}
?>
