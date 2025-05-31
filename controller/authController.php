<?php
require_once('../model/authModel.php');

function send_fake_email($email, $subject, $message) {
    echo "<div style='background:#e7f3fe;padding:1em;border:1px solid #2196F3;margin-bottom:1em;'>
            <strong>To: $email</strong><br><strong>Subject:</strong> $subject<br>$message
          </div>";
}

$msg = '';
$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'signup':
        $name = $_POST['name'];
        $email = $_POST['email'];
        $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $token = bin2hex(random_bytes(16));

        if (createUser($name, $email, $pass, $token)) {
            $msg = "Signup successful. You can now log in.";
        } else {
            $msg = "Signup failed: Email might already exist.";
        }
        break;

    case 'login':
        $email = $_POST['email'];
        $pass = $_POST['password'];
        $user = getUserByEmail($email);

        if ($user && password_verify($pass, $user['password'])) {
            $msg = "Login successful! Welcome, " . htmlspecialchars($user['name']);
        } else {
            $msg = "Invalid credentials.";
        }
        break;

    case 'forgot':
        $email = $_POST['email'];
        $user = getUserByEmail($email);
        if ($user) {
            $link = "http://localhost/view/index.php?action=reset&token=" . $user['token'];
            send_fake_email($email, "Reset your password", "Click to reset: <a href='$link'>$link</a>");
            $msg = "Reset link sent to your email.";
        } else {
            $msg = "Email not found.";
        }
        break;

    case 'reset_submit':
        $token = $_POST['token'];
        $new_pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
        updatePassword($token, $new_pass);
        $msg = "Password reset successfully.";
        break;
}
