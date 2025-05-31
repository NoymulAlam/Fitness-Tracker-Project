<?php
function getDbConnection() {
    return new mysqli("localhost", "root", "", "fitness_tracker");
}

function getUserProfile($conn, $user_id) {
    $res = $conn->query("SELECT * FROM profile_management WHERE id = $user_id");
    return $res->fetch_assoc();
}

function updateProfile($conn, $user_id, $name, $email) {
    $stmt = $conn->prepare("UPDATE profile_management SET name=?, email=? WHERE id=?");
    $stmt->bind_param("ssi", $name, $email, $user_id);
    $stmt->execute();
}

function updatePassword($conn, $user_id, $password) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE profile_management SET password=? WHERE id=?");
    $stmt->bind_param("si", $hash, $user_id);
    $stmt->execute();
}

function updateAvatar($conn, $user_id, $filename) {
    $stmt = $conn->prepare("UPDATE profile_management SET avatar=? WHERE id=?");
    $stmt->bind_param("si", $filename, $user_id);
    $stmt->execute();
}
