<?php
session_start();
require_once "../model/contactModel.php";

$conn = connectDB();
$msg = "";
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['captcha'] != $_SESSION['captcha_code']) {
        $errors[] = "CAPTCHA does not match.";
    } else {
        $name = htmlspecialchars(trim($_POST['name']));
        $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
        $subject = htmlspecialchars(trim($_POST['subject']));
        $message = htmlspecialchars(trim($_POST['message']));

        if (!$email) {
            $errors[] = "Invalid email format.";
        }

        if (empty($errors)) {
            if (insertContactMessage($conn, $name, $email, $subject, $message)) {
                // Send auto response email
                $headers = "From: no-reply@fitnesstracker.com\r\n";
                $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
                $emailBody = "Hi $name,\n\nThank you for contacting Fitness Tracker!\n\nSubject: $subject\nMessage: $message\n\nWe'll get back to you shortly.\n\n- Fitness Tracker Team";
                mail($email, "We Received Your Inquiry", $emailBody, $headers);

                $msg = "✅ Your inquiry was submitted successfully. A confirmation email has been sent.";
            } else {
                $errors[] = "Something went wrong. Try again.";
            }
        }
    }
}

$captcha_code = rand(1000, 9999);
$_SESSION['captcha_code'] = $captcha_code;

require_once "../view/contactView.php";
?>
