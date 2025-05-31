<?php
session_start();
require_once '../model/profileModel.php';

$conn = getDbConnection();
$user_id = 1; // Simulated logged-in user
$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Update profile info
    if (isset($_POST['update_profile'])) {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        if ($name !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            updateProfile($conn, $user_id, $name, $email);
            $msg = "✅ Profile updated!";
        } else {
            $msg = "Invalid name or email.";
        }
    }

    // Update password
    if (isset($_POST['update_password'])) {
        $password = $_POST['password'];
        if (strlen($password) >= 6) {
            updatePassword($conn, $user_id, $password);
            $msg = "🔒 Password updated!";
        } else {
            $msg = "Password must be at least 6 characters.";
        }
    }

    // Upload avatar
    if (isset($_POST['upload_avatar']) && isset($_FILES['avatar'])) {
        $upload_dir = '../uploads/';
        // Create uploads folder if not exists
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $file = $_FILES['avatar'];
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];

        if (in_array($file['type'], $allowed_types) && $file['error'] === 0) {
            $filename = uniqid() . '_' . basename($file['name']);
            $target_file = $upload_dir . $filename;

            if (move_uploaded_file($file['tmp_name'], $target_file)) {
                // Save relative path to DB (remove ../ from path)
                updateAvatar($conn, $user_id, "uploads/" . $filename);
                $msg = "🖼️ Avatar uploaded and saved!";
            } else {
                $msg = "Error uploading avatar.";
            }
        } else {
            $msg = "Invalid file type or upload error.";
        }
    }
}

// Reload fresh user data from DB after any update
$user = getUserProfile($conn, $user_id);

// Provide fallback values for avatar/name/email if null (optional)
$user['avatar'] = $user['avatar'] ?? 'uploads/default_avatar.png';
$user['name'] = $user['name'] ?? 'No Name';
$user['email'] = $user['email'] ?? 'No Email';

// Load the view (assumes ../view/profileView.php exists and uses $user, $msg)
include '../view/profileView.php';
