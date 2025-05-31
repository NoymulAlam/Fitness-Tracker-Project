<?php
$conn = new mysqli("localhost", "root", "", "fitness_tracker");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

function getUserByEmail($email) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function createUser($name, $email, $password, $token) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, token) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $password, $token);
    return $stmt->execute();
}

function updatePassword($token, $new_pass) {
    global $conn;
    $stmt = $conn->prepare("UPDATE users SET password=? WHERE token=?");
    $stmt->bind_param("ss", $new_pass, $token);
    return $stmt->execute();
}
