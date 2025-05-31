<?php

function handleFormSubmission($name, $email, $age) {
    $conn = new mysqli("localhost", "root", "", "fitness_tracker");
    if ($conn->connect_error) {
        return "❌ Database connection failed.";
    }

    if ($name && filter_var($email, FILTER_VALIDATE_EMAIL) && $age > 0) {
        $stmt = $conn->prepare("INSERT INTO user_form_validation (name, email, age) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $name, $email, $age);
        if ($stmt->execute()) {
            return "✅ Form submitted successfully! Thanks!";
        } else {
            return "❌ Error: Email already exists or submission failed.";
        }
    } else {
        return "❌ Invalid input. Please correct the form.";
    }
}
